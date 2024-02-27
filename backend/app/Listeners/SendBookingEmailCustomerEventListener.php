<?php

namespace App\Listeners;

use App\Events\SendBookingEmailCustomerEvent;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

use Illuminate\Support\Facades\Mail;
use App\Mail\SendBookingEmail;

use App\Terminal;
use App\TerminalRegion;
use App;
use Carbon\Carbon;
use App\Supplier;
use App\Carrier;
use App\Mail\SimpleString;


use Illuminate\Support\Facades\Storage;

class SendBookingEmailCustomerEventListener
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

    /**
     * Handle the event.
     *
     * @param  SendBookingEmailEvent  $event
     * @return void
     */
    public function handle(SendBookingEmailCustomerEvent $event)
    {
        //\Debugbar::info('hello');
        $shipment = $event->resource;
        $findShipment = $shipment;

        //ss
        //$shipmentType = $findShipment->type;

        //schedule merge
        $mergeSchedules = (is_array(json_decode($findShipment->schedules_group_bookings))) ? json_decode($findShipment->schedules_group_bookings) : [];

        $mergeToScheduleBookings = (is_array(json_decode($findShipment->schedules_group))) ? json_decode($findShipment->schedules_group) : [];

        //ss
        $newSchedules = [];

        foreach ($mergeSchedules as $key => $value) {
            array_push($newSchedules, $value->id);
        }

        foreach ($mergeToScheduleBookings as $key => $value) {
            if (!in_array($value->id, $newSchedules)) {
                array_push($newSchedules, $value->id);
            }
        }

        $newFinalSchedules = [];

        foreach($newSchedules as $n) {

            $setId = null;
            foreach ($mergeSchedules as $key => $value) {
               if ($n==$value->id) {
                    array_push($newFinalSchedules, $value);
                    $setId = $value->id;
               }
            }

            foreach ($mergeToScheduleBookings as $key => $value) {
                if ($n==$value->id && $setId!=$value->id) {
                    array_push($newFinalSchedules, $value);
               }
            }
        }

        //selected schedule
        $selectedSchedule = [];
        $carrierName = '';
        if (count($newFinalSchedules)>0) {

            $has_confirmed = false;

            foreach ($newFinalSchedules as $key => $value) {

                if ($value->is_confirmed==1) {
                    $has_confirmed = true;
                    array_push($selectedSchedule, $value);
                }
            }

            if (!$has_confirmed) {
                if(isset($newFinalSchedules[0]->carrier_name) && isset($newFinalSchedules[0]->carrier_name->id)) {
                    $findCarrier = Carrier::find(intval($newFinalSchedules[0]->carrier_name->id));
                    if (isset($findCarrier->name)) {
                        $carrierName = $findCarrier->name;
                    }
                }
            }
            /*
            if (!$has_confirmed) {
                array_push($selectedSchedule, $newFinalSchedules[0]);
            }*/
        }

        $shipmentType = (isset($selectedSchedule[0]->type)) ? $selectedSchedule[0]->type : 'N/A';
        if (isset($selectedSchedule[0]) && isset($selectedSchedule[0]->carrier_name) && isset($selectedSchedule[0]->carrier_name->id)) {

            $findCarrier = Carrier::find(intval($selectedSchedule[0]->carrier_name->id));
            if (isset($findCarrier->name)) {
                $carrierName = $findCarrier->name;
            }


        }

        //suppliers merge
        $mergeSuppliers = (is_array(json_decode($findShipment->suppliers_group_bookings))) ? json_decode($findShipment->suppliers_group_bookings) : [];

        $mergeToSupplierBookings = (is_array(json_decode($findShipment->suppliers_group))) ? json_decode($findShipment->suppliers_group) : [];

        //ss
        $newSuppliers = [];

        foreach ($mergeSuppliers as $key => $value) {
            array_push($newSuppliers, $value->id);
        }

        foreach ($mergeToSupplierBookings as $key => $value) {
            if (!in_array($value->id, $newSuppliers)) {
                array_push($newSuppliers, $value->id);
            }
        }

        $newFinalSuppliers = [];

        foreach($newSuppliers as $n) {

            $setId = null;
            foreach ($mergeSuppliers as $key => $value) {
               if ($n==$value->id) {
                    array_push($newFinalSuppliers, $value);
                    $setId = $value->id;
               }
            }

            foreach ($mergeToSupplierBookings as $key => $value) {
                if ($n==$value->id && $setId!=$value->id) {
                    array_push($newFinalSuppliers, $value);
               }
            }
        }


        //containers merge
        $mergeContainers = (is_array(json_decode($findShipment->containers_group_bookings))) ? json_decode($findShipment->containers_group_bookings) : [];

        $mergeToContainerBookings = (is_array(json_decode($findShipment->containers_group))) ? json_decode($findShipment->containers_group) : [];

        //ss
        $newContainers = [];

        foreach ($mergeContainers as $key => $value) {
            array_push($newContainers, $value->id);
        }

        foreach ($mergeToContainerBookings as $key => $value) {
            if (!in_array($value->id, $newContainers)) {
                array_push($newContainers, $value->id);
            }
        }

        $newFinalContainers = [];

        foreach($newContainers as $n) {

            $setId = null;
            foreach ($mergeContainers as $key => $value) {
               if ($n==$value->id) {
                    array_push($newFinalContainers, $value);
                    $setId = $value->id;
               }
            }

            foreach ($mergeToContainerBookings as $key => $value) {
                if ($n==$value->id && $setId!=$value->id) {
                    array_push($newFinalContainers, $value);
               }
            }
        }

        $suppliers = $newFinalSuppliers;
        $po_number = [];
        $po_ids = [];


        $attachment = [];

        if (count($suppliers) > 0) {

            foreach ($suppliers as $key => $supplier) {
                if (isset($supplier->po_id) && $supplier->po_id!==null && $supplier->po_id!=='') {
                    $po = \App\PurchaseOrder::find(intval($supplier->po_id));
                    array_push($po_number,(isset($po->name)) ? $po->name : '');
                    $suppliers[$key]->po_name = (isset($po->name)) ? $po->name : '';
                } else {
                    $suppliers[$key]->po_name = (isset($supplier->po_num)) ? $supplier->po_num : '';
                    array_push($po_number, (isset($supplier->po_num)) ? $supplier->po_num : '');
                }
                $suppliers[$key]->po_name_array = explode(',', $suppliers[$key]->po_name);

                if ($supplier->hbl_copy && !is_object($supplier->hbl_copy)) {
                    $path = storage_path('/app/public/'.$supplier->hbl_copy);
                    if (file_exists($path)) {
                        array_push($attachment, $path);
                    }
                }
                if ($supplier->packing_list && !is_object($supplier->packing_list)) {
                    $path = storage_path('/app/public/'.$supplier->packing_list);
                    if (file_exists($path)) {
                        array_push($attachment, $path);
                    }
                }
                if ($supplier->commercial_documents && !is_object($supplier->commercial_documents)) {
                    $path = storage_path('/app/public/'.$supplier->commercial_documents);
                    if (file_exists($path)) {
                        array_push($attachment, $path);
                    }
                }
                if ($supplier->commercial_invoice && !is_object($supplier->commercial_invoice)) {
                    $arr_file = (array)$supplier->commercial_invoice;
                    $path = storage_path('/app/public/'.$supplier->commercial_invoice);
                    if (file_exists($path)) {
                        array_push($attachment, $path);
                    }
                }

                //additional attachment for new documents field related
                $checkSupplier = Supplier::find($supplier->supplier_id);
                $suppliers[$key]->company_name = '';
                if ( isset($checkSupplier->id) ) {

                    $suppliers[$key]->company_name = (isset($checkSupplier->company_name)) ? $checkSupplier->company_name : '';

                    $get_supplier_documents = $checkSupplier->documents;
                    if ( $get_supplier_documents!==null && is_countable($get_supplier_documents) && count ($get_supplier_documents) > 0) {

                        $new_documents = [];
                        $check_document_names = [];

                        foreach ( $get_supplier_documents as $get_supplier_document ) {
                            //if ( $get_supplier_document->is_added_by_customer == 0) {
                            if ( $findShipment->id == $get_supplier_document->shipment_id ) {

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
        }

        $po_num_join = implode('/', $po_number);
        $containers = $newFinalContainers;


        $cheapest = 0;
        $cheapestKey = 0;


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


        if (count($newFinalSchedules) > 0) {

            foreach ($newFinalSchedules as $key => $newFinalSchedule) {

                if (isset($newFinalSchedule->etd) && isset($newFinalSchedule->eta)) {
                    $etd = Carbon::parse($newFinalSchedule->etd);
                    $eta = Carbon::parse($newFinalSchedule->eta);
                    $newFinalSchedules[$key]->transit = $etd->diffInDays($eta);

                } else {
                    $newFinalSchedules[$key]->transit = '';
                }
                $totalAmount = 0;
                if (isset($newFinalSchedule->sell_rates) && $newFinalSchedule->sell_rates!==null && $newFinalSchedule->sell_rates!=='' && count($newFinalSchedule->sell_rates) > 0) {

                    foreach ($newFinalSchedule->sell_rates as $keySecond => $sell_rate) {
                        $totalAmount = $totalAmount + ($sell_rate->qty * $sell_rate->amount);
                        $newFinalSchedules[$key]->sell_rates[$keySecond]->total = ($sell_rate->qty * $sell_rate->amount);

                        $hasValidity =false;
                        //convert eta to timestamp
                        $etaTimestamp = Carbon::parse($newFinalSchedule->eta)->timestamp;
                        $etdTimestamp = Carbon::parse($newFinalSchedule->etd)->timestamp;
                        //$validFrom = '';
                        $validTo = '';

                        //$newFinalSchedule->sell_rates[$keySecond]->validFromValid = '';
                        $newFinalSchedule->sell_rates[$keySecond]->validToValid = '';
                        /*
                        if (isset($sell_rate->valid_from) && $sell_rate->valid_from!=='') {
                            $validFrom = Carbon::parse($sell_rate->valid_from)->timestamp;
                        } */
                        if (isset($sell_rate->valid_to) && $sell_rate->valid_to!=='') {
                            $validTo = Carbon::parse($sell_rate->valid_to)->timestamp;
                        }
                        //$newFinalSchedule->sell_rates[$keySecond]->validFromValid = $validFrom;
                        $newFinalSchedule->sell_rates[$keySecond]->validToValid = $validTo;

                        //if ( $validFrom!=='' && $validTo!=='' ) {
                        if ($validTo!=='') {
                            //check if eta is outside timestamp
                            //if ( intval($etaTimestamp) >= intval($validFrom) && intval($etaTimestamp)<=intval($validTo)) {
                            /*    
                            if (intval($etdTimestamp) > intval($validTo)){
                                $hasValidity = true;
                            }*/
                            /*
                            if ( intval($etaTimestamp) <= intval($validTo)) {
                                $hasValidity = true;
                            }*/
                            $hasValidity = true;
                        }

                        if ($hasValidity) {
                            $newFinalSchedule->sell_rates[$keySecond]->hasValidity = $hasValidity;
                        }

                    }
                }
                if ($cheapest==0) {
                    $cheapest = $totalAmount;
                } else {
                    if ($totalAmount < $cheapest) {
                        $cheapest = $totalAmount;
                        $cheapestKey = $key;
                    }
                }

                $newFinalSchedules[$key]->set_carrier_name = '';

                if (isset($newFinalSchedule->carrier_name)) {
                    if (isset($newFinalSchedule->carrier_name->id)) {

                        $cCarrier = Carrier::find(intval($newFinalSchedule->carrier_name->id));
                        if (isset($cCarrier->id))
                            $newFinalSchedules[$key]->set_carrier_name = $cCarrier->name;
                    }
                }

                $newFinalSchedules[$key]->total_amount = $totalAmount;


            }
        }


        $content = [
            "customer_id" => $shipment->customer_id,
            "confirm_email" => 1,
            "shipment_id" => $shipment->id,
            "cheapest_key" => $cheapestKey,
            "type" => $shipmentType,
            "selected_schedule" => $selectedSchedule,
            "CompanyName" => "Shifl",
            "InvDate" => date("m/d/Y", strtotime(date("Y-m-d"))),
            // "customer" => $shipment->customer->company_name,
            "customer" => $shipment->getCustomerImportName(),
            "po_number" => $po_number,
            "shifl_ref" => $shipment->shifl_ref,
            "schedule" => $newFinalSchedules,
            "status" => $shipment->shipment_status,
            "suppliers_group" => $suppliers,
            "containers_group" =>$newFinalContainers,
            //"carrier" => isset($shipment->carrier->name) ? $shipment->carrier->name : '',
            "carrier" => $carrierName,
            "memo_customer" => (isset($shipment->memo_customer)) ? $shipment->memo_customer : '',
            "vessel" => $shipment->vessel,
            "mbl_num" => $shipment->mbl_num,
            "account_representative_phone" => (isset($shipment->customer)) ? (isset($shipment->customer->manager)) ? $shipment->customer->manager->phone : '' : '',
            "account_representative_email" => (isset($shipment->customer)) ? (isset($shipment->customer->manager)) ? $shipment->customer->manager->email : 'No match' : 'No match',
            "account_representative_name" => (isset($shipment->customer)) ? (isset($shipment->customer->manager)) ? $shipment->customer->manager->name : 'No match' : 'No match'
            //"type" => "dn"
        ];

        //$to = ['kenjos75@yahoo.com'];
        //$to = ['kenjos75@gmail.com'];
        //$to = ['kenjos75@gmail.com'];
        //$to = ['mdshamsc@gmail.com'];
        //$to = ['shabsie@shifl.com'];
        //$to = ['kenjos75@gmail.com'];
       //$to = ['kenjos75@yahoo.com', 'kenjos75@gmail.com'];
        //$to = ['shabsie@shifl.com'];
        //$cc = ['jing@shifl.cn'];
        //$to = ['shabsie@shifl.com'];
        //$cc = [];
        //$to = ['kenjos75@yahoo.com'];
        //$cc = ['shabsie@shifl.com', 'chris@shifl.cn', 'jing@shifl.cn', 'shia@shifl.com', 'lisa@shifl.cn'];
        //$cc = ['shabsie@shifl.com', 'shia@shifl.com'];
        $cc = [];
        //$cc = [];
        //check origin country from
        //send to all manager related to the office from
       // $cc = [];
        $to = [];

        array_push($cc, 'teams@shifl.com');

        if ( isset($shipment->officeFrom)) {

            if (isset($shipment->officeFrom->name)) {

                if ($shipment->officeFrom->name=='Shifl China') {
                    //special inclusion
                    array_push($cc, 'pricing@shifl.cn');
                }


                if ($shipment->officeFrom->name=='Shifl USA') {
                    //special inclusion
                    foreach (config('mail-settings.booking_email') as $email){
                        array_push($cc, $email);
                    }
                   // array_push($cc, 'shia@shifl.com');
                }

            }

            if ( isset($shipment->customer)) {
                if (isset($shipment->customer->offices_managers)) {

                    try {
                        $allManagers = json_decode($shipment->customer->offices_managers);

                        if ( is_array($allManagers) && count($allManagers) > 0 ) {
                            $checkAccountManager = null;

                            foreach ($allManagers as $key => $allManager) {

                                if ( $allManager->office_id==$shipment->officeFrom->id ) {
                                    $setManager = \App\AccountManager::find($allManager->manager_id);

                                    if (isset($setManager->id)) {
                                        array_push($cc, $setManager->email);
                                    }
                                }
                            }

                        }

                    } catch (Exception $e) {

                    }

                }
            }
        }
        $subjects = sprintf('Booking Confirmation: ID#%d - PO#,%s', $shipment->shifl_ref, $po_num_join);
        //Mail::to($to)->send(new SendBookingEmail($subjects, $content, $attachment));
        //ee

        //if shifl from usa
        //add levan@shifl.com
        //add shia@shifl.com


        if ( isset($shipment->customer)){

            //flag for sending to default email recipients
            $sendToDefault = false;

            //send to booking email recipients
            if (isset($shipment->customer->booking_email_recipients) && $shipment->customer->booking_email_recipients!==null && $shipment->customer->booking_email_recipients!=='') {
                
                $booking_email_recipients = ( is_array($shipment->customer->booking_email_recipients)) ? $shipment->customer->booking_email_recipients : json_decode($shipment->customer->booking_email_recipients);

                if (is_array($booking_email_recipients)) {
                    if (count($booking_email_recipients)>0) {
                        foreach ($booking_email_recipients as $recipient) {
                            array_push($to, $recipient);
                        }
                    } else {
                        $sendToDefault = true;
                    }
                } else {
                    $sendToDefault = true;
                }


            } else {
                $sendToDefault = true;
            }
            $customer_emails = [];

            //send to customer emails by default
            if ( $sendToDefault ) {
                if (isset($shipment->customer->emails)) {
                    $customer_emails = $shipment->customer->emails;
                    if ( is_countable($customer_emails) && count($customer_emails) > 0 ) {
                        foreach ($customer_emails as $email) {
                            array_push($to, $email['email']);
                        }
                    }
                }
            }

            if (isset($shipment->customer->manager)) {
                array_push($cc, (is_object($shipment->customer->manager)) ? $shipment->customer->manager->email : $shipment->customer->manager);
            }

            if (isset($shipment->customer->salesRepresentative)) {
                array_push($cc, (is_object($shipment->customer->salesRepresentative)) ? $shipment->customer->salesRepresentative->email : $shipment->customer->salesRepresentative);
            }
        }
        

        if (count($to)>0) {

            //add frady
            array_push($cc, 'frady@shifl.com');

            if ( isset($shipment->customer->booking_and_updated_emails) ) {
                $dynamic_emails = $shipment->customer->booking_and_update_emails;
                
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


            $check_office_to = \App\ShiflOfficeEmail::where('type', 'to')
                                                ->where('office_id', $shipment->officeTo->id)
                                                ->first();

            $check_office_from = \App\ShiflOfficeEmail::where('type', 'from')
                                                ->where('office_id', $shipment->officeFrom->id)
                                                ->first();


            if ( isset($shipment->customer->booking_confirmation_emails) ) {
                $dynamic_emails = $shipment->customer->booking_confirmation_emails;
                
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


            if ( isset($check_office_to->id) ) {

                $dynamic_emails = $check_office_to->booking_confirmation_emails;
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
                
                $dynamic_emails = $check_office_from->booking_confirmation_emails;
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
        

            Mail::to($to)->cc($cc)->send(new SendBookingEmail($subjects, $content, $attachment));
            
        }

    }
}
