<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use QuickBooksOnline\API\Facades\Bill;
use App\Bill as ShiflBill;
use App\BillItem;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Auth;


class ShipmentQBOBillController extends Controller
{
    public function addBill(Request $request){

        $response = ['success' => false];
        $quickbooks = app('QuickBooks');
        $lines = $request->line;
        $qboCompanyInfo = $request->qboCompanyInfo;
        $shipmentInfo = $request->shipmentInfo;
        $qboCountry = $qboCompanyInfo['Country'];
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

        $totalBillAmount = 0;
        if(count($lineItems) > 0){
            $totalBillAmount = collect($lineItems)->sum('Amount');
        }

        $billObjValues = [];
        if(isset($qboCompanyInfo['Country']) && $qboCompanyInfo['Country'] === "IN"){
            $billObjValues = $this->setBillObjectValuesForIndia($lineItems,$request);
        }
        else{
            $billObjValues = [
                "Line" => $lineItems,
                "DocNumber" => $request->billNum,
                "VendorRef" => [
                    "value" => $request->vendorId
                ],
                "SalesTermRef" => [
                    "value" => $request->termId
                ],
                "TxnDate" => $request->billDate,
                "DueDate" => $request->dueDate,
                "PrivateNote"=>$request->memo,
                "ExchangeRate" => isset($request->exchangeRateValue) && $request->exchangeRateValue !== null ? $request->exchangeRateValue['Rate'] : '',
                "CurrencyRef" => [
                    "value" => isset($request->vendorCurrencyRef) && $request->vendorCurrencyRef !== null ? $request->vendorCurrencyRef : '',
                ],
            ];
        }

        $qbBillResourceObj = Bill::create($billObjValues);

        $resultingObj = $quickbooks->getDataService()->Add($qbBillResourceObj);

        $error = $quickbooks->getDataService()->getLastError();

        if($error){
            return $error->getResponseBody();
        }else{
            if(isset($qboCompanyInfo['Country']) && $qboCompanyInfo['Country'] === 'IN'){
                if(isset($resultingObj->TxnTaxDetail)){
                    $taxDetail = $this->setTaxDetail($resultingObj->TxnTaxDetail);
                }
            }

            $shiflBill = new ShiflBill;
            $shiflBill->qbo_bill_id = $resultingObj->Id;
            $shiflBill->shipment_id = $request->resourceId;
            $shiflBill->qbo_vendor_id = $request->vendorId;
            $shiflBill->qbo_term_id = $request->termId;
            $shiflBill->qbo_bill_num = $request->billNum;
            $shiflBill->qbo_term_name = $request->termName;
            $shiflBill->qbo_term_days = $request->termDueDays;
            $shiflBill->qbo_vendor_name = $request->vendorName;
            $shiflBill->qbo_mailing_address = $request->mailingAddress;
            $shiflBill->qbo_memo = $request->memo;
            $shiflBill->qbo_bill_date = $request->billDate;
            $shiflBill->qbo_due_date = $request->dueDate;
            // $shiflBill->total_amount = $totalBillAmount;
            // $shiflBill->total_amount = isset($resultingObj->TotalAmt) ? $resultingObj->TotalAmt : $totalBillAmount;
            $shiflBill->total_amount = isset($resultingObj->TotalAmt) ? $resultingObj->TotalAmt : 0;
            $shiflBill->qbo_country = $qboCountry;
            $shiflBill->qbo_company = isset($qboCompanyInfo['CompanyName']) ? $qboCompanyInfo['CompanyName'] : null;
            $shiflBill->qbo_tax_detail = isset($taxDetail) ? json_encode($taxDetail) : null;
            $shiflBill->total_tax = isset($resultingObj->TxnTaxDetail->TotalTax) ? $resultingObj->TxnTaxDetail->TotalTax : 0;
            $shiflBill->shifl_office_from_id = isset($shipmentInfo['shifl_office_origin_from_id']) ? $shipmentInfo['shifl_office_origin_from_id'] : 0;
            $shiflBill->shifl_office_to_id = isset($shipmentInfo['shifl_office_origin_to_id']) ? $shipmentInfo['shifl_office_origin_to_id'] : 0;
            $shiflBill->qbo_company_realmid = $qboRealmId;
            //No HomeTotalAmount in Bill $shiflBill->home_total_amount = isset($resultingObj->HomeTotalAmt) ? $resultingObj->HomeTotalAmt : null;
            $shiflBill->balance = isset($resultingObj->Balance) ? $resultingObj->Balance : null;
            $shiflBill->home_balance = isset($resultingObj->HomeBalance) ? $resultingObj->HomeBalance : null;
            $shiflBill->currency_ref = isset($resultingObj->CurrencyRef) ? $resultingObj->CurrencyRef : null;
            $shiflBill->exchange_rate = isset($resultingObj->ExchangeRate) ? $resultingObj->ExchangeRate : null;
            $shiflBill->exchange_rate_info = $request->exchangeRateValue !== null ? json_encode($request->exchangeRateValue) : null;
            $shiflBill->global_tax_calculation = isset($resultingObj->GlobalTaxCalculation) ? $resultingObj->GlobalTaxCalculation : null;
            $shiflBill->sync_token = isset($resultingObj->SyncToken) ? $resultingObj->SyncToken : 0;

            $shiflBill->save();

            //Save each line to Shifl DB
            foreach($lines as $line){
                $billItemIns = new BillItem;

                $billItemIns->bill_id = $shiflBill->id;
                $billItemIns->qbo_item_value = isset($line['Category']['Id']) ? $line['Category']['Id'] : '';
                //$billItemIns->qbo_customer_id = isset($line['Customer']['Id']) ? $line['Customer']['Id'] : '';
                $billItemIns->qbo_item_name = isset($line['Category']['Name']) ? $line['Category']['Name'] : '';
                //$billItemIns->qbo_customer_name = isset($line['Customer']['DisplayName']) ? $line['Customer']['DisplayName'] : '';
                $billItemIns->qbo_line_type = "AccountBasedExpenseLineDetail";
                $billItemIns->qbo_description = isset($line['Description']) ? $line['Description'] : '';
                $billItemIns->qbo_amount = isset($line['Amount']) ? $line['Amount'] : 0;
                $billItemIns->qbo_tax_code = isset($line['Tax']) ? json_encode($line['Tax']) : null;
                $billItemIns->save();

                $response['qbo_bill_id'] = $resultingObj->Id;
                $response['bill_id'] = $shiflBill->id;
            }
            $response['success'] = true;
        }
        return response()->json($response); 
    }

    public function editBill(Request $request){
        $response = ['success' => false, 'result'=>[]];
        $quickbooks = app('QuickBooks');
        $qboCompanyInfo = $request->qboCompanyInfo;
        $lines = $request->line;
        // $qboTxnTaxDetail = isset($request->qboTxnTaxDetail) ? json_decode($request->qboTxnTaxDetail) : [];
        if(isset($request->qboBillId) && $request->qboBillId > 0 ){

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

            $totalBillAmount = 0;
            if(count($lineItems) > 0){
                $totalBillAmount = collect($lineItems)->sum('Amount');
            }

            $getBillObject  = $quickbooks->getDataService()->FindbyId('bill',$request->qboBillId);

            $billObjectValue = [];
            if(isset($qboCompanyInfo['Country']) && $qboCompanyInfo['Country'] === 'IN'){
                $billObjectValue = $this->setBillObjectValuesForIndia($lineItems,$request);
            }else{
                $billObjectValue = [
                    "Id"=> $request->qboBillId,
                    "DocNumber" => $request->billNum,
                    "VendorRef" => [
                        "value" => $request->vendorId,
                        "name"=>$request->vendorName
                    ],
                    "SalesTermRef" => [
                        "value" => $request->termId
                    ],
                    "TxnDate" => $request->billDate,
                    "DueDate" => $request->dueDate,
                    "PrivateNote"=>$request->memo,
                    "Line" => $lineItems,
                    "ExchangeRate" => isset($request->exchangeRateValue) && $request->exchangeRateValue !== null ? $request->exchangeRateValue['Rate'] : '',
                    "CurrencyRef" => [
                        "value" => isset($request->vendorCurrencyRef) && $request->vendorCurrencyRef !== null ? $request->vendorCurrencyRef : '',
                    ],
                ];
            }
            //return response()->json($billObjectValue);
            $qbBillResourceObj = Bill::update($getBillObject,$billObjectValue);

            $resultingObj  = $quickbooks->getDataService()->Update($qbBillResourceObj);
            $error = $quickbooks->getDataService()->getLastError();

            if($error){
                return $error->getResponseBody();
            }else{
                if(isset($qboCompanyInfo['Country']) && $qboCompanyInfo['Country'] === 'IN'){
                    if(isset($resultingObj->TxnTaxDetail)){
                        $taxDetail = $this->setTaxDetail($resultingObj->TxnTaxDetail);
                    }
                }
                $shiflBill = ShiflBill::find($request->shiflBillId);
                $shiflBill->qbo_vendor_id = $request->vendorId;
                $shiflBill->qbo_term_id = $request->termId;
                $shiflBill->qbo_bill_num = $request->billNum;
                $shiflBill->qbo_term_name = $request->termName;
                $shiflBill->qbo_term_days = $request->termDueDays;
                $shiflBill->qbo_vendor_name = $request->vendorName;
                $shiflBill->qbo_mailing_address = $request->mailingAddress;
                $shiflBill->qbo_memo = $request->memo;
                $shiflBill->qbo_bill_date = $request->billDate;
                $shiflBill->qbo_due_date = $request->dueDate;
                // $shiflBill->total_amount = isset($resultingObj->TotalAmt) ? $resultingObj->TotalAmt : $totalBillAmount;
                // $shiflBill->total_amount = $totalBillAmount;
                $shiflBill->total_amount = isset($resultingObj->TotalAmt) ? $resultingObj->TotalAmt : 0;
                $shiflBill->qbo_tax_detail = isset($taxDetail) ? json_encode($taxDetail) : null;
                $shiflBill->total_tax = isset($resultingObj->TxnTaxDetail->TotalTax) ? $resultingObj->TxnTaxDetail->TotalTax : 0;
                //No HomeTotalAmount in Bill $shiflBill->home_total_amount = isset($resultingObj->HomeTotalAmt) ? $resultingObj->HomeTotalAmt : null;
                $shiflBill->balance = isset($resultingObj->Balance) ? $resultingObj->Balance : null;
                $shiflBill->home_balance = isset($resultingObj->HomeBalance) ? $resultingObj->HomeBalance : null;
                $shiflBill->currency_ref = isset($resultingObj->CurrencyRef) ? $resultingObj->CurrencyRef : null;
                $shiflBill->exchange_rate = isset($resultingObj->ExchangeRate) ? $resultingObj->ExchangeRate : null;
                $shiflBill->exchange_rate_info = $request->exchangeRateValue !== null ? json_encode($request->exchangeRateValue) : null;
                $shiflBill->global_tax_calculation = isset($resultingObj->GlobalTaxCalculation) ? $resultingObj->GlobalTaxCalculation : null;
                $shiflBill->sync_token = isset($resultingObj->SyncToken) ? $resultingObj->SyncToken : 0;
            
                $shiflBill->save();

                $result = $this->updateBillItems($lines,$shiflBill->bill_items,$request->shiflBillId);

                $response['success'] = true;
                $response['result'] = $result;
            }
        }
        
        return response()->json($response); 

    }

    public function searchVendors(Request $request){
        $name = $request['query'];
        $quickbooks = app('QuickBooks');
        $activeVendors = $quickbooks->getDataService()->Query("select * from Vendor where Active=true AND DisplayName like '%".$name."%'");
        return response()->json($activeVendors);
    }

    public function getActiveVendors(Request $request){
        $name = $request['query'];
        $quickbooks = app('QuickBooks');
        $activeVendors = $quickbooks->getDataService()->Query("select Id, DisplayName from Vendor where Active=true");
        return response()->json($activeVendors);
    }

    public function getItemServices()
    {
        $quickbooks = app('QuickBooks');
        $allServices = $quickbooks->getDataService()->Query("select * from Item where Type='Service'");
        return $allServices;
    }

    public function getItemCategories()
    {
        $quickbooks = app('QuickBooks');
        $allCategories= $quickbooks->getDataService()->Query("select * from Item");
        return $allCategories;
    }

    public function getCustomers()
    {
        // $quickbooks = app('QuickBooks');
        // $allActiveCustomers = $quickbooks->getDataService()->Query("select * from Customer where Active=true");
        // return $allActiveCustomers;

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
            if ($x = 1){
                $cust[$x-1] = $quickbooks->getDataService()->Query("select Id, FullyQualifiedName from Customer Where Active = true", $x, $max);
                $prevMax = ($max*$x);
                $cust[$x-1] = (object) array_filter((array) $cust[$x-1], function ($val) {
                    return !is_null($val);
                });
                $allCusts = array_merge($allCusts,(array)$cust[$x-1]);
            }else{
                if ($x != $totPages){
                    $cust[$x-1] = $quickbooks->getDataService()->Query("select Id, FullyQualifiedName from Customer Where Active = true", ($prevMax+1), $max);
                    $prevMax = ($max*$x);
                    $cust[$x-1] = (object) array_filter((array) $cust[$x-1], function ($val) {
                        return !is_null($val);
                    });
                    $allCusts = array_merge($allCusts,(array)$cust[$x-1]);
                }else{
                    $cust[$x-1] = $quickbooks->getDataService()->Query("select Id, FullyQualifiedName from Customer Where Active = true", ($prevMax+1), $remainder);
                    $cust[$x-1] = (object) array_filter((array) $cust[$x-1], function ($val) {
                        return !is_null($val);
                    });
                    $allCusts = array_merge($allCusts,(array)$cust[$x-1]);
                }
            }
        }
        return response()->json($allCusts);
    }

    public function searchCustomers(Request $request)
    {
        $name = $request['query'];
        $quickbooks = app('QuickBooks');
        $allActiveCustomers = $quickbooks->getDataService()->Query("select Id, DisplayName from Customer where Active=true AND DisplayName like '%".$name."%'");
        return $allActiveCustomers;
    }

    public function getExpenseAccounts()
    {
        // $quickbooks = app('QuickBooks');
        // $allCategories= $quickbooks->getDataService()->Query("select * from Account where AccountType='Expense'");
        // return $allCategories;
        $quickbooks = app('QuickBooks');
        $categoryCount = $quickbooks->getDataService()->Query("select count(*) from Account where AccountType='Expense' AND Active=true");
        
        $max = 1000;
        $pages = ($categoryCount / $max);
        $remainder = ($categoryCount % $max);

        if ($remainder > 0){
            $totalPages = ($pages + 1);
        }else{
            $totalPages = $pages;
        }

        $cat = Array();
        $allCategories = Array();

        for ($x = 1; $x <= $totalPages; $x++) {
            if ($x = 1){
                $cat[$x-1] = $quickbooks->getDataService()->Query("select * from Account Where AccountType='Expense' AND Active=true", $x, $max);
                $prevMax = ($max*$x);
                $cust[$x-1] = (object) array_filter((array) $cat[$x-1], function ($val) {
                    return !is_null($val);
                });
                $allCategories = array_merge($allCategories,(array)$cat[$x-1]);
            }else{
                if ($x != $totalPages){
                    $cat[$x-1] = $quickbooks->getDataService()->Query("select * from Account Where AccountType='Expense' AND Active=true", ($prevMax+1), $max);
                    $prevMax = ($max*$x);
                    $cat[$x-1] = (object) array_filter((array) $cat[$x-1], function ($val) {
                        return !is_null($val);
                    });
                    $allCategories = array_merge($allCategories,(array)$cat[$x-1]);
                }else{
                    $cat[$x-1] = $quickbooks->getDataService()->Query("select * from Account Where AccountType='Expense' AND Active=true", ($prevMax+1), $remainder);
                    $cat[$x-1] = (object) array_filter((array) $cat[$x-1], function ($val) {
                        return !is_null($val);
                    });
                    $allCategories = array_merge($allCategories,(array)$cat[$x-1]);
                }
            }
        }
        return response()->json($allCategories);
    }

    public function searchExpenseAccounts(Request $request){
        $name = $request['query'];
        $quickbooks = app('QuickBooks');
        //$activeVendors = $quickbooks->getDataService()->Query("select Id, Name, Classification, AccountType from  Account where AccountType='Expense' AND Active=true AND Name like '%".$name."%'");
        $activeAccounts = $quickbooks->getDataService()->Query("select Id, Name, Classification, AccountType from  Account where Active=true AND Name like '%".$name."%'");
        return $activeAccounts;
    }

    public function getBillSyncToken($id){
        $quickbooks = app('QuickBooks');
        $idString = strval($id);
        $bill = $quickbooks->getDataService()->Query("select Id, SyncToken  from  Bill where Id='".$idString."'");
        return response()->json($bill);
    }

    public function getQBOBill($id){
        $quickbooks = app('QuickBooks');
        $idString = strval($id);
        $bill = $quickbooks->getDataService()->Query("select * from  Bill where Id='".$idString."'");
        return response()->json($bill);
    }

    public function deleteBill(Request $request){
        $response = ['success' => false];
        $quickbooks = app('QuickBooks');

        if(isset($request->qbo_bill_id) && $request->qbo_bill_id > 0 ){

            $getBillObject  = $quickbooks->getDataService()->FindbyId('bill',$request->qbo_bill_id);

            if($getBillObject === null){
                $shiflBill = ShiflBill::find($request->id)->delete();
                //***deleting of bill items is done in Bill model**//
                $response['success'] = true;
            }else{
                $resultingObj = $quickbooks->getDataService()->Delete($getBillObject);
                $error = $quickbooks->getDataService()->getLastError();
                if($error){
                    return $error->getResponseBody();
                }else{
                    $shiflBill = ShiflBill::find($request->id)->delete();
                    //***deleting of bill items is done in Bill model**//
                    $response['success'] = true;
                }
            }
        }
        return response()->json($response);     
    }

    private function updateBillItems($newItems,$oldItems,$shiflBillId){

        $newIds = array_filter(Arr::pluck($newItems, 'bill_item_id'), 'is_numeric');
        $oldIds = Arr::pluck($oldItems, 'id');

        $actions = ['deleted'=>0, 'updated'=>0, 'created'=>0];
        
        $items_to_delete = collect($oldItems)->filter(function ($model) use ($newIds) {
            return !in_array($model->id, $newIds);
        });

        $items_to_update = collect($newItems)->filter(function ($model) use ($oldIds) {
            return in_array($model['bill_item_id'], $oldIds);
        });

        $items_to_insert = collect($newItems)->filter(function ($model) use ($oldIds) {
            return $model['bill_item_id'] === 0;
        });
        
        if(count($items_to_delete)>0){
            $delete_ids = Arr::pluck($items_to_delete, 'id');
            $delete_count = 0;
            foreach($delete_ids as $id){
                BillItem::where('id',$id)->delete();
                $delete_count++;
            }
            if($delete_count > 0){
                $actions['deleted'] = $delete_count;
            }
        }

        if(count($items_to_update)>0){
            $update_ids = Arr::pluck($items_to_update, 'bill_item_id');
            $update_count = 0;
            foreach($items_to_update as $item){
                BillItem::where('id',$item['bill_item_id'])->update([
                    'qbo_item_value'=>$item['Category']['Id'],
                    //'qbo_customer_id'=>$item['Customer']['Id'],
                    'qbo_item_name'=>$item['Category']['Name'],
                    //'qbo_customer_name'=>$item['Customer']['DisplayName'],
                    'qbo_description'=>$item['Description'],
                    'qbo_amount'=>$item['Amount'],
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
                BillItem::create([
                    'bill_id' => $shiflBillId,
                    'qbo_item_value'=>$item ['Category']['Id'],
                    //'qbo_customer_id'=>$item['Customer']['Id'],
                    'qbo_item_name'=>$item['Category']['Name'],
                    //'qbo_customer_name'=>$item['Customer']['DisplayName'],
                    'qbo_line_type'=>"AccountBasedExpenseLineDetail",
                    'qbo_description'=>$item['Description'],
                    'qbo_amount'=>$item['Amount'],
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

    private function setLineItemsForIndia($lines){
        $lineItems = [];
        foreach($lines as $line){
            $lineitem = [
                "DetailType" => "AccountBasedExpenseLineDetail",
                "Amount" => isset($line['Amount']) ? $line['Amount'] : 0,
                "Description" => isset($line['Description']) ? $line['Description'] : '',
                "AccountBasedExpenseLineDetail" => [
                    "AccountRef" => [
                        "value" => isset($line['Category']['Id']) ? $line['Category']['Id'] : '',
                        "name" => isset($line['Category']['Name']) ? $line['Category']['Name'] : '',
                    ],
                    "TaxCodeRef" => [
                        "value" => $line['Tax']['Id'],
                        "name" => $line['Tax']['Name'],
                    ]
                ]
            ];
            array_push($lineItems, $lineitem);
        }
        return $lineItems;        
    }

    private function setLineItemsForUS($lines){
        $lineItems = [];
        foreach ($lines as $line) {
            $lineitem = [
                "DetailType" => "AccountBasedExpenseLineDetail",
                "Amount" => isset($line['Amount']) ? $line['Amount'] : 0,
                "Description" => isset($line['Description']) ? $line['Description'] : '',
                "AccountBasedExpenseLineDetail" => [
                    "AccountRef" => [
                        "value" => isset($line['Category']['Id']) ? $line['Category']['Id'] : '',
                        "name" => isset($line['Category']['Name']) ? $line['Category']['Name'] : '',
                    ]
                ]
            ];
            array_push($lineItems, $lineitem);
        }
        return $lineItems;       
    }

    private function setBillObjectValuesForIndia($lineItems,$request){
        // $qboTxnTaxDetail = isset($request->qboTxnTaxDetail) ? json_decode($request->qboTxnTaxDetail) : [];
        // $taxLine = [];
        // if(isset($qboTxnTaxDetail->TaxLine)){
        //     foreach($qboTxnTaxDetail->TaxLine as $taxline){
        //         $tl = [
        //             "Amount" => $taxline->Amount,
        //             "DetailType" => "TaxLineDetail",
        //             "TaxLineDetail" => (array)$taxline->TaxLineDetail
        //         ];
        //         array_push($taxLine, $tl);
        //     }
        // }
        return [
            "Line" => $lineItems,
            "DocNumber" => $request->billNum,
            "VendorRef" => [
                "value" => $request->vendorId
            ],
            "SalesTermRef" => [
                "value" => $request->termId
            ],
            "TxnDate" => $request->billDate,
            "DueDate" => $request->dueDate,
            "PrivateNote"=>$request->memo,
            "GlobalTaxCalculation" => "TaxExcluded",
            //"GlobalTaxCalculation" => "TaxInclusive",
            //"GlobalTaxCalculation" => "NotApplicable",
            "TxnTaxDetail" => [
                // "DefaultTaxCodeRef" => isset($qboTxnTaxDetail->DefaultTaxCodeRef) ? $qboTxnTaxDetail->DefaultTaxCodeRef : '',
                // "TaxLine" => $taxLine,
                // "TaxReviewStatus" => isset($qboTxnTaxDetail->TaxReviewStatus) ? $qboTxnTaxDetail->TaxReviewStatus : '',
                // "TotalTax" => isset($qboTxnTaxDetail->TotalTax) ? $qboTxnTaxDetail->TotalTax : '',
                // "TxnTaxCodeRef" => isset($qboTxnTaxDetail->TxnTaxCodeRef) ? $qboTxnTaxDetail->TxnTaxCodeRef : '',
                // "UseAutomatedSalesTax" => isset($qboTxnTaxDetail->UseAutomatedSalesTax) ? $qboTxnTaxDetail->UseAutomatedSalesTax : '',
            ],
            "ExchangeRate" => isset($request->exchangeRateValue) && $request->exchangeRateValue !== null ? $request->exchangeRateValue['Rate'] : '',
            "CurrencyRef" => [
                "value" => isset($request->vendorCurrencyRef) && $request->vendorCurrencyRef !== null ? $request->vendorCurrencyRef : '',
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
        // $taxLine = [];
        // if(isset($taxDetailData->TaxLine)){
        //     foreach($taxDetailData->TaxLine as $tl){
        //         $taxline = [
        //             "Amount" => $tl->Amount,
        //             "DetailType" => $tl->DetailType,
        //             "TaxLineDetail" => $tl->TaxLineDetail
        //         ];
        //         array_push($taxLine,$taxline);
        //     }
        // }
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
