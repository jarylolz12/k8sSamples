<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Bill as ShiflBill;
use App\Invoice as ShiflInvoice;


class ShipmentQBOExistenceCheckController extends Controller
{
    public function checkBillForQBOExistenceById(Request $request){
        $response = ['success' => true, 'isExist'=>true, 'error'=>[]];
        $quickbooks = app('QuickBooks');

        $entity= $request->entity;
        $id= $request->id;

        if(isset($request->entity) && isset($request->id)){
            $getBillObject  = $quickbooks->getDataService()->FindbyId($entity,$id);
            $error = $quickbooks->getDataService()->getLastError();
            if($error){
                $response['error'] = $error->getResponseBody();
                $response['success'] = false;
                $response['isExist'] = false;
            }else{
                if($getBillObject->Id > 0){
                    $response['success'] = true;
                    $response['isExist'] = true;
                }
            }
            return response()->json($response);
        }else{
            $response['success'] = false;
            $response['error'] = 'Please provide correct values for $entity and $id';
            return response()->json($response);
        }
    }
}
