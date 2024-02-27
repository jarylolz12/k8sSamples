<?php

namespace Shifl\QuickbooksAudits\Http\Controllers;

use Illuminate\Routing\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Arr;
use App\Bill;
use Carbon\Carbon;

class VerifyBillController extends Controller
{

    public function verifyBillInQBO(Request $request){
        $requestData = json_decode($request->payload);
        if(isset($requestData->qbo_bill_id)){
            $response = ['success' => false, 'result'=> '','is_present' => null, 'error' => false ];
            $quickbooks = app('QuickBooks');

            $qboBill  = $quickbooks->getDataService()->Query("Select *  from Bill where Id='".$requestData->qbo_bill_id."'");
            //$qboBill  = $quickbooks->getDataService()->FindById('Bill',$requestData->qbo_bill_id);
            
            $error = $quickbooks->getDataService()->getLastError();
            if($error) {
                $response['error'] = true;
                $response['result'] = $error->getResponseBody();
            }else{
                $response['success'] = true;
                if($qboBill === null || $qboBill === ''){
                    $response['is_present'] = false;
                }elseif(count($qboBill)>0){
                    $response['is_present'] = true;
                }else{
                    $response['is_present'] = true;
                }
            }
            return response()->json($response);
        }
    }
    
    public function verifyBillInShifl(Request $request){
        $requestData = json_decode($request->payload);
        
        if(isset($requestData->qbo_bill_id)){
            $response = ['success' => false, 'result'=> '', 'error' => false, 'is_present' => null];
            $companyInfo = $this->queryQBOCompanyInfo();
            
            $companyName = $companyInfo['result']->CompanyName;
            $qboCountry = $companyInfo['result']->Country;
            $qboBillId = $requestData->qbo_bill_id;
            // $qboBillId = 127;
            if(strtoupper($qboCountry) === 'US'){
                $shiflBill = Bill::where('qbo_bill_id', $qboBillId)
                    ->where(function($q) use ($companyName){
                        $q->where('qbo_company',$companyName)
                        ->orWhere('qbo_company', null);
                    })
                    ->first();
                    
            }elseif(strtoupper($qboCountry) === 'IN') {
                $shiflBill = Bill::where('qbo_bill_id', $qboBillId)
                    ->where('qbo_company',$companyName)
                    ->where('qbo_country','IN')
                    ->first();
            }
            // else{
            //     $shiflBill = Bill::where('qbo_bill_id', $qboBillId)
            //         ->where('qbo_company',$companyName)
            //         ->first();
            // }

            if(!isset($shiflBill->id)){
                $response['success'] = true;
                $response['is_present'] = false;
            }else{
                $response['is_present'] = true;
                $response['result'] = $shiflBill;
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

    public function deleteBill(Request $request){
        $response = ['success' => false, 'result'=> null, 'error' => false ];
        $quickbooks = app('QuickBooks');
        
        if($request->location === 'SHIFL_DB'){
            $checkExistence = Bill::where('id', $request->shifl_id)->first();
            // $checkExistence = Bill::where('id', 1000)->first();
            if(!isset($checkExistence->id)){
                $response['error'] = true;
                $response['result'] = "Bill not found. It might be already deleted.";
            }else{
                $delete = Bill::where('id',$request->shifl_id)->delete();
                if($delete){
                    $response['success'] = true;
                    $response['result'] = $delete;
                }
            }
        }
        if($request->location === 'QBO_DB'){

            $quickbooks = app('QuickBooks');
            $qboId = $request->qbo_bill_id;
            
            $qboBill = $quickbooks->getDataService()->FindbyId('Bill',$qboId);
            $error = $quickbooks->getDataService()->getLastError();

            if($error){
                $response['error'] = true;
                $response['result'] = $error->getResponseBody();
            }else{
                $resultingObj = $quickbooks->getDataService()->Delete($qboBill);
                $error = $quickbooks->getDataService()->getLastError();
                if($error){
                    $response['error'] = true;
                    $response['result'] = $error->getResponseBody();
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
