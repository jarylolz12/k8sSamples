<?php

namespace Shifl\QuickbooksAudits\Http\Controllers;

use Illuminate\Routing\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Arr;
use App\Invoice;
use Carbon\Carbon;

class VerifyInvoiceController extends Controller
{

    public function verifyInvoiceInQBO(Request $request){
        $requestData = json_decode($request->payload);
        if(isset($requestData->qbo_id)){
            $response = ['success' => false, 'result'=> '', 'error' => false, 'is_present' => true];
            $quickbooks = app('QuickBooks');
            $qboInvoice  = $quickbooks->getDataService()->Query("Select *  from Invoice where Id='".$requestData->qbo_id."'");
            // $qboInvoice  = $quickbooks->getDataService()->Query("Select *  from Invoice where Id='100'");
            $error = $quickbooks->getDataService()->getLastError();
            if($error) {
                $response['error'] = true;
                $response['result'] = $error->getResponseBody();
            }else{
                $response['success'] = true;
                if($qboInvoice === null || $qboInvoice === ''){
                    $response['is_present'] = false;
                }elseif(count($qboInvoice)>0){
                    $response['is_present'] = true;
                }else{
                    $response['is_present'] = true;
                }
            }
            return response()->json($response);
        }
    }
    
    public function verifyInvoiceInShifl(Request $request){
        $requestData = json_decode($request->payload);
        
        if(isset($requestData->qbo_id)){
            $response = ['success' => false, 'result'=> '', 'error' => false, 'is_present' => null];
            $companyInfo = $this->queryQBOCompanyInfo();
            $companyName = $companyInfo['result']->CompanyName;
            $qboCountry = $companyInfo['result']->Country;

            if(strtoupper($qboCountry) === 'US'){
                $shiflInvoice = Invoice::where('qbo_id', $requestData->qbo_id)
                    ->where(function($q) use ($companyName){
                        $q->where('qbo_company',$companyName)
                        ->orWhere('qbo_company', null);
                    })
                    ->first();
                    
            }elseif(strtoupper($qboCountry) === 'IN') {
                $shiflInvoice = Invoice::where('qbo_id', $requestData->qbo_id)
                    ->where('qbo_company',$companyName)
                    ->where('qbo_country','IN')
                    ->first();
            }
            // else{
            //     $shiflInvoice = Invoice::where('qbo_id', $requestData->qbo_id)
            //         ->where('qbo_company',$companyName)
            //         ->first();
            // }
                
            if(!isset($shiflInvoice->id)){
                $response['success'] = true;
                $response['is_present'] = false;
            }else {
                $response['success'] = true;
                $response['is_present'] = true;
            }
            return response()->json($response);
        }
    }

    private function queryQBOCompanyInfo(){
        $response = ['success' => false, 'result'=> [], 'errors' => []];
        $quickbooks = app('QuickBooks');
        try{
            $companyInfo = $quickbooks->getDataService()->getCompanyInfo();
            $error = $quickbooks->getDataService()->getLastError();
            if($error){
                return $error->getResponseBody();
            }else{
                $response['success'] = true;
                $response['result'] = $companyInfo;
            }
            return $response;
        }catch(Throwable $e){
            $response['errors'] = $e;
            return $response;
        }
    }

    public function deleteInvoice(Request $request){
        $response = ['success' => false, 'result'=> [], 'errors' => []];
        $quickbooks = app('QuickBooks');
        if($request->location === 'SHIFL_DB'){
            $checkExistence = Invoice::where('id', $request->shifl_id)->first();
            if(!isset($checkExistence->id)){
                $response['errors'] = "Invoice not found. It might be already deleted.";
            }else{
                $delete = Invoice::where('id',$request->shifl_id)->delete();
                if($delete){
                    $response['success'] = true;
                    $response['result'] = $delete;
                }
            }
        }
        if($request->location === 'QBO_DB'){
            $quickbooks = app('QuickBooks');
            $qboId = $request->qbo_id;
            //$qboId = 300;
            $qboInvoice  = $quickbooks->getDataService()->FindbyId('invoice',$qboId);
            
            $error = $quickbooks->getDataService()->getLastError();
            if($error){
                $response['errors'] = "Invoice not found. It might be already deleted.";
            }else{
                $resultingObj = $quickbooks->getDataService()->Delete($qboInvoice);
                $error = $quickbooks->getDataService()->getLastError();
                if($error){
                    $response['errors'] = "Failed to delete invoice.";
                }else{
                    $response['success'] = true;
                    $response['result'] = $resultingObj;
                }
            }
        }
        return response()->json($response);
    }

    // public function saveToArchive(Request $request){
        
    //     $response = ['success' => false, 'result'=> [], 'errors' => []];
    //     $qboCompanyName = '';
    //     $qboCountry = '';
    //     if($request->location === 'SHIFL_DB'){
    //         $checkExistence = MismatchedEntities::where('qbo_invoice_id',  $request->qbo_id)
    //         ->where('qbo_company', $request->qbo_company)
    //         ->first();
    //     }elseif($request->location === 'QBO_DB'){
    //         $companyInfo = $this->queryQBOCompanyInfo();
    //         $qboCompanyName = $companyInfo['result']->CompanyName;
    //         $qboCountry = $companyInfo['result']->Country;

    //         $checkExistence = MismatchedEntities::where('qbo_invoice_id',  $request->qbo_id)
    //         ->where('qbo_company', $qboCompanyName)
    //         ->first();
    //     }

        
    //     if(isset($checkExistence->id)){
    //         $response['errors'] = "Invoice is already archived.";
    //     }else{
    //         if($request->location === 'SHIFL_DB'){
    //             $mismatchedEntities = new MismatchedEntities;
    //             $mismatchedEntities->entity = "INVOICE";
    //             $mismatchedEntities->document_number = $request->invoice_num;
    //             $mismatchedEntities->qbo_invoice_id = $request->qbo_id;
    //             $mismatchedEntities->shifl_invoice_id = isset($request->shifl_id) ? $request->shifl_id : null;
    //             $mismatchedEntities->qbo_company = $request->qbo_company;
    //             $mismatchedEntities->qbo_country = $request->qbo_country;
    //             $mismatchedEntities->is_qbo_origin = false;
    //             $mismatchedEntities->invoice_date = Carbon::parse($request->invoice_date);
    //             $mismatchedEntities->invoice_created_at = Carbon::parse($request->invoice_created_at);
    //             $mismatchedEntities->save();
    
    //             if(isset($mismatchedEntities->id)){
    //                 $response['success'] = true;
    //             }
    
    //         }elseif($request->location === 'QBO_DB'){

    //             $mismatchedEntities = new MismatchedEntities;
    //             $mismatchedEntities->entity = "INVOICE";
    //             $mismatchedEntities->document_number = $request->invoice_num;
    //             $mismatchedEntities->qbo_invoice_id = $request->qbo_id;
    //             $mismatchedEntities->shifl_invoice_id = isset($request->shifl_id) ? $request->shifl_id : null;
    //             $mismatchedEntities->qbo_company = $qboCompanyName;
    //             $mismatchedEntities->qbo_country = $qboCountry;
    //             $mismatchedEntities->is_qbo_origin = true;
    //             $mismatchedEntities->invoice_date = Carbon::parse($request->invoice_date);
    //             $mismatchedEntities->invoice_created_at = Carbon::parse($request->invoice_created_at);
    //             $mismatchedEntities->save();
    
    //             if(isset($mismatchedEntities->id)){
    //                 $response['success'] = true;
    //             }
    //         }else{
    
    //         }
    //     }
    //     return response()->json($response);
    // }

}
