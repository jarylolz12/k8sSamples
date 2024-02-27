<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use QuickBooksOnline\API\Facades\Invoice;
use App\Invoice as ShiflInvoice;
use App\InvoiceService;
use App\InvoiceAttachment;
use App\Shipment;
use App\Supplier;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Exception;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Storage;
use QuickBooksOnline\API\Data\IPPReferenceType;
use QuickBooksOnline\API\Data\IPPAttachableRef;
use QuickBooksOnline\API\Data\IPPAttachable;
use Illuminate\Support\Facades\File;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

class ShipmentQBOController extends Controller
{

   public function getServices()
   {
        $quickbooks = app('QuickBooks');
        $allServices = $quickbooks->getDataService()->Query("select * from Item where Type='Service'");
        return $allServices;
   }

   public function getCustomers()
   {
        $quickbooks = app('QuickBooks');
        $custCount = $quickbooks->getDataService()->Query("select count(*) from Customer where Active=true");// where Active=true

        $max = 1000;
        $pages = ($custCount / $max);
        $remainder = ($custCount % $max);
        if ($remainder > 0){
            $totPages = ($pages + 1);
        }else{
            $totPages = $pages;
        }
        $cust = Array();
        $allCusts = Array();
        for ($x = 1; $x <= $totPages; $x++) {
            if ($x == 1){
                $cust[$x-1] = $quickbooks->getDataService()->Query("select Id, FullyQualifiedName from Customer Where Active = true", $x, $max);
                $prevMax = ($max*$x);
                $cust[$x-1] = (object) array_filter((array) $cust[$x-1], function ($val) {
                    return !is_null($val);
                });
                $allCusts = array_merge($allCusts,$cust[$x-1]);
            }else{
                if ($x != $totPages){
                    $cust[$x-1] = $quickbooks->getDataService()->Query("select Id, FullyQualifiedName from Customer Where Active = true", ($prevMax+1), $max);
                    $prevMax = ($max*$x);
                    $cust[$x-1] = (object) array_filter((array) $cust[$x-1], function ($val) {
                        return !is_null($val);
                    });
                    $allCusts = array_merge($allCusts,$cust[$x-1]);
                }else{
                    $cust[$x-1] = $quickbooks->getDataService()->Query("select Id, FullyQualifiedName from Customer Where Active = true", ($prevMax+1), $remainder);
                    $cust[$x-1] = (object) array_filter((array) $cust[$x-1], function ($val) {
                        return !is_null($val);
                    });
                    $allCusts = array_merge($allCusts,$cust[$x-1]);
                }
            }
        }
        /* echo "<pre>";
        print_r($cust);
        echo "</pre>"; */
        return response()->json($allCusts);
   }

   public function searchCustomers(Request $request)
    {
        $name = $request->query('query');

        $quickbooks = app('QuickBooks');
        $allCusts = $quickbooks->getDataService()->Query("select * from Customer where Active=true AND FullyQualifiedName like '%".$name."%' MAXRESULTS 800");

        /* $max = 100;
        $pages = ($custCount / $max);
        $remainder = ($custCount % $max);
        if ($remainder > 0){
            $totPages = ($pages + 1);
        }else{
            $totPages = $pages;
        }
        $cust = Array();
        $allCusts = Array();
        for ($x = 1; $x <= $totPages; $x++) {
            if ($x == 1){
                $cust[$x-1] = $quickbooks->getDataService()->Query("select Id, FullyQualifiedName from Customer Where Active = true AND FullyQualifiedName like '%".$name."%'", $x, $max);
                $prevMax = ($max*$x);
                $cust[$x-1] = (object) array_filter((array) $cust[$x-1], function ($val) {
                    return !is_null($val);
                });
                $allCusts = array_merge($allCusts,$cust[$x-1]);
            }else{
                if ($x != $totPages){
                    $cust[$x-1] = $quickbooks->getDataService()->Query("select Id, FullyQualifiedName from Customer Where Active = true AND FullyQualifiedName like '%".$name."%'", ($prevMax+1), $max);
                    $prevMax = ($max*$x);
                    $cust[$x-1] = (object) array_filter((array) $cust[$x-1], function ($val) {
                        return !is_null($val);
                    });
                    $allCusts = array_merge($allCusts,$cust[$x-1]);
                }else{
                    $cust[$x-1] = $quickbooks->getDataService()->Query("select Id, FullyQualifiedName from Customer Where Active = true AND FullyQualifiedName like '%".$name."%'", ($prevMax+1), $remainder);
                    $cust[$x-1] = (object) array_filter((array) $cust[$x-1], function ($val) {
                        return !is_null($val);
                    });
                    $allCusts = array_merge($allCusts,$cust[$x-1]);
                }
            }
        } */
        //$source = json_decode($allCusts, true);
        //$result = $this->processList($allCusts);
        /* echo "<pre>";
        print_r($result);
        echo "</pre>"; */
        //return response()->json($result['value']);
        return $allCusts;
    }

    public function processList($list)
    {
        $listResult = ['keepValue' => false, // once set true will propagate upward
                        'value'       => []];

        foreach ($list as $name => $item ) {
            if (is_null($item)) { // see is_scalar test
                continue;
            }

            if (is_scalar($item)) { // keep the value?
                if (!empty($item) || strlen(trim($item)) > 0) {
                    $listResult['keepValue'] = true;
                    $listResult['value'][$name] = $item;
                }

            } else { // new list... recurse

                $itemResult = $this->processList($item);
                if ($itemResult['keepValue']) {
                    $listResult['keepValue'] = true;
                    $listResult['value'][$name] = $itemResult['value'];
                }
            }
        }
        return $listResult;
    }

   public function getCustomersCount()
   {
        $quickbooks = app('QuickBooks');
        $custCount = $quickbooks->getDataService()->Query("select count(*) from Customer where Active=true");// where Active=true

        return response()->json($custCount);
   }

   public function getTerms()
   {
        $quickbooks = app('QuickBooks');
        $allTerms = $quickbooks->getDataService()->Query("select * from Term");
        return $allTerms;
   }

   public function addInvoice(Request $request){
        $response = ['success' => false, 'invoice_id' => []];

        $quickbooks = app('QuickBooks');

        // $allowCreditCard = isset($request->allowCreditCard) ? $request->allowCreditCard : true;
        $allowCreditCard = false;
        $allowACHPayment = isset($request->allowACHPayment) ? $request->allowACHPayment : true;
        $qboCompanyInfo = $request->qboCompanyInfo;
        $shipmentInfo = $request->shipmentInfo;
        $qboCountry = $qboCompanyInfo['Country'];
        $lines = $request->line;
        $qboRealmId = Auth::user()->quickbookstoken->realm_id;

        $lineItems = [];
        if(isset($qboCompanyInfo['Country']) && $qboCompanyInfo['Country'] === "IN"){
            $lineItems = $this->setLineItemsForIndia($lines);
        }
        // if(isset($qboCompanyInfo['Country']) && $qboCompanyInfo['Country'] === "US"){
        //     $lineItems = $this->setLineItemsForUS($lines);
        // }
        else{
            $lineItems = $this->setLineItemsForUS($lines);
        }

        $totalInvoiceAmount = 0;
        if(count($lineItems) > 0){
            $totalInvoiceAmount = collect($lineItems)->sum('Amount');
        }

        $invoiceObjectValue = [];
        if(isset($qboCompanyInfo['Country']) && $qboCompanyInfo['Country'] === 'IN'){
            $invoiceObjectValue = $this->setInvoiceObjectValueForIndia($lineItems,$request,$allowCreditCard,$allowACHPayment);
        }else{

            $date_create = date_create($request->dueDate);
            $due_date_formatted = date_format($date_create, 'Y-m-d');
            $date_create =date_create($request->invoiceDate);
            $invoice_date_formatted = date_format($date_create, 'Y-m-d');
            

            $invoiceObjectValue = [
                "Line" => $lineItems,
                "DocNumber" => $request->invoiceNum,
                //"TxnDate" => $invoice_date_formatted,
                //"TxnDate" => $request->invoiceDate,
                "TxnDate" => Carbon::createFromFormat('Y-m-d', $invoice_date_formatted)->format('Y-m-d'),
                "DueDate" => Carbon::createFromFormat('Y-m-d', $due_date_formatted)->format('Y-m-d'),
                "SalesTermRef" => [
                    "value" => $request->termId
                ],
                "CustomerRef" => [
                      "value" => $request->customerId
                ],
                "BillEmail" => [
                      "Address" => $request->customerEmail
                ],
                "BillAddr" => [
                    "Line1" => $request->billingAddress
                ],
                "CustomerMemo" => [
                    "value" => $request->customerMemo
                ],
                "AllowOnlineCreditCardPayment" => $allowCreditCard,
                "AllowOnlineACHPayment" => $allowACHPayment,
                "ExchangeRate" => isset($request->exchangeRateValue) && $request->exchangeRateValue !== null ? $request->exchangeRateValue['Rate'] : '',
                "CurrencyRef" => [
                    "value" => isset($request->customerCurrencyRef) && $request->customerCurrencyRef !== null ? $request->customerCurrencyRef : '',
                ],
            ];
        }

        // return response()->json($invoiceObjectValue);

        $qbinvoice = Invoice::create($invoiceObjectValue);
        $resultingObj = $quickbooks->getDataService()->Add($qbinvoice);
        $error = $quickbooks->getDataService()->getLastError();
        if ($error) {
            return $error->getResponseBody();
        }else {
            if(isset($qboCompanyInfo['Country']) && $qboCompanyInfo['Country'] === 'IN'){

                if(isset($resultingObj->TxnTaxDetail)){
                    $taxDetail = $this->setTaxDetail($resultingObj->TxnTaxDetail);
                }
            }

            $invoice = new ShiflInvoice;

            $invoice->qbo_customer_id = $request->customerId;
            $invoice->qbo_customer_name = $request->customerName;
            $invoice->invoice_num = $request->invoiceNum;
            $invoice->auto_invoice_date_update = $request->auto_invoice_date_update;
            $invoice->qbo_bill_to_email =  $request->customerEmail;
            $invoice->qbo_bill_to_address = $request->billingAddress;
            $invoice->term = $request->termId;
            $invoice->invoice_date = $request->invoiceDate;
            $invoice->due_date = $request->dueDate;
            $invoice->shipment_id = $request->resourceId;
            $invoice->qbo_id = $resultingObj->Id;
            $invoice->qbo_term_id = $request->termId;
            $invoice->qbo_term_name = $request->termName;
            $invoice->qbo_term_days = $request->termDueDays;
            $invoice->qbo_customer_memo = $request->customerMemo;
            $invoice->qbo_country = $qboCountry;
            $invoice->qbo_company = isset($qboCompanyInfo['CompanyName']) ? $qboCompanyInfo['CompanyName'] : null;
            $invoice->qbo_tax_detail = isset($taxDetail) ? json_encode($taxDetail) : null;
            $invoice->allow_credit_card_payment = $allowCreditCard;
            $invoice->allow_online_ach_payment = $allowACHPayment;
            $invoice->total_amount = isset($resultingObj->TotalAmt) ? $resultingObj->TotalAmt : 0;
            $invoice->total_tax = isset($resultingObj->TxnTaxDetail->TotalTax) ? $resultingObj->TxnTaxDetail->TotalTax : 0;
            //$invoice->total_amount = isset($resultingObj->TotalAmt) ? $resultingObj->TotalAmt : $totalInvoiceAmount;
            $invoice->shifl_office_from_id = isset($shipmentInfo['shifl_office_origin_from_id']) ? $shipmentInfo['shifl_office_origin_from_id'] : 0;
            $invoice->shifl_office_to_id = isset($shipmentInfo['shifl_office_origin_to_id']) ? $shipmentInfo['shifl_office_origin_to_id'] : 0;
            $invoice->qbo_customer_gstin = isset($request->customerGSTIN) ? $request->customerGSTIN : null;
            $invoice->qbo_company_realmid = $qboRealmId;
            $invoice->home_total_amount = isset($resultingObj->HomeTotalAmt) ? $resultingObj->HomeTotalAmt : null;
            $invoice->balance = isset($resultingObj->Balance) ? $resultingObj->Balance : null;
            $invoice->home_balance = isset($resultingObj->HomeBalance) ? $resultingObj->HomeBalance : null;
            $invoice->currency_ref = isset($resultingObj->CurrencyRef) ? $resultingObj->CurrencyRef : null;
            // $invoice->home_currency_ref = isset($resultingObj->CurrencyRef) ? $resultingObj->CurrencyRef : null;
            $invoice->exchange_rate = isset($resultingObj->ExchangeRate) ? $resultingObj->ExchangeRate : null;
            $invoice->exchange_rate_info = $request->exchangeRateValue !== null ? json_encode($request->exchangeRateValue) : null;
            $invoice->global_tax_calculation = isset($resultingObj->GlobalTaxCalculation) ? $resultingObj->GlobalTaxCalculation : null;
            $invoice->sync_token = isset($resultingObj->SyncToken) ? $resultingObj->SyncToken : 0;
            $invoice->save();

            foreach ($lines as $line) {

                $invoiceService = new InvoiceService;

                $invoiceService->invoice_id = $invoice->id;;
                $invoiceService->qbo_service_id = $line['Service']['Id'];
                $invoiceService->description = $line['Description'];
                $invoiceService->quantity = $line['Quantity'];
                $invoiceService->rate = $line['UnitPrice'];
                $invoiceService->qbo_service_name = $line['Service']['Name'];
                $invoiceService->qbo_tax_code = isset($line['Tax']) ? json_encode($line['Tax']) : null;
                $invoiceService->save();

                $response['qbo_invoice_id'] = $resultingObj->Id;
                $response['invoice_id'] = $invoice->id;
            }
            $response['success'] = true;

        }
        return response()->json($response);
    }

    public function editInvoice(Request $request){
        $response = ['success' => false];
        $quickbooks = app('QuickBooks');

        // $allowCreditCard = isset($request->allowCreditCard) ? $request->allowCreditCard : true;
        $allowCreditCard = false;
        $allowACHPayment = isset($request->allowACHPayment) ? $request->allowACHPayment : true;
        $qboCompanyInfo = $request->qboCompanyInfo;
        $qboCountry = $qboCompanyInfo['Country'];
        $lines = $request->line;

        $date_create = date_create($request->dueDate);
        $due_date_formatted = date_format($date_create, 'Y-m-d');

        $date_create =date_create($request->invoiceDate);
        $invoice_date_formatted = date_format($date_create, 'Y-m-d');

        $lineItems = [];
        if(isset($qboCompanyInfo['Country']) && $qboCompanyInfo['Country'] === 'IN'){
            $lineItems = $this->setLineItemsForIndia($lines);
        }
        // if(isset($qboCompanyInfo['Country']) && $qboCompanyInfo['Country'] === 'US'){
        //     $lineItems = $this->setLineItemsForUS($lines);
        // }
        else{
            $lineItems = $this->setLineItemsForUS($lines);
        }

        $totalInvoiceAmount = 0;
        if(count($lineItems) > 0){
            $totalInvoiceAmount = collect($lineItems)->sum('Amount');
        }

        $invoiceObject  = $quickbooks->getDataService()->FindbyId('invoice',$request->qboInvoiceId);

        $invoiceObjectValue = [];
        if(isset($qboCompanyInfo['Country']) && $qboCompanyInfo['Country'] === 'IN'){
            $invoiceObjectValue = $this->setInvoiceObjectValueForIndia($lineItems,$request,$allowCreditCard,$allowACHPayment);
        }else{

            $invoiceObjectValue = [
                "Line" => $lineItems,
                "DocNumber" => $request->invoiceNum,
                //"TxnDate" => $request->invoiceDate,
                "TxnDate" => Carbon::createFromFormat('Y-m-d', $invoice_date_formatted)->format('Y-m-d'),
                "DueDate" => Carbon::createFromFormat('Y-m-d', $due_date_formatted)->format('Y-m-d'),
                "SalesTermRef" => [
                    "value" => $request->termId
                ],
                "CustomerRef" => [
                      "value" => $request->customerId
                ],
                "BillEmail" => [
                      "Address" => $request->customerEmail
                ],
                "BillAddr" => [
                    "Line1" => $request->billingAddress
                ],
                "CustomerMemo" => [
                    "value" => $request->customerMemo
                ],
                "AllowOnlineCreditCardPayment" => $allowCreditCard,
                "AllowOnlineACHPayment" => $allowACHPayment,
                "ExchangeRate" => isset($request->exchangeRateValue) && $request->exchangeRateValue !== null ? $request->exchangeRateValue['Rate'] : '',
                "CurrencyRef" => [
                    "value" => isset($request->customerCurrencyRef) && $request->customerCurrencyRef !== null ? $request->customerCurrencyRef : '',
                ],
            ];
        }

        $qbInvoiceResourceObj = Invoice::update($invoiceObject,$invoiceObjectValue);

        $resultingObj  = $quickbooks->getDataService()->Update($qbInvoiceResourceObj);
        $error = $quickbooks->getDataService()->getLastError();
        if($error){
            return $error->getResponseBody();
        }else{
            if(isset($qboCompanyInfo['Country']) && $qboCompanyInfo['Country'] === 'IN'){
                $taxDetail = $this->setTaxDetail($resultingObj->TxnTaxDetail);
            }

            $shiflInvoice = ShiflInvoice::find($request->shiflInvoiceId);
            $shiflInvoice->qbo_customer_id = $request->customerId;
            $shiflInvoice->qbo_customer_name = $request->customerName;
            $shiflInvoice->auto_invoice_date_update = $request->auto_invoice_date_update;
            $shiflInvoice->invoice_num = $request->invoiceNum;
            $shiflInvoice->qbo_bill_to_email =  $request->customerEmail;
            $shiflInvoice->qbo_bill_to_address = $request->billingAddress;
            $shiflInvoice->term = $request->termId;
            //$shiflInvoice->invoice_date = $request->invoiceDate;
            $shiflInvoice->invoice_date = $invoice_date_formatted;
            $shiflInvoice->due_date = $due_date_formatted;
            //$shiflInvoice->due_date = $request->dueDate;
            //$shiflInvoice->shipment_id = $request->resourceId;
            //$shiflInvoice->qbo_id = $resultingObj->Id;
            $shiflInvoice->qbo_term_id = $request->termId;
            $shiflInvoice->qbo_term_name = $request->termName;
            $shiflInvoice->qbo_term_days = $request->termDueDays;
            $shiflInvoice->qbo_customer_memo = $request->customerMemo;
            $shiflInvoice->qbo_tax_detail = isset($taxDetail) ? json_encode($taxDetail) : null;
            $shiflInvoice->allow_credit_card_payment = $allowCreditCard;
            $shiflInvoice->allow_online_ach_payment = $allowACHPayment;
            $shiflInvoice->total_amount = isset($resultingObj->TotalAmt) ? $resultingObj->TotalAmt : 0;
            $shiflInvoice->total_tax = isset($resultingObj->TxnTaxDetail->TotalTax) ? $resultingObj->TxnTaxDetail->TotalTax : 0;
            // $shiflInvoice->total_amount = isset($resultingObj->TotalAmt) ? $resultingObj->TotalAmt : $totalInvoiceAmount;
            $shiflInvoice->qbo_customer_gstin = isset($request->customerGSTIN) ? $request->customerGSTIN : null;
            $shiflInvoice->home_total_amount = isset($resultingObj->HomeTotalAmt) ? $resultingObj->HomeTotalAmt : null;
            $shiflInvoice->balance = isset($resultingObj->Balance) ? $resultingObj->Balance : null;
            $shiflInvoice->home_balance = isset($resultingObj->HomeBalance) ? $resultingObj->HomeBalance : null;
            $shiflInvoice->currency_ref = isset($resultingObj->CurrencyRef) ? $resultingObj->CurrencyRef : null;
            // $invoice->home_currency_ref = isset($resultingObj->CurrencyRef) ? $resultingObj->CurrencyRef : null;
            $shiflInvoice->exchange_rate = isset($resultingObj->ExchangeRate) ? $resultingObj->ExchangeRate : null;
            $shiflInvoice->exchange_rate_info = $request->exchangeRateValue !== null ? json_encode($request->exchangeRateValue) : null;
            $shiflInvoice->global_tax_calculation = isset($resultingObj->GlobalTaxCalculation) ? $resultingObj->GlobalTaxCalculation : null;
            $shiflInvoice->sync_token = isset($resultingObj->SyncToken) ? $resultingObj->SyncToken : 0;

            $shiflInvoice->save();

            $result = $this->updateInvoiceServices($lines,$shiflInvoice->invoiceServices,$request->shiflInvoiceId);

            $response['success'] = true;
            $response['result'] = $result;
        }

        return response()->json($response);
    }

   public function searchServices(Request $request)
    {
        $name = $request->query('query');

        $quickbooks = app('QuickBooks');
        //$allServices = $quickbooks->getDataService()->Query("select * from Item where Type='Service' AND FullyQualifiedName like '%".$name."%'");// where Active=true "select * from Item where Type='Service'"
        $allItems = $quickbooks->getDataService()->Query("select * from Item where FullyQualifiedName like '%".$name."%'");// where Active=true "select * from Item where Type='Service'"

        $result = [];
        if(count((array)$allItems) > 0){
            $result = $this->processList($allItems);
            return response()->json($result['value']);
        }

        return response()->json($result);
    }

    public function deleteInvoice(Request $request){
        $response = ['success' => false];
        $quickbooks = app('QuickBooks');

        if(isset($request->qbo_id) && $request->qbo_id > 0 ){
            $invoiceObject  = $quickbooks->getDataService()->FindbyId('invoice',$request->qbo_id);
            if($invoiceObject === null){
                if(ShiflInvoice::find($request->id)->delete()){
                    //***deleting of invoice items is done in Invoice model**//
                    $this->deleteAttachmentsOnDeleteInvoice($request->id);
                    $response['success'] = true;
                }
            }else{
                $resultingObj = $quickbooks->getDataService()->Delete($invoiceObject);
                $error = $quickbooks->getDataService()->getLastError();

                if($error){
                    return $error->getResponseBody();
                }else{
                    if(ShiflInvoice::find($request->id)->delete()){
                        //***deleting of invoice items is done in Invoice model**//
                        $this->deleteAttachmentsOnDeleteInvoice($request->id);
                        $response['success'] = true;
                    }
                }
            }
        }
        return response()->json($response);
    }

    private function updateInvoiceServices($newItems,$oldItems,$shiflInvoiceId){

        $newIds = array_filter(Arr::pluck($newItems, 'invoice_item_id'), 'is_numeric');
        $oldIds = Arr::pluck($oldItems, 'id');

        $actions = ['deleted'=>0, 'updated'=>0, 'created'=>0];

        $services_to_delete = collect($oldItems)->filter(function ($model) use ($newIds) {
            return !in_array($model->id, $newIds);
        });

        $items_to_update = collect($newItems)->filter(function ($model) use ($oldIds) {
            return in_array($model['invoice_item_id'], $oldIds);
        });

        $items_to_insert = collect($newItems)->filter(function ($model) use ($oldIds) {
            return $model['invoice_item_id'] === 0;
        });
        //  return ["deleted"=>$services_to_delete,"updated"=>$items_to_update,"created"=>$items_to_insert];

        if(count($services_to_delete)>0){
            $delete_ids = Arr::pluck($services_to_delete, 'id');
            $delete_count = 0;
            foreach($delete_ids as $id){
                InvoiceService::where('id',$id)->delete();
                $delete_count++;
            }
            if($delete_count > 0){
                $actions['deleted'] = $delete_count;
            }
        }

        if(count($items_to_update)>0){
            $update_ids = Arr::pluck($items_to_update, 'invoice_item_id');
            $update_count = 0;
            foreach($items_to_update as $item){
                InvoiceService::where('id',$item['invoice_item_id'])->update([
                    'qbo_service_id'=>$item['Service']['Id'],
                    'description'=>$item['Description'],
                    'quantity'=>$item['Quantity'],
                    'rate'=>$item['UnitPrice'],
                    'qbo_service_name'=>$item['Service']['Name'],
                    'qbo_tax_code' => isset($item['Tax']) ? json_encode($item['Tax']) : null
                ]);
                $update_count++;
            }
            if($update_count > 0){
                $actions['updated'] = $update_count;
            }
        }
        if(count($items_to_insert)>0){
            $create_count = 0;
            foreach($items_to_insert as $item){
                InvoiceService::create([
                    'invoice_id' => $shiflInvoiceId,
                    'qbo_service_id'=>$item['Service']['Id'],
                    'description'=>$item['Description'],
                    'quantity'=>$item['Quantity'],
                    'rate'=>$item['UnitPrice'],
                    'qbo_service_name'=>$item['Service']['Name'],
                    'qbo_tax_code' => isset($item['Tax']) ? json_encode($item['Tax']) : null
                ]);
                $create_count++;
            }
            if($create_count > 0){
                $actions['created'] = $create_count;
            }
        }
        return $actions;
    }

    public function uploadAttachmentToQBO(Request $request){
        $response = ['success' => false, 'result'=> [], 'errors' => []];
        $quickbooks = app('QuickBooks');
        $getAll = $request->all();

        $qboInvoiceId = $getAll['qbo_invoice_id'];
        $invoiceId = $getAll['invoice_id'];
        $shipmentId = $getAll['shipment_id'];

        $getFiles = isset($getAll['file']) ? $getAll['file'] : [];
        $shipmentDocs = isset($getAll['shipment_docs']) ? $getAll['shipment_docs'] : [];

        $filesToUpdate = isset($getAll['existing_file']) ? $getAll['existing_file'] : [];
        $filesForDelete= isset($getAll['files_for_delete']) ? json_decode($getAll['files_for_delete']) : [];

        $entityRef = new IPPReferenceType(array('value'=>$qboInvoiceId, 'type'=>'Invoice'));
        $attachableRef = new IPPAttachableRef(array('EntityRef'=>$entityRef, 'IncludeOnSend'=>true));

        $errors = [];
        $filesForUpload = [];

        if(count($filesForDelete)>0){
            $deleteFile = $this->deleteAttachable($filesForDelete);
            $response['success'] = true;
        }


        if(count($shipmentDocs)>0){
            foreach($shipmentDocs as $doc){
                if (Storage::disk('public')->exists($doc)) {
                    $file = Storage::disk('public')->get($doc);
                    $path = Storage::disk('public')->path($doc);
                    $mimeType = Storage::disk('public')->mimeType($doc);
                    array_push($filesForUpload, [
                        'fileMimeType' => $mimeType,
                        'fileName' => basename($path),
                        'fileLocalPath' => $doc,
                        'fileBase64' => base64_encode($file),
                        'forUpload' => false,
                        'isInvoiceOrigin' => false,
                    ]);
                }
            }
        }

        if(isset($getAll['file'])){
            foreach($getFiles as $key => $file){
                $copyKey = intval($key + 1);
                $fileMimeType = $file->getMimeType();
                $fileName = preg_replace('/\s+/', '', basename($file->getClientOriginalName(), '.' . $file->guessExtension())) . '_' . $copyKey . '_' . $shipmentId . '_' . $invoiceId .'.'. $file->guessExtension();
                $fileLocalPath = 'invoice/attachments/' . $fileName;
                $fileBase64 = base64_encode(file_get_contents($file));

                array_push($filesForUpload, [
                    'fileMimeType' => $fileMimeType,
                    'fileName' => $fileName,
                    'fileLocalPath' => $fileLocalPath,
                    'fileBase64' => $fileBase64,
                    'fileUnencoded' => $file,
                    'forUpload' => true,
                    'isInvoiceOrigin' => true,
                ]);
            }
        }

        $uploadCount = 0;
        if(count($filesForUpload) > 0){
            foreach($filesForUpload as $file){
                $objAttachable = new IPPAttachable();
                $objAttachable->FileName = $file['fileName'];
                $objAttachable->AttachableRef = $attachableRef;
                //$objAttachable->Category = 'Image';
                //$objAttachable->Tag = 'Tag_' . $copyKey;

                $resultObj =  $quickbooks->getDataService()
                                ->Upload($file['fileBase64'],$objAttachable->FileName,$file['fileMimeType'],$objAttachable);

                $error = $quickbooks->getDataService()->getLastError();
                if($error){
                    array_push($errors,$error);
                }else{
                    if($file['forUpload'] === true){
                        Storage::disk('local')->putFileAs('/public', $file['fileUnencoded'], $file['fileLocalPath']);
                    }
                    $invoice_attachment = new InvoiceAttachment;
                    $invoice_attachment->invoice_id = $invoiceId;
                    $invoice_attachment->qbo_invoice_id = $qboInvoiceId;
                    $invoice_attachment->shipment_id = $shipmentId;
                    $invoice_attachment->qbo_attachable_id = $resultObj->Attachable->Id;
                    $invoice_attachment->mime_type = $file['fileMimeType'];
                    $invoice_attachment->file_name = $file['fileName'];
                    $invoice_attachment->file_path = $file['fileLocalPath'];
                    $invoice_attachment->is_invoice_origin = $file['isInvoiceOrigin'];
                    $invoice_attachment->save();
                    $uploadCount++;
                }
            }
            $response['success'] = true;
        }
        $response['errors'] = $errors;
        $response['result'] = $uploadCount;

        return response()->json($response);
    }

    public function getShipmentDocuments($shipment_id){
        $response = ['success' => false, 'data'=> []];
        $shipment = Shipment::with('shipmentSuppliers')->where('id',$shipment_id)->first();

        $supplierDocuments = [];
        if (isset($shipment->shipmentSuppliers)) {
            $suppliers = $shipment->shipmentSuppliers;
            foreach ($suppliers as $key => $supplier) {
                array_push($supplierDocuments, [
                    "shipment_id" => $shipment->id,
                    "supplier_id" => $supplier->supplier_id,
                    "hbl_copy" => $supplier->hbl_copy,
                    "packing_list" => $supplier->packing_list,
                    "commercial_documents" => $supplier->commercial_documents,
                    "commercial_invoice" => $supplier->commercial_invoice,
                ]);
            }
            $response['success'] = true;
        }

        $response['data'] = $supplierDocuments;

        return response()->json($response, 200);
    }

    public function getShipmentCommercialDocuments($shipment_id){
        $response = ['success' => false, 'data'=> []];
        $shipment = Shipment::select('id','suppliers_commercial_docs','netchb_pdf','netchb_xml')->where('id',$shipment_id)->first();
        $files = ['netchb_pdf'=>null,'commercial_docs'=>[]];

        if($shipment->netchb_pdf !== null){
            $files['netchb_pdf'] = $shipment->netchb_pdf;
        }

        if (isset($shipment->suppliers_commercial_docs)) {
            $supplier_commercial_docs = json_decode($shipment->suppliers_commercial_docs);

            foreach ($supplier_commercial_docs as $com_docs) {
                if(isset($com_docs->commercial_documents)){
                    foreach($com_docs->commercial_documents as $docs){
                        array_push($files['commercial_docs'], $docs);
                    }
                }
            }
        }

        $response['success'] = true;
        $response['data'] = $files;

        return response()->json($response, 200);
    }

    private function deleteAttachable($files){
        $deleteCount = 0;
        if(count($files)>0){
            $quickbooks = app('QuickBooks');
            foreach($files as $f){
                $attachableObject  = $quickbooks->getDataService()->Query("select Id from attachable where Id='".$f->qbo_attachable_id."'");

                $resultingObj = $quickbooks->getDataService()->Delete($attachableObject[0]);
                $error = $quickbooks->getDataService()->getLastError();

                if($error){
                    return $error->getResponseBody();
                }else{
                    if($f->is_invoice_origin){
                        $deleteOnStorage = Storage::disk('public')->delete($f->file_path);
                    }
                    InvoiceAttachment::where('id',$f->attachment_id)->delete();
                    $deleteCount++;
                }
            }
        }
        return $deleteCount;
    }

    private function deleteAttachmentsOnDeleteInvoice($invoice_id){
        $success = false;
        $attachments = InvoiceAttachment::where('invoice_id',$invoice_id)->get();
        $quickbooks = app('QuickBooks');
        if (count($attachments) > 0) {
            foreach ($attachments as $attachment) {
                if(Storage::disk('public')->exists($attachment['file_path'])){
                    $attachableObject  = $quickbooks->getDataService()->Query("select Id from attachable where Id='".$attachment['qbo_attachable_id']."'");
                    $resultingObj = $quickbooks->getDataService()->Delete($attachableObject[0]);
                    $error = $quickbooks->getDataService()->getLastError();
                    if($error){
                        InvoiceAttachment::where('id',$attachment['id'])->delete();
                        if($attachment['is_invoice_origin']){
                            $deleteOnStorage = Storage::disk('public')->delete($attachment['file_path']);
                        }
                        return $error->getResponseBody();
                    }else{
                        InvoiceAttachment::where('id',$attachment['id'])->delete();
                        if($attachment['is_invoice_origin']){
                            $deleteOnStorage = Storage::disk('public')->delete($attachment['file_path']);
                        }
                        $success = true;
                    }
                }
            }

        }
    }

    private function ifNull($firstValue, $secondValue, $nullValue = null){
        return (empty($firstValue) ? (empty($secondValue) ? $nullValue : $secondValue) : $firstValue);
    }

    private function array_unique_multidimensional($input)
    {
        $serialized = array_map('serialize', $input);
        $unique = array_unique($serialized);
        return array_intersect_key($input, $unique);
    }

    private function merge_group($array_first, $array_second) {

        $merge_first = (is_array(json_decode($array_first))) ? json_decode($array_first) : [];
        $merge_to_second = (is_array(json_decode($array_second))) ? json_decode($array_second) : [];
        $merge_arrays = array_merge($merge_first, $merge_to_second);
        $final_data = $this->array_unique_multidimensional($merge_arrays);
        return json_encode($final_data);
    }

    public function sendInvoice(Request $request){
        $response = ['success' => false, 'result'=> [], 'errors' => []];
        $quickbooks = app('QuickBooks');
        $getAll = $request->all();

        $invoiceId = $getAll['invoice_id'];

        $getInvoice = ShiflInvoice::where('id',$invoiceId)->first();

        if(isset($getInvoice->qbo_id)){

            $findShipment = Shipment::find($getInvoice->shipment_id);
            $selected_eta = null;
            $selected_eta_format = null;

            if ( isset($findShipment->id) ) {

                if($findShipment['is_tracking_shipment']){
                    if(!empty($findShipment['mbl_num'])){

                        $terminal49_shipment = $findShipment->terminal_fortynine;
                        if ( isset ($terminal49_shipment->attributes) ) {
                            $attributes = json_decode($terminal49_shipment->attributes,true);
                            $selected_eta = date_create($this->ifNull($attributes['pod_eta_at'],$attributes['pod_ata_at']));
                            $selected_eta_format = date_format($selected_eta, 'Y-m-d');
                        }
                    }
                } else {

                    try {
                        $schedules = json_decode($this->merge_group($findShipment->schedules_group_bookings, $findShipment->schedules_group));
                        if ( is_countable($schedules) && count($schedules) > 0 ) {
                            foreach ($schedules as $schedule ) {
                                if ( isset($schedule->is_confirmed) && $schedule->is_confirmed == 1 ) {
                                    $selected_eta = date_create($schedule->eta);
                                    $selected_eta_format = date_format($selected_eta, 'Y-m-d');
                                }
                            }
                        }
                    }catch(Exception $e) {

                    }

                }
                //
            }

            $invoiceObject  = $quickbooks->getDataService()->FindbyId('invoice',$getInvoice->qbo_id);
            $invoice_date_formatted = null;
            $due_date_formatted = null;
            if ( $getInvoice->auto_invoice_date_update == 1 ) {
                $invoice_date_formatted = $selected_eta_format;
                $due_date_formatted = Carbon::createFromFormat('Y-m-d', $invoice_date_formatted)->addDays($getInvoice->qbo_term_days)->format('Y-m-d');
            } else {
                $date_create = date_create($getInvoice->invoice_date);
                $invoice_date_formatted = date_format($date_create, 'Y-m-d');  

                $date_create = date_create($getInvoice->due_date);
                $due_date_formatted = date_format($date_create, 'Y-m-d');  
            }

            $iDate = Carbon::createFromFormat('Y-m-d', $invoice_date_formatted)->timestamp;
            //$dDate = Carbon::createFromFormat('Y-m-d', $due_date_formatted)->timestamp;
            $dDate = Carbon::createFromFormat('Y-m-d', $due_date_formatted)->timestamp;
            if ( $dDate < $iDate ) {
                return response()->json(['errorMessage' => 'Due Date should be greater than Invoice Date.']);
            } else {
                
                $invoiceObjectValue = [
                    "sparse" => true,
                    "TxnDate" => Carbon::createFromFormat('Y-m-d', $invoice_date_formatted)->format('Y-m-d'),
                    //"TxnDate" => Carbon::createFromFormat('Y-m-d', $invoice_date_formatted)->format('Y-m-d'),
                    //"TxnDate" =>  Carbon::now(),
                    "DueDate" =>  Carbon::createFromFormat('Y-m-d', $due_date_formatted)->format('Y-m-d')
                    //"DueDate" => Carbon::now()->addDays($getInvoice->qbo_term_days),
                ];


                $qbInvoiceResourceObj = Invoice::update($invoiceObject,$invoiceObjectValue);
                $resultingObj  = $quickbooks->getDataService()->Update($qbInvoiceResourceObj);
                $error = $quickbooks->getDataService()->getLastError();

                if($error){
                    return $error->getResponseBody();
                }else{
                    $sendInvoice = $quickbooks->getDataService()->SendEmail($invoiceObject);
                    $error = $quickbooks->getDataService()->getLastError();
                    if($error){
                        return $error->getResponseBody();
                    }else{
                        $getInvoice->is_email_sent = true;
                        $getInvoice->email_sent_count = $getInvoice->email_sent_count + 1;
                        $getInvoice->email_sent_at = Carbon::now();

                        $getInvoice->invoice_date = $invoice_date_formatted;
                        $getInvoice->due_date = $due_date_formatted;
                        //$getInvoice->invoice_date = Carbon::now();
                        //$getInvoice->due_date = Carbon::now()->addDays($getInvoice->qbo_term_days);
                        $getInvoice->save();
                        $response['success'] = true;
                    }
                }
            }




            
        }

        return response()->json($response);
    }

    public function getQBOCompanyInfo(){
        $response = ['success' => false, 'result'=> [], 'errors' => []];
        $quickbooks = app('QuickBooks');
        $companyInfo = $quickbooks->getDataService()->getCompanyInfo();
        $error = $quickbooks->getDataService()->getLastError();
        if($error){
            return $error->getResponseBody();
        }else{
            $response['success'] = true;
            $response['result'] = $companyInfo;
        }
        return response()->json($response);
    }

    public function getShipmentInfo($shipment_id){
        $response = ['success' => false, 'result'=> [], 'errors' => []];
        $shipment = Shipment::with('customer')->select('id','shifl_ref','shifl_office_origin_from_id','shifl_office_origin_to_id','customer_id')->where('id',$shipment_id)->first();
        if(isset($shipment->id)){
            $qboCust = json_decode($shipment->customer->qbo_customer);
            $response['result'] = [
                'shipment'=> $shipment,
                'customer'=> $qboCust,
                'customer_qbo_realm_id'=> isset($qboCust->realm_id) ? strval($qboCust->realm_id) : '',
                'customer_qbo_company'=> isset($qboCust->company) ? $qboCust->company : '',
            ];
            $response['success'] = true;
        }
        return response()->json($response);
    }

    private function setLineItemsForIndia($lines){
        $lineItems = [];
        foreach ($lines as $line) {
            $lineitem = [
                "Amount" => $line['UnitPrice'] * $line['Quantity'],
                "Description" => $line['Description'],
                "DetailType" => "SalesItemLineDetail",
                "SalesItemLineDetail" => [
                    "ItemRef" => [
                        "value" => $line['Service']['Id']
                    ],
                    "Qty" => $line['Quantity'],
                    "UnitPrice" => $line['UnitPrice'],
                    "TaxCodeRef" => [
                        "value" => $line['Tax']['Id'],
                        "name" => $line['Tax']['Name'],
                    ]
                ],
            ];
            array_push($lineItems, $lineitem);
        }
        return $lineItems;
    }

    private function setLineItemsForUS($lines){
        $lineItems = [];
        foreach ($lines as $line) {
            $lineitem = [
                "Amount" => $line['UnitPrice'] * $line['Quantity'],
                "Description" => $line['Description'],
                "DetailType" => "SalesItemLineDetail",
                "SalesItemLineDetail" => [
                    "ItemRef" => [
                        "value" => $line['Service']['Id']
                    ],
                    "Qty" => $line['Quantity'],
                    "UnitPrice" => $line['UnitPrice']
                ]
            ];
            array_push($lineItems, $lineitem);
        }
        return $lineItems;
    }

    private function setInvoiceObjectValueForIndia($lineItems,$request,$allowCreditCard,$allowACHPayment){
        $customerGSTIN = '';
        if(isset($request->customerGSTIN) && $request->customerGSTIN !== null){
            $customerGSTIN = substr($request->customerGSTIN,0,2);
        }
        return [
            "Line" => $lineItems,
            "DocNumber" => $request->invoiceNum,
            "TxnDate" => $request->invoiceDate,
            "DueDate" => $request->dueDate,
            "SalesTermRef" => [
                "value" => $request->termId
            ],
            "CustomerRef" => [
                  "value" => $request->customerId
            ],
            "BillEmail" => [
                  "Address" => $request->customerEmail
            ],
            "BillAddr" => [
                "Line1" => $request->billingAddress
            ],
            "CustomerMemo" => [
                "value" => $request->customerMemo
            ],
            "AllowOnlineCreditCardPayment" => $allowCreditCard,
            "AllowOnlineACHPayment" => $allowACHPayment,
            "GlobalTaxCalculation" => "TaxExcluded",
            //"GlobalTaxCalculation" => "TaxInclusive",
            //"GlobalTaxCalculation" => "NotApplicable",
            "TxnTaxDetail" => [],
            "TransactionLocationType" => $customerGSTIN,
            "ExchangeRate" => isset($request->exchangeRateValue) && $request->exchangeRateValue !== null ? $request->exchangeRateValue['Rate'] : '',
            "CurrencyRef" => [
                "value" => isset($request->customerCurrencyRef) && $request->customerCurrencyRef !== null ? $request->customerCurrencyRef : '',
            ],
        ];
    }

    private function setTaxDetail($taxDetailData){
        $taxLine = [];
        if(isset($taxDetailData->TaxLine)){
            if(!is_array($taxDetailData->TaxLine)){
                $tl = $taxDetailData->TaxLine;
                $taxline = [
                    "Amount" => $tl->Amount,
                    "DetailType" => $tl->DetailType,
                    "TaxLineDetail" => $tl->TaxLineDetail
                ];
                array_push($taxLine,$taxline);
            }else{
                foreach($taxDetailData->TaxLine as $tl){
                    $taxline = [
                        "Amount" => $tl->Amount,
                        "DetailType" => $tl->DetailType,
                        "TaxLineDetail" => $tl->TaxLineDetail
                    ];
                    array_push($taxLine,$taxline);
                }
            }
        }
        $txnTaxDetail = [
            "DefaultTaxCodeRef" => isset($taxDetailData->DefaultTaxCodeRef) ? $taxDetailData->DefaultTaxCodeRef : null,
            "TaxLine" => $taxLine,
            "TaxReviewStatus" => isset($taxDetailData->TaxReviewStatus) ? $taxDetailData->TaxReviewStatus : null,
            "TotalTax" => isset($taxDetailData->TotalTax) ? $taxDetailData->TotalTax : null,
            "TxnTaxCodeRef" => isset($taxDetailData->TxnTaxCodeRef) ? $taxDetailData->TxnTaxCodeRef : null,
            "UseAutomatedSalesTax" => isset($taxDetailData->UseAutomatedSalesTax) ? $taxDetailData->UseAutomatedSalesTax : null,
        ];
        return $txnTaxDetail;
    }
}
