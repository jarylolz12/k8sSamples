<?php

namespace Shifl\QuickbooksAudits\Http\Controllers;

use Illuminate\Routing\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Arr;
use App\Invoice;
use App\Bill;
use Illuminate\Support\Facades\Auth;


class SearchMismatchEntitiesController extends Controller
{

    public function searchMismatchEntities(Request $request){
        $response = ['success' => false, 'result'=> [], 'error' => false ];
        $quickbooks = app('QuickBooks');
        $dateFrom = $request->dateFrom;
        $dateTo = $request->dateTo;
        $requestEntity = $request->entity;
        $qboInvoices = [];
        $userQBORealmId = Auth::user()->quickbookstoken->realm_id;

        //$companyInfo = $this->queryQBOCompanyInfo();
        if($userQBORealmId){
            //$companyName = $companyInfo['result']->CompanyName;
            //$qboCountry = $companyInfo['result']->Country;
            
            if($dateFrom !== null && strtoupper($requestEntity) === 'INVOICE'){
                $qboInvoices = $this->getEntityFromQBO('Invoice',$dateFrom,$dateTo);
                // $qboInvoices  = $quickbooks->getDataService()->Query("Select *  from Invoice where Metadata.CreateTime >='".$dateFrom."' and Metadata.CreateTime <='".$dateTo."' maxresults 1000");
                // $error = $quickbooks->getDataService()->getLastError();
                
                if($qboInvoices['error']){
                    $response['error'] = true;
                    $response['result'] = "Something went wrong while fetching invoices from Quickbooks.";
                }
                else{
                    if(count($qboInvoices['result']) > 0){
                    $response['result'] = $this->getMismatchInvoices($qboInvoices['result'],$dateFrom,$dateTo,$userQBORealmId);
                    $response['success'] = true;
                    }else{
                        $response['error'] = true;
                        $response['result'] = "No invoice have found.";
                    }
                }  
            }
            if($dateFrom !== null && strtoupper($requestEntity) === 'BILL'){
                $qboBills = $this->getEntityFromQBO('Bill',$dateFrom,$dateTo);
                
                if($qboBills['error']){
                    $response['error'] = true;
                    $response['result'] = "Something went wrong while fetching bills from Quickbooks.";
                }
                else{
                    if(count($qboBills['result']) > 0){
                    $response['result'] = $this->getMismatchBills($qboBills['result'],$dateFrom,$dateTo,$userQBORealmId);
                    $response['success'] = true;
                    }else{
                        $response['error'] = true;
                        $response['result'] = "No bills have found.";
                    }
                }  
            }
        }
        
        return response()->json($response);
    }

    private function getEntityFromQBO($entity,$dateFrom,$dateTo){
        $response = ['success' => false, 'result'=> [], 'error' => false ];
        $quickbooks = app('QuickBooks');
        $entityType = Str::camel($entity);
        $entities = [];
        $allEntities = [];
        
        if($entityType !== null){
            $entityCount  = $quickbooks->getDataService()->Query("Select count(*)  from ".$entityType."");
            $error = $quickbooks->getDataService()->getLastError();
            $max = 1000;
            
            if($error) {
                $response['error'] = true;
                $response['result'] = $error->getResponseBody();
            }else{
                $pages = $entityCount/$max;
                $remainder = $entityCount % $max;
                $totalPages = $remainder > 0 ? (int)$pages + 1 : (int)$pages;
                $prevP = 0;

                for($p = 1; $p <= $totalPages; $p++){
                    if($p == 1){
                        $entities[$p-1] = $quickbooks->getDataService()->Query("select * from ".$entityType." where Metadata.CreateTime >='".$dateFrom."' and Metadata.CreateTime <='".$dateTo."'",$p,$max);
                        $entities[$p-1] = array_filter((array) $entities[$p-1], function ($val) {
                            return !is_null($val);
                        });
                        $allEntities = array_merge($allEntities, $entities[$p-1]);
                        $prevP = $max*$p;
                    }else{
                        if($p != $totalPages){
                            $entities[$p-1] = $quickbooks->getDataService()->Query("select * from ".$entityType." where Metadata.CreateTime >='".$dateFrom."' and Metadata.CreateTime <='".$dateTo."'",($prevP+1),$max);
                            $entities[$p-1] = array_filter((array) $entities[$p-1], function ($val) {
                                return !is_null($val);
                            });
                            $allEntities = array_merge($allEntities, $entities[$p-1]);
                            $prevP = $max*$p;
                        }else{
                            $entities[$p-1] = $quickbooks->getDataService()->Query("select * from ".$entityType." where Metadata.CreateTime >='".$dateFrom."' and Metadata.CreateTime <='".$dateTo."'",($prevP+1),$remainder);
                            $entities[$p-1] = array_filter((array) $entities[$p-1], function ($val) {
                                return !is_null($val);
                            });
                            $allEntities = array_merge($allEntities, $entities[$p-1]);
                        }
                    }
                    $error = $quickbooks->getDataService()->getLastError();
                    if($error){
                        $response['error'] = true;
                        $response['result'] = $error->getResponseBody();
                        return $response;
                    }         
                }
            }
            $response['result'] = $allEntities;
        }
        return $response;
    }

    private function getMismatchInvoices($qboInvoices,$dateFrom,$dateTo,$userQBORealmId){
        $data = ['invoicesNotInQBO'=>[],'invoicesNotInShifl'=>[], 'qboQueryCounts' => 0, 'shiflQueryCounts' => 0];
        $qbo_invoice_ids = [];
        $shifl_invoice_ids = [];
        $invoicesNotInQBO = [];
        $invoicesNotInShifl = [];
        
        // $companyName = $companyInfo->CompanyName;
        // $qboCountry = $companyInfo->Country;
        $qboInvoicesArr = $qboInvoices !== null ? $qboInvoices  : [];

        $get_qbo_invoices_ids = Arr::pluck($qboInvoicesArr,'Id');

        if(count($get_qbo_invoices_ids)>0){
            foreach($get_qbo_invoices_ids as $id){
                array_push($qbo_invoice_ids,(int)$id);
            }
        }

        $shiflInvoices = Invoice::where('created_at', '>=', $dateFrom)
        ->where('created_at', '<=', $dateTo)
        ->where('qbo_company_realmid',$userQBORealmId)
        ->get();
        // if($qboCountry === 'IN'){
        //     $getInvoices = Invoice::where('created_at', '>=', $dateFrom)
        //     ->where('created_at', '<=', $dateTo)
        //     ->where('qbo_country', 'IN')
        //     ->where('qbo_company', $companyName)
        //     ->get();
        // }elseif($qboCountry === 'US'){
        //     $getInvoices = Invoice::where('created_at', '>=', $dateFrom)
        //     ->where('created_at', '<=', $dateTo)
        //     ->where(function($q) use ($companyName){
        //         $q->where('qbo_company','=',$companyName)
        //         ->orWhere('qbo_company','=',null);
        //     })
        //     ->get();
        // }

        if(count($shiflInvoices)>0){
            $shifl_invoice_ids = Arr::pluck($shiflInvoices,'qbo_id');
        }

        $invoicesNotInQBO = collect($shiflInvoices)->filter(function ($model) use ($qbo_invoice_ids) {
            return !in_array($model->qbo_id, $qbo_invoice_ids);
        });
        $invoicesNotInShifl = collect($qboInvoices)->filter(function ($model) use ($shifl_invoice_ids) {
            return !in_array($model->Id, $shifl_invoice_ids);
        });

        //return $get_qbo_invoices_ids;
        $data['invoicesNotInQBO'] = $invoicesNotInQBO;
        $data['invoicesNotInShifl'] = $invoicesNotInShifl;
        $data['qboQueryCounts'] = is_array($qboInvoices) ? count($qboInvoices) : 0;
        $data['shiflQueryCounts'] = count(isset($shiflInvoices) ? $shiflInvoices : []);
        return $data;
    }
    
    // public function searchMismatchBill(Request $request){
    //     $response = ['success' => false, 'result'=> [], 'error' => false];
    //     $quickbooks = app('QuickBooks');
    //     $dateFrom = $request->dateFrom;
    //     $dateTo = $request->dateTo;
    //     $requestEntity = $request->entity;
    //     $qboBills = [];

    //     $companyInfo = $this->queryQBOCompanyInfo();
    //     if($companyInfo['success']){
    //         $companyName = $companyInfo['result']->CompanyName;
    //         $qboCountry = $companyInfo['result']->Country;
            
    //         if($dateFrom !== null && strtoupper($requestEntity) === 'BILL'){
    //             $qboBills  = $quickbooks->getDataService()->Query("Select *  from Bill where Metadata.CreateTime >='".$dateFrom."' and Metadata.CreateTime <='".$dateTo."'");
    //             $error = $quickbooks->getDataService()->getLastError();
                
    //             if($error) {
    //                 $response['error'] = true;
    //                 $response['result'] = $error->getResponseBody();
    //             }else{
    //                 $response['result'] = $this->getMismatchBills($qboBills,$dateFrom,$dateTo,$companyInfo['result']);
    //                 $response['success'] = true;
    //             }    
    //         }
    //     }
    //     return response()->json($response);
    // }

    private function getMismatchBills($qboBills,$dateFrom,$dateTo,$userQBORealmId){
        $data = ['billsNotInQBO'=>[],'billsNotInShifl'=>[], 'qboQueryCounts' => 0, 'shiflQueryCounts' => 0];
        $qbo_bills_ids = [];
        $shifl_bills_ids = [];
        $billsNotInQBO = [];
        $billsNotInShifl = [];

        //$companyName = $companyInfo->CompanyName;
        //$qboCountry = $companyInfo->Country;
        $qboBillsArr = $qboBills !== null ? $qboBills  : [];

        $shiflBills = Bill::where('created_at', '>=', $dateFrom)
                ->where('created_at', '<=', $dateTo)
                ->where('qbo_company_realmid',$userQBORealmId)
                ->get();

        if(count($shiflBills)>0){
            $shifl_bills_ids = Arr::pluck($shiflBills,'qbo_bill_id');
        }

        // if(true){
            // $get_qbo_bils_ids = Arr::pluck($qboBillsArr,'Id');
            // if(count($get_qbo_bils_ids)>0){
            //     foreach($get_qbo_bils_ids as $id){
            //         array_push($qbo_bills_ids,(int)$id);
            //     }
            // }
            // if($qboCountry === 'IN'){
            //     $getBills = Bill::where('created_at', '>=', $dateFrom)
            //     ->where('created_at', '<=', $dateTo)
            //     ->where('qbo_country', 'IN')
            //     ->where('qbo_company', $companyName)
            //     ->get();
            // }elseif($qboCountry === 'US'){
            //     $getBills = Bill::where('created_at', '>=', $dateFrom)
            //     ->where('created_at', '<=', $dateTo)
            //     ->where(function($q) use ($companyName){
            //         $q->where('qbo_company','=',$companyName)
            //         ->orWhere('qbo_company','=',null);
            //     })
            //     ->get();
            // }
            
        // }
        $billsNotInQBO = collect($shiflBills)->filter(function ($model) use ($qbo_bills_ids) {
            return !in_array($model->qbo_bill_id, $qbo_bills_ids);
        });
        $billsNotInShifl = collect($qboBills)->filter(function ($model) use ($shifl_bills_ids) {
            return !in_array($model->Id, $shifl_bills_ids);
        });
        $data['billsNotInQBO'] = $billsNotInQBO;
        $data['billsNotInShifl'] = $billsNotInShifl;
        $data['qboQueryCounts'] = is_array($qboBills) ? count($qboBills) : 0;
        $data['shiflQueryCounts'] = count(isset($shiflBills) ? $shiflBills : []);
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
