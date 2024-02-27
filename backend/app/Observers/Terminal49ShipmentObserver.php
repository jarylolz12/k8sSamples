<?php

namespace App\Observers;

use App\Terminal49Shipment;
use App\Shipment;
use Carbon\Carbon;
use App\Mail\BasicMail;
use Illuminate\Support\Facades\Mail;
use App\DiscrepancyLog;
use App\Custom\ProcessSchedulesGroupBookings;
use App\Terminal49Changelog;
use App\Custom\Traits\Terminal49Helpers;

class Terminal49ShipmentObserver
{
    use Terminal49Helpers;

    /**
     * Handle the terminal49 shipment "created" event.
     *
     * @param  \App\Terminal49Shipment  $terminal49Shipment
     * @return void
     */
    public function created(Terminal49Shipment $terminal49Shipment)
    {
        //
        $this->syncMilestone($terminal49Shipment);

        \DB::table('shipments')->where('mbl_num', $terminal49Shipment->mbl_num)->update([
            'should_manually_track' => 0
        ]);
    }

    /**
     * Handle the terminal49 shipment "updating" event.
     *
     * @param  \App\Terminal49Shipment  $terminal49Shipment
     * @return void
     */
    public function updating(Terminal49Shipment $terminal49Shipment)
    {

        if ($terminal49Shipment->isDirty("attributes")) {
            $attributes = json_decode($terminal49Shipment->attributes);
            $eta = $attributes->pod_eta_at ?? $attributes->pod_ata_at;
            $etd = $attributes->pol_etd_at ?? $attributes->pol_atd_at;
            if ($eta ?? false && !empty(trim($eta))) {
                $terminal49Shipment->eta = Carbon::parse($eta);
            }
            if ($etd ?? false && !empty(trim($etd))) {
                $terminal49Shipment->etd = Carbon::parse($etd);
            }
            if(($terminal49Shipment->isDirty('eta') && $terminal49Shipment->eta) || ($terminal49Shipment->isDirty('etd') && $terminal49Shipment->etd)){
                $shipments = Shipment::where('mbl_num',$terminal49Shipment->mbl_num)->get();
                foreach ($shipments as $key => $shipment) {
                    // code...
                    $schedules_group_bookings = json_decode($shipment->schedules_group_bookings ?? '[]');
                    foreach ($schedules_group_bookings ?? [] as $key => $schedule) {
                        // code...
                        if($schedule->is_confirmed){
                            if($terminal49Shipment->isDirty('eta') && $terminal49Shipment->eta){
                                $schedule->eta = $terminal49Shipment->eta;
                            }
                            if($terminal49Shipment->isDirty('etd') && $terminal49Shipment->etd){
                                $schedule->etd = $terminal49Shipment->etd;
                            }
                            break;
                        }
                    }
                    $shipment->schedules_group_bookings = json_encode($schedules_group_bookings);
                    $shipment->save();
                }
            }
        }
        if($terminal49Shipment->isDirty("containers")){
            $t49_containers = json_decode($terminal49Shipment->containers ?? '[]', true);
            $t49_containers_old = json_decode($terminal49Shipment->getOriginal('containers') ?? '[]', true);

            foreach ($t49_containers as $key => $container) {
                // code...
                if(isset($t49_containers_old[$key]['attributes']['available_for_pickup']) &&
                   !is_null($t49_containers_old[$key]['attributes']['available_for_pickup']) &&
                   $container['attributes']['available_for_pickup'] != $t49_containers_old[$key]['attributes']['available_for_pickup']){

                    Terminal49Changelog::create([
                        'mbl_num' => $terminal49Shipment->mbl_num,
                        'container_num' => $container['attributes']['number'],
                        'field_name' => 'available_for_pickup',
                        'boolval' => $container['attributes']['available_for_pickup'],
                    ]);
                }
            }

        }


        if($terminal49Shipment->isDirty('ignore_demurrage')){
            $ignore_demurrage = json_decode($terminal49Shipment->ignore_demurrage ?? '[]');
            $isLfdInDemmurage = 0;
            foreach ($ignore_demurrage ?? [] as $key => $value) {
                // code...
                if($value){
                    $isLfdInDemmurage = $this->isLfdInDemmurage($terminal49Shipment);
                    break;
                }
            }
            \DB::table("shipments")->where("mbl_num",$terminal49Shipment->mbl_num)->update([
                  'lfd_ignore_demurrage' => $isLfdInDemmurage,
                  'should_manually_track' => $isLfdInDemmurage
            ]);
        }

        if($terminal49Shipment->isDirty("containers") || $terminal49Shipment->isDirty("transport_events")){
            $now = now();
            try {
                $containers = json_decode($terminal49Shipment->containers ?? '[]');
                $ignore_demurrage = json_decode($shipment->ignore_demurrage ?? '[]', true);

                $is_released = 0;
                $is_completed = 0;
                $container_count = 0;
                $is_past_lfd_not_released = 0;

                // throw new \Exception("Error Processing Request", 1);
                foreach ($containers as $key => $container) {
                    // code...
                    $container_count++;
                    $pod_full_out_at = $container->attributes->pod_full_out_at;
                    $empty_terminated_at = $container->attributes->empty_terminated_at;
                    $event_empty_in = $this->getTransportEvent($container->id, $terminal49Shipment->transport_events, "container.transport.empty_in");
                    $event_full_out = $this->getTransportEvent($container->id, $terminal49Shipment->transport_events, "container.transport.full_out");

                    $is_released += (!empty($container->attributes->empty_terminated_at ?? $container->attributes->pod_full_out_at) || $event_full_out || $event_empty_in);

                    if($container->attributes->empty_terminated_at || $event_empty_in) $is_completed++;
                    else if($container->attributes->pod_full_out_at || $event_full_out){
                        $pod_full_out_at = Carbon::parse($container->attributes->pod_full_out_at ?? $event_full_out['attributes']['timestamp']);
                        if($now->gt($pod_full_out_at) && $now->diffInDays($pod_full_out_at) > 40) $is_completed++;
                    }


                    if(!$is_past_lfd_not_released){
                        $passed_lfd = false;
                        $skip_container = false;

                        // check lfd
                        $lfd = $container->attributes->pickup_lfd;
                        if (empty($lfd)) {
                            continue;
                        }
                        $lfd = \Carbon\Carbon::parse($lfd);
                        if (now()->gt($lfd)) {
                            $passed_lfd = true;
                        }
                        //
                        $released_at_terminal = (!empty($container->attributes->empty_terminated_at ?? $container->attributes->pod_full_out_at) || $event_full_out || $event_empty_in);   ;

                        if (isset($ignore_demurrage[$container->attributes->number]) && $ignore_demurrage[$container->attributes->number]) $skip_container = true;


                        if ($passed_lfd && !$released_at_terminal && !$skip_container) {
                            // here goes the code
                            $is_past_lfd_not_released = 1;
                        }
                    }


                }
                $terminal49Shipment->is_past_lfd_not_released = $is_past_lfd_not_released;
                $terminal49Shipment->is_completed = ($is_completed == $container_count);
                $terminal49Shipment->is_released = ($is_released == $container_count);
            } catch (\Exception $e) {
                \Mail::
                      to(["tanvir@shifl.com"])
                      ->send(new \App\Mail\SimpleString(" Failure Log - t49 is_released, is_completed status sync Observer code - " . $terminal49Shipment->mbl_num ,
                             "<h5> " . $e->getMessage() . "</h5> <br> <pre> " . $e->getTraceAsString() . "</pre> <br>"));
            }


        }

    }

    /**
     * Handle the terminal49 shipment "updated" event.
     *
     * @param  \App\Terminal49Shipment  $terminal49Shipment
     * @return void
     */
    public function updated(Terminal49Shipment $terminal49Shipment)
    {
        // check milestones
        $this->syncMilestone($terminal49Shipment);

        if ($terminal49Shipment->isDirty("containers") || $terminal49Shipment->isDirty("attributes")) {
            // check eta vs ata and discharged
            $this->checkEtaVsAtaAndDischarged($terminal49Shipment);
        } else {
            $shipment = Shipment::where('mbl_num', $terminal49Shipment->mbl_num)->first();
        }

        try {
            
            \DB::table('shipments')->where('mbl_num',$terminal49Shipment->mbl_num)
            ->update([
                'is_tracking_processing' => 0,
                'is_tracking_info_processing' => 0
            ]);

        } catch(Exception $e) {

        }

    }

    /**
     * Handle the terminal49 shipment "deleted" event.
     *
     * @param  \App\Terminal49Shipment  $terminal49Shipment
     * @return void
     */
    public function deleted(Terminal49Shipment $terminal49Shipment)
    {
        //
    }

    /**
     * Handle the terminal49 shipment "restored" event.
     *
     * @param  \App\Terminal49Shipment  $terminal49Shipment
     * @return void
     */
    public function restored(Terminal49Shipment $terminal49Shipment)
    {
        //
    }

    /**
     * Handle the terminal49 shipment "force deleted" event.
     *
     * @param  \App\Terminal49Shipment  $terminal49Shipment
     * @return void
     */
    public function forceDeleted(Terminal49Shipment $terminal49Shipment)
    {
        //
    }

    /**
     * handle Released at terminal milestone's value
     *
     * @param  \App\Terminal49Shipment  $terminal49Shipment
     * @return void
     */

    private function syncMilestone($terminal49Shipment)
    {
        if (!empty($terminal49Shipment->containers)) {
            $containers = json_decode($terminal49Shipment->containers);
            if (!empty($containers)) {
                $shipment = Shipment::where("mbl_num", "=", $terminal49Shipment->mbl_num)->first();
                if ($shipment) {
                    // $released_at_terminal = $this->isReleasedAtTerminal($containers);
                    // $shipment->released_at_terminal = $released_at_terminal;
                    // $shipment->save();
                    \DB::table("shipments")->where("id", "=", $shipment->id)->update([
                        "released_at_terminal" => $this->isReleasedAtTerminal($containers),
                    ]);
                }
            }
        }
    }

    /**
     * determine if the shipment released at terminal
     *
     * @param  \App\Terminal49Shipment::containers  $containers
     * @return bool
     */

    private function isReleasedAtTerminal($containers)
    {
        if (count($containers) > 0) {
            $released_at_terminal = true;
            foreach ($containers as $container) {
                // code...
                if (!$container->attributes->available_for_pickup) {
                    $released_at_terminal = $container->attributes->available_for_pickup;
                }
            }
            return $released_at_terminal;
        } else {
            return false;
        }
    }
    // check eta vs ata
    private function checkEtaVsAtaAndDischarged($terminal49Shipment)
    {
        $shipment = Shipment::where('mbl_num', $terminal49Shipment->mbl_num)->first();
        if (!empty($shipment) && !$shipment->delivered) {
            $subject = " ID# ". $shipment->shifl_ref.", MBL# ". $shipment->mbl_num;
            $to = $shipment->officeToAccountManager();

            $schedules_group_bookings = $shipment->schedules_group_bookings;
            $schedules_group_bookings = (new ProcessSchedulesGroupBookings($schedules_group_bookings))->getSchedule();

            if (!empty($schedules_group_bookings) && !empty($schedules_group_bookings->eta)) {

                $app_eta = Carbon::parse($schedules_group_bookings->eta)->startOfDay();

                if (!empty($app_eta)) {
                    $attributes = json_decode($terminal49Shipment->attributes);

                    // discharged check
                    $containers = $terminal49Shipment->containers;
                    if (!empty($containers)) {
                        $containers = json_decode($containers);
                        if (!empty($containers)) {
                            $container_data = [];
                            foreach ($containers as $container) {   
                                // code...
                                $discharged_at = $container->attributes->pod_discharged_at;
                                if (!empty($discharged_at)) {
                                    $discharged_at = Carbon::parse($discharged_at)->startOfDay();
                                    if (!empty($discharged_at)) {
                                        if ($app_eta->gt($discharged_at)) {
                                            array_push($container_data, [
                                              'number' => $container->attributes->number,
                                              'discharged_at' => $discharged_at->format("M d, Y")
                                            ]);
                                        }
                                    }
                                }
                            }

                            if (count($container_data) > 0) {
                                $this->sendDiscrepancyMail(null, $app_eta, $subject, 3, $shipment->mbl_num, $to, $container_data, $shipment);
                                return;
                            }
                        }
                    }

                    ///////////////////////////////////////

                    if (!empty($attributes)) {
                        $t49_ata = $attributes->pod_ata_at;
                        // ata  check

                        if (!empty($t49_ata)) {
                            $t49_ata = Carbon::parse($t49_ata)->startOfDay();
                            if (!empty($t49_ata)) {
                                if ($app_eta->gt($t49_ata) && $app_eta->diffInDays($t49_ata) > 1) {
                                    $this->sendDiscrepancyMail($t49_ata, $app_eta, $subject, 2, $shipment->mbl_num, $to, null, $shipment);
                                    return;
                                }
                            }
                        }

                        // check eta

                        $carrier_eta = $shipment->carrier_arrival_notice_eta;
                        if (!empty($carrier_eta)) {
                            
                            $carrier_eta = Carbon::parse($carrier_eta)->startOfDay();
                            if (!empty($carrier_eta)) {

                                $diff_days = $app_eta->diffInDays($carrier_eta);
                                if ($app_eta->gt($carrier_eta) && $diff_days > 3) {

                                    $this->sendDiscrepancyMail($carrier_eta, $app_eta, $subject, 1, $shipment->mbl_num, $to, $diff_days, $shipment);
                                    return;
                                }
                            }
                        }
                    }

                    // ------------
                }
            }
        }
    }

    private function sendCustom($content) {

        Mail::to('kenneth@shifl.com')->cc([])->send(new BasicMail($content['subject'], $content['content'], [],'mails.basic'));
    }

    // send discrepancy mail
    private function sendDiscrepancyMail($t49_date, $system_date, $subject_suffix, $type = 1, $mbl_num, $to, $extra_data = null, $shipment = null)
    {
        $content = [];
        $discrepancy_log = new DiscrepancyLog();

        if ($type == 1) {
            $markdown = "mails.discrepancy.eta";
            $subject = 'Carrier ETA Discrepancy! : ';

            $content['t49_eta'] = $t49_date->format('M d, Y');
            $content['system_eta'] = $system_date->format('M d, Y');
            $content['diff_days'] = $extra_data;

            //check the logs for duplicate discrepany
            if (DiscrepancyLog::where('mbl_num', $mbl_num)->where('type', 'eta')->whereDate('system_date', $system_date)->whereDate("t49_date", $t49_date)->exists()) {
                return;
            }
            // log the discrepany
            $discrepancy_log->mbl_num = $mbl_num;
            $discrepancy_log->type = "eta";
            $discrepancy_log->system_date = $system_date;
            $discrepancy_log->t49_date = $t49_date;
        //
        } elseif ($type == 2) {
            $markdown = "mails.discrepancy.ata";
            $subject = 'ATA Discrepancy! : ';
            $content['t49_ata'] = $t49_date->format('M d, Y');
            $content['system_eta'] = $system_date->format('M d, Y');

            //check the logs for duplicate discrepany
            if (DiscrepancyLog::where('mbl_num', $mbl_num)->where('type', 'ata')->whereDate('system_date', $system_date)->whereDate("t49_date", $t49_date)->exists()) {
                return;
            }
            // log the discrepany
            $discrepancy_log->mbl_num = $mbl_num;
            $discrepancy_log->type = "ata";
            $discrepancy_log->system_date = $system_date;
            $discrepancy_log->t49_date = $t49_date;
        //
        } elseif ($type == 3) {
            $markdown = "mails.discrepancy.discharged";
            $subject = 'Discharged Discrepancy! ';

            $content['system_eta'] = $system_date->format('M d, Y');
            $content['containers'] = $extra_data;

            //check the logs for duplicate discrepany
            if (DiscrepancyLog::where('mbl_num', $mbl_num)->where('type', 'discharged')->whereDate('system_date', $system_date)->where("data", json_encode($extra_data))->exists()) {
                return;
            }
            // log the discrepany
            $discrepancy_log->mbl_num = $mbl_num;
            $discrepancy_log->type = "discharged";
            $discrepancy_log->system_date = $system_date;
            $discrepancy_log->data = json_encode($extra_data);
            //
        }
        $subject .= $subject_suffix;
        $cc = config('mail-settings.discrepancy_email');

        $has_office_to = false;
        $has_office_from = false;

        if ( isset($shipment->officeTo) && isset($shipment->officeTo->id) ) {
            $has_office_to = true;
            $check_office_to = \App\ShiflOfficeEmail::where('type', 'to')
                                                ->where('office_id', $shipment->officeTo->id)
                                                ->first();    
        }
        
        if ( isset($shipment->officeFrom) && isset($shipment->officeFrom->id) ) {
            $has_office_from = true;
            $check_office_from = \App\ShiflOfficeEmail::where('type', 'from')
                                            ->where('office_id', $shipment->officeFrom->id)
                                            ->first();
        }

        if ( $type == 3) {

            if ( $has_office_to ) {
                if ( isset($check_office_to->id) ) {
                    $dynamic_emails = $check_office_to->discharged_discrepancy_emails;
                    if ( $dynamic_emails !=null ) {

                        $dynamic_emails = ( is_array($dynamic_emails)) ? $dynamic_emails : json_decode($dynamic_emails);
                        $dynamic_emails = ($dynamic_emails == '') ? [] : $dynamic_emails;

                        foreach($dynamic_emails as $dynamic_email ) {
                            if ( !in_array($dynamic_email, $cc)) {
                                array_push($cc, $dynamic_email);
                                //array_push($extra_ccs, $dynamic_email);
                            }
                        }
                    }
                }    
            }
            

            if ( $has_office_from ) {
                if ( isset($check_office_from->id) ) {
                
                    $dynamic_emails = $check_office_from->discharged_discrepancy_emails;
                    if ( $dynamic_emails !=null ) {

                        $dynamic_emails = ( is_array($dynamic_emails)) ? $dynamic_emails : json_decode($dynamic_emails);
                        $dynamic_emails = ($dynamic_emails == '') ? [] : $dynamic_emails;

                        foreach($dynamic_emails as $dynamic_email ) {
                            if ( !in_array($dynamic_email, $cc)) {
                                array_push($cc, $dynamic_email);
                                //array_push($extra_ccs, $dynamic_email);
                            }
                        }
                    }
                }    
            }
            

        } elseif( $type == 2 ) {
            
            if ( $has_office_to ) {
                if ( isset($check_office_to->id) ) {

                    $dynamic_emails = $check_office_to->ata_discrepancy_emails;
                    if ( $dynamic_emails !=null ) {

                        $dynamic_emails = ( is_array($dynamic_emails)) ? $dynamic_emails : json_decode($dynamic_emails);
                        $dynamic_emails = ($dynamic_emails == '') ? [] : $dynamic_emails;

                        foreach($dynamic_emails as $dynamic_email ) {
                            if ( !in_array($dynamic_email, $cc)) {
                                array_push($cc, $dynamic_email);
                                //array_push($extra_ccs, $dynamic_email);
                            }
                        }
                    }
                }    
            }
            
            if ( $has_office_from ) {
                if ( isset($check_office_from->id) ) {
                    $dynamic_emails = $check_office_from->ata_discrepancy_emails;
                    if ( $dynamic_emails !=null ) {

                        $dynamic_emails = ( is_array($dynamic_emails)) ? $dynamic_emails : json_decode($dynamic_emails);
                        $dynamic_emails = ($dynamic_emails == '') ? [] : $dynamic_emails;

                        foreach($dynamic_emails as $dynamic_email ) {
                            if ( !in_array($dynamic_email, $cc)) {
                                array_push($cc, $dynamic_email);
                                //array_push($extra_ccs, $dynamic_email);
                            }
                        }
                    }
                }    
            }

            


        } elseif ($type == 1 ) {
            
            if ( $has_office_to ) {
                if ( isset($check_office_to->id) ) {

                    $dynamic_emails = $check_office_to->carrier_eta_discrepancy_emails;
                    if ( $dynamic_emails !=null ) {

                        $dynamic_emails = ( is_array($dynamic_emails)) ? $dynamic_emails : json_decode($dynamic_emails);
                        $dynamic_emails = ($dynamic_emails == '') ? [] : $dynamic_emails;

                        foreach($dynamic_emails as $dynamic_email ) {
                            if ( !in_array($dynamic_email, $cc)) {
                                array_push($cc, $dynamic_email);
                                //array_push($extra_ccs, $dynamic_email);
                            }
                        }
                    }
                }    
            }            
            
            if ( $has_office_from ) {
                if ( isset($check_office_from->id) ) {
                
                    $dynamic_emails = $check_office_from->carrier_eta_discrepancy_emails;
                    if ( $dynamic_emails !=null ) {

                        $dynamic_emails = ( is_array($dynamic_emails)) ? $dynamic_emails : json_decode($dynamic_emails);
                        $dynamic_emails = ($dynamic_emails == '') ? [] : $dynamic_emails;

                        foreach($dynamic_emails as $dynamic_email ) {
                            if ( !in_array($dynamic_email, $cc)) {
                                array_push($cc, $dynamic_email);
                                //array_push($extra_ccs, $dynamic_email);
                            }
                        }
                    }
                }    
            }
            
        }

        Mail::to($to)->cc($cc)->send(new BasicMail($subject, $content, [], $markdown));
        $discrepancy_log->save();
    }

    private function isLfdInDemmurage ($shipment) {
        $containers = json_decode($shipment->containers ?? '[]');
        foreach ($containers as $key => $container) {
            $passed_lfd = false;
            $lfd = $container->attributes->pickup_lfd;
            if (empty($lfd)) {
                continue;
            }
            $lfd = Carbon::parse($lfd)->startOfDay();
            if (now()->startOfDay()->gt($lfd)) {
                $passed_lfd = true;
            }
            //
            $released_at_terminal = $container->attributes->pod_full_out_at ?? $container->attributes->empty_terminated_at;

            if ($passed_lfd && empty($released_at_terminal)) {
                return true;
            }
        }
        return false;

    }
}
