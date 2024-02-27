<?php

namespace App\Http\Controllers;

use App\Invoice;
use App\Shipment;
use App\InvoiceAttachment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;

class InvoiceController extends Controller
{
    public function getAllByUser()
    {
        $currentUser = JWTAuth::parseToken()->authenticate();

        $customer = $currentUser
        ->customer()
        ->orderBy('created_at', 'DESC')
        ->get()
        ->toArray();
        $data = $currentUser;
        $data->shipments = Shipment::with(['shipmentSchedules', 'shipmentSuppliers', 'customer'])->where('customer_id', '=', $customer[0]['id'])->get();

        //$customer = $currentUser->customers()->find($id);

        return $data;
    }

    public function getByShipment($id){
        $response = ['success' => false,'results' => []];

        $shipmentInvoices = Invoice::with(['invoiceServices'])->where('shipment_id', '=', $id)->orderBy('created_at','DESC')->get();
        $userRealmId = Auth::user()->quickbookstoken->realm_id;
        $invoiceWithPayments = [];
        if(count($shipmentInvoices) > 0){
            foreach($shipmentInvoices as $invoice){
                if($invoice->qbo_company_realmid === $userRealmId){
                    $inv = $this->getQBOInvoiceWithPayment($invoice->qbo_id);
                }
                $merge = [
                    "invoice" => $invoice,
                    "payment" => isset($inv['results']['payment']) ? $inv['results']['payment'] : [],
                    "realm_id" => strval($invoice->qbo_company_realmid)
                ];
                array_push($invoiceWithPayments, $merge);
            }
            $response['success'] = true;
            $response['results'] = $invoiceWithPayments;
        }
        // if (count($shipmentInvoices)>0) {
        //     $response['success'] = true;
        //     $response['results'] = $shipmentInvoices;
        // }
        return response()->json($response);
    }

    // public function getByShipmentAndByQBO(Request $request){
    //     $response = ['success' => false,'results' => []];

    //     $shipmentInvoices = Invoice::with(['invoiceServices'])
    //         ->where('shipment_id', '=',$request->shipmentId)
    //         ->where('qbo_company_realmid','=',$request->realmId)
    //         ->orderBy('created_at','DESC')->get();
        
    //     $invoiceWithPayments = [];
    //     if(count($shipmentInvoices) > 0){
    //         foreach($shipmentInvoices as $invoice){
    //             $inv = $this->getQBOInvoiceWithPayment($invoice->qbo_id);
    //             $merge = [
    //                 "invoice" => $invoice,
    //                 "payment" => isset($inv['results']['payment']) ? $inv['results']['payment'] : [],
    //             ];
    //             array_push($invoiceWithPayments, $merge);
    //         }
    //         $response['success'] = true;
    //         $response['results'] = $invoiceWithPayments;
    //     }
    //     // if (count($shipmentInvoices)>0) {
    //     //     $response['success'] = true;
    //     //     $response['results'] = $shipmentInvoices;
    //     // }
    //     return response()->json($response);
    // }

    public function getInvoiceById($id){
        $response = ['status' => 'ok','results' => []];

        $invoice = Invoice::with(['invoiceServices'])->where('id', $id)->get();
        if(count($invoice) > 0){
            $response['results'] = $invoice;
        }
        
        return response()->json($response);
    }

    public function getInvoiceAttachements($invoice_id){
        $response = ['success' => false, 'data'=> []];
        $attachments = InvoiceAttachment::where('invoice_id',$invoice_id)->get();

        $invoiceAttachments = [];
        if (count($attachments) > 0) {

            foreach ($attachments as $attachment) {
                array_push($invoiceAttachments, [
                    "attachment_id" => $attachment['id'],
                    "qbo_attachable_id" => $attachment['qbo_attachable_id'],
                    "file_name" => $attachment['file_name'],
                    "mime_type" => $attachment['mime_type'],
                    "file_path" => $attachment['file_path'],
                    "include_on_send" => $attachment['include_on_send'],
                    "is_invoice_origin" => $attachment['is_invoice_origin'],
                ]);
            }
            $response['success'] = true;
        }

        $response['data'] = $invoiceAttachments;

        return response()->json($response, 200);
    }

    public function autoGenerateInvoiceNumber($shipment_id){
        $response = ['success' => false, 'results'=> []];
        $shipment = Shipment::where('id',$shipment_id)->select('id','shifl_ref')->get();

        $invoices = Invoice::where('shipment_id',$shipment_id)
            ->where('invoice_num','like','%'.$shipment[0]->shifl_ref.'-%')
            ->select('id','shipment_id','invoice_num')
            ->orderBy('created_at','desc')
            ->get();
        $numbers = [];
        if(count($invoices) > 0){
            foreach($invoices as $invce){
                if(Str::contains((string)$invce->invoice_num, '-')){
                    $separateSequence  = explode('-',$invce->invoice_num);
                    $last = end($separateSequence);
                    if(is_numeric($last)){
                        array_push($numbers,(int)$last);
                    }
                }  
            }
        }
        if(count($numbers)>0){
            $max_num = max($numbers);
            $generatedSequence = $max_num + 1;
            $generatedInvoiceNumber = (string)$shipment[0]->shifl_ref.'-'.(string)$generatedSequence;
            $response['success'] = true;
            $response['results'] = $generatedInvoiceNumber;
        }else{
            $generatedSequence = 1;
            $generatedInvoiceNumber = (string)$shipment[0]->shifl_ref.'-'.(string)$generatedSequence;
            $response['success'] = true;
            $response['results'] = $generatedInvoiceNumber;
        }
        return response()->json($response);
    }

    private function getQBOInvoiceWithPayment($qboInvoiceId){
        $response = ['success' => false, 'results'=> [], 'error' => false];
        $quickbooks = app('QuickBooks');
        $invoiceObject = $quickbooks->getDataService()->findById("Invoice",$qboInvoiceId);
        $error = $quickbooks->getDataService()->getLastError();
        $paymentDetail = [];

        if($error){
            $response['error'] = true;
            // $response['result'] = $error->getResponseBody();
            $response['results'] = [];
        }else{
            if($invoiceObject->LinkedTxn !== null){
                if(is_object($invoiceObject->LinkedTxn)){
                    $txnType = strtoupper($invoiceObject->LinkedTxn->TxnType);
                    if($txnType === "PAYMENT"){
                        $getPayment = $this->getPaymentById($invoiceObject->LinkedTxn->TxnId);
                        if(!$getPayment['error']){
                            array_push($paymentDetail, $getPayment['results']);
                        }
                    }
                }else{
                    if(count($invoiceObject->LinkedTxn)>0){
                        foreach($invoiceObject->LinkedTxn as $linkTxn){
                            $txnType = strtoupper($linkTxn->TxnType);
                            if($txnType === "PAYMENT"){
                                $getPayment = $this->getPaymentById($linkTxn->TxnId);
                                if(!$getPayment['error']){
                                    array_push($paymentDetail, $getPayment['results']);
                                }
                            }
                        }
                    }
                }
            }
            $response['results'] = [
                'invoice' => $invoiceObject,
                'payment' => $paymentDetail,
            ];
            $response['success'] = true;
        }   
        return $response;
    }

    private function getPaymentById($paymentId){
        $response = ['success' => false, 'results'=> [], 'error' => false];
        $quickbooks = app('QuickBooks');
        
        $paymentObject = $quickbooks->getDataService()->findById("Payment",$paymentId);
        $error = $quickbooks->getDataService()->getLastError();
            
        if($error){
            $response['error'] = true;
            // $response['result'] = $error->getResponseBody();
            $response['results'] = [];
        }else{
            $response['results'] = $paymentObject;
            $response['success'] = true;
        }
        return $response;
    }

}
