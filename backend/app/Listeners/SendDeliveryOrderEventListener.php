<?php

namespace App\Listeners;

use App\Events\SendDeliveryOrderEvent;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

use Illuminate\Support\Facades\Mail;
use App\Mail\ShipmentLeave;

use PDF;
use App;

use Illuminate\Support\Facades\Storage;
use Carbon\Carbon;

use App\Terminal49Shipment;
use App\Mail\SimpleString;
use App\Trucker;
use App\CustomTruckingDO;
use App\Terminal;
use App\Customer;

class SendDeliveryOrderEventListener
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

    public function array_unique_multidimensional($input)
    {
        $serialized = array_map('serialize', $input);
        $unique = array_unique($serialized);
        return array_intersect_key($input, $unique);
    }

    public function merge_group($array_first, $array_second) {

        $merge_first = (is_array(json_decode($array_first))) ? json_decode($array_first) : [];
        $merge_to_second = (is_array(json_decode($array_second))) ? json_decode($array_second) : [];
        $merge_arrays = array_merge($merge_first, $merge_to_second);
        $final_data = $this->array_unique_multidimensional($merge_arrays);
        return json_encode($final_data);
    }


    /**
     * Handle the event.
     *
     * @param  SendDeliveryOrderEvent  $event
     * @return void
     */
    public function handle(SendDeliveryOrderEvent $event)
    {
        // \Debugbar::info('hello');
        $shipment = $event->resource;

        if( empty($shipment->terminal_id) ){
            throw new \Exception('We cannot send the delivery order without having first put in the terminal');
        }

        $suppliers = $shipment->suppliers_group_bookings ? json_decode($shipment->suppliers_group ) : [];

        $containers = json_decode($this->merge_group($shipment->containers_group, $shipment->containers_group_bookings));

        //$containers = $shipment->containers_group_bookings ? json_decode($shipment->containers_group ) : [];
        $content = [
            "CompanyName" => "Shifl",
            "InvDate" => date("m/d/Y", strtotime(date("Y-m-d"))),
            // "customer" => $shipment->customer->company_name,
            "customer" => $shipment->getCustomerImportName(),
            "shifl_ref" => $shipment->shifl_ref,
            "schedule" => json_decode($shipment->schedules_group_bookings),
            "status" => $shipment->shipment_status,
            "suppliers_group" =>null,
            // "suppliers_group" =>json_decode($event->resource->suppliers_group),
            "containers_group" =>$containers,
            // "carrier" => isset($shipment->carrier->name) ? $shipment->carrier->name : '',
            "ais_link" => $this->getAis($shipment),
            "vessel" => $shipment->vessel,
            "mbl_num" => $shipment->mbl_num,
            "terminal" => $shipment->terminal,
            "trucker" => $shipment->trucker->name ?? null,
            "notes" => $shipment->trucker_custom_note,
            "type" => "do"
        ];

        $processSchedulesGroupBookings = new \App\Custom\ProcessSchedulesGroupBookings($shipment->schedules_group_bookings);

        $selected_schedule = $processSchedulesGroupBookings->getSchedule();

        $content['selected_schedule'] = $selected_schedule;

        $cc = [];

        array_push($cc, 'teams@shifl.com');
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
                foreach (config('mail-settings.booking_email') as $email){
                    array_push($cc, $email);
                }

                //add levan
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

        $subjects = $air_text . "Delivery Order : ID#". $shipment->shifl_ref . " | PO# ";

        $attachment = [];

        $location_to = '';
        $eta = '';
        $carrier = '';
        // foreach ($content['schedule'] as $schedule) {
        //     if ($schedule->location_to) {
        //         $location_to =  \App\TerminalRegion::find($schedule->location_to)->name;
        //     }
        //     if ($schedule->eta) {
        //         $eta = \Carbon\Carbon::parse($schedule->eta)->format('m/d/y');
        //     }
        // }
        if (isset($selected_schedule)) {
            if ($selected_schedule->location_to ?? false) {
                $location_to =  \App\TerminalRegion::find($selected_schedule->location_to)->name ?? '';
            }
            if ($selected_schedule->eta ?? false) {
                $eta = \Carbon\Carbon::parse($selected_schedule->eta)->format('m/d/y');
            }
            if ($selected_schedule->carrier_name ?? false) {
                $carrier = \App\Carrier::find(intval($selected_schedule->carrier_name->id))->name ?? '' ;
            }
        }

        $content['location_to'] = $location_to;
        $content['eta'] = $eta;
        $content['carrier'] = $carrier;

        // foreach ($suppliers as $s) {
        //     if ($s->hbl_copy) {
        //         $path = storage_path('/app/public/'.$s->hbl_copy);
        //         if (file_exists($path)) {
        //             array_push($attachment, $path);
        //         }
        //     }
        //     if ($s->packing_list) {
        //         $path = storage_path('/app/public/'.$s->packing_list);
        //         if (file_exists($path)) {
        //             array_push($attachment, $path);
        //         }
        //     }
        //     if ($s->commercial_documents) {
        //         $path = storage_path('/app/public/'.$s->commercial_documents);
        //         if (file_exists($path)) {
        //             array_push($attachment, $path);
        //         }
        //     }
        //     if ($s->commercial_invoice) {
        //         $path = storage_path('/app/public/'.$s->commercial_invoice);
        //         if (file_exists($path)) {
        //             array_push($attachment, $path);
        //         }
        //     }
        // }

        // $pdf = App::make('dompdf.wrapper');
        // $pdf->loadHTML('<h1>Test</h1>');
        // return $pdf->stream();``

        $product_description = '';
        $po = '';
        $ctr = 0;
        foreach ($suppliers as $supplier) {
            if ($supplier->product_description) {
                $product_description .= $supplier->product_description . ' . ';
            }
            if ($supplier->po_num) {
                // code...
                $po .= $supplier->po_num . ',';
            }
            if ($supplier->po_num) {
                if ($ctr == 0) {
                    $subjects .= $supplier->po_num;
                } else {
                    $subjects .= ', ' . $supplier->po_num;
                }
                $ctr++;
            }
        }

        $content["product_description"] = $product_description;
        $content["po"] = $po;
        if ($suppliers) {
            $content["ams"] = $suppliers[0]->ams_num;
        } else {
            $content["ams"] = '';
        }
        //
        $subjects .= " | Container# ";
        $should_add_comma = false;
        foreach ($containers ?? [] as $container) {
          // code...
          if($should_add_comma){
             $subjects .= ', ';
          }else {
             $should_add_comma = true;
          }
          $subjects .= $container->container_num;
        }
        $subjects .= " | MBL# ". $shipment->mbl_num;
        //

        $pdf = PDF::loadView('pdf.do', ['content' => $content])->setPaper(array(0,0,630,800))->setOptions(['dpi' => 72, 'isRemoteEnabled' => true]);

        Storage::put('public/shipment/pdf/do_#'.$shipment->shifl_ref.'.pdf', $pdf->download()->getOriginalContent());

        array_push($attachment, storage_path('app/public/shipment/pdf/do_#'.$shipment->shifl_ref.'.pdf'));


        $to = [];

        // foreach ($shipment->customer->customerAdmins as $customerAdmin) {
        //     array_push($to, $customerAdmin->email);
        // }

        if ($shipment->copy_customer) {
            if (is_array($shipment->customer->emails)) {
                foreach ($shipment->customer->emails as $email) {
                    array_push($to, $email['email']);
                }
            } else {
                array_push($to, 'anthony@shifl.com');
            }
        }


        //type checking the trucker if it is object
        if ( isset ($shipment->trucker) ) {
            if ( isset($shipment->trucker->email) ) {
                array_push($to, $shipment->trucker->email);
            }
            if ( isset($shipment->trucker->email_recipient) && $shipment->trucker->email_recipient!=='' && $shipment->trucker->email_recipient!==null && is_countable($shipment->trucker->email_recipient) && count($shipment->trucker->email_recipient) > 0) {
                foreach ( $shipment->trucker->email_recipient as $email ) {
                    array_push($to, $email['email']);
                }
            } else {
                throw new \Exception('Trucker recipient email not found');
            }
        }

        //temporary solution for trying to get proparty email on non object
        /*
        if ($shipment->trucker->email ?? false) {
            array_push($to, $shipment->trucker->email);
        }

        if( empty($shipment->trucker->email_recipient) ){
            throw new \Exception('Trucker recipient email not found');
        }else{
            if ($shipment->trucker->email_recipient) {
            foreach ($shipment->trucker->email_recipient as $email) {
                    array_push($to, $email['email']);
                }
            }
        }*/

        $adminUsers = 'shabsie@shifl.com';


        array_push($cc, $shipment->customer->manager);

        if ( isset($shipment->customer->delivery_order_emails) ) {
            $dynamic_emails = $shipment->customer->delivery_order_emails;

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

        $is_delivery_recipients = false;
        $send_it = false;

        if ($shipment->customer->salesRepresentative) {
            array_push($cc, (is_object($shipment->customer->manager)) ? $shipment->customer->manager->email : $shipment->customer->manager);
            array_push($cc, (is_object($shipment->customer->salesRepresentative)) ? $shipment->customer->salesRepresentative->email : $shipment->customer->salesRepresentative);
            $is_delivery_recipients = true;
            $send_it = true;

        } else {
            array_push($cc, $shipment->customer->manager);
            $is_delivery_recipients = true;
            $send_it = true;
        }

        if ( $is_delivery_recipients ) {
            $check_office_to = \App\ShiflOfficeEmail::where('type', 'to')
                                                ->where('office_id', $shipment->officeTo->id)
                                                ->first();
            $check_office_from = \App\ShiflOfficeEmail::where('type', 'from')
                                                ->where('office_id', $shipment->officeFrom->id)
                                                ->first();

            if ( isset($check_office_to->id) ) {

                $dynamic_emails = $check_office_to->delivery_order_emails;
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

                $dynamic_emails = $check_office_from->delivery_order_emails;
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

        if ( $send_it ) {
            Mail::to($to)->cc($cc)->send(new ShipmentLeave($subjects, $content, $attachment, (is_object($shipment->customer->manager)) ? $shipment->customer->manager->email : $shipment->customer->manager));
        }

        $shipment->delivery_order_left = 1;
        $shipment->do_sent_at = Carbon::now()->setTimezone('America/New_York')->format("m/d/y  h:i a");
        $shipment->save();

        // validate container count
        if ($shipment->mbl_num ?? false) {
            $terminal49_shipment = Terminal49Shipment::find($shipment->mbl_num);
            if ($terminal49_shipment) {
                // $to = ['tanvir@shifl.com'];
                //$shipment_containers_count = count(json_decode($shipment->containers_group_bookings, true) ?? []);
                $shipment_containers_count = count($containers);
                $terminal49_containers_count = count(json_decode($terminal49_shipment->containers, true) ?? []);
                if ($shipment_containers_count != $terminal49_containers_count) {
                    $cc = ['Shabsie@shifl.com','chana@shifl.com'];

                    $subject = "Container Count Mismatch! Ref#".$shipment->shifl_ref.", MBL#".$shipment->mbl_num;
                    $message = "For the container you are saying terminal 49 indicates that this shipment has a different container count than entered in our system. Please double check containers to make sure we did not miss a container on the arrival notice and delivery order";
                    // $office_to = $shipment->officeTo;
                    // if ($office_to) {
                    //     if ($office_to->managers) {
                    //         Mail::to($office_to->managers)->cc($cc)->send(new SimpleString($subject, $message));
                    //     }
                    // }

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

                    //Mail::to(['chana@shifl.com'])->send(new SimpleString($subject, $message));
                    Mail::to(['chana@shifl.com'])->cc($cc)->send(new SimpleString($subject, $message));

                }
            }
        }

        /**
         *  Alex Added Shifl Auto Generated Do for Trucking Platform
         *  Feeding New Do  to Trucking as Incoming Delivery Order
         */
        try {
            //code...
            $delivery_order_objectConnect = \DB::connection('mysql-trucking');
            $shipment = $event->resource;
            // check the trucker if it has trucking_company_key
            // if it does, then it is connected to the trucking Backend Trucker Resource.
            $customer_trucker_resource = Trucker::where('id',$shipment->trucker_id)->where('trucking_company_key','<>','');

            // Check if it has Mbl #
            // we dont allow empty MBL it is required.
            if( !empty($shipment->mbl_num))
            {
                if($customer_trucker_resource->count())
                {
                    // Check if the do is existing in Trucking Platform by central_shipment_id
                    $get_central_shipment_id_in_trucking_be = CustomTruckingDO::where('central_shipment_id',$shipment->id);
                    $customer_trucker_resource = $customer_trucker_resource->first();

                    $has_do_in_trucking_be = $delivery_order_objectConnect->table('truckers')->where('trucking_company_key',$customer_trucker_resource->trucking_company_key);

                    // If Zero no Do yet in Trucking
                    if($has_do_in_trucking_be->count())
                    {
                        $delivery_order_object = ( $get_central_shipment_id_in_trucking_be->count() == 0 ) ? new CustomTruckingDO() : $get_central_shipment_id_in_trucking_be->first();

                        // $shipment->id we use this to connect to trucking delivery order column central_shipment_id,
                        // if we send and is already in trucking DB then we update
                        $delivery_order_object->central_shipment_id = $shipment->id;

                        $delivery_order_object->mbl_number    = $shipment->mbl_num;
                        $delivery_order_object->eta           = date('Y-m-d', strtotime($shipment->eta));
                        $delivery_order_object->vessel        = $shipment->vessel;
                        $delivery_order_object->trucker       = $has_do_in_trucking_be->first()->id;   // trucking owner in trucking platform
                        $delivery_order_object->carrier       = !empty($shipment->carrier_id) ? Carrier::where('id',$shipment->carrier_id)->first()->name : '';
                        $delivery_order_object->terminal_name = !empty($shipment->terminal_id)? Terminal::where('id',$shipment->terminal_id)->first()->name : '';
                        $delivery_order_object->customer_ref_number = !empty($shipment->po_num) ? '[{"customer_ref_number":'.$shipment->po_num.'}]' : NULL;
                        $delivery_order_object->containers_group_bookings = !empty( $shipment->containers_group_bookings && $shipment->containers_group_bookings !='[]') ? $shipment->containers_group_bookings : null;
                        $delivery_order_object->description   = $shipment->trucker_custom_note;
                        $delivery_order_object->customer_name = !empty($shipment->customer_id) ? Customer::where('id',$shipment->customer_id)->first()->company_name : '';
                        $delivery_order_object->source_of_creation = 'SD'; // SHIFL AUTO DO
                        $delivery_order_object->contacts =   json_encode([$shipment->officeToAccountManager()->email]);
                        $delivery_order_object->iscustomernotifysend = 0; // send email to customer
                        $delivery_order_object->created = \Carbon\Carbon::now()->format('Y-m-d'); // send email to customer
                        $objectCentral =  $delivery_order_objectConnect->table('object_option');
                        $delivery_order_object->save();
                        $centralUrl =  $objectCentral->where('option_name', 'central_url')->first();
                        if($centralUrl){
                            $objectCentral->where('option_name', 'central_url')->update(array('option_value' => \URL::to('/')));
                        }else{
                            $objectCentral->insert(
                                array('option_name' => 'central_url', 'option_value' => \URL::to('/'))
                            );
                        }
                        // save Delivery order with Default Pending
                        return 'Successfully Saved.';
                    }else{
                        throw new \Exception("We can not send the Delivery Order because the Trucking Company Key does not exist.");
                    }
                }else{
                    throw new \Exception('We can not send the Delivery Order because the Trucking Company Key does not exist');
                }
            }else{
                throw new \Exception('Unable to send the Delivery Order because it has no MBL#.');
            }
        } catch (\Exception $e) {
            //throw $th;

            $html = "";
            $errors = [['title' => $e->getMessage(), 'trace' => $e->getTraceAsString()]];
            foreach ($errors as $key => $error) {
                $html .= "<h5> " . $error["title"] . "</h5> <br> <pre> " . $error["trace"] . "</pre> <br>";
            }
            \Mail::to(["tanvir@shifl.com","alex@shifl.com"])
                  ->send(new \App\Mail\SimpleString("Shifl DO Failure Log - #REF" . $shipment->shifl_ref ?? '', $html));
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
