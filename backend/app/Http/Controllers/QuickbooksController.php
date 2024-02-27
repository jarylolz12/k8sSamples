<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Resources\Standard as StandardResource;
use Illuminate\Support\Collection;
use App\User;
use QuickBooksOnline\API\DataService\DataService;
use Illuminate\Support\Facades\Auth;
use QuickBooksOnline\API\Core\OAuth\OAuth2\OAuth2LoginHelper;
class QuickbooksController extends Controller
{

    public function getQuickbookInvoiceDetails($id) {

        $response = ['item' => [],'error' => ''];
        $qb = app('QuickBooks');
        $invoice = $qb->getDataService()->FindbyId("Invoice",$id);
        $error = $qb->getDataService()->getLastError();
        $response['item'] = $invoice;

        return response()->json($response);
        
    }

    public function getChangeDataCaptureOperation(Request $request) {

        $response = ['status' => 'not ok', 'errors' => [],'message' => ''];

        $minutes = 10 * 24 * 60;
        $create_date = Carbon::now()->subMinute($minutes)->format('Y-m-d');
        $qb = app('QuickBooks');

        $entity_list = ['bill'];

        $results = $qb->getDataService()->CDC($entity_list, sprintf('%sT12:28:32-08:00',$create_date));
        $error = $qb->getDataService()->getLastError();

        if ( $error ) {

            $response['errors']['status_code'] = $error->getHttpStatusCode();
            $response['errors']['helper_message'] = $error->getOAuthHelperError();
            $response['message'] = $error->getResponseBody();
            
        } else {
            if ( $results->entities ) {
                
                foreach ($results->entities as $entityName => $entityArray) {
                    // echo "CDC Says " . count($entityArray) . " Updated Entities of Type = {$entityName}\n";
                }
                $response['message'] = $results->entities;
            }
        }

        return response()->json($response);

    }

    public function getPushNotifications(Request $request) {

        $signature = $request->header('intuit-signature');

        if ( $signature !== '' && $signature !== null ) {
            $verifier_key = '73775551-e83b-4fa7-924a-0e5a77506964';
            //$converted_signature = base_convert($signature,64, 16);
            $binary = base64_decode($signature);
            $converted_signature = bin2hex($binary);
            $hashed_payload = hash_hmac('sha256',json_encode($request->all()),$verifier_key);

            if ( $hashed_payload === $converted_signature ) {
                
                /*
                \DB::table('temporary_qb_test_data')->insert([
                    'notification' => json_encode($request->all()),
                    'hash_payload' => $hashed_payload,
                    'signature' => $converted_signature 
                ]);*/

                $get_user = User::where('email', 'shabsie@shifl.com')->first();

                if (isset($get_user->id)) {

                    $oauth2LoginHelper = new OAuth2LoginHelper(config('quickbooks.data_service.client_id'),config('quickbooks.data_service.client_secret'));
                    $accessTokenObj = $oauth2LoginHelper->
                                        refreshAccessTokenWithRefreshToken($get_user->quickbookstoken->refresh_token);
                    $accessTokenValue = $accessTokenObj->getAccessToken();
                    $refreshTokenValue = $accessTokenObj->getRefreshToken();

                    \DB::table('quickbooks_tokens')->where('user_id', $get_user->id)
                                                ->update([
                                                    'access_token' => $accessTokenValue,
                                                    'refresh_token' => $refreshTokenValue
                                                ]);
                    
                    $data_service = DataService::Configure([
                    'auth_mode' => 'oauth2',
                    'ClientID' => config('quickbooks.data_service.client_id'),
                    'ClientSecret' => config('quickbooks.data_service.client_secret'),
                    'accessTokenKey' => $accessTokenValue,
                    'refreshTokenKey' => $refreshTokenValue,
                    'QBORealmID' => $get_user->quickbookstoken->realm_id,
                    'baseUrl' => 'Production'
                    ]);

                    $checkPayload = json_decode(json_encode($request->all()));
                    if (isset($checkPayload->eventNotifications)) {
                        if (is_array($checkPayload->eventNotifications)) {
                            if (count($checkPayload->eventNotifications)>0) {
                                $eventNotification = $checkPayload->eventNotifications[0];
                                if (isset($eventNotification->dataChangeEvent) && isset($eventNotification->realmId) && $eventNotification->realmId=='123146157027444') {
                                    if (isset($eventNotification->dataChangeEvent->entities)) {
                                        if (count($eventNotification->dataChangeEvent->entities)>0) {
                                            $finalNotification = $eventNotification->dataChangeEvent->entities[0];
                                                
                                            if ( $finalNotification->name=='Payment') {

                                                if ($finalNotification->name=='Payment' && $finalNotification->operation == 'Create') {
                                                    $checkPayment = $data_service->FindById("Payment", $finalNotification->id);

                                                    if (isset($checkPayment->Line)) {
                                                        if (isset($checkPayment->Line->LinkedTxn)) {
                                                            if (isset($checkPayment->Line->LinkedTxn->TxnId)) {
                                                                if ($checkPayment->Line->LinkedTxn->TxnType=='Invoice') {
                                                                    $checkInvoice = $data_service->FindById("Invoice", $checkPayment->Line->LinkedTxn->TxnId);

                                                                    if (isset($checkInvoice->TotalAmt) && isset($checkInvoice->Balance)) {

                                                                        //update invoice data in the system
                                                                        $updateInvoice = \App\Invoice::where('invoice_num', $checkInvoice->DocNumber)->first();

                                                                        $updateInvoice->total_amount = $checkInvoice->TotalAmt;
                                                                        $updateInvoice->balance = $checkInvoice->Balance;
                                                                        $updateInvoice->save();

                                                                    }

                                                                }

                                                            }
                                                        }

                                                    } 
                                                
                                                }
                                            } elseif( $finalNotification->name=='Bill' && $finalNotification->operation =='Update') {

                                                //s
                                                try {

                                                    //check if part of bill paylists
                                                    $checkBillPayList = \App\BillPaymentList::where('bill_id', $finalNotification->id)->first();

                                                    if ( isset($checkBillPayList->id)) {
                                                        $checkBill = \App\Bill::where('qbo_bill_id', $finalNotification->id)
                                                            ->where(function($q) {
                                                                $q->where('payment_status', 1);
                                                                $q->orWhere('payment_status', 3);
                                                                return $q;
                                                            })
                                                            ->first();
                                                        $currentUserRealmId = $get_user->quickbookstoken->realm_id;

                                                        if (isset($checkBill->id) && $checkBill->qbo_company_realmid === $currentUserRealmId) {

                                                            $qbill_obj = $qb->getDataService()->FindbyId("Bill", $checkBill->qbo_bill_id);
                                                            if ( is_object($qbill_obj) && isset($qbill_obj->Balance)) {
                                                                
                                                                $check_balance = intval($qbill_obj->Balance);
                                                                $total_amount = intval($qbill_obj->TotalAmt);
                                                                if ($check_balance==0) {
                                                                    $checkBill->payment_status = 2;
                                                                } else {
                                                                    if ($check_balance!==$total_amount) {
                                                                        $checkBill->payment_status = 3;
                                                                    } else {
                                                                        /*
                                                                        if ($check_balance==$total_amount) {
                                                                             $checkBill->payment_status = 1;
                                                                        }*/
                                                                    }
                                                                }
                                                                $response['processed'] = 1;
                                                                $checkBill->save();
                                                            }
                                                        }    
                                                    }
                                                    
                                                } catch(Exception $e) {
                                                }
                                                //e

                                            } elseif($finalNotification->name=='Invoice' && $finalNotification->operation=='Update') {
                                                $checkInvoice = $data_service->FindById("Invoice", $finalNotification->id);

                                                if (isset($checkInvoice->TotalAmt) && isset($checkInvoice->Balance)) {

                                                    //update invoice data in the system
                                                    $updateInvoice = \App\Invoice::where('invoice_num', $checkInvoice->DocNumber)->first();

                                                    $updateInvoice->total_amount = $checkInvoice->TotalAmt;
                                                    $updateInvoice->balance = $checkInvoice->Balance;
                                                    $updateInvoice->save();

                                                }
                                            }
                                            
                                        }

                                    }

                                }

                            }

                        }
                    }
                }
                /*
                $final_notification = json_decode(json_encode($request->all()));
                
                if ( isset($final_notification->eventNotifications)) {
                    if ( count($final_notification->eventNotifications) > 0) {
                        $check_notification = $final_notification->eventNotifications[0];
                        if ($check_notification->realmId === '123146157027444') {
                            $get_event = $check_notification->dataChangeEvent->entities;
                            if ($get_event[0]->name === 'Invoice' && $get_event[0]->operation === 'Update') {
                                \DB::table('quickbooks_push_notifications')->insert([
                                    'is_processed' => 0,
                                    'notifications' => json_encode($request->all())
                                ])
                            }
                        }
                    }
                }*/
                /*
                \DB::table('quickbooks_push_notifications')->insert([
                    'is_processed' => 0,
                    'notifications' => json_encode($request->all())
                ]);*/

            }
        }
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
