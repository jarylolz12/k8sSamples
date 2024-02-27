<?php

namespace App\Listeners;

use App\AccountManager;
use App\Customer;
use App\Events\SendUpdatedBookingEmailEvent;
use App\PurchaseOrder;
use Illuminate\Support\Facades\Mail;
use App\Mail\SendBookingEmail;
use App;
use Carbon\Carbon;
use App\Supplier;
use App\Carrier;

class SendUpdatedBookingEmailEventListener
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
    public function handle(SendUpdatedBookingEmailEvent $event)
    {
        $shipment = $event->resource;
        $findShipment = $shipment;

        $mergeSchedules = (is_array(json_decode($findShipment->schedules_group_bookings))) ? json_decode($findShipment->schedules_group_bookings) : [];
        $mergeToScheduleBookings = (is_array(json_decode($findShipment->schedules_group))) ? json_decode($findShipment->schedules_group) : [];
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
        }

        $shipmentType = (isset($selectedSchedule[0]->type)) ? $selectedSchedule[0]->type : 'N/A';
        if (isset($selectedSchedule[0]) && isset($selectedSchedule[0]->carrier_name) && isset($selectedSchedule[0]->carrier_name->id)) {
            $findCarrier = Carrier::find(intval($selectedSchedule[0]->carrier_name->id));
            if (isset($findCarrier->name)) {
                $carrierName = $findCarrier->name;
            }
        }

        $mergeSuppliers = (is_array(json_decode($findShipment->suppliers_group_bookings))) ? json_decode($findShipment->suppliers_group_bookings) : [];
        $mergeToSupplierBookings = (is_array(json_decode($findShipment->suppliers_group))) ? json_decode($findShipment->suppliers_group) : [];
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

        $mergeContainers = (is_array(json_decode($findShipment->containers_group_bookings))) ? json_decode($findShipment->containers_group_bookings) : [];
        $mergeToContainerBookings = (is_array(json_decode($findShipment->containers_group))) ? json_decode($findShipment->containers_group) : [];
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
        $attachment = [];

        if (count($suppliers) > 0) {
            foreach ($suppliers as $key => $supplier) {
                if (isset($supplier->po_id) && $supplier->po_id!==null && $supplier->po_id!=='') {
                    $po = PurchaseOrder::find(intval($supplier->po_id));
                    array_push($po_number,(isset($po->name)) ? $po->name : '');
                    $suppliers[$key]->po_name = (isset($po->name)) ? $po->name : '';
                } else {
                    $suppliers[$key]->po_name = (isset($supplier->po_num)) ? $supplier->po_num : '';
                    array_push($po_number, (isset($supplier->po_num)) ? $supplier->po_num : '');
                }
                $suppliers[$key]->po_name_array = explode(',', $suppliers[$key]->po_name);

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
                            if ( strtolower($get_supplier_document->type)!=='hbl' && $findShipment->id==$get_supplier_document->shipment_id) {
                                if ( !in_array($get_supplier_document->name, $check_document_names)) {
                                    array_push($new_documents, $get_supplier_document);
                                    array_push($check_document_names, $get_supplier_document->name);
                                    $path = storage_path('/app/public/'.$get_supplier_document->path);
                                    if ( file_exists($path) ) {
                                        array_push( $attachment, $path);
                                    }
                                }
                            }
                        }
                    }
                }

                if(!empty($supplier->supplier_id)){
                    $suppliers[$key]->company_name = Supplier::findOrFail($supplier->supplier_id)->company_name;
                }

                if(!empty($supplier->buyer_id)){
                    $suppliers[$key]->company_name = Customer::findOrFail($shipment->customer_id)->company_name;
                }
            }
        }


        $newAttachments = [];
        $validFileTypes = ['pdf','jpg','png','docx','jfif','xlsx','xlsm','xlsb','xltx','xltm','xls','xlt'];
        if  ( count ($attachment) > 0 ) {
            foreach ($attachment as $a) {
                if ( in_array(pathinfo($a,PATHINFO_EXTENSION),$validFileTypes)) {
                   array_push($newAttachments, $a);
                }
            }
        }
        $attachment = $newAttachments;
        $po_num_join = implode('/', $po_number);
        $cheapest = 0;
        $cheapestKey = 0;

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
                        $validTo = '';
                        $newFinalSchedule->sell_rates[$keySecond]->validToValid = '';
                        if (isset($sell_rate->valid_to) && $sell_rate->valid_to!=='') {
                            $validTo = Carbon::parse($sell_rate->valid_to)->timestamp;
                        }
                        $newFinalSchedule->sell_rates[$keySecond]->validToValid = $validTo;
                        if ($validTo!=='') {
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
            "shipment_id" => $shipment->id,
            "cheapest_key" => $cheapestKey,
            "type" => $shipmentType,
            "selected_schedule" => $selectedSchedule,
            "CompanyName" => "Shifl",
            "InvDate" => date("m/d/Y", strtotime(date("Y-m-d"))),
            "customer" => $shipment->getCustomerImportName(),
            "po_number" => $po_number,
            "shifl_ref" => $shipment->shifl_ref,
            "schedule" => $newFinalSchedules,
            "status" => $shipment->shipment_status,
            "suppliers_group" => $suppliers,
            "containers_group" =>$newFinalContainers,
            "carrier" => $carrierName,
            "memo_customer" => (isset($shipment->memo_customer)) ? $shipment->memo_customer : '',
            "vessel" => $shipment->vessel,
            "mbl_num" => $shipment->mbl_num,
            "account_representative_phone" => (isset($shipment->customer)) ? (isset($shipment->customer->manager)) ? $shipment->customer->manager->phone : '' : '',
            "account_representative_email" => (isset($shipment->customer)) ? (isset($shipment->customer->manager)) ? $shipment->customer->manager->email : 'No match' : 'No match',
            "account_representative_name" => (isset($shipment->customer)) ? (isset($shipment->customer->manager)) ? $shipment->customer->manager->name : 'No match' : 'No match'
        ];

        $cc = [];
        array_push($cc, 'teams@shifl.com');
        $to = [];

        $officeFromEmails = collect([
            [
                'officeName' => 'Shifl USA',
                'emails' => config('mail-settings.update_booking_email')
            ],
            [
                'officeName' => 'Shifl China',
                'emails' => ['pricing@shifl.cn']
            ],
            [
                'officeName' =>  'Shifl Vietnam',
                'emails' => ['Joy@shifl.com', 'Leonardo@shifl.com']
            ],
            [
                'officeName' =>  'Shifl India',
                'emails' => ['Renu@shifl.com', 'Prateek@shifl.com']
            ],
            [
                'officeName' =>  'Shifl Malaysia',
                'emails' => config('mail-settings.update_booking_email')
            ]
        ]);

        if ( isset($shipment->officeFrom)) {
            if (isset($shipment->officeFrom->name)) {
                $officeFromFilteredEmails = $officeFromEmails->search(function ($item, $key) use($shipment) {
                    return $item['officeName'] == $shipment->officeFrom->name;
                });

                if($officeFromFilteredEmails >= 0){
                    $officeFromEmails[$officeFromFilteredEmails];
                    foreach ($officeFromEmails[$officeFromFilteredEmails]['emails'] as $officeEmail) {
                        $cc[] = $officeEmail;
                    }
                }
            }
        }

        if ( isset($shipment->customer)) {
            if (isset($shipment->customer->offices_managers)) {
                $allManagers = json_decode($shipment->customer->offices_managers);
                if ( is_array($allManagers) && count($allManagers) > 0 ) {
                    foreach ($allManagers as $key => $allManager) {
                        if ( $allManager->office_id==$shipment->officeFrom->id ) {
                            $setManager = AccountManager::find($allManager->manager_id);
                            if (isset($setManager->id)) {
                                array_push($cc, $setManager->email);
                            }
                        }
                    }
                }
            }
        }

        $subjects = sprintf('Updated Booking: ID#%d - PO#,%s', $shipment->shifl_ref, $po_num_join);
        if ( isset($shipment->customer)){
            $sendToDefault = false;
            if (isset($shipment->customer->booking_email_recipients) && $shipment->customer->booking_email_recipients!==null && $shipment->customer->booking_email_recipients!=='') {
                $booking_email_recipients = ( is_array($shipment->customer->booking_email_recipients)) ? $shipment->customer->booking_email_recipients : json_decode($shipment->customer->booking_email_recipients);
                if (is_array($booking_email_recipients)) {
                    if (count($booking_email_recipients)>0) {
                        foreach ($booking_email_recipients as $recipient) {
                            if(isset($recipient->label) && filter_var($recipient->label, FILTER_VALIDATE_EMAIL)){
                                $recipient = $recipient->label;
                            }
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

            if ( $sendToDefault ) {
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
            }

            if (isset($shipment->customer->manager)) {
                array_push($cc, (is_object($shipment->customer->manager)) ? $shipment->customer->manager->email : $shipment->customer->manager);
            }

            if (isset($shipment->customer->salesRepresentative)) {
                array_push($cc, (is_object($shipment->customer->salesRepresentative)) ? $shipment->customer->salesRepresentative->email : $shipment->customer->salesRepresentative);
            }
        }

        if (count($to)>0) {
            array_push($cc, 'frady@shifl.com');
            if ( isset($shipment->customer->booking_and_updated_emails) ) {
                $dynamic_emails = $shipment->customer->booking_and_update_emails;
                if ( $dynamic_emails !=null ) {
                    $dynamic_emails = ( is_array($dynamic_emails)) ? $dynamic_emails : json_decode($dynamic_emails);
                    $dynamic_emails = ($dynamic_emails == '') ? [] : $dynamic_emails;
                    foreach($dynamic_emails as $dynamic_email) {
                        if ( !in_array($dynamic_email, $cc)) {
                            if(isset($dynamic_email->label) && filter_var($dynamic_email->label, FILTER_VALIDATE_EMAIL)){
                                $dynamic_email = $dynamic_email->label;
                            }
                            array_push($cc, $dynamic_email);
                        }
                    }
                }
            }

            if ( isset($shipment->customer->booking_and_updated_emails) ) {
                $dynamic_emails = $shipment->customer->booking_and_updated_emails;
                if ( $dynamic_emails !=null ) {
                    $dynamic_emails = ( is_array($dynamic_emails)) ? $dynamic_emails : json_decode($dynamic_emails);
                    $dynamic_emails = ($dynamic_emails == '') ? [] : $dynamic_emails;
                    foreach($dynamic_emails as $dynamic_email ) {
                        if ( !in_array($dynamic_email, $cc)) {
                            if(isset($dynamic_email->label) && filter_var($dynamic_email->label, FILTER_VALIDATE_EMAIL)){
                                $dynamic_email = $dynamic_email->label;
                            }
                            array_push($cc, $dynamic_email);
                        }
                    }
                }
            }

            if ( isset($shipment->officeTo->booking_and_updated_emails) ) {
                $dynamic_emails = $shipment->officeTo->booking_and_updated_emails;
                if ( $dynamic_emails !=null ) {
                    $dynamic_emails = ( is_array($dynamic_emails)) ? $dynamic_emails : json_decode($dynamic_emails);
                    $dynamic_emails = ($dynamic_emails == '') ? [] : $dynamic_emails;
                    foreach($dynamic_emails as $dynamic_email ) {
                        if ( !in_array($dynamic_email, $cc)) {
                            if(isset($dynamic_email->label) && filter_var($dynamic_email->label, FILTER_VALIDATE_EMAIL)){
                                $dynamic_email = $dynamic_email->label;
                            }
                            array_push($cc, $dynamic_email);
                        }
                    }
                }
            }

            if ( isset($shipment->officeFrom->booking_and_updated_emails) ) {
                $dynamic_emails = $shipment->officeFrom->booking_and_updated_emails;
                if ( $dynamic_emails !=null ) {
                    $dynamic_emails = ( is_array($dynamic_emails)) ? $dynamic_emails : json_decode($dynamic_emails);
                    $dynamic_emails = ($dynamic_emails == '') ? [] : $dynamic_emails;
                    foreach($dynamic_emails as $dynamic_email ) {
                        if ( !in_array($dynamic_email, $cc)) {
                            if(isset($dynamic_email->label) && filter_var($dynamic_email->label, FILTER_VALIDATE_EMAIL)){
                                $dynamic_email = $dynamic_email->label;
                            }
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

                $dynamic_emails = $check_office_to->booking_and_updated_emails;
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

                $dynamic_emails = $check_office_from->booking_and_updated_emails;
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
            Mail::to($to)->cc($cc)->send(new SendBookingEmail($subjects, $content, $attachment));
            //Mail::to($to)->cc($cc)->send(new SendBookingEmail($subjects, $content, $attachment));
            if($shipment != null){
                if ($shipment->id != ''){
                    App\Shipment::where('id',$shipment->id)->update(['is_booking_email_sent' => '1']);
                }
            }
        }
    }
}
