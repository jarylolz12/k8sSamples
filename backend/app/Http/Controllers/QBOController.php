<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;
use App\QuickbooksCompany;
use App\Invoice;
use App\Bill;
use QuickBooksOnline\API\Data\IPPExchangeRate;
use Carbon\Carbon;

class QBOController extends Controller
{
    /********
        INVOICE
    ********/
    public function getInvoiceById(Request $request){
        $response = ['success' => false, 'result'=> [], 'error' => false];
        $qboInvoiceId = $request->qboInvoiceId;
        $quickbooks = app('QuickBooks');

        $invoiceObject = $quickbooks->getDataService()->findById("Invoice",$qboInvoiceId);
        $error = $quickbooks->getDataService()->getLastError();

        $paymentDetail = [];

        if($error){
            $response['error'] = true;
            $response['result'] = $error->getResponseBody();
        }else{

            if($invoiceObject->LinkedTxn !== null){
                if(is_object($invoiceObject->LinkedTxn)){
                    $txnType = strtoupper($invoiceObject->LinkedTxn->TxnType);
                    if($txnType === "PAYMENT"){
                        $getPayment = $this->getPaymentById($invoiceObject->LinkedTxn->TxnId);
                        if(!$getPayment['error']){
                            array_push($paymentDetail, $getPayment['result']);
                        }
                    }
                }else{
                    if(count($invoiceObject->LinkedTxn)>0){
                        foreach($invoiceObject->LinkedTxn as $linkTxn){
                            $txnType = strtoupper($linkTxn->TxnType);
                            if($txnType === "PAYMENT"){
                                $getPayment = $this->getPaymentById($linkTxn->TxnId);
                                if(!$getPayment['error']){
                                    array_push($paymentDetail, $getPayment['result']);
                                }
                            }
                        }
                    }
                }
            }
            $response['result'] = [
                'invoice' => $invoiceObject,
                'payment' => $paymentDetail,
            ];
            $response['success'] = true;
        }
        return response()->json($response);
    }
    /********
        INVOICE END
    ********/


    /********
        TAXES
    ********/
    public function getTaxRates(){
        $quickbooks = app('QuickBooks');
        $response = ['success' => false, 'result'=> []];

        $taxRate = $quickbooks->getDataService()->Query("select * from Taxrate");
        $error = $quickbooks->getDataService()->getLastError();

        if($error){
            return $error->getResponseBody();
        }else{
            $response['result'] = $taxRate;
        }
        return response()->json($response);
    }

    // public function getTaxRates(){
    //     $quickbooks = app('QuickBooks');
    //     $response = ['success' => false, 'result'=> []];

    //     $taxRate = $quickbooks->getDataService()->Query("select * from Taxrate where Description like '%GST%'");
    //     $error = $quickbooks->getDataService()->getLastError();

    //     if($error){
    //         return $error->getResponseBody();
    //     }else{
    //         $response['result'] = $taxRate;
    //         $response['success'] = true;
    //     }
    //     return response()->json($response);
    // }

    public function getTaxCodes(){
        $quickbooks = app('QuickBooks');
        $response = ['success' => false, 'result'=> []];

        $taxRate = $quickbooks->getDataService()->Query("select * from Taxcode");
        $error = $quickbooks->getDataService()->getLastError();

        if($error){
            return $error->getResponseBody();
        }else{
            $response['result'] = $taxRate;
            $response['success'] = true;
        }
        return response()->json($response);
    }

    public function getTaxRateById(Request $request){
        $taxRateId = $request->query('taxRateRef');
        $quickbooks = app('QuickBooks');
        $response = ['success' => false, 'result'=> []];

        $taxRate = $quickbooks->getDataService()->Query("select Id, Name, RateValue from TaxRate where Id ='".$taxRateId."'");
        $error = $quickbooks->getDataService()->getLastError();

        if($error){
            return $error->getResponseBody();
        }else{
            $response['result'] = $taxRate;
            $response['success'] = true;
        }
        return response()->json($response);
    }
    /********
        TAXES END
    ********/

    /********
        PAYMENT
    ********/
    public function getPaymentById($paymentId){
        $response = ['success' => false, 'result'=> [], 'error' => false];
        $quickbooks = app('QuickBooks');

        $paymentObject = $quickbooks->getDataService()->findById("Payment",$paymentId);
        $error = $quickbooks->getDataService()->getLastError();

        if($error){
            $response['error'] = true;
            $response['result'] = $error->getResponseBody();
        }else{
            $response['result'] = $paymentObject;
            $response['success'] = true;
        }
        return $response;
    }
    /********
        PAYMENT END
    ********/

    /********
        COMPANY
    ********/
    public function getCompanyInfo(){
        $response = ['success' => false, 'result'=> [], 'errors' => []];
        $quickbooks = app('QuickBooks');
        $qboRealmId = Auth::user()->quickbookstoken->realm_id;
        $companyInfo = $quickbooks->getDataService()->getCompanyInfo();
        $error = $quickbooks->getDataService()->getLastError();
        if($error){
            return $error->getResponseBody();
        }else{
            $response['success'] = true;
            $response['result'] = [
                'company' => $companyInfo,
                'realm_id' => strval($qboRealmId)
            ];
        }
        return response()->json($response);
    }

    public function getQBOCompanies(){
        $companies = [];
        $getCompanies = QuickbooksCompany::select('id','company_realm_id','company_name','country','currency','currency_code')->get();
        foreach($getCompanies as $comp){
            $c = [
                'id'=> $comp->id,
                'company_realm_id' => strval($comp->company_realm_id),
                'company_name' => $comp->company_name,
                'country' => $comp->country,
                'currency'=> $comp->currency,
                'currency_code'=> $comp->currency_code,
            ];
            array_push($companies,$c);
        }
        return response()->json($companies);
    }
    /********
        COMPANY END
    ********/

    /********
        CURRENCY
    ********/
    public function getExchangerateByCurrencyCode(Request $request){
        $response = ['result'=> null, 'error' => false ];

        $sourceCurrencyCode = $request->sourceCurrencyCode;
        $targetCurrencyCode = $request->targetCurrencyCode;

        $quickbooks = app('QuickBooks');
        $exchangeRates = $quickbooks->getDataService()->Query("select * from exchangerate where sourcecurrencycode='".$sourceCurrencyCode."' and asofdate='".Carbon::now()->subdays(1)->format('Y-m-d')."'");
        $error = $quickbooks->getDataService()->getLastError();
        if($error){
            $response['error'] = true;
        }else{
            $collectER = collect($exchangeRates)->filter(function($o) use ($sourceCurrencyCode, $targetCurrencyCode) {
                // return $o->SourceCurrencyCode !== $o->TargetCurrencyCode;
                return $o->SourceCurrencyCode !== $o->TargetCurrencyCode && ($o->SourceCurrencyCode === $sourceCurrencyCode && $o->TargetCurrencyCode === $targetCurrencyCode);
            });
            if(count($collectER)>0){
                $response['result'] = $collectER[0];
            }
        }

        return response()->json($response);

    }

    /********
        CURRENCY END
    ********/


    /********
        CUSTOMER STARTS
    ********/

    public function getUpdatedCustomerEmail($customer_id){
        $response = ['result'=> null, 'error' => false ];

        $quickbooks = app('QuickBooks');
        $customer = $quickbooks->getDataService()->Query("select Id, PrimaryEmailAddr from customer where id='".$customer_id."'");
        $error = $quickbooks->getDataService()->getLastError();
        if($error){
            $response['error'] = true;
        }else{
            $response['result'] = $customer[0];
        }

        return response()->json($response);
    }

    /********
        CUSTOMER ENDS
    ********/

    /********
        VENDOR STARTS
    ********/

    // public function getUpdatedVendorEmail($vendor_id){
    //     $response = ['result'=> null, 'error' => false ];

    //     $quickbooks = app('QuickBooks');
    //     $vendor = $quickbooks->getDataService()->Query("select Id, PrimaryEmailAddr from vendor where id='".$vendor_id."'");
    //     $error = $quickbooks->getDataService()->getLastError();
    //     if($error){
    //         $response['error'] = true;
    //     }else{
    //         $response['result'] = $vendor[0];
    //     }

    //     return response()->json($response);
    // }

    /********
        VENDOR ENDS
    ********/

}
