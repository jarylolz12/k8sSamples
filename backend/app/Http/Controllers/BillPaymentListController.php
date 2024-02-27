<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Bill;
use App\BillPaymentList;
use Illuminate\Support\Facades\Validator;
use App\Http\Resources\Standard as StandardResource;
use App\Rules\ZeroOrOneOrTwo;
use App\Shipment;
use Illuminate\Support\Collection;
use QuickBooksOnline\API\Facades\BillPayment;
use QuickBooksOnline\API\Facades\PurchaseOrder;
use App\Vendor;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Auth;
use App\Events\InsertBrexEvent;

class BillPaymentListController extends Controller
{


    public function deleteVendors() {
        Vendor::truncate();
    }

    private function generateCode() {
        $letters = 'abcdefghjiklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $code = '';

        for( $x=0;$x<6;$x++ ) {
            $code .=$letters[rand(0, strlen($letters) - 1)];
        }
        return $code;
    }

    public function createBrexVendor(Request $request) {

        $rules = [
            'company_name' => ['required'],
            'type' => ['required'],
            'account_number' => ['required'],
            'routing_number' => ['required'],
            'line1' => ['required'],
            'city' => ['required'],
            'line2' => ['required'],
            'state_value' => ['required'],
            'postal_code' => ['required'],
            'phone_number' => ['required']
        ];


        if ($request->has('email') && $request->input('email')!=='' && $request->input('email')!==null) {
            $rules['email'] = ['email'];
        }

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) return $this->sendError('Validation Error.', $validator->errors());

        
        $brex_token = 'bxt_81zGTa64Rf0Ypt4Wc6ahB2pC83pGbqP6Uam3';
        $brex_api_url = 'https://platform.brexapis.com/v1/vendors';

        $payment_accounts = [[
            'details' => [
                'type' => $request->input('type'),
                'routing_number' => $request->input('routing_number'),
                'account_number' => $request->input('account_number'),
                'address' => [
                    'line1' => $request->input('line1'),
                    'line2' => $request->input('line2'),
                    'city' => $request->input('city'),
                    'state' => strtolower($request->input('state_value')),
                    'country' => 'us',
                    'postal_code' => $request->input('postal_code'),
                    'phone_number' => $request->input('phone_number')
                ]
            ]
        ]];

        $brex_data = [
            'company_name' => $request->input('company_name'),
            'payment_accounts' => $payment_accounts,
            'email' => null,
            'phone' => null
        ];

        if ($request->has('email') && $request->input('email')!=='' && $request->input('email')!==null) {
            $brex_data['email'] = $request->input('email');
        }

        if ($request->has('phone') && $request->input('phone')!=='' && $request->input('phone')!==null) {
            $brex_data['phone'] = $request->input('phone');
        }        

        $ikey = $this->generateCode();

        $response = Http::withHeaders([
                        "Authorization" => sprintf('Bearer %s', $brex_token),
                        "Content-type" => "application/json",
                        "Idempotency-Key" => $ikey
                    ])
                    ->post($brex_api_url, $brex_data);

        $get_data = $response->json();

        return response()->json([
            'status' => 'ok',
            'result' => $get_data
        ]);
    }

    public function getBrexVendor(Request $request, $brex_id) {
        $response = ['item' => null,'error' => ''];

        $brex_token = 'bxt_81zGTa64Rf0Ypt4Wc6ahB2pC83pGbqP6Uam3';
        $brex_api_url = sprintf('https://platform.brexapis.com/v1/vendors/%s',$brex_id);

        try {
            $brex_vendor = Http::withHeaders([
                        "Authorization" => sprintf('Bearer %s', $brex_token),
                        "Content-type" => "application/json",
                    ])
                    ->get($brex_api_url);

            $brex_vendor = $brex_vendor->json();
            $response['item'] = $brex_vendor;

        }catch(Exception $e) {
            $response['error'] = $e;
        }

        return response()->json($response);

    }

    public function getBrexVendors() {

        $response = ['results' => []];
        $brex_api_url = 'https://platform.brexapis.com/v1/vendors';
        $brex_token = 'bxt_xVuN4E6B9iMSikuGT8MW3QflOThIDiZtbltx';
        $brex_vendors = Http::withHeaders([
                        "Authorization" => sprintf('Bearer %s', $brex_token),
                        "Content-type" => "application/json",
                    ])
                    ->get($brex_api_url);

        $brex_vendors = $brex_vendors->json();

        if ( isset($brex_vendors['items']) && count($brex_vendors['items'])>0 ) {

            foreach( $brex_vendors['items'] as $key => $item) {
                $brex_vendors['items'][$key]['label'] = $item['company_name'];
                $brex_vendors['items'][$key]['value'] = $item['id'];
            }
            array_push($brex_vendors['items'], ['label' => 'Select Brex Vendor', 'value' => null]);

            $response['results'] = $brex_vendors['items'];

        }

        return response()->json($response);
    }



    public function getBillQbInfo(Request $request, $bill_id) {

        $response = [
            'status' => 'not ok',
            'result' => null,
            'error' => '',
        ];

        $qb = app('QuickBooks');
        try {

            //check if the bill belongs to current logged in user
            $checkBill = Bill::where('qbo_bill_id', $bill_id)->first();
            $currentUserRealmId = Auth::user()->quickbookstoken->realm_id;
            if (isset($checkBill->id) && $checkBill->qbo_company_realmid === $currentUserRealmId) {

                $qbill_obj = $qb->getDataService()->FindbyId("Bill", $bill_id);

                if ( is_object($qbill_obj) && isset($qbill_obj->LinkedTxn)) {

                    //check balance against the bill's total amount
                    $total_balance = (floatval($qbill_obj->Balance==0)) ? 0 : floatval(intval($qbill_obj->TotalAmt) - floatval($qbill_obj->Balance));
                    
                    //flag transaction
                    $hasTxn = false;

                    if ( isset($qbill_obj->LinkedTxn->TxnId)) {
                        $hasTxn = true;
                        $txn_id = $qbill_obj->LinkedTxn->TxnId;
                    } else {
                        $hasTxn = true;
                        if ( is_countable($qbill_obj->LinkedTxn) ) {
                            $txn_id = $qbill_obj->LinkedTxn[0]->TxnId;
                        }
                    }

                    //if there is transaction
                    if ( $hasTxn ) {
                        $qbill_payment_obj = $qb->getDataService()->FindbyId("BillPayment", $txn_id);
                        if ( $total_balance==0 ) {
                            $checkBill->payment_status = 2;
                        } elseif ($total_balance > 0) {
                            $checkBill->payment_status = 3;
                        }
                        $checkBill->save();    
                    }

                    $checkBillPaymentList = BillPaymentList::where('bill_id', $checkBill->id)->first();

                    if (isset($checkBillPaymentList->id)) {
                        $response['has_paylist'] = true;
                    }
                    /*
                    $checkBillPaymentList = BillPaymentList::where('bill_id', $checkBill->id)->first();

                    if (!isset($checkBillPaymentList->id)) {
                        BillPaymentList::create([
                            'amount' => $checkBill->total_amount,
                            'bill_id' => $checkBill->id
                        ]);
                    } else {
                        $checkBillPaymentList->amount = $checkBill->total_amount;
                        $checkBillPaymentList->save();
                    } */

                    $response['balance'] = $qbill_obj->Balance;
                    $response['payment_status'] = $checkBill->payment_status;
                    $response['bill_payment'] = $qbill_payment_obj;
                }
            }
            
        } catch(Exception $e) {
            $response['error'] = $e;
        }
        

        return response()->json($response);
    }

    public function getAllBills(Request $request) {


        $total_pages = 0;
        $response = [
            'results' => []
        ];

        $bills = Bill::where('payment_status', 1)
                     ->orWhere('payment_status', 2)
                     ->paginate(50);

        $response['results'] = $bills;

        return response()->json($response);
    }
    public function processCorrectBill(Request $request, $bill_id) {

        $response = ['status' => 'not ok'];
        $checkBill = Bill::find($bill_id);

         $currentUserRealmId = Auth::user()->quickbookstoken->realm_id;

        if (isset($checkBill->id) && $checkBill->qbo_company_realmid=== $currentUserRealmId) {
            $qbo_bill_id = $checkBill->qbo_bill_id;
            $qb = app('QuickBooks');

            //s
            $qbill_obj = $qb->getDataService()->FindbyId("Bill", $qbo_bill_id);

            if ( is_object($qbill_obj) && isset($qbill_obj->LinkedTxn) && isset($qbill_obj->LinkedTxn->TxnId)) {

                //check balance againtst the bill's total amount
                $$total_balance = (intval($qbill_obj->Balance==0)) ? 0 : intval(intval($qbill_obj->TotalAmt) - intval($qbill_obj->Balance));
                
                $txn_id = $qbill_obj->LinkedTxn->TxnId;
                $qbill_payment_obj = $qb->getDataService()->FindbyId("BillPayment", $txn_id);

                if (isset($checkBill->id)) {

                    if ($total_balance==0) {
                        $checkBill->payment_status = 2;
                    } elseif ($total_balance > 0) {
                        $checkBill->payment_status = 3;
                    }

                    $response['payment_status'] = $checkBill->payment_status;
                    $response['total_balance'] = $total_balance;
                    $checkBill->save();
                    
                    $checkBillPaymentList = BillPaymentList::where('bill_id', $checkBill->id)->first();

                    if (!isset($checkBillPaymentList->id)) {
                        BillPaymentList::create([
                            'amount' => $checkBill->total_amount,
                            'bill_id' => $checkBill->id
                        ]);
                    } else {
                        $checkBillPaymentList->amount = $checkBill->total_amount;
                        $checkBillPaymentList->save();
                    }
                }
                
                $response['status'] = 'ok';

            }
            //e

        }

        return response()->json($response);

    }

    public function correctBills(Request $request) {

        //$qb = app('QuickBooks');
        //$bill_obj = $qb->getDataService()->Query("SELECT * FROM Bill");

        return view('custom/correct');

    }


    public function getBillLines(Request $request) {

        $response = [
            'status' => 'not ok'
        ];

        $validator = Validator::make($request->all(), [
            'bill_id' => ['required'],
        ]);

        if ($validator->fails()) return $this->sendError('Validation Error.', $validator->errors());

        $bill_id = $request->input('bill_id');        

        $qb = app('QuickBooks');
        $bill_obj = $qb->getDataService()->FindbyId("Bill", $bill_id);
        $response['result'] = $bill_obj;
        return response()->json($response);
    }

    private function indexOf($arr, $str) {

        $returnKey = -1;

        foreach($arr as $key => $a) {
            if ( $str === $arr)  {
                $returnKey = $key;
            }

        }

        return $returnKey;

    }
    public function syncExternalVendorsBrex(Request $request) {

        $qb = app('QuickBooks');

        $emails = [];
        $vendorData = [];

        $brex_api_url = 'https://platform.brexapis.com/v1/vendors';
        $brex_token = 'bxt_xVuN4E6B9iMSikuGT8MW3QflOThIDiZtbltx';
        
        $brex_vendors = Http::withHeaders([
                        "Authorization" => sprintf('Bearer %s', $brex_token),
                        "Content-type" => "application/json",
                    ])
                    ->get($brex_api_url);

        $brex_vendors = $brex_vendors->json();

        //fetch brex vendors
        if ( isset($brex_vendors['items']) && count($brex_vendors['items'])>0 ) {
            foreach( $brex_vendors['items'] as $brex_vendor ) {

                if (isset($brex_vendor['email'])) {
                    array_push($emails, $brex_vendor['email']);
                    array_push($vendorData, [
                        'email' => $brex_vendor['email'],
                        'company_name' => (isset($brex_vendor['company_name'])) ? $brex_vendor['company_name'] : '',
                        'phone' => (isset($brex_vendor['phone'])) ? $brex_vendor['phone'] : '',
                        'payment_accounts' => (isset($brex_vendor['payment_accounts'])) ? json_encode($brex_vendor['payment_accounts']) : '[]'
                    ]);
                }
                
            }
        }

        //check all quickbooks data
        $vendor_obj = $qb->getDataService()->Query("select * from Vendor");
        
        if (count($vendor_obj) > 0) {
            foreach ($vendor_obj as $key => $v) {

                if (isset($v->PrimaryEmailAddr) && isset($v->PrimaryEmailAddr->Address)) {

                    if (in_array($v->PrimaryEmailAddr->Address, $emails)) {

                        $vendorDataIndex = $this->indexOf($emails, $v->PrimaryEmailAddr->Address);
                        $currentUserRealmId = Auth::user()->quickbookstoken->realm_id;

                        foreach($vendorData as $keySecond => $vd) {
                            if ($vendorDataIndex===$keySecond) {
                                $vendorData[$keySecond]['realm_id'] = $currentUserRealmId;
                                $vendorData[$keySecond]['country'] = (isset($v->BillAddr) && isset($v->BillAddr->Country)) ? $v->BillAddr->Country : null;
                                $vendorData[$keySecond]['city'] = (isset($v->BillAddr) && isset($v->BillAddr->City)) ? $v->BillAddr->City : null;

                                $vendorData[$keySecond]['line1'] = (isset($v->BillAddr) && isset($v->BillAddr->Line1)) ? $v->BillAddr->Line1 : null;                        
                                $vendorData[$keySecond]['line2'] = (isset($v->BillAddr) && isset($v->BillAddr->Line2)) ? $v->BillAddr->Line2 : null;

                                $vendorData[$keySecond]['line3'] = (isset($v->BillAddr) && isset($v->BillAddr->Line3)) ? $v->BillAddr->Line3 : null;

                                $vendorData[$keySecond]['postal_code'] = (isset($v->BillAddr) && isset($v->BillAddr->PostalCode)) ? $v->BillAddr->PostalCode : null;

                                $vendorData[$keySecond]['line3'] = (isset($v->BillAddr) && isset($v->BillAddr->Line3)) ? $v->BillAddr->Line3 : null;

                                $vendorData[$keySecond]['country_subdivision_code'] = (isset($v->BillAddr) && isset($v->BillAddr->CountrySubdivisionCode)) ? $v->BillAddr->CountrySubdivisionCode : null;    
                            }
                            
                        }


                        
                    }
                    
                }
            }            

        }

        if (count($vendorData) > 0) {

            $checkMainVendors = Vendor::all();

            if ( count($checkMainVendors)>0 ) {
                foreach( $checkMainVendors as $checkMainVendor ) {
                    foreach($vendorData as $k => $v) {
                        if ( $v['email'] == $checkMainVendor->email ) {
                            \DB::table('vendors')
                               ->where('id', $checkMainVendor->id)
                               ->update($v);

                            unset($vendorData[$k]);
                        }
                    }                    
                }
            }

            \DB::table('vendors')->insert($vendorData);
        }

    }

    public function syncExternalVendors(Request $request) {

        $qb = app('QuickBooks');

        $emails = [];
        $displayNames = [];
        $vendorData = [];

        $vendor_obj = $qb->getDataService()->Query("select * from Vendor MAXRESULTS 800");

        $brex_vendor_items = [];
        $qb_emails = [];
        $qb_company_names = [];
        if (count($vendor_obj) > 0) {
            foreach ($vendor_obj as $key => $v) {
                /*
                if (1==1) {

                    if (isset($v->PrimaryEmailAddr) && isset($v->PrimaryEmailAddr->Address)) {
                        array_push($emails, $v->PrimaryEmailAddr->Address);    
                    } else {
                        array_push($emails, '');
                    }

                    if (isset($v->DisplayName)) {
                        array_push($displayNames, $v->DisplayName);
                    } else {
                        array_push($displayNames, '');
                    }


                    //s
                    

                }*/
                $vendorSingleData = [
                    'company_name' => $v->DisplayName
                ];

                array_push($qb_company_names, $v->CompanyName);

                $currentUserRealmId = Auth::user()->quickbookstoken->realm_id;
                $vendorSingleData['realm_id'] = $currentUserRealmId;
                $vendorSingleData['company_name'] = $v->DisplayName;
                $vendorSingleData['qb_id'] = intval($v->Id);
                $vendorSingleData['country'] = (isset($v->BillAddr) && isset($v->BillAddr->Country)) ? $v->BillAddr->Country : null;
                $vendorSingleData['city'] = (isset($v->BillAddr) && isset($v->BillAddr->City)) ? $v->BillAddr->City : null;

                $vendorSingleData['line1'] = (isset($v->BillAddr) && isset($v->BillAddr->Line1)) ? $v->BillAddr->Line1 : null;

                $vendorSingleData['line2'] = (isset($v->BillAddr) && isset($v->BillAddr->Line2)) ? $v->BillAddr->Line2 : null;

                $vendorSingleData['line3'] = (isset($v->BillAddr) && isset($v->BillAddr->Line3)) ? $v->BillAddr->Line3 : null;

                $vendorSingleData['postal_code'] = (isset($v->BillAddr) && isset($v->BillAddr->PostalCode)) ? $v->BillAddr->PostalCode : null;

                $vendorSingleData['country_subdivision_code'] = (isset($v->BillAddr) && isset($v->BillAddr->CountrySubdivisionCode)) ? $v->BillAddr->CountrySubdivisionCode : null;

                $vendorSingleData['email'] = (isset($v->PrimaryEmailAddr) && isset($v->PrimaryEmailAddr->Address)) ? $v->PrimaryEmailAddr->Address : null;

                $vendorSingleData['phone'] = (isset($v->Mobile) && isset($v->Mobile->FreeFormNumber)) ? $v->Mobile->FreeFormNumber : null;

                $vendorSingleData['payment_accounts'] = '[]';


                array_push($vendorData, $vendorSingleData);
            }

            /*
            $brex_api_url = 'https://platform.brexapis.com/v1/vendors';
            $brex_token = 'bxt_xVuN4E6B9iMSikuGT8MW3QflOThIDiZtbltx';
            
            $brex_vendors = Http::withHeaders([
                            "Authorization" => sprintf('Bearer %s', $brex_token),
                            "Content-type" => "application/json",
                        ])
                        ->get($brex_api_url);

            $brex_vendors = $brex_vendors->json();
            $brex_vendor_emails = [];

            //fetch brex vendors
            if ( isset($brex_vendors['items']) && count($brex_vendors['items'])>0 ) {
                
                foreach( $brex_vendors['items'] as $brex_vendor ) {
                    array_push( $brex_vendor_emails, $brex_vendor['email'] );
                    if ( isset( $brex_vendor['email']) ) {
                        if ( in_array( $brex_vendor['email'], $emails ) ) {

                            $vendorDataIndex = $this->indexOf($emails, $brex_vendor['email']);
                            if ( count($vendorData)>0 ) {
                                foreach($vendorData as $keySecond => $vd) {

                                    if ($vendorDataIndex===$keySecond) {
                                        $vendorData[$keySecond]['payment_accounts'] = json_encode($brex_vendor['payment_accounts']);
                                    }
                                }
                            }
                        }
                    } 
                }
            }

            $brex_vendor_items = [];
            foreach( $qb_emails as $key => $qb_email) {
                if ( !in_array($qb_email, $brex_vendor_emails) ) {
                    array_push($brex_vendor_items, [
                        'company_name' => $qb_company_names[$key],
                        'email' => $qb_email
                    ]);
                }
            }

            if (count($brex_vendor_items)>0) {

                foreach($brex_vendor_items as $bvi) {
                    event(new InsertBrexEvent($bvi));
                }
            }*/

        }
        
        $updatedData = [];
        /*
        if (count($vendorData) > 0) {
            $checkMainVendors = Vendor::all();

            if ( count($checkMainVendors)>0 ) {
                foreach( $checkMainVendors as $checkMainVendor ) {
                    foreach($vendorData as $k => $v) {
                        if ( $v['company_name'] == $checkMainVendor->company_name ) {
                            \DB::table('vendors')
                               ->where('id', $checkMainVendor->id)
                               ->update($v);
                            array_push($updatedData, $v);
                            unset($vendorData[$k]);
                        }
                    }                    
                }
            }

            \DB::table('vendors')->insert($vendorData);
        }*/
        \DB::table('vendors')->insert($vendorData);

        return response()->json($vendorData);

    }

    private function markPaid($id) {
        
        $checkBill = Bill::find($id);
        $error = null;

        $currentUserRealmId = Auth::user()->quickbookstoken->realm_id;

        if (isset($checkBill->id) && $checkBill->qbo_company_realmid === $currentUserRealmId) {
            
            //update to mark as paid status
            $checkBill->payment_status = 2;
            $checkBill->save();

            //insert also the bill payment list table if not inserted yet
            $checkBillPaymentList = BillPaymentList::where('bill_id', $id)->first();
            $billPaymentData = null;

            $qb = app('QuickBooks');

            //get the bill object
            $qbill_obj = $qb->getDataService()->FindbyId("Bill", $checkBill->qbo_bill_id);

            $bill_payment_lines = [];

            if ( isset($qbill_obj->Line) ) {

                $set_bill_lines = $qbill_obj->Line;
                if (is_array($set_bill_lines)) {
                    foreach ($set_bill_lines as $key => $value) {
                        array_push($bill_payment_lines, [
                            'DetailType' => 'AccountBasedExpenseLineDetail',
                            'Amount' => number_format((float)$value->Amount, 2, '.', ''),
                            'LinkedTxn' => [
                                [ 
                                  'TxnId' => $qbill_obj->Id,
                                  'TxnType' => 'Bill'
                                ]
                            ],
                        ]);
                    }

                } else {
                    array_push($bill_payment_lines, [
                        'DetailType' => 'AccountBasedExpenseLineDetail',
                        'Amount' => number_format((float)$set_bill_lines->Amount, 2, '.', ''),
                        'LinkedTxn' => [
                            [ 
                              'TxnId' => $qbill_obj->Id,
                              'TxnType' => 'Bill'
                            ]
                        ]
                    ]);
                }
            }

            if (count($bill_payment_lines) > 0) {

                //brex checking bank account id = 168
                //live quickbooks
                $brex_account_id = 168;
                //$capital_account_id = 173;

                //sandbox quickbooks
                //$brex_account_id = 35;

                $billPaymentData = [
                    'DocNumber' => (isset($qbill_obj->DocNumber)) ? $qbill_obj->DocNumber : '',
                    'VendorRef' => [
                        'value' => (isset($checkBill->qbo_vendor_id)) ? strval($checkBill->qbo_vendor_id) : ''
                    ],
                    'TotalAmt' => number_format((float)$checkBill->total_amount, 2, '.', ''),
                    'PayType' => 'Check',
                    'Line' => $bill_payment_lines,
                    'CheckPayment' => [
                        'BankAccountRef' => [
                            'value' => strval($brex_account_id)
                        ]
                    ]
                ];

                $billPaymentObj = BillPayment::create($billPaymentData);

                //create bill payment
                //mark as paid
                $qb->getDataService()->Add($billPaymentObj);
                $error = $qb->getDataService()->getLastError();
                
            }
            
            if (!isset($checkBillPaymentList->id)) {
                BillPaymentList::create([
                    'amount' => $checkBill->total_amount,
                    'bill_id' => $id
                ]);
            } else {
                $checkBillPaymentList->amount = $checkBill->total_amount;
                $checkBillPaymentList->save();
            }

        }
    }

    public function pay(Request $request) {

        $qb = app('QuickBooks');
        
        $response = [
            'status' => 'not ok',
            'result' => null
        ];
        
        $validator = Validator::make($request->all(), [
            'bill_id' => ['required'],
        ]);

        if ($validator->fails()) return $this->sendError('Validation Error.', $validator->errors());

        $bill_id = intval($request->input('bill_id'));

        $checkBill = Bill::find($bill_id);
        $currentUserRealmId = Auth::user()->quickbookstoken->realm_id;

        
        if (isset($checkBill->qbo_vendor_id) && $checkBill->qbo_company_realmid === $currentUserRealmId) {

            $qbill_obj = $qb->getDataService()->FindbyId("Bill", $checkBill->qbo_bill_id);

            $amount_to_pay = 0;

            if (isset($qbill_obj->LinkedTxn) && isset($qbill_obj->LinkedTxn->TxnId)) {
                $amount_to_pay = intval($qbill_obj->Balance) * 100;
            }
            //check vendor
            $checkVendor = Vendor::where('qb_id', $checkBill->qbo_vendor_id)->first();

            if (isset($checkVendor->brex_id) && $checkVendor->brex_id!==null) {

                $set_brex_id = 0;
                try {
                    $set_brex_id = json_decode($checkVendor->brex_id);
                    if (isset($set_brex_id->value)) {
                        $set_brex_id = $set_brex_id->value;
                    }
                }catch(Exception $e) {
                }

                //check to the brex the email if that is already existing
                //$brex_token = 'bxt_Bbsgq2gyBQiz3PJIzX4wBiJcsOw7Bxp2RZ2d';
                $brex_token = 'bxt_81zGTa64Rf0Ypt4Wc6ahB2pC83pGbqP6Uam3';
                $brex_api_url = sprintf('https://platform.brexapis.com/v1/vendors/%s',$set_brex_id);

                $letters = 'abcdefghjiklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
                $code = '';

                for( $x=0;$x<6;$x++ ) {
                    $code .=$letters[rand(0, strlen($letters) - 1)];
                }

                try {

                    $r = Http::withHeaders([
                        "Authorization" => sprintf('Bearer %s', $brex_token),
                        "Content-type" => "application/json",
                        "Idempotency-Key" => $code
                    ])
                    ->get($brex_api_url);

                    if (isset($r)) {
                        $r = $r->json();

                        if (!isset($r['id'])) {
                            $response['message'] = 'Vendor not found.';
                        } else {
                            
                            try {

                                if (isset($r['payment_accounts']) && count($r['payment_accounts']) > 0) {
                                    $convert_to_cents = $amount_to_pay;
                                    //$convert_to_cents = intval($checkBill->total_amount * 100);

                                    $brex_api_url = 'https://platform.brexapis.com/v1/transfers';
                                    $brex_data = [
                                        'counterparty' => [
                                            'type' => 'VENDOR',
                                            'payment_instrument_id' => $r['payment_accounts'][0]['details']['payment_instrument_id']
                                        ],
                                        'amount' => [
                                            'amount' => $convert_to_cents,
                                            'currency' => 'USD'
                                        ],
                                        'external_memo' => sprintf('Payment to Bill #%s', $checkBill->qbo_bill_num),
                                        'description' => sprintf('Payment to Bill #%s', $checkBill->qbo_bill_num),
                                        'originating_account' => [
                                            'type' => 'BREX_CASH',
                                            'id' => 'dpacc_ckvofb3ii0ac101lyccg596zh' //brex real cash account //shabsie's cash account
                                        ]
                                    ];

                                    $letters = 'abcdefghjiklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
                                    $code = '';

                                    for( $x=0;$x<6;$x++ ) {
                                        $code .=$letters[rand(0, strlen($letters) - 1)];
                                    }

                                    $rr = Http::withHeaders([
                                        "Authorization" => sprintf('Bearer %s', $brex_token),
                                        "Content-type" => "application/json",
                                        "Idempotency-Key" => $code
                                    ])->post($brex_api_url, $brex_data);

                                    //mark as paid
                                    $this->markPaid($bill_id);
                                    $response['payment_result'] = $rr;
                                    $response['status'] = 'ok';
                                } else {
                                    $response['error'] = 'An error occured. Please make sure that the vendor you are paying has payment account details.';
                                }

                            } catch(Exception $e) {
                                $response['error'] = $e;
                            }
                            
                        }
                    }



                } catch(Exception $e) {
                    $response['message'] = $e;

                }
            } else {
                $response['no_brex'] = true;
            }

        }

        $response['id'] = $bill_id;
        return response()->json($response);

    }

    public function findQbBillById(Request $request, $bill_id) {

        $response = [
            'status' => 'not ok',
            'result' => null,
            'payment_status' => 1
        ];

        //bill id = bill payment txnid 


        $qb = app('QuickBooks');
        
        $qbill_obj = null;
        $qbill_payment_obj = null;

        $qbill_obj = $qb->getDataService()->FindbyId("Bill", $bill_id);

        //check if it is already linked in the vendor system with brex
        $checkVendorSystem = Vendor::where('qb_id', $qbill_obj->VendorRef)->first();
        $is_integrated_brex = false;
        if ( isset($checkVendorSystem->id) && isset($checkVendorSystem->brex_id) && $checkVendorSystem->brex_id!==null ) {
            $is_integrated_brex = true;
        }

        $response['is_integrated_brex'] = $is_integrated_brex;
        $response['vendor_link'] = (isset($checkVendorSystem->id)) ? $checkVendorSystem->id : '';

        if (isset($qbill_obj->LinkedTxn) && isset($qbill_obj->LinkedTxn->TxnId)) {

            $total_balance = intval($qbill_obj->Balance);
            
            $txn_id = $qbill_obj->LinkedTxn->TxnId;
            $qbill_payment_obj = $qb->getDataService()->FindbyId("BillPayment", $txn_id);
            $checkBill = Bill::where('qbo_bill_id',$bill_id)->first();

            $currentUserRealmId = Auth::user()->quickbookstoken->realm_id;

            $checkCompany = \App\QuickbooksCompany::where('company_realm_id', $currentUserRealmId)->first();

            $response['company'] = (isset($checkCompany->company_name)) ? $checkCompany->company_name : '';

            if (isset($checkBill->id) && $checkBill->qbo_company_realmid=== $currentUserRealmId) {

                if ($total_balance==0) {
                    $checkBill->payment_status = 2;
                } elseif ($total_balance > 0) {
                    $checkBill->payment_status = 3;
                }

                $response['payment_status'] = $checkBill->payment_status;
                $response['total_balance'] = $qbill_obj->Balance;

                $checkBill->save();
                

                $checkBillPaymentList = BillPaymentList::where('bill_id', $checkBill->id)->first();

                if (!isset($checkBillPaymentList->id)) {
                    BillPaymentList::create([
                        'amount' => $checkBill->total_amount,
                        'bill_id' => $checkBill->id
                    ]);
                } else {
                    $checkBillPaymentList->amount = $checkBill->total_amount;
                    $checkBillPaymentList->save();
                }
                $response['status'] = 'ok';
            } else {
                $response['status'] = 'not ok';
            }
            
            

        } else {
            $total_balance = intval($qbill_obj->Balance);
            $checkBill = Bill::where('qbo_bill_id',$bill_id)->first();
            $currentUserRealmId = Auth::user()->quickbookstoken->realm_id;
            $checkCompany = \App\QuickbooksCompany::where('company_realm_id', $currentUserRealmId)->first();
            $response['company'] = (isset($checkCompany->company_name)) ? $checkCompany->company_name : '';

            if (isset($checkBill->id) && $checkBill->qbo_company_realmid=== $currentUserRealmId) {

                if ($total_balance==0) {
                    $checkBill->payment_status = 2;
                } elseif ($total_balance > 0) {
                    $checkBill->payment_status = 1;
                }

                $response['payment_status'] = $checkBill->payment_status;
                $response['total_balance'] = $qbill_obj->Balance;
                $checkBill->save();
                

                $checkBillPaymentList = BillPaymentList::where('bill_id', $checkBill->id)->first();

                if (!isset($checkBillPaymentList->id)) {
                    BillPaymentList::create([
                        'amount' => $checkBill->total_amount,
                        'bill_id' => $checkBill->id
                    ]);
                } else {
                    $checkBillPaymentList->amount = $checkBill->total_amount;
                    $checkBillPaymentList->save();
                }
                $response['status'] = 'ok';
            } else {
                $response['status'] = 'not ok';
            }
        }
            
        $response['result'] = $qbill_payment_obj;
        return response()->json($response);

    }

    public function search(Request $request) {

        $query = ($request->has('q')) ? $request->input('q') : '';
        $field = $request->has('field') ? $request->input('field') : 'eta';
        $orderBy = $request->has('orderBy') ? $request->input('orderBy') : 'desc';
        $checkUser = Auth::user();
        $checkUserRealmId = (isset($checkUser->quickbookstoken)) ? $checkUser->quickbookstoken->realm_id : '';
        if ( isset($checkUserRealmId) && $checkUserRealmId!=='') {
            $checkBillPaymentLists = \DB::table('bill_paylists')
                                    ->join('bills', 'bills.id', '=', 'bill_paylists.bill_id')
                                    ->join('shipments', 'shipments.id', '=', 'bills.shipment_id')
                                    ->join('customers', 'customers.id', '=', 'shipments.customer_id')
                                    ->select('bills.qbo_bill_num AS qbo_bill_num', 'customers.company_name AS customer_name', 'bills.payment_status AS payment_status', 'bills.qbo_vendor_name AS vendor_name', 'shipments.eta AS eta','bills.qbo_bill_num AS reference_num','bills.total_amount AS total_amount', 'bills.id AS bill_id', 'shipments.shifl_ref AS shifl_ref', 'bills.qbo_bill_id AS qbo_bill_id', 'bills.qbo_vendor_id AS qbo_vendor_id', 'bills.balance AS balance_pay','bills.qbo_company_realmid AS realm_id')
                                    ->where(function($q) {
                                        $q->where('payment_status', 1);
                                        $q->orWhere('payment_status', 3);
                                        return $q;
                                    })
                                    ->where('bills.qbo_company_realmid', $checkUserRealmId)
                                    ->where(function($q) use ($query){
                                        if ($query=='') {
                                            return $q;
                                        } else {
                                            $q->where('bills.qbo_bill_num','LIKE','%'.$query.'%');
                                            $q->orWhere('customers.company_name', 'LIKE', '%'.$query.'%');
                                            $q->orWhere('bills.qbo_vendor_name', 'LIKE', '%'.$query.'%');
                                            $q->orWhere('bills.qbo_bill_num', 'LIKE', '%'.$query.'%');
                                            $q->orWhere('bills.total_amount', 'LIKE', '%'.$query.'%');
                                            $q->orWhere('shipments.shifl_ref', 'LIKE', '%'.$query.'%');    
                                        }

                                        return $q;
                                    })
                                    ->orderBy($field, $orderBy);
        } else {
            $checkBillPaymentList = [];
        }
        

        $results = (is_null($request->per_page)) ? $checkBillPaymentLists->paginate(50) : $checkBillPaymentLists->paginate(intval($request->per_page));

        return response()->json([
            'results' => $results
        ]);
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {

        $field = $request->has('field') ? $request->input('field') : 'eta';
        $orderBy = $request->has('orderBy') ? $request->input('orderBy') : 'desc';

        $checkUser = Auth::user();

        $checkUserRealmId = (isset($checkUser->quickbookstoken)) ? $checkUser->quickbookstoken->realm_id : '';

        if ( isset($checkUserRealmId) && $checkUserRealmId!=='') {
            $checkBillPaymentLists = \DB::table('bill_paylists')
            ->join('bills', 'bills.id', '=', 'bill_paylists.bill_id')
            ->join('shipments', 'shipments.id', '=', 'bills.shipment_id')
            ->join('customers', 'customers.id', '=', 'shipments.customer_id')
            ->select('bills.qbo_bill_num AS qbo_bill_num', 'customers.id AS customer_id','customers.company_name AS customer_name','customers.managers AS customer_manager', 'bills.payment_status AS payment_status', 'bills.qbo_vendor_name AS vendor_name', 'shipments.eta AS eta','bills.qbo_bill_num AS reference_num','bills.total_amount AS total_amount', 'bills.id AS bill_id', 'shipments.shifl_ref AS shifl_ref', 'bills.qbo_bill_id AS qbo_bill_id', 'bills.qbo_vendor_id AS qbo_vendor_id', 'bills.balance AS balance_pay','bills.qbo_company_realmid AS realm_id', \DB::raw('( SELECT email FROM users WHERE id = customers.managers LIMIT 1) AS account_representative_email') )
            ->where(function($q) {
                $q->where('payment_status', 1);
                $q->orWhere('payment_status', 3);
                return $q;
            })
            ->where('bills.qbo_company_realmid', $checkUserRealmId)
            ->orderBy($field, $orderBy);
        } else {
            $checkBillPaymentLists = [];
        }

        $results = (is_null($request->per_page)) ? $checkBillPaymentLists->paginate(50) : $checkBillPaymentLists->paginate(intval($request->per_page));

        // $results = json_decode(json_encode($results));

        // $results->data = collect($results->data)->map(function($item){
        //     $customer = \App\Customer::find($item->customer_id);

        //     if( !empty($customer) ){
        //         $item->overdue_balance = 'Loading...';
        //         $item->last_payment = 'Loading...';
        //     }

        //     return $item;
        // })
        // ->all();

        return response()->json([
            'results' => $results
        ]);
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
     * sendResponse
     *
     * @param  mixed $result
     * @param  mixed $message
     * @return void
     */
    public function sendResponse($result, $message)
    {
        $response = [
            'success' => true,
            'data' => isset($result->resource) ? $result->resource : $result,
            'message' => $message,
            'status' => 'ok'
        ];

        return response()->json($response, 200);
    }

    /**
     * sendError
     *
     * @param  mixed $error
     * @param  mixed $errorMessages
     * @param  mixed $code
     * @return void
     */
    public function sendError($error, $errorMessages = [], $code = 404)
    {
        $response = [
            'success' => false,
            'message' => $error,
            'status' => 'not ok'
        ];


        if(!empty($errorMessages)){
            $response['data'] = $errorMessages;
        }


        return response()->json($response, $code);
    }


    public function removeBill(Request $request, $bill_id) {

        $response = ['status' => 'ok'];

        $checkBillPaymentList = BillPaymentList::where('bill_id', $bill_id)->first();


        $checkBill = Bill::find($bill_id);


        $currentUserRealmId = Auth::user()->quickbookstoken->realm_id;


        if (isset($checkBillPaymentList->id) && $checkBill->qbo_company_realmid===$currentUserRealmId) {
            $checkBill = Bill::find($checkBillPaymentList->bill_id);

            if (isset($checkBill->id)) {
                $checkBill->payment_status = 0;
                $checkBill->save();

                //delete bill paylist
                $checkBillPaymentList->delete();

            }
        }

        return response()->json($response);
 
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

        $checkBill = Bill::find($id);
        $error = null;
        $currentUserRealmId = Auth::user()->quickbookstoken->realm_id;

        if (isset($checkBill->id) && $checkBill->qbo_company_realmid===$currentUserRealmId) {
            
            $validator = Validator::make($request->all(), [
                'payment_status' => ['required', new ZeroOrOneOrTwo]
            ]);

            if ($validator->fails()) return $this->sendError('Validation Error.', $validator->errors());

            $validatedData = $request->all();


            $checkBill->payment_status = intval($request->payment_status);
            $checkBill->save();

            //insert also the bill payment list table if not inserted yet
            $checkBillPaymentList = BillPaymentList::where('bill_id', $id)->first();

            $billPaymentData = null;


            //if mark as paid
            if ($request->payment_status==2) {

                $qb = app('QuickBooks');

                //get the bill object
                $qbill_obj = $qb->getDataService()->FindbyId("Bill", $checkBill->qbo_bill_id);

                $bill_payment_lines = [];

                if ( isset($qbill_obj->Line) ) {

                    $set_bill_lines = $qbill_obj->Line;
                    if (is_array($set_bill_lines)) {
                        foreach ($set_bill_lines as $key => $value) {
                            array_push($bill_payment_lines, [
                                'DetailType' => 'AccountBasedExpenseLineDetail',
                                'Amount' => number_format((float)$value->Amount, 2, '.', ''),
                                'LinkedTxn' => [
                                    [ 
                                      'TxnId' => $qbill_obj->Id,
                                      'TxnType' => 'Bill'
                                    ]
                                ],
                            ]);
                        }

                    } else {
                        array_push($bill_payment_lines, [
                            'DetailType' => 'AccountBasedExpenseLineDetail',
                            'Amount' => number_format((float)$set_bill_lines->Amount, 2, '.', ''),
                            'LinkedTxn' => [
                                [ 
                                  'TxnId' => $qbill_obj->Id,
                                  'TxnType' => 'Bill'
                                ]
                            ]
                        ]);
                    }
                }

                if (count($bill_payment_lines) > 0) {

                    //brex checking bank account id = 168
                    $brex_account_id = 168;
                    //$capital_account_id = 173;
                    
                    //sandbox quickbooks
                    //$brex_account_id = 35;

                    $billPaymentData = [
                        'DocNumber' => (isset($qbill_obj->DocNumber)) ? $qbill_obj->DocNumber : '',
                        'VendorRef' => [
                            'value' => (isset($checkBill->qbo_vendor_id)) ? strval($checkBill->qbo_vendor_id) : ''
                        ],
                        'TotalAmt' => number_format((float)$checkBill->total_amount, 2, '.', ''),
                        'PayType' => 'Check',
                        'Line' => $bill_payment_lines,
                        'CheckPayment' => [
                            'BankAccountRef' => [
                                'value' => strval($brex_account_id)
                            ]
                        ]
                    ];

                    $billPaymentObj = BillPayment::create($billPaymentData);

                    //create bill payment
                    //mark as paid
                    $qb->getDataService()->Add($billPaymentObj);
                    $error = $qb->getDataService()->getLastError();
                    
                }
                
            }
            
            if (!isset($checkBillPaymentList->id)) {
                BillPaymentList::create([
                    'amount' => $checkBill->total_amount,
                    'bill_id' => $id
                ]);
            } else {
                $checkBillPaymentList->amount = $checkBill->total_amount;
                $checkBillPaymentList->save();
            }


            return response()->json([
                'status' => 'ok',
                'result' => $billPaymentData,
                'bill' => $checkBill,
                'error' => $error,
            ]);

        } else {
            return $this->sendError('Validation Error.', [
                'id' => 'ID is required',
            ]);
        }

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
