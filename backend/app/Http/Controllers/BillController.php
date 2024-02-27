<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Bill;
use App\BillAttachment;
use Illuminate\Support\Facades\Validator;
use QuickBooksOnline\API\Data\IPPReferenceType;
use QuickBooksOnline\API\Data\IPPAttachableRef;
use QuickBooksOnline\API\Data\IPPAttachable;
use Illuminate\Support\Facades\Storage;
use App\Events\InsertBillAttachmentEvent;
use Carbon\Carbon;

class BillController extends Controller
{

    public function handleDeleteBillAttachment(Request $request) {

        $error = '';
        if ( $request->has('shipment_id') && $request->has('bill_id') && $request->has('qbo_attachable_id') ) {
            
            try {
                $attachments = BillAttachment::where('shipment_id', $request->input('shipment_id'))
                              ->where('bill_id', $request->input('bill_id'))
                              ->where('qbo_attachable_id', $request->input('qbo_attachable_id'))
                              ->get();
                $quickbooks = app('QuickBooks');
                if (count($attachments) > 0) {
                    foreach ($attachments as $attachment) {

                        if (Storage::exists('public/'.$attachment->path)) {
                            Storage::delete('public/'.$attachment->path);
                            $attachableObject  = $quickbooks->getDataService()->Query("select Id from attachable where Id='".$attachment['qbo_attachable_id']."'");
                            $quickbooks->getDataService()->Delete($attachableObject[0]);
                        }

                        $attachment->delete();
                    }
                }
            } catch (Exception $e) {
                $error = $e;
            }
            
        }
        return response()->json([
            'status' => 'ok',
            'error' => $error
        ]);
    }

    public function getBillAttachments(Request $request) {
        
        $response = ['status' => 'not ok'];
        $attachments = BillAttachment::where('shipment_id', $request->input('shipment_id'))
                                     ->where('bill_id', $request->input('bill_id'))
                                     ->get();

        return response()->json([
            'items' => $attachments
        ]);
    }
    
    public function handleBillAttachment(Request $request) {
        $response = ['status' => 'ok'];

        $validator = Validator::make($request->all(), [
            'shipment_id' => ['required'],
            'qbo_bill_id' => ['required'],
            'bill_id' => ['required'],
            'bill_attachment_file' => 'required|array',
            'bill_attachment_file.*.file' => ['file', 'mimes:pdf,jpg,png,docx,jfif,xlsx,xlsm,xlsb,xltx,xltm,xls,xlt']
        ]);


        if ($validator->fails()) {
            return response()->json(
            [
                'status' => 'not ok',
                'errors' => $validator->errors()
            ]
            ,400);
        } else {
            $args = $request->all();
            $bill_attachments = $args['bill_attachment_file'];

            if ( count ($bill_attachments) > 0) {
                foreach($bill_attachments as $bill_attachment ) {
                    $entity = [
                        'resource' => $bill_attachment,
                        'args' => $args
                    ];
                    event(new InsertBillAttachmentEvent($entity));
                }
            }
        }
        
        return response()->json($response);
    }

    public function getByShipment($shipmentId){

        $response = ['success' => false,'results' => []];

        $shipmentBills = Bill::with(['bill_items'])->where('shipment_id', '=', $shipmentId)->orderBy('created_at','DESC')->get();
        $bills = [];
        if (count($shipmentBills)>0) { 
            foreach($shipmentBills as $bill){
                $merge = [
                    "bill" => $bill,
                    "realm_id" => strval($bill->qbo_company_realmid)
                ];
                array_push($bills,$merge);
            }
            $response['results'] = $bills;
            $response['success'] = true;
        }

        return response()->json($response);
    }

    public function getBillById($id){

        $response = ['status' => 'ok','results' => [],'exchange_rate' => null, 'currency_ref' => null,'exchange_rate_info' => null];
        $qb = app('QuickBooks');
        $bill = Bill::with(['bill_items'])->where('id', $id)->get();
        if(count($bill) > 0) {
            try {
                $check_bill = $qb->getDataService()->FindById("Bill", $bill[0]->qbo_bill_id);
                if ( isset($check_bill->ExchangeRate) ) {
                    $response['exchange_rate'] = $check_bill->ExchangeRate; 
                }


                if ( isset($check_bill->CurrencyRef) ) {
                    $response['currency_ref'] = $check_bill->CurrencyRef;  
                    $sourceCurrencyCode = $check_bill->CurrencyRef; 
                }

                if ( $sourceCurrencyCode!==null ) {
                    $check_exchange_rate = $qb->getDataService()->Query("select * from exchangerate where sourcecurrencycode='".$sourceCurrencyCode."' and asofdate='".Carbon::now()->format('Y-m-d')."'");

                    $response['exchange_rate_info'] = $check_exchange_rate; 

                }


            }catch(Exception $e) {

            }
            
            $response['results'] = $bill;
        }
        
        return response()->json($response);
    }

}
