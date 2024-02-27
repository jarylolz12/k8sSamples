<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Supplier;
use App\TerminalRegion;
use Auth;
use App\User;
use App\Invoice;
use App\Http\Resources\Standard as StandardResource;
use Illuminate\Support\Collection;
use GuzzleHttp\Client;
use Carbon\Carbon;

use QuickBooksOnline\API\Facades\Bill as QBill;
use QuickBooksOnline\API\Facades\BillPayment;
use QuickBooksOnline\API\Facades\PurchaseOrder;
use QuickBooksOnline\API\Facades\Vendor as QVendor;

use Illuminate\Support\Facades\Http;
use QuickBooksOnline\API\WebhooksService\WebhooksService;
use QuickBooksOnline\API\DataService\DataService;
use QuickBooksOnline\API\Core\OAuth\OAuth2\OAuth2LoginHelper;
use App\Custom\CustomJWTGenerator;

class TestController extends Controller
{
    private function ifNull($firstValue, $secondValue, $nullValue = null){
        return (empty($firstValue) ? (empty($secondValue) ? $nullValue : $secondValue) : $firstValue);
    }

    public function defaultInvoiceUpdate(){
        \DB::table('invoices')->update([
            'auto_invoice_date_update' => 1
        ]);
    }
    

    public function dynamicInvoices(Request $request) {

        /*
        if ( $request->has('proceed')) {

            $insertItem = [
            "qbo_customer_id" => 8180,
            "qbo_customer_name" => "I Health Intl LLC",
            "invoice_num" => "719970-1",
            "qbo_bill_to_email" => "sm.simlu@gmail.com,Emily@simluclothing.com,finance@brecx.com",
            "qbo_bill_to_address" => "I Health Intl LLC",
            "term" => "1",
            "invoice_date" => "2023-03-03",
            "due_date" => "2023-03-03T00:00:00.000000Z",
            "shipment_id" => 19970,
            "shifl_office_from_id" => 2,
            "shifl_office_to_id" => 1,
            "total_tax" => "0.00",
            "total_amount" => "4205.00",
            "home_total_amount" => "4205.00",
            "balance" => "4205.00",
            "home_balance" => "4205.00",
            "currency_ref" => "USD",
            "exchange_rate" => "1",
            "qbo_id" => 146807,
            "qbo_term_id" => 1,
            "qbo_term_name" => "Due on receipt",
            "qbo_term_days" =>0,
            "qbo_customer_memo" => "MBL#: EGLV003203162973\nPO#: PO-22060837\nFrom: KAOSIUNG\nTo: NY/NJ\nETD: 01/03/2023\nETA: 02/15/2023\nContainers: TCLU4342057\nSuppliers: \nCHUAN DAI ENTERPRISE CO/1391ctns",
            "qbo_country" => "US",
            "qbo_company" => "shifl Inc",
            "qbo_company_realmid" => 123146157027444,
            "allow_credit_card_payment" => 0,
            "allow_online_ach_payment" => 1,
            "is_email_sent" => "No",
            "sync_token" => 0,
            "is_processed" => 0,
            "auto_invoice_date_update" => 0
            ];
        
        
            $invoice_id = \DB::table('invoices')->insertGetId($insertItem);

            //insert services too
            $invoice_services_items = [[
                'invoice_id' => $invoice_id,
                'qbo_service_id' => 10,
                'quantity' => "1.00",
                'rate' => "3975.00",
                'qbo_service_name' => 'Ocean Freight'
            ],
            [
                'invoice_id' => $invoice_id,
                'qbo_service_id' => 14,
                'quantity' => "1.00",
                'rate' => "150.00",
                'qbo_service_name' => 'Customs handling'
            ],
            [
                'invoice_id' => $invoice_id,
                'qbo_service_id' => 25,
                'quantity' => "1.00",
                'rate' => "80.00",
                'qbo_service_name' => 'Chassis'
            ]
            ];

            \DB::table('invoice_services')->insert($invoice_services_items);
        }*/

        return response()->json([
            'status' => 'ok'
        ]);        

    }


    public function indicate(Request $request) {
        return response()->json([
            'version' => 40
        ]);
    }
    public function testingg(Request $request) {

        $shipments = [];        
        if ( $request->has('ssid') && $request->has('password') ) {   
            $password = 'test15123';
            if ( $request->input('password') == $password ) {
                $shipments = \App\Shipment::where('customer_id', 650)->get();

                if ( count($shipments) > 0 ) {
                    foreach($shipments as $shipment ) {
                        if ( $shipment->booking_confirmed === 1 && ($shipment->mbl_num == null || $shipment->mbl_num== '') && $shipment->is_tracking_shipment == 0) {
                            $schedules_group_bookings = [];
                            try {
                                $schedules_group_bookings = json_decode($shipment->schedules_group_bookings);
                                $selectedSchedule = null;
                                if ( count($schedules_group_bookings)  == 1 ) {
                                    foreach($schedules_group_bookings as $keySecond => $schedule_group ) {
                                        if ( $selectedSchedule == null ) {
                                            $schedules_group_bookings[$keySecond]->is_confirmed = 1;
                                            /*
                                            if ( isset($schedule_group->is_confirmed) && $schedule_group->is_confirmed == 1 ) {
                                                $selectedSchedule = $schedule_group;
                                            }*/
                                        }
                                    }
                                    $shipment->schedules_group_bookings = json_encode($schedules_group_bookings);
                                    $shipment->schedules_group = json_encode($schedules_group_bookings);
                                    $shipment->save();
                                    $selectedSchedule = $schedules_group_bookings[0];
                                }

                                if ( $selectedSchedule!==null ) {
                                    \DB::table('shipments')->where('id', $shipment->id)
                                    ->update([
                                    'eta' => $selectedSchedule->eta,
                                    'etd' => $selectedSchedule->etd
                                    ]);
                                }

                            } catch(Exception $e) {

                            }
                        }
                        
                    }
                }



            }
        }

        return response()->json([
            'shipments' => $shipments
        ]);

        /*
        $diff_days = Carbon::parse('2023-01-10T00:00:00.000000Z')->diffInDays(now(), false);
        if ($diff_days<60) {

            $schedules = "[{\"id\":\"1671820222940\",\"eta\":\"2023-01-10 15:24:22\",\"etd\":\"2023-01-01 15:24:22\",\"location_from\":5,\"location_to\":4,\"mode\":\"Ocean\",\"legs\":[],\"type\":\"FCL\",\"carrier_name\":{\"id\":0},\"carrier_name_label\":\"\",\"is_confirmed\":0,\"size_id\":null,\"price\":null,\"selling_size_id\":null,\"selling_price\":null,\"sell_rates\":[],\"buy_rates\":[]}]";

            $schedules = json_decode($schedules);

            if (count($schedules)>0) {

                foreach($schedules as $keySecond => $schedule) {
                    $hasUnset = false;

                    if ( !$hasUnset ) {
                        $hasUnset = true;
                        $eta = Carbon::parse($schedule->eta)->diffInDays(now(), false);
                        if ($eta>60) {
                            unset($schedules[$keySecond]);
                        }
                    }

                }
                if (count($schedules)>0) {
                    echo 'greater than 0';
                }
            }


        } else {
            echo 'asdfsdf';
        }*/
    }

    public function updateProcess() {
        \DB::table('shipments')->update([
            'is_tracking_processing' => 0,
            'is_tracking_info_processing' => 0
        ]);
    }

    public function updateProcessAnother() {
        \DB::table('shipments')->update([
            'has_check_docs' => 0
        ]);
    }


    public function getBottomShipments() {
        $checkShipments = \App\Shipment::orderBy('id', 'desc')
                                  ->paginate(200);

        return response()->json([
            'shipments' => $checkShipments
        ]);

    }
    public function checkProcessed() {

        $processed = \DB::table('shipments')->where('is_tracking_processing', 1)->count();
        $processed_tracking_info = \DB::table('shipments')->where('is_tracking_info_processing', 1)->count();
        $processed_docs = \DB::table('shipments')->where('has_check_docs', 1)->count();
        $total = \DB::table('shipments')->count();

        return response()->json([
            'processed_tracking' => $processed,
            'processed_tracking_info' => $processed_tracking_info,
            'processed_docs' => $processed_docs,
            'total' => $total
        ]);
    }




    private function deleteDistributions($shipment_meta_ids) {

        $response = null;

        $jwtToken = CustomJWTGenerator::generateToken();
        $poInstanceClient = new \GuzzleHttp\Client([
            'base_uri' => 'http://localhost:8001',
            'verify' => false,
            'headers' => [
                'Content-Type' => 'application/json',
                'Accept'     => 'application/json',
                'Authorization'  => 'Bearer ' . $jwtToken,
            ]
        ]);
        
        try {

            $res = $poInstanceClient->put('/api/po/delete-distributionse', [
                "json" => [
                    'ids' => $shipment_meta_ids,
                ]
            ]);

            $response = $res->json();

        }catch(Exception $e) {
            $response = [
                'error' => $e
            ];
        }

        return $response;
    }
    

    public function updateShipments(Request $request) {

        if ( $request->has('password') ) {
            $check_password = $request->input('password');
            if ( $check_password === 'testhere1234' ) {
                $check_shipment_ids = \App\ShipmentMeta::get()->pluck('shipment_id');
                //$distribution_response = null;
        
                if ( count($check_shipment_ids) > 0 ) {
                //   \DB::table('shipments')->whereIn('id', $check_shipment_ids)->delete();
                    //$distribution_response = $this->deleteDistributions($check_shipment_metas);
                }
                /*
                return response()->json([
                    'po_response' => $distribution_response,
                    'check_shipment_metas' => $check_shipment_metas
                ]);*/

                return response()->json([
                    'shipment_ids' => $check_shipment_ids
                ]);
            }
            
        } else {
            return response()->json([
                'status' => 'not ok'
            ]);
        }

    }


    public function checkBillStatus(Request $request) {

        $qb = app('QuickBooks');
        $response = ['status' => 'not ok'];
        $qbill_obj = null;
        $qbill_payment_obj = null;
        $bill_id = 583;

        $bills = $qb->getDataService()->Query("SELECT * FROM Bill");

        return response()->json($bills);


        /*
        $qbill_obj = $qb->getDataService()->FindById("Bill", $bill_id);

        return response()->json($qbill_obj);
        if (isset($qbill_obj->LinkedTxn) && isset($qbill_obj->LinkedTxn->TxnId)) {
            
             $txn_id = $qbill_obj->LinkedTxn->TxnId;
            $qbill_payment_obj = $qb->getDataService()->FindById("BillPayment", $txn_id);

            //return response()->json($qbill_obj);
            return response()->json($qbill_payment_obj);
        }*/

    }
    public function getCashAccountsQuickbooks(Request $request) {

        $qb = app('QuickBooks');

        $accounts = $qb->getDataService()->Query("SELECT * FROM Account");

        $bank_accounts = [];

        if (is_array($accounts) && count($accounts) > 0) {
                        
            //check for account which is a bank account
            //put them in the bank accounts array
            foreach ($accounts as $account) {
                if ( isset($account->AccountType) ) {
                    if ($account->AccountType==='Bank' && $account->AccountSubType=='Checking') {
                        array_push($bank_accounts, $account);
                    }
                }
            }
        }

        return response()->json($bank_accounts);

    }


    public function getVendorQ(Request $request) {
        
        $qb = app('QuickBooks');
        $emails = [];
        $vendorData = [];

        $vendor_obj = $qb->getDataService()->Query("select * from Vendor");

        echo var_dump($vendor_obj);

        /*
        $qb = app('QuickBooks');
        $vendor_id = strval(403);
        $vendorObj = $qb->getDataService()->FindbyId('Vendor',403);

        if (isset($vendorObj->Id)) {
            $vendorData = [
                'Id' => $vendor_id,
                'BillAddr' => [
                    'Line1' => 'line 1111'
                ],
            ];

            try {
                
                $vendorQb = QVendor::update($vendorObj, $vendorData);
                $obb = $qb->getDataService()->Update($vendorQb);
                $error = $qb->getDataService()->getLastError();
                return response()->json($obb);
            } catch (Exception $e) {
                return response()->json($e);
            }
        } else {
            echo 'wew';
            echo var_dump($vendorObj);
        }*/
        

        //return response()->json($syncToken);

    }

    public function getTransaction(Request $request) {
        $brex_token = 'bxt_81zGTa64Rf0Ypt4Wc6ahB2pC83pGbqP6Uam3';
        $cash_id = 'dpacc_ckvofb3ii0ac101lyccg596zh';
        $brex_api_url = sprintf('https://platform.brexapis.com/v2/transactions/cash/%s',$cash_id);

        $r = Http::withHeaders([
            "Authorization" => sprintf('Bearer %s', $brex_token),
            "Content-type" => "application/json",
        ])
        ->get($brex_api_url);

        return response()->json($r->json());
        
    }


    
    public function testPayAgain(Request $request) {

        $response = ['status' => 'not ok'];
        $brex_token = 'bxt_81zGTa64Rf0Ypt4Wc6ahB2pC83pGbqP6Uam3';
        $vendor_id = 'pdcont_ckx4n121q00070e5np9emf56h';
        $brex_api_url = sprintf('https://platform.brexapis.com/v1/vendors/%s',$vendor_id);

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

                return response()->json($r);
                exit;

                if (!isset($r['id'])) {
                    $response['message'] = 'Vendor not found.';
                } else {
                    $response['response'] = $r;
                    try {

                        $brex_api_url = 'https://platform.brexapis.com/v1/transfers';
                        $brex_data = [
                            'counter_party' => [
                                'type' => 'VENDOR',
                                'payment_instrument_id' => $r->payment_accounts[0]->details->payment_instrument_id
                            ],
                            'amount' => [
                                'amount' => intval($checkBill->total_amount),
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

                        Http::withHeaders([
                            "Authorization" => sprintf('Bearer %s', $brex_token),
                            "Content-type" => "application/json",
                            "Idempotency-Key" => $code
                        ])->post($brex_api_url, $brex_data);
                        

                        $response['status'] = 'ok';
                    } catch(Exception $e) {
                        $response['error'] = $e;
                    }
                    
                }
            }



        } catch(Exception $e) {
            $response['message'] = $e;

        }
        

        return response()->json($response);
    }


    public function getBrexVendors(Request $request) {
        $brex_api_url = 'https://platform.brexapis.com/v1/vendors';
        $brex_token = 'bxt_xVuN4E6B9iMSikuGT8MW3QflOThIDiZtbltx';
        
        
        $response = Http::withHeaders([
            "Authorization" => sprintf('Bearer %s', $brex_token),
            "Content-type" => "application/json",
            //"Idempotency-Key" => "lfihs"
        ])->get($brex_api_url);

        $response = $response->json();

        return response()->json($response['items']);
    }

    public function fetchBrexQuickbooks(Request $request) {

        $qb = app('QuickBooks');
        $emails = [];
        $vendorData = [];

        $vendor_obj = $qb->getDataService()->Query("select * from Vendor");
        
        if (count($vendor_obj) > 0) {
            foreach ($vendor_obj as $key => $v) {
                if (isset($v->PrimaryEmailAddr) && isset($v->PrimaryEmailAddr->Address)) {

                    array_push($emails, $v->PrimaryEmailAddr->Address);
                    //$checkVendor = \App\Vendor::where('email', $v->PrimaryEmailAddr->Address)->first();

                    if (!isset($checkVendor->email)) {
                        
                        //s
                        
                        $vendorSingleData = [
                            'company_name' => $v->CompanyName
                        ];

                        $vendorSingleData['country'] = (isset($v->BillAddr) && isset($v->BillAddr->Country)) ? $v->BillAddr->Country : null;
                        $vendorSingleData['city'] = (isset($v->BillAddr) && isset($v->BillAddr->City)) ? $v->BillAddr->City : null;

                        $vendorSingleData['line1'] = (isset($v->BillAddr) && isset($v->BillAddr->Line1)) ? $v->BillAddr->Line1 : null;                        

                        $vendorSingleData['line2'] = (isset($v->BillAddr) && isset($v->BillAddr->Line2)) ? $v->BillAddr->Line2 : null;

                        $vendorSingleData['line3'] = (isset($v->BillAddr) && isset($v->BillAddr->Line3)) ? $v->BillAddr->Line3 : null;

                        $vendorSingleData['postal_code'] = (isset($v->BillAddr) && isset($v->BillAddr->PostalCode)) ? $v->BillAddr->PostalCode : null;

                        $vendorSingleData['line3'] = (isset($v->BillAddr) && isset($v->BillAddr->Line3)) ? $v->BillAddr->Line3 : null;

                        $vendorSingleData['country_subdivision_code'] = (isset($v->BillAddr) && isset($v->BillAddr->CountrySubdivisionCode)) ? $v->BillAddr->CountrySubdivisionCode : null;

                        $vendorSingleData['country_subdivision_code'] = (isset($v->BillAddr) && isset($v->BillAddr->CountrySubdivisionCode)) ? $v->BillAddr->CountrySubdivisionCode : null;                        
                        $vendorSingleData['email'] = (isset($v->PrimaryEmailAddr) && isset($v->PrimaryEmailAddr->Address)) ? $v->PrimaryEmailAddr->Address : null;

                        $vendorSingleData['phone'] = (isset($v->Mobile) && isset($v->Mobile->FreeFormNumber)) ? $v->Mobile->FreeFormNumber : null;

                        $vendorSingleData['payment_accounts'] = '[]';

                        array_push($vendorData, $vendorSingleData);
                    }
                }
            }

            $brex_api_url = 'https://platform.brexapis.com/v1/vendors';
            $brex_token = 'bxt_xVuN4E6B9iMSikuGT8MW3QflOThIDiZtbltx';
            
            $brex_vendors = Http::withHeaders([
                            "Authorization" => sprintf('Bearer %s', $brex_token),
                            "Content-type" => "application/json",
                        ])
                        ->get($brex_api_url);

            $brex_vendors = $brex_vendors->json();

            //fetch brex vendors
            foreach ( $vendorData as $key => $data ) {

                if ( isset($brex_vendors['items']) && count($brex_vendors['items'])>0 ) {
                    foreach( $brex_vendors['items'] as $brex_vendor ) {
                        if ( strtolower($data['company_name'])===strtolower($brex_vendor['company_name']) ) {
                            $vendorData[$key]['payment_accounts'] = json_encode($brex_vendor['payment_accounts']);
                        }
                    }
                }
                //$vendorData
            }

        }

        if (count($vendorData) > 0) {
            \DB::table('vendors')->insert($vendorData);
        }

        return response()->json($vendorData);

    }



    public function listCards(Request $request) {

        $brex_api_url = 'https://platform.brexapis.com/v2/accounts/cash';
        $brex_token = 'bxt_xVuN4E6B9iMSikuGT8MW3QflOThIDiZtbltx';
        
    
        $response = Http::withHeaders([
            "Authorization" => sprintf('Bearer %s', $brex_token),
            "Content-type" => "application/json",
            "Idempotency-Key" => "adfafas"
        ])->get($brex_api_url);

        return response()->json($response->json());

    }

    public function checkVendorQuickbooks(Request $request) {
        $qb = app('QuickBooks');
        $valid = true;

        $value = 'shifff@gmail.com';
        $emails = [];
        $vendor_obj = $qb->getDataService()->Query("select * from Vendor");
        
        if (count($vendor_obj) > 0) {
            foreach ($vendor_obj as $key => $v) {
                if (isset($v->PrimaryEmailAddr) && isset($v->PrimaryEmailAddr->Address)) {
                    array_push($emails, $v->PrimaryEmailAddr->Address);
                }
            }
        }

        if (in_array($value, $emails)) {
            echo 'not valid';
        } else {
            echo var_dump($value);
            echo var_dump($emails);
            //echo 'valid';
        }
    }

    public function checkQuickbooks(Request $request) {
        $qb = app('QuickBooks');
        $valid = true;

        $vendor_obj = $qb->getDataService()->Query("select * from Vendor");
        
        return response()->json($vendor_obj);

        
    }

    public function checkAccounts(Request $request) {

        /*
        $qb = app('QuickBooks');
        $value = 'Books@Intuit.com';

        $emails = [];
        $name = [];
        $vendor_obj = $qb->getDataService()->Query("select * from Vendor");
        
        if (count($vendor_obj) > 0) {
            foreach ($vendor_obj as $key => $value) {
                if (isset($value->PrimaryEmailAddr) && isset($value->PrimaryEmailAddr->Address)) {
                    array_push($emails, $value->PrimaryEmailAddr->Address);
                }

                if (isset($value->DisplayName)) {
                    array_push($name, $value->DisplayName);
                }
            }
        }
        return response()->json($vendor_obj);*/
        //return response()->json($name);
        /*
        if (isset($vendor_obj->QueryResponse) && isset($vendor_obj->QueryResponse->Vendor)) {
          //  return (count($vendor_obj->QueryResponse->Vendor) == 0);
        }*/

        //return response()->json($emails);

        //return sprintf("select * from Vendor where PrimaryEmailAddr.Address='%s'",$value);
    
        $brex_api_url = 'https://platform.brexapis.com/v1/vendors';
        $brex_token = 'bxt_xVuN4E6B9iMSikuGT8MW3QflOThIDiZtbltx';
        
        
        $response = Http::withHeaders([
            "Authorization" => sprintf('Bearer %s', $brex_token),
            "Content-type" => "application/json",
            //"Idempotency-Key" => "lfihs"
        ])->get($brex_api_url);

        $response = $response->json();

        return response()->json($response['items']);

        $qb = app('QuickBooks');


        $vendorData = [
            'PrimaryEmailAddr' => [
                'Address' =>'vendorhere@gmail.com',
            ],
            'PrimaryPhone' => [
                'FreeFormNumber' => '1234'
            ],
            'DisplayName' =>'Vendor Here',
            //'Suffix' => $item->suffix,
            //'Title' => 'Shifs',
            'Mobile' => [
                'FreeFormNumber' => '1234'
            ],
            'TaxIdentifier' => '213125',
            //'AccNum' => '12321415123',
            //'FamilyName' => $item->family_name,
            //'TaxIdentifier' => $item->tax_identifier,
            //'AccNum' => $item->account_number,
            'CompanyName' => 'Vendor Here',
            'BillAddr' => [
                'City' => 'asdf',
                'Country' => 'asdfsdf',
                'Line3' => 'asdfsdf',
                'Line2' => 'asdfsdf',
                'Line1'=> 'asdfasdf',
                'PostalCode' => '94030',
                'CountrySubDivisionCode' => 'CA',
            ],
            //'GivenName' => $item->given_name,
            //'PrintOnCheckName' => $item->print_on_check_name
        ];

        
        
        try {
            //$results = $qb->getDataService()->Query("SELECT * FROM vendor");
            //echo var_dump($results);
            //echo var_dump($vendorData);
            //$vendorObj = QVendor::create($vendorData);
            //$vendorQb = $qb->getDataService()->Add($vendorObj);
            //echo var_dump($qb->getDataService()->getLastError());
            
            
            $brex_token = 'bxt_xVuN4E6B9iMSikuGT8MW3QflOThIDiZtbltx';
            //$brex_token = 'bxt_xVuN4E6B9iMSikuGT8MW3QflOThIDiZtbltxa';
            $brex_api_url = 'https://platform.brexapis.com/v1/vendors';


            $brex_payment_accounts = [[
                'details' => [
                    'type' => 'DOMESTIC_WIRE',
                    'routing_number' => '021000021',
                    'account_number' => '905935839',
                    'address' => [
                        'line1' => null,
                        'line2' => null,
                        'city' => null,
                        'state' => null,
                        'country' => null,
                        'postal_code' => null,
                        'phone_number' => null
                        //'phone_number' => '+17185015200',
                    ]
                ]
            ]];

            $brex_data = [
                'company_name' => 'shiftestnow',
                'email' => 'shiftestnow@gmail.com',
                'phone' => '15123',
                'payment_accounts' => $brex_payment_accounts
            ];


            $letters = 'abcdefghjiklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
            $code = '';

            for( $x=0;$x<6;$x++ ) {
                $code .=$letters[rand(0, strlen($letters) - 1)];
            }
            
            $response = Http::withHeaders([
                            "Authorization" => sprintf('Bearer %s', $brex_token),
                            "Content-type" => "application/json",
                            "Idempotency-Key" => $code
                        ])
                        ->post($brex_api_url, $brex_data);

            $response = json_decode($response->json());




        }catch(Exception $e) {
            echo var_dump($e);
        }

        /*
        $brex_api_url = 'https://platform.brexapis.com/v1/vendors';
        $brex_token = 'bxt_81zGTa64Rf0Ypt4Wc6ahB2pC83pGbqP6Uam3';


        $response = Http::withHeaders([
            "Authorization" => sprintf('Bearer %s', $brex_token),
            "Content-type" => "application/json",
            "Idempotency-Key" => "lfihs"
        ])->get($brex_api_url);

        $response = $response->json();
        $emails = [];
        $name = [];

        if ( isset($response['items']) ) {
            if ( count($response['items']) > 0 ) {
                foreach($response['items'] as $item) {
                    if (isset($item['email']))
                        array_push($emails, $item['email']);
                    if (isset($item['company_name']))
                        array_push($name, $item['company_name']);
                }
            }
        }

        //if (in_array($value, haystack))
        return response()->json($name);
        //echo var_dump($emails);*/

    }

    public function testPay(Request $request) {

        //get the bill object
        $bill_id = 81271;
        $vendor_id = 119;

        $qb = app('QuickBooks');
        $qbill_obj = $qb->getDataService()->FindById("Bill", $bill_id);


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
                          'TxnId' => $bill_id,
                          'TxnType' => 'Bill'
                        ]
                    ]
                ]);
            }
        }

        if (count($bill_payment_lines) > 0) {

            //brex checking bank account id = 168
            $brex_account_id = 168;

            $billPaymentData = [
                'DocNumber' => (isset($qbill_obj->DocNumber)) ? $qbill_obj->DocNumber : '',
                'VendorRef' => [
                    'value' => (isset($vendor_id)) ? strval($vendor_id) : ''
                ],
                'TotalAmt' => number_format((float)$qbill_obj->TotalAmt, 2, '.', ''),
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
            //$error = $qb->getDataService()->getLastError();
            
            return response()->json($billPaymentData);
        }
    }



    public function testCheck(Request $request) {
        $qb = app('QuickBooks');
        $qbDataService = $qb->getDataService();

        $checkBill = $qb->getDataService()->Query("SELECT * FROM Bill WHERE DocNumber='TestBill711803'");
        ///check accounts
        /*
        $accounts = $qb->getDataService()->Query("SELECT * FROM Account");

        $bank_accounts = [];

        if (is_array($accounts) && count($accounts) > 0) {
                        
            //check for account which is a bank account
            //put them in the bank accounts array
            foreach ($accounts as $account) {
                if ( isset($account->AccountType) ) {
                    if ($account->AccountType==='Bank' && $account->AccountSubType=='Checking') {
                        array_push($bank_accounts, $account);
                    }
                }
            }
        }*/

        return response()->json($checkBill);
       // return response()->json($bank_accounts);

    }


    public function createTestBill(Request $request) {

        $base_url = 'https://sandbox-quickbooks.api.intuit.com';
        $realm_id = Auth::user()->quickbookstoken->realm_id;
        $token = Auth::user()->quickbookstoken->access_token;

        $qb = app('QuickBooks');
        $qbDataService = $qb->getDataService();


        /*

        {
  "PrivateNote": "Acct. 1JK90", 
  "VendorRef": {
    "name": "Bob's Burger Joint", 
    "value": "56"
  }, 
  "TotalAmt": 200.0, 
  "PayType": "Check", 
  "Line": [
    {
      "Amount": 200.0, 
      "LinkedTxn": [
        {
          "TxnId": "234", 
          "TxnType": "Bill"
        }
      ]
    }
  ], 
  "CheckPayment": {
    "BankAccountRef": {
      "name": "Checking", 
      "value": "35"
    }
  }
}

*/
        $obj = json_decode('{ "DocNumber": "1651512", "VendorRef": { "value": "40"}, "TotalAmt": "50.5", "PayType": "Check", "Line": [ { "DetailType": "AccountBasedExpenseLineDetail", "Amount": "50.5", "LinkedTxn": [{ "TxnId": "561", "TxnType": "Bill"}] } ], "CheckPayment": { "BankAccountRef": { "value": "35" } } }', true);

        $result = BillPayment::create($obj);

        $qb->getDataService()->add($result);
        
        return response()->json($result);

       // $result = BillPayment::create($obj);
        //$qb->getDataService()->add($result);

        //return response()->json($result);


        /*
        $qb = app('QuickBooks');
        $qbDataService = $qb->getDataService();

        $vendors = $qb->getDataService()->Query("SELECT * FROM Vendor");

        echo json_encode($vendors);
        exit;*/

        
        /*
        $vendor = [
            'value' => 30
        ];

        $accountExpense = [
            'name' => 'Advertising',
            'value' => 7
        ];


        $billData = [
            "VendorRef" => [
                "value" => strval($vendor['value']),
            ],
            'Line' => [
                [
                "DetailType" => "AccountBasedExpenseLineDetail",
                "Amount" => number_format((float)16, 1, '.', ''),
                "AccountBasedExpenseLineDetail" => [
                    "AccountRef" => [
                        "value" => strval($accountExpense['value'])
                    ]
                ]
                ]
            ]
        ];


        $endpoint = '/v3/company/'.$realm_id.'/bill?minorversion=40';

        try {

           // $response = shell_exec('curl -X POST \''.$base_url.$endpoint .'\' -H \'Content-Type: application/json\' -H \'Authorization: Bearer '.$token.'d\' -d \''.json_encode($billData).'\'');

            //echo 'curl -X POST \''.$base_url.$endpoint .'\' -H \'Content-Type: application/json\' -H \'Authorization: Bearer '.$token.'\' -d \''.json_encode($billData).'\'';

          //  echo $response;

        } catch(Exception $e) {
            echo var_dump($e);
        }
        


        //-d \''.json_encode($tokens).'


        $qb = app('QuickBooks');
        $qbDataService = $qb->getDataService();

        //$bills = $qb->getDataService()->Query("SELECT * FROM Bill");

        
        

        $result = QBill::create($billData);

        $qb->getDataService()->add($result);
        


        //return response()->json($bills);
       return response()->json($result); */

    }

    public function getBill(Request $request) {

        $response = [
            'status' => 'not ok',
            'result' => null
        ];

        $qb = app('QuickBooks');

        $vendor = [
            'name' => 'Bob\'s Burger Join',
            'value' => 56
        ];

        $accountExpense = [
            'name' => 'Advertising',
            'value' => 7
        ];



        $billData = [
            'VendorRef' => [
                'name' => $vendor['name'],
                'value' => strval($vendor['value']),
            ],
            'DocNumber' => 15123512321345,
            'TxnDate' => Carbon::now()->format('Y-m-d'),
            'DueDate' => Carbon::now()->addDays(15)->format('Y-m-d'),
            'Line' => [
                [
                'DetailType' => 'AccountBasedExpenseLineDetail',
                'Amount' => floatval(31),
                'AccountBasedExpenseLineDetail' => [
                    'AccountRef' => [
                        'name' => $accountExpense['name'],
                        'value' => $accountExpense['value']
                    ],
                    'BillableStatus' => 'Billable',
                ],
                'Description' => 'This is a test bill.',
                ]
            ],
            'TotalAmt' => floatval(31)
        ];

        $result = QBill::create($billData);
        $qb->getDataService()->add($result);

        return response()->json(['result' =>$result, 'error' => $qb->getDataService()->getLastError()]);

        //bill id = bill payment txnid 


        //$qb = app('QuickBooks');
        //$accounts = $qb->getDataService()->Query("SELECT * FROM Account WHERE AccountType = 'Expense'");

        //$vendors = $qb->getDataService()->Query("SELECT * FROM Vendor");
        /*
        $vendor = [
            'name' => $vendors[0]->Name,
            'value' => $vendors[0]->Id
        ];*/

        //$response['result']= $accounts[0];

        //$bills = $qb->getDataService()->FindById("Bill", 561);

        //$bills = $qb->getDataService()->Query("SELECT * From Bill");
        //$response['result'] = $bills;
        //$companyInfo = $qb->getDataService()->getCompanyInfo();
        //$response['result'] = $companyInfo;

        
        /*
        $qbill_obj = null;
        $qbill_payment_obj = null;

        if ($request->has('id')) {
            $bill_id = intval($request->input('id'));
            $qbill_obj = $qb->getDataService()->FindById("Bill", $bill_id);

            if (isset($qbill_obj->LinkedTxn) && isset($qbill_obj->LinkedTxn->TxnId)) {
                
                $txn_id = $qbill_obj->LinkedTxn->TxnId;
                $qbill_payment_obj = $qb->getDataService()->FindById("BillPayment", $txn_id);
            }

        }*/
        //$qbill_payment_obj = $qb->getDataService()->Query("SELECT * FROM Bill");
        //$qbill_payment_obj = $qb->getDataService()->Query("SELECT * FROM Accounts");
    
        //$response['result'] = $qbill_payment_obj[0];
        //return response()->json($response);
    }


    public function getQuickbooksUpdate(Request $request) {

        $response = ['status' => 'not ok', 'errors' => [],'message' => ''];

        $minutes = 10 * 24 * 60;
        $create_date = Carbon::now()->subMinute($minutes)->format('Y-m-d');
        $qb = app('QuickBooks');

        //$check_user = Auth::user()->quickbookstoken;
        $entity_list = ['bill'];

        //$results = $dataService->CDC($entity_list, $create_date);
        //$error = $dataService->getLastError();


        $results = $qb->getDataService()->CDC($entity_list, sprintf('%sT12:28:32-08:00',$create_date));
        $error = $qb->getDataService()->getLastError();

        if ( $error ) {
            $response['errors']['status_code'] = $error->getHttpStatusCode();
            $response['errors']['helper_message'] = $error->getOAuthHelperError();
            $response['message'] = $error->getResponseBody();
        } else {
            if ($results->entities) {
                
                foreach ($results->entities as $entityName => $entityArray) {
                    // echo "CDC Says " . count($entityArray) . " Updated Entities of Type = {$entityName}\n";
                }
                $response['message'] = $results->entities;
            }
        }

        return response()->json($response);

    }

    public function getTestData(Request $request) {
        $data = \DB::table('temporary_qb_test_data')->get();
        return response()->json($data);
    }

	private function getTerminal($id) {

        $response = null;
        $getTerminal = TerminalRegion::find($id);

        if (isset($getTerminal->id) && $getTerminal->id == $id) {

            $terminalResource = new StandardResource($getTerminal);
            if (isset($terminalResource['name'])) {
            	$response = $terminalResource['name'];
                //$response = $terminalResource->from->data->name;
            	
            }
        }
        return $response;
    }
    
    //
    public function testing(Request $request) {

    	$findUser = User::find(15);
    	/*
    	$http = new Client;
    	$response = $http->post('http://shiflnova.net/oauth/token', [
		    'form_params' => [
		        'grant_type' => 'password',
		        'client_id' => config('services.passport.password_client_id'),
		        'client_secret' => config('services.passport.password_client_secret'),
		        'username' => 'shabsielevy@gmail.com',
		        'password' => '12345678',
		        'scope' => '*',
		    ],
		]);*/

		//return json_decode((string) $response->getBody(), true);


    	//echo response()->json($response->getBody());

		
    	$params = [
            'grant_type' => 'password',
            'username' => 'shabsielevy@gmail.com',
            'password' => '12345678',
        ];

    	$params = array_merge([
            'client_id' => config('services.passport.password_client_id'),
            'client_secret' => config('services.passport.password_client_secret'),
            'scope' => '*',
        ], $params);

        $proxy = Request::create('http://shiflnova.net/oauth/token', 'post', $params);
        //$resp = json_decode(app()->handle($proxy)->getContent());
        echo app()->handle($proxy)->getContent();

        //echo response()->json($params);


    	/*
    	$customers = $findUser->customersApi()->get();
        $shipments = [];

        foreach ($customers as $customer) {
            $getShipmentsArray = $customer->shipments->toArray();
            $shipments = array_merge($shipments, $getShipmentsArray);
        }

        if (count($shipments) > 0) {
        	foreach ($shipments as $key => $shipment) {
        		if (isset($shipment['schedules_group']) && $shipment['schedules_group']!=='') {
        			$getShipmentSchedules = json_decode($shipment['schedules_group']);
        			if (count($getShipmentSchedules)>0) {
        				
        				foreach($getShipmentSchedules as $getShipmentSchedule) {
        					$shipments[$key]['location_to_name'] = $this->getTerminal($getShipmentSchedule->location_to);
        					$shipments[$key]['location_from_name'] = $this->getTerminal($getShipmentSchedule->location_from);
        				}
        			}
        		}
        	}
        }



        if (is_null($request->per_page)) {
            echo response()->json(StandardResource::collection((new Collection($shipments))));
        }
        if (is_numeric($request->per_page)) {
            echo response()->json(StandardResource::collection((new Collection($shipments))))->paginate($request->per_page);
        }*/

    }
}