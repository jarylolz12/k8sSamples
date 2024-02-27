<?php

namespace App\Listeners;

use App\Events\SendArrivalNoticeEvent;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

use Illuminate\Support\Facades\Mail;
use App\Mail\ShipmentLeave;
use App\Mail\BasicMail;


use PDF;
use App;
use App\Supplier;
use Illuminate\Support\Facades\Storage;
use Carbon\Carbon;
use App\Custom\ProcessSchedulesGroupBookings;
use App\Terminal49Shipment;
use App\Mail\SimpleString;
use App\TerminalRegion;

class SendArrivalNoticeEventListener
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
     * @param  SendArrivalNoticeEvent  $event
     * @return void
     */
    public function handle(SendArrivalNoticeEvent $event)
    {
        //\Debugbar::info('hello');
        $shipment = $event->resource;

        $suppliers = json_decode($shipment->suppliers_group_bookings ?? $shipment->suppliers_group);
        $containers = json_decode($shipment->containers_group_bookings ?? $shipment->containers_group);
        $content = [
            "CompanyName" => "Shifl",
            "InvDate" => date("m/d/Y", strtotime(date("Y-m-d"))),
            // "customer" => $shipment->customer->company_name,
            "customer" => $shipment->getCustomerImportName(),
            "shifl_ref" => $shipment->shifl_ref,
            "schedule" => json_decode($shipment->schedules_group_bookings),
            "status" => $shipment->shipment_status,
            "suppliers_group" => $suppliers,
            "containers_group" => $containers,
            // "carrier" => isset($shipment->carrier->name) ? $shipment->carrier->name : '',
            "ais_link" => $this->getAis($shipment),
            "vessel" => $shipment->vessel,
            "mbl_num" => $shipment->mbl_num,
            "terminal" => $shipment->terminal,
            "type" => "an",
        ];

        $processSchedulesGroupBookings = new ProcessSchedulesGroupBookings($shipment->schedules_group_bookings);
        $selected_schedule = $processSchedulesGroupBookings->getSchedule();


        //if tracking, process schedule with different logic
        $tracking_status = $shipment->getTrackingStatus();

        //set valid tracking labels
        $validTrackings = ['Auto Tracking', 'Auto Tracked', 'Manual Tracking', 'Manual Tracked'];

        //if tracking, process schedule with different logic
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
                                                            ->orWhere('name', 'LIKE' ,'%'.strtoupper($schedule_location_to).'%')
                                                            ->first();
    
                if ( isset($schedule_location_to_fetch->id )) {
                    $schedule_location_to_id = $schedule_location_to_fetch->id;
                    $schedule_location_to = $schedule_location_to_fetch->name;
                }
    
                //parse eta and etd
                $etd = \Carbon\Carbon::parse($schedule_etd);
                $eta = \Carbon\Carbon::parse($schedule_eta);
                if ( !empty($selected_schedule)) {
                    $selected_schedule->location_from = $schedule_location_from_id;
                    $selected_schedule->location_to = $schedule_location_to_id;
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
        //
        // if (isset($content['schedule']) && $content['schedule'] != '' && isset($content['schedule'][0])) {
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
        // } else {
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
                array_push($cc, 'levan@shifl.com');
            } else {
                $is_air = false;
                $air_text = "";
            }
            // }
        } else {
            $is_air = false;
            $air_text = "";
        }

        // container number
        $containers_group_bookings = json_decode($shipment->containers_group_bookings, true);
        $c_num = "";
        foreach ($containers_group_bookings as $k => $v) {
            if ($v['container_num']) {
                if ($k == 0) {
                    $c_num .= $v['container_num'];
                } else {
                    $c_num .= ', ' . $v['container_num'];
                }
            }
        }
        $subjects = $air_text . "Arrival Notice : ID#". $shipment->shifl_ref ." - MBL#". $shipment->mbl_num ." - Container#". $c_num ." - PO#";

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

                    $new_documents = [];
                    $check_document_names = [];
                    foreach ( $get_supplier_documents as $get_supplier_document ) {
                        if ( $shipment->id === $get_supplier_document->shipment_id ) {
                            
                            if ( !in_array($get_supplier_document->name, $check_document_names)) {
                                array_push($new_documents, $get_supplier_document);
                                array_push($check_document_names, $get_supplier_document->name);

                                $path = storage_path('/app/public/'.$get_supplier_document->path);
                                if ( file_exists($path) ) {
                                    array_push( $attachment, $path);
                                }
                            }
                            
                            
                        }
                        //}   
                    }
                }    
            }
        }


        $pdf = PDF::loadView('pdf.an', ['content' => $content]);

        Storage::put('public/shipment/pdf/an_#'.$shipment->shifl_ref.'.pdf', $pdf->download()->getOriginalContent());

        array_push($attachment, storage_path('app/public/shipment/pdf/an_#'.$shipment->shifl_ref.'.pdf'));

        $to = [];

        // foreach ($shipment->customer->customerAdmins as $customerAdmin) {
        //     array_push($to, $customerAdmin->email);
        // }

        if (isset($shipment->customer->emails)) {
            $customer_emails = $shipment->customer->emails;
            
            if ( is_countable($customer_emails) && count($customer_emails) > 0 ) {
                foreach ($customer_emails as $email) {
                    array_push($to, $email['email']);
                }
            }
        } else {
            array_push($to, 'kenneth@shifl.com');
        }

        
        $adminUsers = 'shabsie@shifl.com';
        array_push($cc, 'Carla@shifl.com');

        if (! isset($shipment->customsBroker)) {
            if ($shipment->customs_broker_id === 0) {
                $cc[] = 'customs@shifl.com';
            }
        } else {
            foreach (json_decode($shipment->customsBroker->emails, true) as $email) {
                $cc[] = $email;
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


        array_push($cc, $shipment->customer->manager);

        //save original cc value
        $original_cc = $cc;

        if ( isset($shipment->customer->arrival_notice_emails) ) {
            $dynamic_emails = $shipment->customer->arrival_notice_emails;
            
            if ( $dynamic_emails !=null ) {

                $dynamic_emails = ( is_array($dynamic_emails)) ? $dynamic_emails : json_decode($dynamic_emails);

                $dynamic_emails = ($dynamic_emails == '') ? [] : $dynamic_emails;
                foreach($dynamic_emails as $dynamic_email ) {
                    if ( !in_array($dynamic_email, $cc)) {
                        array_push($cc, $dynamic_email);
                    }
                }
            }                
        }
       
        //s
        //check office to emails
        $check_office_to = \App\ShiflOfficeEmail::where('type', 'to')
                                            ->where('office_id', $shipment->officeTo->id)
                                            ->first();

        $check_office_from = \App\ShiflOfficeEmail::where('type', 'from')
                                            ->where('office_id', $shipment->officeFrom->id)
                                            ->first();

        if ( isset($check_office_to->id) ) {
            $dynamic_emails = $check_office_to->arrival_notice_emails;
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
            
            $dynamic_emails = $check_office_from->arrival_notice_emails;
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
        //e



        if ($shipment->customer->salesRepresentative) {
            array_push($cc, (is_object($shipment->customer->manager)) ? $shipment->customer->manager->email : $shipment->customer->manager);
            array_push($cc, (is_object($shipment->customer->salesRepresentative)) ? $shipment->customer->salesRepresentative->email : $shipment->customer->salesRepresentative);

            Mail::to($to)->cc($cc)->send(new ShipmentLeave($subjects, $content, $attachment, (is_object($shipment->customer->manager)) ? $shipment->customer->manager->email : $shipment->customer->manager));
            
        } else {
            array_push($cc, (is_object($shipment->customer->manager)) ? $shipment->customer->manager->email : $shipment->customer->manager);

            Mail::to($to)->cc($cc)->send(new ShipmentLeave($subjects, $content, $attachment, (is_object($shipment->customer->manager)) ? $shipment->customer->manager->email : $shipment->customer->manager));
        }

        //revert back to original cc
        $cc = $original_cc;

        \DB::table("shipments")->where("id", "=", $shipment->id)->update([
          'arrival_notice' => 1,
          'an_sent_at' =>  Carbon::now()->setTimezone('America/New_York')->format("m/d/y  h:i a")
        ]);

        // $shipment->arrival_notice = 1;
        // $shipment->an_sent_at = Carbon::now()->setTimezone('America/New_York')->format("m/d/y  h:i a");
        // $shipment->save();

        // validate container count
        if ($shipment->mbl_num ?? false) {
            $terminal49_shipment = Terminal49Shipment::find($shipment->mbl_num);
            if ($terminal49_shipment) {
                // $to = ['tanvir@shifl.com'];
                $shipment_containers_count = count(json_decode($shipment->containers_group_bookings, true) ?? []);
                $terminal49_containers_count = count(json_decode($terminal49_shipment->containers, true) ?? []);
                if ($shipment_containers_count != $terminal49_containers_count) {
                    $subject = "Container Count Mismatch! Ref#".$shipment->shifl_ref.", MBL#".$shipment->mbl_num;
                    $message = "For the container you are saying terminal 49 indicates that this shipment has a different container count than entered in our system. Please double check containers to make sure we did not miss a container on the arrival notice and delivery order";
                    // $office_to = $shipment->officeTo;
                    // if ($office_to) {
                    //     if ($office_to->managers) {
                    //         Mail::to($office_to->managers)->cc($cc)->send(new SimpleString($subject, $message));
                    //     }
                    // }
                    // temp
                    $cc = ['shia@shifl.com', 'chana@shifl.com'];

                    if ( isset($shipment->officeTo->container_count_mismatch_emails) ) {
                        $dynamic_emails = $shipment->officeTo->container_count_mismatch_emails;   
                        if ( $dynamic_emails !=null ) {

                            $dynamic_emails = ( is_array($dynamic_emails)) ? $dynamic_emails : json_decode($dynamic_emails);
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

                    if ( isset($shipment->officeFrom->container_count_mismatch_emails) ) {
                        $dynamic_emails = $shipment->officeFrom->container_count_mismatch_emails;
                        
                        if ( $dynamic_emails !=null ) {

                            $dynamic_emails = ( is_array($dynamic_emails)) ? $dynamic_emails : json_decode($dynamic_emails);
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

                    Mail::to(['jayakumar@shifl.com', 'aravinthsamy@shifl.com'])->cc($cc)->send(new SimpleString($subject, $message));

                }
            }
        }
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
