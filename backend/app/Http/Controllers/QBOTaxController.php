<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class QBOTaxController extends Controller
{
    // public function getTaxRates(){
    //     $quickbooks = app('QuickBooks');
    //     $response = ['success' => false, 'result'=> []];
        
    //     $taxRate = $quickbooks->getDataService()->Query("select * from Taxrate");
    //     $error = $quickbooks->getDataService()->getLastError();
            
    //     if($error){
    //         return $error->getResponseBody();
    //     }else{
    //         $response['result'] = $taxRate;
    //     }
    //     return response()->json($response);
    // }

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

    // public function getTaxCodes(){
    //     $quickbooks = app('QuickBooks');
    //     $response = ['success' => false, 'result'=> []];
        
    //     $taxRate = $quickbooks->getDataService()->Query("select * from Taxcode");
    //     $error = $quickbooks->getDataService()->getLastError();
            
    //     if($error){
    //         return $error->getResponseBody();
    //     }else{
    //         $response['result'] = $taxRate;
    //         $response['success'] = true;
    //     }
    //     return response()->json($response);
    // }

    // public function getTaxRateById(Request $request){
    //     $taxRateId = $request->query('taxRateRef');
    //     $quickbooks = app('QuickBooks');
    //     $response = ['success' => false, 'result'=> []];
        
    //     $taxRate = $quickbooks->getDataService()->Query("select Id, Name, RateValue from TaxRate where Id ='".$taxRateId."'");
    //     $error = $quickbooks->getDataService()->getLastError();
            
    //     if($error){
    //         return $error->getResponseBody();
    //     }else{
    //         $response['result'] = $taxRate;
    //         $response['success'] = true;
    //     }
    //     return response()->json($response);
    // }
}
