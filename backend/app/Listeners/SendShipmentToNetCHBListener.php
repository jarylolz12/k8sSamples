<?php

namespace App\Listeners;

use SoapClient;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Mail;
use App\Mail\ShipmentLeave;
use App\TerminalRegion;
use Carbon\Carbon;
use Illuminate\Support\Facades\Storage;
use PDF;
use App\Custom\ProcessSchedulesGroupBookings;
use App\ImportNames;
use App\Supplier;

class SendShipmentToNetCHBListener
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
     * @param  object  $event
     * @return void
     */
    public function handle($event)
    {
        $shipment = $event->resource;
        
        //validate shifl reference number
        $pattern = "/[\D]/";
        if ( preg_match($pattern, $shipment->shifl_ref) == 1) {
            throw new \ErrorException("Please take out letters out from your reference number.");
            return false;
        }


        $suppliers = json_decode($shipment->suppliers_group);
        $suppliersCommercialDocs = $shipment->suppliers_commercial_docs ? json_decode($shipment->suppliers_commercial_docs) : null;

        // cc to the customer account manager
        $cc = [$shipment->customer->manager->email];
        // \Debugbar::info($cc);

        array_push($cc, 'teams@shifl.com');
        
        $customerImportName = $shipment->getCustomerImportName();
        $content = [
            "CompanyName" => "Shifl",
            "InvDate" => date("m/d/Y", strtotime(date("Y-m-d"))),
            // "customer" => $shipment->customer->company_name,
            "customer" => $customerImportName,
            "shifl_ref" => $shipment->shifl_ref,
            "schedule" => json_decode($shipment->schedules_group_bookings),
            "status" => $shipment->shipment_status,
            "suppliers_group" => json_decode($event->resource->suppliers_group),
            "containers_group" => json_decode($event->resource->containers_group),
            "carrier" => isset($shipment->carrier->name) ? $shipment->carrier->name : '',
            "vessel" => $shipment->vessel,
            "mbl_num" => $shipment->mbl_num,
            "terminal" => $shipment->terminal,
            "type" => "an"
        ];

        $processSchedulesGroupBookings = new ProcessSchedulesGroupBookings($shipment->schedules_group_bookings);

        $selected_schedule = $processSchedulesGroupBookings->getSchedule();

        $content['selected_schedule'] = $selected_schedule;

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


        $subjects = $air_text . "Customs Entry Notice : ID#" . $shipment->shifl_ref . " - PO#";

        // \Debugbar::info($suppliers);  shifl_ref
        $attachment = [];
        $ctr = 0;
        foreach ($suppliers as $s) {
            if ($s->hbl_copy && !is_object($s->hbl_copy)) {
                $path = storage_path('/app/public/' . $s->hbl_copy);
                if (file_exists($path)) {
                    array_push($attachment, $path);
                }
            }
            if ($s->packing_list && !is_object($s->packing_list)) {
                $path = storage_path('/app/public/' . $s->packing_list);
                if (file_exists($path)) {
                    array_push($attachment, $path);
                }
            }
            if ($s->commercial_documents && !is_object($s->commercial_documents)) {
                $path = storage_path('/app/public/' . $s->commercial_documents);
                if (file_exists($path)) {
                    array_push($attachment, $path);
                }
            }
            if ($s->commercial_invoice && !is_object($s->commercial_invoice)) {
                $path = storage_path('/app/public/' . $s->commercial_invoice);
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
                        //if ( $get_supplier_document->is_added_by_customer == 0) {

                        //}
                    }
                }
            }
        }

        if ($suppliersCommercialDocs && count($suppliersCommercialDocs) > 0) {
            foreach ($suppliersCommercialDocs as $supplierCommercialDoc) {
                foreach ($supplierCommercialDoc->commercial_documents as $cd) {
                    $path = storage_path('/app/public/' . $cd->file);
                    if (file_exists($path)) {
                        array_push($attachment, $path);
                    }
                }
            }
        }

        $pdf = PDF::loadView('pdf.an', ['content' => $content]);

        Storage::put('public/shipment/pdf/an_#' . $shipment->shifl_ref . '.pdf', $pdf->download()->getOriginalContent());

        array_push($attachment, storage_path('app/public/shipment/pdf/an_#' . $shipment->shifl_ref . '.pdf'));


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

        $to = ['entry@shipofl.com'];
        // $bcc = ['Jorgelina@shifl.com'];

        $entryDate = Carbon::now()->format('Y-m-d');
        $terminalRegionId = null;
        $shipmenSchedulesGroupBookings = json_decode($shipment->schedules_group_bookings);

        foreach ($shipmenSchedulesGroupBookings as $scheduleBooking) {
            if (property_exists($scheduleBooking, 'is_confirmed') && $scheduleBooking->is_confirmed) {
                $terminalRegionId = $scheduleBooking->location_to;
                break;
            }
        }
        
        //if tracking, process schedule with different logic
        $tracking_status = $shipment->getTrackingStatus();

        //set valid tracking labels
        $validTrackings = ['Auto Tracking', 'Auto Tracked', 'Manual Tracking', 'Manual Tracked'];
    
        // check if it's tracking shipment
        if( in_array($tracking_status, $validTrackings) && $shipment->mbl_num!==null && $shipment->mbl_num!=='' ){
            if(!empty($shipment['mbl_num'])){
                $terminal49_shipment = $shipment->terminal_fortynine;
                if( isset($terminal49_shipment->id) ){
                    $attributes = json_decode($terminal49_shipment->attributes,true);
                    $location_to = $attributes['port_of_discharge_name'];
                    $terminalRegionIdTracking = TerminalRegion::where('name','LIKE','%'.$location_to.'%')->first();
                    if ( isset($terminalRegionIdTracking->id) ) {
                        $terminalRegionId = $terminalRegionIdTracking->id;
                    }
                }
            }
        }
        
        // Header Fields
        if (!$terminalRegionId) {
            throw new \ErrorException("No terminal region ID");
        }
        if (!$shipment->customer->ein) {
            throw new \ErrorException("No Customer EIN");
        }
        $customerEin = $shipment->customer->ein;
        $importEin = '';
        if(isset($shipment->import_name_id)){
            $importName = (new ImportNames)->getImportNameByShipmentImportNameId($shipment)->first();
            $importEin = $importName ? $importName->ein : $customerEin;
        }

        $customerEin = ( $importEin==='' || $importEin===null ) ? $customerEin : $importEin;

        $terminalRegion = TerminalRegion::find($terminalRegionId);
        if (!$terminalRegion) {
            throw new \ErrorException("No Terminal Region");
        }
        if (!$terminalRegion->code) {
            throw new \ErrorException("No Terminal Region Code");
        }
        $customEntryNo = str_pad($shipment->shifl_ref, 7, "0", STR_PAD_RIGHT);

        // Manifest Fields
        $suppliersBillOfLading = json_decode($shipment->suppliers_group_bookings);
        $manifestBillOfLading = '';

        if ( is_array($suppliersBillOfLading) && count($suppliersBillOfLading) > 0 ) {
            foreach ($suppliersBillOfLading as $sbol) {
                $masterScac = substr($shipment->mbl_num, 0, 4);
                $masterBill = substr($shipment->mbl_num, 4);
                $houseScac  = substr($sbol->ams_num, 0, 4);
                $houseBill  = substr($sbol->ams_num, 4);
                $billQuantity = $sbol->total_cartons;
                $billUnit     = "PKGS";

                if (!$masterScac) {
                    throw new \ErrorException("Master SCAC(mbl#) is required");
                }
                if (!$masterBill) {
                    throw new \ErrorException("Master Bill(mbl#) is required");
                }
                if (!$billQuantity) {
                    throw new \ErrorException("Cartons must be greater than 0");
                }

                $houseScacXML = '';
                $houseBillXML = '';
                if ($houseScac) {
                    $houseScacXML = '<house-scac>' . $houseScac . '</house-scac>';
                }
                if ($houseBill) {
                    $houseBillXML = '<house-bill>' . $houseBill . '</house-bill>';
                }

                $tempXML = '
                    <bill-of-lading>
                        <master-scac>' . $masterScac . '</master-scac>
                        <master-bill>' . $masterBill . '</master-bill>'
                    . $houseScacXML . $houseBillXML .
                    '<quantity>' . $billQuantity . '</quantity>
                        <unit>' . $billUnit . '</unit>
                    </bill-of-lading>
                ';
                $manifestBillOfLading = $manifestBillOfLading . $tempXML;
            }
        }

        $check_office_to = \App\ShiflOfficeEmail::where('type', 'to')
                                                ->where('office_id', $shipment->officeTo->id)
                                                ->first();

        $check_office_from = \App\ShiflOfficeEmail::where('type', 'from')
                                            ->where('office_id', $shipment->officeFrom->id)
                                            ->first();

        if ( isset($check_office_to->id) ) {

            $dynamic_emails = $check_office_to->customer_entry_notice_emails;
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
            
            $dynamic_emails = $check_office_from->customer_entry_notice_emails;
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

        Mail::to($to)->cc($cc)->send(new ShipmentLeave($subjects, $content, $attachment, $shipment->customer->manager));
        $client = new SoapClient("https://www.netchb.com/main/services/entry/EntryUploadService?wsdl");
        $response = $client->__soapCall("uploadEntry", [
            "username" => config('netchb.username'),
            "password" => config('netchb.password'),
            "entryXml" => '
                <entry xmlns="http://www.netchb.com/xml/entry" xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xsi:schemaLocation="http://www.netchb.com/xml/entry http://www.netchb.com/xml/entry/entry.xsd">
                    <entry-no>
                        <user-specified>
                            <filer-code>85B</filer-code>
                            <entry-no>' . $customEntryNo . '</entry-no>
                        </user-specified>
                    </entry-no>
                    <header>
                        <processing-port>' . $terminalRegion->code . '</processing-port>
                        <entry-port>' . $terminalRegion->code . '</entry-port>
                        <entry-date>' . $entryDate . '</entry-date>
                        <entry-type>01</entry-type>
                        <importer-tax-id>' . $customerEin . '</importer-tax-id>
                        <ultimate-consignee>
                            <tax-id>' . $customerEin . '</tax-id>
                        </ultimate-consignee>
                    </header>
                    <manifest>' . $manifestBillOfLading . '</manifest>
                    <containers />
                    <ace-cargo-release-parties />
                    <invoices>
                    </invoices>
                </entry>
         '
        ]);

        $xml = simplexml_load_string($response);
        if (!isset($xml->{'entry-no'})) {
            if (isset($xml->{'validation-error'})) {
                throw new \ErrorException($xml->{'validation-error'});
            }
            throw new \ErrorException($xml);
        } else {
            $shipment->entry_netchb_submitted = 1;
            $shipment->entry_netchb_no = $xml->{'entry-no'};
            $shipment->entry_netchb_date = Carbon::now();
            $shipment->save();
        }
    }
}