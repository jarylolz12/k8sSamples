<?php

namespace App\Listeners;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Mail;
use App\Mail\ShipmentLeave;
use App\Supplier;
use App\TerminalRegion;

class SendLeaveShipmentNotificationMailEventListener
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    private function ifNull($firstValue, $secondValue, $nullValue = null){
        return (empty($firstValue) ? (empty($secondValue) ? $nullValue : $secondValue) : $firstValue);
    }


    /**
     * Handle the event.
     *
     * @param  object  $event
     * @return void
     */
    public function handle($event)
    {
        // \Debugbar::info(json_decode($event->resource->suppliers_group));
        $shipment = $event->resource;
        $shipment->shipment_left = 1;
        $shipment->save();

        $suppliers = json_decode($shipment->suppliers_group);
        $containers = json_decode($shipment->containers_group);
        $content = [
            "CompanyName" => "Shifl",
            "InvDate" => date("m/d/Y", strtotime(date("Y-m-d"))),
            // "customer" => $shipment->customer->company_name,
            "customer" => $shipment->getCustomerImportName(),
            "shifl_ref" => $shipment->shifl_ref,
            "schedule" => json_decode($shipment->schedules_group_bookings),
            "status" => $shipment->shipment_status,
            "suppliers_group" =>json_decode($event->resource->suppliers_group),
            "containers_group" =>json_decode($event->resource->containers_group),
            // "carrier" => isset($shipment->carrier->name) ? $shipment->carrier->name : '',
            "ais_link" => $this->getAis($shipment),
            "vessel" => $shipment->vessel,
            "mbl_num" => $shipment->mbl_num,
            "type" => "dn"
        ];

        $processSchedulesGroupBookings = new \App\Custom\ProcessSchedulesGroupBookings($shipment->schedules_group_bookings);

        $selected_schedule = $processSchedulesGroupBookings->getSchedule();

        //if tracking, process schedule with different logic
        $tracking_status = $shipment->getTrackingStatus();

        //set valid tracking labels
        $validTrackings = ['Auto Tracking', 'Auto Tracked', 'Manual Tracking', 'Manual Tracked'];

        if( in_array($tracking_status, $validTrackings) && $shipment->mbl_num!==null && $shipment->mbl_num!=='' && $shipment->is_tracking == 1){
            $schedule_location_from = '';
            $schedule_location_to = '';
            $schedule_eta = '';
            $schedule_etd = '';
            $terminal49_shipment = $shipment->terminal_fortynine;
            if ( isset ($terminal49_shipment['attributes']) ) {
                $attributes = json_decode($terminal49_shipment['attributes'],true);
                $schedule_location_from = $attributes['port_of_lading_name'];
                $schedule_location_to = $attributes['port_of_discharge_name'];
                $schedule_eta = $this->ifNull($attributes['pod_eta_at'],$attributes['pod_ata_at']);
                $schedule_etd = $this->ifNull($attributes['pol_etd_at'],$attributes['pol_atd_at']);
                $schedule_location_from_id = 0;
                $schedule_location_to_id = 0;
                
                $schedule_location_from_fetch = TerminalRegion::where('name','LIKE','%'.$schedule_location_from.'%')
                                                            ->orWhere('name', 'LIKE' ,'%'.strtoupper($schedule_location_from).'%')
                                                            ->first();
    
                if ( isset($schedule_location_from_fetch->id )) {
                    $schedule_location_from_id = $schedule_location_from_fetch->id;
                    $schedule_location_from = $schedule_location_from_fetch->name;
                }
    
                $schedule_location_to_fetch = TerminalRegion::where('name','LIKE','%'.$schedule_location_to.'%')
                                                            ->orWhere('name', 'LIKE', '%',strtoupper($schedule_location_to).'%')
                                                            ->first();
    
                if ( isset($schedule_location_to_fetch->id )) {
                    $schedule_location_to_id = $schedule_location_to_fetch->id;
                    $schedule_location_to = $schedule_location_to_fetch->name;
                }
    
                //parse eta and etd
                $etd = \Carbon\Carbon::parse($schedule_etd);
                $eta = \Carbon\Carbon::parse($schedule_eta);
    
                if ( !empty($selected_schedule) ) {
                    $selected_schedule->location_from = $schedule_location_from_id;
                    $selected_schedule->location_to = $schedule_location_to_id;
                    $selected_schedule->location_from_name = $schedule_location_from;
                    $selected_schedule->location_to_name = $schedule_location_to;
                    $selected_schedule->etd = $etd;
                    $selected_schedule->eta = $eta;
                } else {
                    //create shipment schedules
                    $newFinalSchedules = [[
                        'eta' => $eta,
                        'etd' => $etd,
                        'eta_readable' => $eta->format('F d, Y'),
                        'etd_readable' => $etd->format('F d, Y'),
                        'location_from' => $schedule_location_from_id,
                        'location_to' =>  $schedule_location_to_id,
                        'location_from_name' => $schedule_location_from,
                        'location_to_name' => $schedule_location_to,
                        'mode' => null,
                        'legs' => [],
                        'type' => '',
                        'carrier_name' => null,
                        'carrier_name_label' => '',
                        'is_confirmed' => 1,
                        'size_id' => null,
                        'price' => null,
                        'selling_size_id' => null,
                        'selling_price' => null,
                        'sell_rates' => [],
                        'size_name' => '',
                        'selling_size_name' => '',
                        'buy_rates' => []
                    ]];
                    $selected_schedule = (object) $newFinalSchedules[0];
                }
            }

            
        }

        $content['selected_schedule'] = $selected_schedule;

        $cc = [];
        array_push($cc, 'teams@shifl.com');

        // if (isset($content['schedule']) && $content['schedule'] != '' && isset($content['schedule'][0])){
        //     if (array_key_exists(1, $content['schedule'])) {
        //         if ($content['schedule'][0]->mode == 'Air' || $content['schedule'][1]->mode == 'Air' || $shipment->type == 'Air') {
        //             $is_air = true;
        //             $air_text = "Air ";
        //             array_push($cc, 'shia@shifl.com');
        //             array_push($cc, 'levan@shifl.com');
        //         } else {
        //             $is_air = false;
        //             $air_text = "";
        //         }
        //     } else {
        //         if ($content['schedule'][0]->mode == 'Air' || $shipment->type == 'Air') {
        //             $is_air = true;
        //             $air_text = "Air ";
        //             array_push($cc, 'shia@shifl.com');
        //             array_push($cc, 'levan@shifl.com');
        //         } else {
        //             $is_air = false;
        //             $air_text = "";
        //         }
        //     }
        // }else{
        //     $is_air = false;
        //     $air_text = "";
        // }

        if (isset($content['selected_schedule']) && !empty($content['selected_schedule'])) {
            // if (array_key_exists(1, $content['selected_schedule'])) {
            //     if ($content['selected_schedule']->mode == 'Air' || $content['selected_schedule']->mode == 'Air' || $shipment->type == 'Air') {
            //         $is_air = true;
            //         $air_text = "Air ";
            //         array_push($cc, 'shia@shifl.com');
            //         array_push($cc, 'levan@shifl.com');
            //     } else {
            //         $is_air = false;
            //         $air_text = "";
            //     }
            // } else {
            if ($content['selected_schedule']->mode == 'Air' || $shipment->type == 'Air') {
                $is_air = true;
                $air_text = "Air ";
                array_push($cc, 'shia@shifl.com');
                foreach (config('mail-settings.booking_email') as $email){
                    array_push($cc, $email);
                }
            } else {
                $is_air = false;
                $air_text = "";
            }
            // }
        } else {
            $is_air = false;
            $air_text = "";
        }


        $subjects = $air_text . "Departure Notice: ID#". $shipment->shifl_ref . " - PO#";

        $attachment = [];
        $ctr = 0;
        foreach ($suppliers as $s) {
            if ($s->hbl_copy && !is_object($s->hbl_copy)) {
                $path = storage_path('/app/public/'.$s->hbl_copy);
                if (file_exists($path)) {
                    array_push($attachment, $path);
                }
            }
            if ($s->packing_list && !is_object($s->packing_list)) {
                $path = storage_path('/app/public/'.$s->packing_list);
                if (file_exists($path)) {
                    array_push($attachment, $path);
                }
            }
            if ($s->commercial_documents && !is_object($s->commercial_documents)) {
                $path = storage_path('/app/public/'.$s->commercial_documents);
                if (file_exists($path)) {
                    array_push($attachment, $path);
                }
            }
            if ($s->commercial_invoice && !is_object($s->commercial_invoice)) {
                $arr_file = (array)$s->commercial_invoice;
                $path = storage_path('/app/public/'.$s->commercial_invoice);
                if (file_exists($path)) {
                    array_push($attachment, $path);
                }
            }
            if ($s->po_num) {
                $ctr++;
                if ($ctr == 0) {
                    $subjects .= $s->po_num;
                } else {
                    $subjects .= ', ' . $s->po_num;
                }
            }

            //additional attachment for new documents field related
            $checkSupplier = Supplier::find($s->supplier_id);

            if ( isset($checkSupplier->id) ) {
                $get_supplier_documents = $checkSupplier->documents;
                if ( $get_supplier_documents!==null && is_countable($get_supplier_documents) && count ($get_supplier_documents) > 0) {
                    foreach ( $get_supplier_documents as $get_supplier_document ) {

                        //if ( $get_supplier_document->is_added_by_customer == 0) {
                        if ( $shipment->id == $get_supplier_document->shipment_id ) {
                            $path = storage_path('/app/public/'.$get_supplier_document->path);
                            if ( file_exists($path) ) {
                                array_push( $attachment, $path);
                            }
                        }

                        //}
                    }
                }
            }
        }


        $newAttachments = [];
        //re validate the attachment
        $validFileTypes = ['pdf','jpg','png','docx','jfif','xlsx','xlsm','xlsb','xltx','xltm','xls','xlt'];
        //count the attachment
        if  ( count ($attachment) > 0 ) {
            //iterate attachment
            foreach ($attachment as $a) {
                if ( in_array(pathinfo($a,PATHINFO_EXTENSION),$validFileTypes)) {
                   array_push($newAttachments, $a);
                }
            }
        }
        //assigned with new attachment array
        $attachment = $newAttachments;

        $to = [];

        // foreach ($shipment->customer->customerAdmins as $customerAdmin) {
        //     array_push($to, $customerAdmin->email);
        // }
        $ctr = 1;
        if (is_array($shipment->customer->emails)) {
            foreach ($shipment->customer->emails as $email) {
                if ($email['email'] != '') {
                    //
                } else {
                    $ctr=0;
                }
            }

            if ($ctr == 0) {
                throw new \App\Exceptions\CustomException('No recipients set in customer.');
            } else {
                foreach ($shipment->customer->emails as $email) {
                    array_push($to, $email['email']);
                }
            }

            
            $adminUsers = 'shabsie@shifl.com';

            array_push($cc, $shipment->customer->manager);

            if ( isset($shipment->customer->departure_notice_emails) ) {
                $dynamic_emails = $shipment->customer->departure_notice_emails;
                
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
           
            $check_office_to = \App\ShiflOfficeEmail::where('type', 'to')
                                                ->where('office_id', $shipment->officeTo->id)
                                                ->first();

            $check_office_from = \App\ShiflOfficeEmail::where('type', 'from')
                                                ->where('office_id', $shipment->officeFrom->id)
                                                ->first();

            if ( isset($check_office_to->id) ) {

                $dynamic_emails = $check_office_to->departure_notice_emails;
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

            if ( isset($check_office_from->id) ) {
                
                $dynamic_emails = $check_office_from->departure_notice_emails;
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

            if ($shipment->customer->salesRepresentative) {
                array_push($cc, (is_object($shipment->customer->manager)) ? $shipment->customer->manager->email : $shipment->customer->manager);
                array_push($cc, (is_object($shipment->customer->salesRepresentative)) ? $shipment->customer->salesRepresentative->email : $shipment->customer->salesRepresentative);

                Mail::to($to)->cc($cc)->send(new ShipmentLeave($subjects, $content, $attachment, (is_object($shipment->customer->manager)) ? $shipment->customer->manager->email : $shipment->customer->manager));
                
            } else {

                Mail::to($to)->cc($cc)->send(new ShipmentLeave($subjects, $content, $attachment, (is_object($shipment->customer->manager)) ? $shipment->customer->manager->email : $shipment->customer->manager));
            }

        } else {
            throw new \App\Exceptions\CustomException('Something is wrong with the email recipients.');
        }

        //\Debugbar::info('Email Sent Successfully');
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
