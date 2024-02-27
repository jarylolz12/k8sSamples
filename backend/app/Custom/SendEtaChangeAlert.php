<?php
namespace App\Custom;

use Illuminate\Support\Facades\Mail;
use App\Mail\ShipmentLeave;
use App\Mail\Test;
use Carbon\Carbon;
use App\Terminal49Shipment;
use App\EtaLog;
use App\Mail\BasicMail;

class SendEtaChangeAlert
{
    private $shipment;
    private $processSchedulesGroupBookings;

    private function __construct($shipment)
    {
        $this->shipment = $shipment;
        // \Log::info("Entered into SendEtaChangeAlert Class");
        $this->processSchedulesGroupBookings = new ProcessSchedulesGroupBookings($this->shipment->schedules_group_bookings);
        $this->handle();
    }

    public static function build($shipment)
    {
        (new SendEtaChangeAlert($shipment));
    }

    private function handle()
    {
        // $schedules_group = json_decode($this->shipment->schedules_group);

        // foreach ($schedules_group as $schedule) {
        //     if ($this->isChanged($schedule)) {
        //         if ($this->checkRules($schedule)) {
        //             self::send($this->shipment, $this->getOldEta());
        //         }
        //     }
        //     break;
        // }

        // force back the eta etd synced with t49
        // if ($this->isChangedEtdOrEta() && $this->syncT49()) {
        //     //
        //     return;
        // }
        if ($this->isChanged()) {

            self::send($this->shipment, $this->processSchedulesGroupBookings->getSchedule(), $this->getOldEta(), $this->getAis($this->shipment));
        }
    }

    /**
     * force sync the eta and etd with t49
     *
     * @param  void
     * @return bool
     */

     private function syncT49()
     {
        $shipment_t49 = Terminal49Shipment::find($this->shipment->mbl_num);
        if (empty($shipment_t49)) {
            return false;
        }
        $attributes = json_decode($shipment_t49->attributes);
 
        $eta = $attributes->pod_eta_at;
 
        if (empty($eta)) {
            return false;
        }
        $eta = Carbon::parse($eta);
 
        $schedule = $this->processSchedulesGroupBookings->getSchedule();
 
        $app_eta = Carbon::parse($schedule->eta);
 
        if ($eta->format("Y-m-d") == $app_eta->format("Y-m-d")) {
            return false;
        }
        // old eta
        $old_eta = "";
        $origianl_schedule = $this->processSchedulesGroupBookings->getSchedule($this->shipment->getOriginal("schedules_group_bookings"));
 
        if (!is_null($origianl_schedule)) {
            if (!empty($origianl_schedule->eta)) {
                $old_eta = Carbon::parse($origianl_schedule->eta);
            }
        }
        //
 
        $schedules_group_bookings = json_decode($this->shipment->schedules_group_bookings);
        foreach ($schedules_group_bookings as $schedules_group_booking) {
            // code...
            if ($schedules_group_booking->is_confirmed) {
                $schedules_group_booking->eta = $eta->format("Y-m-d 00:00:00");
                break;
            }
        }
        $this->shipment->schedules_group_bookings = json_encode($schedules_group_bookings);
        $this->shipment->eta = $eta->format("Y-m-d");
 
        if (!empty($old_eta)) {
            if ($old_eta->format("Y-m-d") != $eta->format("Y-m-d")) {
                 //
                self::send($this->shipment, $this->processSchedulesGroupBookings->getSchedule($this->shipment->schedules_group_bookings), $old_eta, $this->getAis($this->shipment));
            }
        }
        return true;
    }

    /**
     * determine if either etd or eta was changed
     *
     * @param  void
     * @return bool
     */

    private function isChangedEtdOrEta()
    {
        $schedule = $this->processSchedulesGroupBookings->getSchedule();
        $origianl_schedule = $this->processSchedulesGroupBookings->getSchedule($this->shipment->getOriginal("schedules_group_bookings"));

        if (is_null($schedule)) {
            return false;
        } elseif (is_null($origianl_schedule)) {
            return true;
        }

        return ($schedule->eta != $origianl_schedule->eta) || ($schedule->etd != $origianl_schedule->etd);
    }

    private function isChanged()
    {
        // \Log::info("Inside isChanged");

        // if ($this->shipment->getOriginal("schedules_group")) {
        //     foreach (json_decode($this->shipment->getOriginal("schedules_group")) as $origianl_schedule) {
        //         // code
        //         if (empty(trim($origianl_schedule->eta)) || empty(trim($schedule->eta))) {
        //             // if (empty(trim($origianl_schedule->eta))) {
        //             return false;
        //         }
        //         return !($schedule->eta == $origianl_schedule->eta);
        //     }
        // }

        $schedule = $this->processSchedulesGroupBookings->getSchedule();
        $origianl_schedule = $this->processSchedulesGroupBookings->getSchedule($this->shipment->getOriginal("schedules_group_bookings"));

        if (is_null($schedule) || is_null($origianl_schedule)) {
            return false;
        }

        if (empty(trim($origianl_schedule->eta)) || empty(trim($schedule->eta))) {
            // if (empty(trim($origianl_schedule->eta))) {
            return false;
        }

        $new_eta = Carbon::parse($schedule->eta)->format("Y-m-d");
        $old_eta = Carbon::parse($origianl_schedule->eta)->format("Y-m-d");

        // log eta changes here
        if ($new_eta != $old_eta) {
            \Log::info("Eta change logged");
            EtaLog::insert([
              "mbl_num" => $this->shipment->mbl_num,
              "old_eta" => $origianl_schedule->eta,
              "eta" => $schedule->eta,
              "created_at" => now()->setTimezone('America/New_York')
            ]);
            
        }
        //

        return ($new_eta != $old_eta) && $this->checkRules($schedule);
    }

    private function sendCustom($content) {

        Mail::to('kenneth@shifl.com')->cc([])->send(new BasicMail($content['subject'], $content['content'], [],'mails.basic'));
    }

    private static function send($shipment, $schedule, $old_eta, $ais_link)
    {
        $content = self::content($shipment, $schedule, $old_eta, $ais_link);


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

        $cc = [];

        $subjects =  "ETA Change Alert: ID#". $shipment->shifl_ref . " - PO# ";

        $attachment = [];

        $ctr = 0;

        foreach ($content['suppliers_group'] ?? [] as $s) {
            if (!empty($s->hbl_copy) && is_string($s->hbl_copy)) {
                $path = storage_path('/app/public/'.$s->hbl_copy);
                if (file_exists($path)) {
                    array_push($attachment, $path);
                }
            }
            if (!empty($s->packing_list) && is_string($s->packing_list)) {
                $path = storage_path('/app/public/'.$s->packing_list);
                if (file_exists($path)) {
                    array_push($attachment, $path);
                }
            }
            if (!empty($s->commercial_documents) && is_string($s->commercial_documents)) {
                $path = storage_path('/app/public/'.$s->commercial_documents);
                if (file_exists($path)) {
                    array_push($attachment, $path);
                }
            }
            if (!empty($s->commercial_invoice) && is_string($s->commercial_invoice)) {
                $path = storage_path('/app/public/'.$s->commercial_invoice);
                if (file_exists($path)) {
                    array_push($attachment, $path);
                }
            }
            if ($s->po_num  && is_string($s->po_num)) {
                if ($ctr == 0) {
                    $subjects .= $s->po_num;
                } else {
                    $subjects .= ', ' . $s->po_num;
                }
                $ctr++;
            }
        }

        $to = [];


        if (is_array($shipment->customer->emails)) {
            foreach ($shipment->customer->emails as $email) {
                if ($email['email'] != '') {
                    array_push($to, $email['email']);
                }
            }

            // $adminUsers = 'shabsie@shifl.com';

            \Log::info("mail Sent ");


            if ( isset($shipment->customer->eta_alert_emails) ) {
                $dynamic_emails = $shipment->customer->eta_alert_emails;
                
                if ( $dynamic_emails !=null ) {

                    $dynamic_emails = ( is_array($dynamic_emails)) ? $dynamic_emails : json_decode($dynamic_emails);
                    $dynamic_emails = ($dynamic_emails == '') ? [] : $dynamic_emails;
                    try {
                        foreach($dynamic_emails as $dynamic_email ) {
                            if ( !in_array($dynamic_email, $cc)) {
                                array_push($cc, $dynamic_email);
                            }
                        }
                    } catch(Exception $e ) {
    
                    }
                }            
            }

            if ( $has_office_to ) {
                if ( isset($check_office_to->id) ) {

                    $dynamic_emails = $check_office_to->eta_alert_emails;
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
                    $dynamic_emails = $check_office_from->eta_alert_emails;
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
            

            if ($shipment->customer->salesRepresentative) {
                array_push($cc, $shipment->customer->manager);
                array_push($cc, $shipment->customer->salesRepresentative);
                Mail::to($to)->cc($cc)->send(new ShipmentLeave($subjects, $content, $attachment, $shipment->customer->manager, "mails.crux_eta_upadate_alert"));

                array_push($cc, $shipment->customer->manager);
                array_push($cc, $shipment->customer->salesRepresentative);
            } else {
                array_push($cc, $shipment->customer->manager);
                Mail::to($to)->cc($cc)->send(new ShipmentLeave($subjects, $content, $attachment, $shipment->customer->manager, "mails.crux_eta_upadate_alert"));

                array_push($cc, $shipment->customer->manager);
            }
            // sent mail for trucker
            if($shipment->delivery_order_left){
                if(isset($shipment->trucker->email)){
                    if ($shipment->trucker->email ?? false) {
                        array_push($to, $shipment->trucker->email);
                    }
                    if ($shipment->trucker->email_recipient) {
                        foreach ($shipment->trucker->email_recipient as $email) {
                            array_push($to, $email['email']);
                        }
                    }
                    //
                    $content['is_for_trucker'] = true;
                    $subjects .= ", Container# ";
                    $should_add_comma = false;
                    foreach ($content['containers_group'] ?? [] as $container) {
                        // code...
                        if($should_add_comma){
                           $subjects .= ", ";
                        }else{
                            $should_add_comma = true;
                        }
                        $subjects .= $container->container_num;
                    }


                    $cc = [];

                    if ( isset($shipment->customer->eta_alert_trucker_emails) ) {
                        $dynamic_emails = $shipment->customer->eta_alert_trucker_emails;

                        if ( $dynamic_emails !=null ) {

                            $dynamic_emails = ( is_array($dynamic_emails)) ? $dynamic_emails : json_decode($dynamic_emails);
                            $dynamic_emails = ($dynamic_emails == '') ? [] : $dynamic_emails;
                            try {
                                foreach($dynamic_emails as $dynamic_email ) {
                                    if ( !in_array($dynamic_email, $cc)) {
                                        array_push($cc, $dynamic_email);
                                        //array_push($extra_ccs, $dynamic_email);
                                    }
                                }
                            } catch(Exception $e ) {
            
                            }
                        }          
                    }

                    
                    if ( isset($check_office_to->id) ) {

                        $dynamic_emails = $check_office_to->eta_alert_trucker_emails;
                        if ( $dynamic_emails !=null ) {

                            $dynamic_emails = ( is_array($dynamic_emails)) ? $dynamic_emails : json_decode($dynamic_emails);
                            $dynamic_emails = ($dynamic_emails == '') ? [] : $dynamic_emails;

                            foreach($dynamic_emails as $dynamic_email ) {
                                if ( !in_array($dynamic_email, $cc)) {
                                    array_push($cc, $dynamic_email);
                                    //array_push($new_cc, $dynamic_email);
                                    //array_push($extra_ccs, $dynamic_email);
                                }
                            }
                        }
                    }

                    if ( isset($check_office_from->id) ) {
                        
                        $dynamic_emails = $check_office_from->eta_alert_trucker_emails;
                        if ( $dynamic_emails !=null ) {

                            $dynamic_emails = ( is_array($dynamic_emails)) ? $dynamic_emails : json_decode($dynamic_emails);
                            $dynamic_emails = ($dynamic_emails == '') ? [] : $dynamic_emails;

                            foreach($dynamic_emails as $dynamic_email ) {
                                if ( !in_array($dynamic_email, $cc)) {
                                    array_push($cc, $dynamic_email);
                                    //array_push($new_cc, $dynamic_email);
                                    //array_push($extra_ccs, $dynamic_email);
                                }
                            }
                        }
                    }

                    $otam = $shipment->officeToAccountManager();

                    if ( isset($otam->email)) {

                        if ( !in_array($otam->email, $cc)) {
                            array_push($cc, $otam->email);
                        }
                    }

                    $subjects .= ", MBL# ". $shipment->mbl_num;
                    Mail::to($to)->cc($cc)->send(new ShipmentLeave($subjects, $content, [], $shipment->customer->manager, "mails.crux_eta_upadate_alert"));
                }

            }
        }
    }

    private static function content($shipment, $schedule, $old_eta, $ais_link)
    {
        return
        [
            "customer" => $shipment->customer->company_name,
            "shifl_ref" => $shipment->shifl_ref,
            "schedule" => $schedule,
            "status" => $shipment->shipment_status,
            "suppliers_group" =>json_decode($shipment->suppliers_group_bookings),
            "containers_group" =>json_decode($shipment->containers_group_bookings),
            "ais_link" => $ais_link,
            "vessel" => $shipment->vessel,
            "mbl_num" => $shipment->mbl_num,
            "old_eta" => $old_eta,
            "is_for_trucker" => false
        ];
    }

    private function checkRules($schedule)
    {
        if ($schedule) {

            // check if eta is later than today

            if (Carbon::parse($schedule->eta)->gt(Carbon::now())) {

                // check if the difference between the old eta and the new eta is more than 2 days

                $old_eta = $this->getOldEta();

                if ($old_eta) {
                    if (Carbon::parse($old_eta)->diffInDays(Carbon::parse($schedule->eta)) > 2) {
                        return true;
                    }
                }
            }
        }
        return false;
    }
    private function getOldEta()
    {
        $origianl_schedule = $this->processSchedulesGroupBookings->getSchedule($this->shipment->getOriginal("schedules_group_bookings"));

        // if ($this->shipment->getOriginal("schedules_group")) {
        //     foreach (json_decode($this->shipment->getOriginal("schedules_group")) as $origianl_schedule) {
        //         return $origianl_schedule->eta;
        //     }
        // }

        if ($origianl_schedule) {
            return $origianl_schedule->eta;
        }
        return null;
    }

    private function getAis($shipment)
    {
        if ($shipment->mbl_num ?? false) {
            $terminal49_shipment = \App\Terminal49Shipment::find($shipment->mbl_num);
            if ($terminal49_shipment ?? false) {
                $attributes = json_decode($terminal49_shipment->attributes);

                if ($attributes ?? false) {
                    if ($attributes->pod_vessel_imo ?? false && !empty($attributes->pod_vessel_imo)) {
                        return "https://www.vesselfinder.com/?imo=" . $attributes->pod_vessel_imo;
                    }
                }
            }
        }

        return false;
    }
}
