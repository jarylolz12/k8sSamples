<?php

namespace Shifl\QuickbooksAudits\Http\Controllers;

use Illuminate\Routing\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Arr;
use App\Invoice;
use App\Bill;

class QBOChangeDataCaptureController extends Controller
{

    public function runCDC(Request $request){
        $response = ['success' => false, 'result'=> [], 'errors' => []];
        $quickbooks = app('QuickBooks');
        $date = $request->date;
        $requestEntity = $request->entity;
        $qboInvoices = [];

        $companyInfo = $this->queryQBOCompanyInfo();
        if($companyInfo['success']){
            $companyName = $companyInfo['result']->CompanyName;
            $qboCountry = $companyInfo['result']->Country;
            
            if($date !== null && $requestEntity === 'INVOICE'){
                $cdcResponse  = $quickbooks->getDataService()->CDC([Str::camel($requestEntity)],$date);
                return response()->json($cdcResponse);
                $error = $quickbooks->getDataService()->getLastError();
    
                if($error) {
                    return $error->getResponseBody();
                }else{
                    if(isset($cdcResponse->entities['Invoice']) && ($requestEntity === 'INVOICE') ) {
                       foreach($cdcResponse->entities['Invoice'] as $invoice){
                           array_push($qboInvoices,$invoice);
                       }
                       if(count($qboInvoices)>0){
                        $response['result'] = $this->getMismatchInvoices($qboInvoices,$date,$companyName,$qboCountry);
                        $response['success'] = true;
                        }
                    }
    
                    if(isset($cdcResponse->entities['Bill']) && ($requestEntity === 'BILL')) {
                        
                    }
                }    
            }
        }
        
        return response()->json($response);
    }

    private function getMismatchInvoices($qboInvoices,$date,$companyName,$qboCountry){
        $data = ['invoicesNotInQBO'=>[],'invoicesNotInShifl'=>[], 'qboQueryCounts' => 0, 'shiflQueryCounts' => 0];
        $qbo_invoice_ids = [];
        $shifl_invoice_ids = [];
        $invoicesNotInQBO = [];
        $invoicesNotInShifl = [];

        if(count($qboInvoices)>0){
            $get_qbo_ids = Arr::pluck($qboInvoices,'Id');
            if(count($get_qbo_ids)>0){
                foreach($get_qbo_ids as $id){
                    array_push($qbo_invoice_ids,(int)$id);
                }
            }
            if($qboCountry !== 'IN'){
                $getInvoices = Invoice::where('created_at', '>=', $date)
                ->where(function($q){
                    $q->where('qbo_country','!=','IN')
                    ->orWhere('qbo_country',null);
                })
                ->get();
            }else{
                $getInvoices = Invoice::where('created_at', '>=', $date)
                ->where(function($q){
                    $q->where('qbo_country','=','IN');
                })
                ->get();
            }
           

            if(count($getInvoices)>0){
                $shifl_invoice_ids = Arr::pluck($getInvoices,'qbo_id');
            }
        }
        if(count($qbo_invoice_ids) > 0 && count($shifl_invoice_ids) > 0){
            $invoicesNotInQBO = collect($getInvoices)->filter(function ($model) use ($qbo_invoice_ids) {
                return !in_array($model->qbo_id, $qbo_invoice_ids);
            });
            $invoicesNotInShifl = collect($qboInvoices)->filter(function ($model) use ($shifl_invoice_ids) {
                return !in_array($model->Id, $shifl_invoice_ids);
            });
        }
        $data['invoicesNotInQBO'] = $invoicesNotInQBO;
        $data['invoicesNotInShifl'] = $invoicesNotInShifl;
        $data['qboQueryCounts'] = count($qboInvoices);
        $data['shiflQueryCounts'] = count(isset($getInvoices) ? $getInvoices : []);
        return $data;
    }

    public function getQBOCompanyInfo(){
        $response = ['success' => false, 'result'=> [], 'errors' => []];
        $companyInfo = $this->queryQBOCompanyInfo();
        if($companyInfo['success']){
            $response['success'] = true;
            $response['result'] = $companyInfo['result'];
        }
        return response()->json($response);
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

}
