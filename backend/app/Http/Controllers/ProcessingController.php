<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Shipment;
use App\Terminal;
use App\TerminalRegion;
use App\Http\Resources\Standard as StandardResource;
use Illuminate\Support\Facades\Mail;
use App\Mail\TestMail;
use App\Mail\TestNewMail;
use App\Mail\TestNewNewMail;
use App\Document;
use App\Events\UpdateDocumentEvent;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

use QuickBooksOnline\API\Facades\Invoice;
use QuickBooksOnline\API\Core\OAuth\OAuth2\OAuth2LoginHelper;
use QuickBooksOnline\API\Facades\Payment;
use QuickBooksOnline\API\DataService\DataService;
use App\User;
use App\ImportNames;
class ProcessingController extends Controller
{
    public function processDocuments(Request $request) {
    
        $get_documents = Document::paginate(30);

        if ( count ($get_documents) > 0) {
            foreach ( $get_documents as $get_document ) {
                event(new UpdateDocumentEvent($get_document));
            }
        }
    }


    public function processNotificationsView(Request $request) {
        return view('custom/processNotification');
    }

    public function processInvoices(Request $request) {
        return view('custom/processInvoice');
    }

    public function processBills(Request $request) {
        return view('custom/processBill');
    }


    public function processCustomersView(Request $request) {
        return view('custom/processCustomer');
    }

    public function deleteAllNotifications(Request $request) {
        \DB::table('quickbooks_push_notifications')
            ->where('is_processed', 1)
            ->orWhere('is_processed', 0)
            ->delete();
    }

    public function deleteNotifications(Request $request) {
        \DB::table('quickbooks_push_notifications')
            ->where('is_processed', 1)
            ->delete();
    }

    public function testtest() {
         \DB::table('shifl_offices_managers')
           ->insert([[
                'manager_id' => 116,
                'shifl_office_id' => 3,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now()
           ]]);
    }
    
    public function getNeededMigrations(Request $request) {

        $getMigrations = \DB::table('migrations')
                            ->where('id', '>', 94)
                            ->where('id', '<', 113)
                            ->get();

        return response()->json($getMigrations);

    }

    public function processSingleNotification(Request $request, $notification_id) {
        
        $qb = app('QuickBooks');
        $response = ['status' => 'not ok'];
        $checkNotification = \DB::table('quickbooks_push_notifications')
                                ->where('id',$notification_id)
                                ->where('is_processed', 0)
                                ->first();

        if (isset($checkNotification->id)) {
            $checkPayload = json_decode($checkNotification->notifications);
            if (isset($checkPayload->eventNotifications)) {
                if (is_array($checkPayload->eventNotifications)) {
                    if (count($checkPayload->eventNotifications)>0) {
                        $eventNotification = $checkPayload->eventNotifications[0];
                        if (isset($eventNotification->dataChangeEvent) && isset($eventNotification->realmId) && $eventNotification->realmId=='123146157027444') {
                            if (isset($eventNotification->dataChangeEvent->entities)) {
                                if (is_array($eventNotification->dataChangeEvent->entities)) {
                                    if (count($eventNotification->dataChangeEvent->entities)>0) {
                                        $finalNotification = $eventNotification->dataChangeEvent->entities[0];
                                        
                                        if ( $finalNotification->name=='Payment' || $finalNotification->name=='Invoice') {

                                            if ($finalNotification->name=='Payment' && $finalNotification->operation == 'Create') {
                                                $checkPayment = $qb->getDataService()->FindById("Payment", $finalNotification->id);

                                                if (isset($checkPayment->Line)) {
                                                    if (isset($checkPayment->Line->LinkedTxn)) {
                                                        if (isset($checkPayment->Line->LinkedTxn->TxnId)) {
                                                            if ($checkPayment->Line->LinkedTxn->TxnType=='Invoice') {
                                                                $checkInvoice = $qb->getDataService()->FindById("Invoice", $checkPayment->Line->LinkedTxn->TxnId);

                                                                if (isset($checkInvoice->TotalAmt) && isset($checkInvoice->Balance)) {

                                                                    //update invoice data in the system
                                                                    $updateInvoice = \App\Invoice::where('invoice_num', $checkInvoice->DocNumber)->first();

                                                                    $updateInvoice->total_amount = $checkInvoice->TotalAmt;
                                                                    $updateInvoice->balance = $checkInvoice->Balance;
                                                                    $updateInvoice->save();


                                                                    \DB::table('quickbooks_push_notifications')
                                                                    ->where('id',$notification_id)
                                                                    ->update(['is_processed' => 1]);

                                                                }

                                                            }

                                                        }
                                                    }

                                                } 
                                            
                                            }else {

                                                if ( $finalNotification->operation == 'Update' ) {

                                                    $checkInvoice = $qb->getDataService()->FindById("Invoice", $finalNotification->id);

                                                    if (isset($checkInvoice->TotalAmt) && isset($checkInvoice->Balance)) {

                                                        //update invoice data in the system
                                                        $updateInvoice = \App\Invoice::where('invoice_num', $checkInvoice->DocNumber)->first();

                                                        $updateInvoice->total_amount = $checkInvoice->TotalAmt;
                                                        $updateInvoice->balance = $checkInvoice->Balance;
                                                        $updateInvoice->save();


                                                        \DB::table('quickbooks_push_notifications')
                                                        ->where('id',$notification_id)
                                                        ->update(['is_processed' => 1]);

                                                    }    
                                                }
                                                
                                            }
                                        }
                                        
                                    }
                                }

                            }

                        }

                    }

                }
            }

        }

        return response()->json([
        'status' => 'ok',
        'notification_id' => $notification_id
        ]);

    }
    
    public function processSingleBill(Request $request, $bill_id) {

        $response = ['status' => 'not ok'];
        $checkBill = \App\Bill::find($bill_id);
        $qb = app('QuickBooks');
        $get_user = Auth::user();
        $bill_obj = [];

        if (isset($checkBill->id)) {
            $currentUserRealmId = $get_user->quickbookstoken->realm_id;
            if (isset($checkBill->id) && $checkBill->qbo_company_realmid === $currentUserRealmId) {

                $qbill_obj = $qb->getDataService()->FindbyId("Bill", $checkBill->qbo_bill_id);
                if ( is_object($qbill_obj) && isset($qbill_obj->Balance)) {
                    //check balance againstt the bill's total amount
                    //$total_balance = (intval($qbill_obj->Balance==0)) ? 0 : intval(intval($qbill_obj->TotalAmt) - intval($qbill_obj->Balance));
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
                                 $checkBill->payment_status = 0;
                            }*/
                        }
                    }
                    $response['bill_obj'] = $qbill_obj;
                    $response['processed'] = 1;
                    $checkBill->save();
                }
            }
        }

        return response()->json([
            'status' => 'ok',
            'bill_id' => $bill_id
        ]);

    }

    public function processSingleInvoice(Request $request, $invoice_id) {

        $response = ['status' => 'not ok'];
        $findInvoice = \App\Invoice::find($invoice_id);

        $get_user = User::where('email', 'shabsie@shifl.com')->first();
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

        $invoice_obj = [];
        $realm_id = 123146157027444;

        if (isset($findInvoice->id) && intval($findInvoice->qbo_company_realmid) === $realm_id ) {
            $invoice_obj = $data_service->FindById("Invoice", $findInvoice->qbo_id);
            if ( isset($invoice_obj->DocNumber)) {
                $getInvoice = $invoice_obj;
                $paidOn = null;
                if ( isset($getInvoice->LinkedTxn)) {
                    try {
                        if  (is_countable($getInvoice->LinkedTxn) && count($getInvoice->LinkedTxn) > 0) {
                            $checkLink = $getInvoice->LinkedTxn[0];
                            if ( $checkLink->TxnType === 'Payment') {
                                $checkPayment = $data_service->FindById("Payment", $checkLink->TxnId);
                                if ( isset($checkPayment->MetaData)) {
                                    $paidOn = $checkPayment->MetaData->LastUpdatedTime;
                                }

                                if ( $paidOn !== null ) {
                                    $findInvoice->paid_on = $paidOn;
                                }
                            }

                        } else {
                            if ( $getInvoice->LinkedTxn->TxnType === 'Payment') {
                                $checkPayment = $data_service->FindById("Payment", $getInvoice->LinkedTxn->TxnId);
                                if ( isset($checkPayment->MetaData)) {
                                    $paidOn = $checkPayment->MetaData->LastUpdatedTime;
                                }

                                if ( $paidOn !== null ) {
                                    $findInvoice->paid_on = $paidOn;
                                }
                            }    
                        }

                        $findInvoice->is_processed = 1;
                        $findInvoice->balance = $getInvoice->Balance;
                        $findInvoice->home_balance = $getInvoice->HomeBalance;
                        $findInvoice->total_amount = $getInvoice->TotalAmt;
                        $findInvoice->save();

                        return response()->json([
                            'status' => 'ok',
                            'invoice_id' => $invoice_id
                        ]);
                    } catch(Exception $e) {
                        /*
                        $findInvoice->is_processed = 1;
                        $findInvoice->balance = $getInvoice->Balance;
                        $findInvoice->home_balance = $getInvoice->HomeBalance;
                        $findInvoice->total_amount = $getInvoice->TotalAmt;
                        $findInvoice->save(); 
                        */
                        return response()->json([
                            'status' => 'ok',
                            'invoice_id' => $invoice_id,
                            'error' => $e
                        ]);
                    }
                    
                }

            }
        }

        return response()->json([
            'status' => 'ok',
            'invoice_id' => $invoice_id
        ]);

    }

    public function processSingleCustomer(Request $request, $customer_id) {

        $response = ['status' => 'not ok'];
        $findCustomer = \App\Customer::find($customer_id);        

        if (isset($findCustomer->id)) {

            $managerOfficeFields = [];

            //get old account manager
            $getOldAccountManager = $findCustomer;

            if ($getOldAccountManager!==null && $getOldAccountManager!=='') {
                $checkOffice = \App\ShiflOffice::where('name', 'Shifl USA')->first();

                if (isset($checkOffice->name)) {
                    array_push($managerOfficeFields, [
                        'office_id' => $checkOffice->id,
                        'manager_id' => intval($getOldAccountManager->managers)
                    ]);

                    //update account manager main association to office USA
                    $updateAccountManager = \App\AccountManager::find(intval($getOldAccountManager->managers));

                    if (isset($updateAccountManager->id)) {
                        $updateAccountManager->shifl_office_id = $checkOffice->id;
                        $updateAccountManager->save();
                    }

                }  

            }

            //get jing
            $getJing = \App\AccountManager::where('email','jing@shifl.cn')->first();
            //$getJing = \App\AccountManager::where('email','jing@test.com')->first();

            if (isset($getJing->id)) {
                $checkOffice = \App\ShiflOffice::where('name', 'Shifl China')->first();

                if (isset($checkOffice->name)) {
                    array_push($managerOfficeFields, [
                        'office_id' => $checkOffice->id,
                        'manager_id' => intval($getJing->id)
                    ]);


                    $getJing->shifl_office_id = $checkOffice->id;
                    $getJing->save();
                    

                }   
            }

            //get tammy
            $getTammy = \App\AccountManager::where('email','tammy@shifl.com')->first();
            //$getTammy = \App\AccountManager::where('email','tammy@test.com')->first();

            if (isset($getTammy->id)) {
                $checkOffice = \App\ShiflOffice::where('name', 'Shifl Vietnam')->first();

                if (isset($checkOffice->name)) {
                    array_push($managerOfficeFields, [
                        'office_id' => $checkOffice->id,
                        'manager_id' => intval($getTammy->id)
                    ]);

         
                    $getTammy->shifl_office_id = $checkOffice->id;
                    $getTammy->save();
                    

                }   
            }

            //get renu
            $getRenu = \App\AccountManager::where('email','renu.dham@shifl.com')->first();
            //$getRenu = \App\AccountManager::where('email','renu@test.com')->first();


            if (isset($getRenu->id)) {
                $checkOffice = \App\ShiflOffice::where('name', 'Shifl India')->first();

                if (isset($checkOffice->name)) {
                    array_push($managerOfficeFields, [
                        'office_id' => $checkOffice->id,
                        'manager_id' => intval($getRenu->id)
                    ]);

                    $getRenu->shifl_office_id = $checkOffice->id;
                    $getRenu->save();

                }   
            }

            if (count($managerOfficeFields) > 0) {

                $findCustomer->offices_managers = json_encode($managerOfficeFields);   
                $findCustomer->save();
            }

        }

        return response()->json([
            'status' => 'ok',
            'customer_id' => $customer_id,
        ]);

    }

    public function getAllNotifications(Request $request) {
        
        $total_pages = 0;
        $response = [
            'results' => []
        ];

        $notifications = \DB::table('quickbooks_push_notifications')->where('is_processed', 0)->paginate(50);
        $response['results'] = $notifications;
        
        return response()->json($response);
    }

    public function getAllBills(Request $request) {
        
        $total_pages = 0;
        $response = [
            'results' => []
        ];

        $check_user = Auth::user();
        $realm_id = $check_user->quickbookstoken->realm_id;

        $bills = \App\Bill::where('qbo_company_realmid',$realm_id)
                          ->has('billPaymentList', '>=', 1)
                          ->where(function($q) {
                                $q->where('payment_status', 1);
                                $q->orWhere('payment_status', 3);
                                return $q;
                          })
                          ->paginate(50);

        $response['results'] = $bills;

        return response()->json($response);
    }

    public function unProcessed(Request $request) {
        \DB::table('invoices')
            ->where('is_processed', 1)
            ->where('balance','<>','0.00')
            ->update(['is_processed' => 0]);
            
    }

    public function getAllInvoices(Request $request) {
        
        $total_pages = 0;
        $response = [
            'results' => []
        ];
        $realm_id = 123146157027444;
        $invoices = \App\Invoice::where('qbo_company_realmid', $realm_id)
                                ->where('is_processed', 0)
                                ->orderBy('id','desc')
                                ->paginate(50);
        $response['results'] = $invoices;
        
        return response()->json($response);
    }


    public function getAllCustomers(Request $request) {
        
        $total_pages = 0;
        $response = [
            'results' => []
        ];

        $customers = \App\Customer::paginate(50);
        $response['results'] = $customers;

        return response()->json($response);
    }


    public function getAllBugsShipment(Request $request) {
        
        $total_pages = 0;
        $response = [
            'results' => []
        ];

        //let's just select the shipment booking confirm ticked
        $shipments = Shipment::where('booking_confirmed', 1)
                             ->paginate(50);
        $response['results'] = $shipments;

        return response()->json($response);
    }

    public function processBugShipment(Request $request, $shipment_id) {
        $findShipment = Shipment::find($shipment_id);

        $newFinalSchedules = [];
        if (isset($findShipment->id)) {

            //check if the booking_confirmed is selected
            if (isset($findShipment->booking_confirmed) && $findShipment->booking_confirmed==1) {
                //check if there is selected schedule
                //but first merge schedules and schedules bookings
                //schedule merge
                $mergeSchedules = (is_array(json_decode($findShipment->schedules_group_bookings))) ? json_decode($findShipment->schedules_group_bookings, true) : [];

                $mergeToScheduleBookings = (is_array(json_decode($findShipment->schedules_group))) ? json_decode($findShipment->schedules_group, true) : [];

                $newFinalSchedules = $this->processDuplicate($mergeSchedules, $mergeToScheduleBookings);


                //check first if it's an array and check if there is selected
                $hasSelected = false;
                $selectedKey = 0;

                if (is_array($newFinalSchedules) && count($newFinalSchedules)>0) {
                    foreach($newFinalSchedules as $key => $newFinalSchedule) {
                        if (isset($newFinalSchedule['is_confirmed']) && $newFinalSchedule['is_confirmed']==1) {
                            $hasSelected = true;
                            $selectedKey = $key;
                        }
                    }

                    //if there is schedules but no selected then set the selected to the first schedule element

                    //then update that shipment
                    if ( !$hasSelected ) {
                        
                        $newFinalSchedules[0]['is_confirmed'] = 1;
                        if (isset($newFinalSchedules[0]['etd']) && $newFinalSchedules[0]['etd']!=='' && $newFinalSchedules[0]['etd']!==null && isset($newFinalSchedules[0]['eta']) && $newFinalSchedules[0]['eta']!=='' && $newFinalSchedules['eta']!==null) {

                            \DB::table('shipments')
                           ->where('id', $shipment_id)
                           ->update(
                                [
                                    'etd' => $newFinalSchedules[0]['etd'],
                                    'eta' => $newFinalSchedules[0]['eta'],
                                    'shipment_status' => 'Approved',
                                    'schedules_group_bookings' => json_encode($newFinalSchedules),
                                    'schedules_group' => json_encode($newFinalSchedules)
                                ]
                            );
                        }
                        
                    } else {

                         if (isset($newFinalSchedules[$selectedKey]['etd']) && $newFinalSchedules[$selectedKey]['etd']!=='' && $newFinalSchedules[$selectedKey]['etd']!==null && isset($newFinalSchedules[$selectedKey]['eta']) && $newFinalSchedules[$selectedKey]['eta']!=='' && $newFinalSchedules['eta']!==null) {
                            \DB::table('shipments')
                           ->where('id', $shipment_id)
                           ->update(
                                [
                                    'etd' => $newFinalSchedules[$selectedKey]['etd'],
                                    'eta' => $newFinalSchedules[$selectedKey]['eta'],
                                    'shipment_status' => 'Approved'
                                ]
                            );
                        }
                        
                    }

                }

            }


        }

        return response()->json([
            'status' => 'ok',
            'shipment_id' => $shipment_id,
            'schedules' => $newFinalSchedules
        ]);
    }



    private function processDuplicate( $test, $testSecond) {

        $newTest = [];
        if (count($test) > 0) {
            foreach ($test as $key => $value) {
                if (count($newTest) > 0) {
                    foreach($newTest as $kk => $vv) {

                        if ($vv['id']==$value['id'] && count(array_keys($value)) > count($vv)) {
                            $newTest[$kk] = $value;
                        }
                        
                    }
                } else {
                    array_push($newTest, $value);
                }
            }
        }

        if (count($testSecond) > 0) {
            foreach ($testSecond as $key => $value) {
                if (count($newTest) > 0) {
                    foreach($newTest as $kk => $vv) {
                       if ($vv['id']==$value['id'] && count(array_keys($value)) > count($vv)) {
                            $newTest[$kk] = $value;
                        }
                    }
                } else {
                    array_push($newTest, $value);
                }
            }
        }
        return $newTest;
    }

    public function testEmailView(Request $request) {

        $shipment = Shipment::find(6442);
        $findShipment = $shipment;

        //ss
        $shipmentType = $findShipment->type;

        //schedule merge
        $mergeSchedules = (is_array(json_decode($findShipment->schedules_group_bookings))) ? json_decode($findShipment->schedules_group_bookings, true) : [];

        $mergeToScheduleBookings = (is_array(json_decode($findShipment->schedules_group))) ? json_decode($findShipment->schedules_group, true) : [];

        $newFinalSchedules = $this->processDuplicate($mergeSchedules, $mergeToScheduleBookings);
        
        $selectedSchedule = [];

        if (count($newFinalSchedules)>0) {
            $newFinalSchedules = json_decode(json_encode($newFinalSchedules));    
            $has_confirmed = false;
            
            foreach ($newFinalSchedules as $key => $value) {
                
                if ($value->is_confirmed==1) {
                    $has_confirmed = true;
                    array_push($selectedSchedule, $value);
                }
            }
        }
        $carrierName = '';

        if (isset($selectedSchedule[0]) && isset($selectedSchedule[0]->carrier_name) && isset($selectedSchedule[0]->caarrier_name->id)) {
                
            $findCarrier = Carrier::find(intval($selectedSchedule[0]->carrier_name->id));

            if (isset($findCarrier->name)) {
                $carrierName = $findCarrier->name;
            }

        }
        
        //suppliers merge
        $mergeSuppliers = (is_array(json_decode($findShipment->suppliers_group_bookings))) ? json_decode($findShipment->suppliers_group_bookings, true) : [];

        $mergeToSupplierBookings = (is_array(json_decode($findShipment->suppliers_group))) ? json_decode($findShipment->suppliers_group, true) : [];

        $newFinalSuppliers = $this->processDuplicate($mergeSuppliers, $mergeToSupplierBookings);
        $newFinalSuppliers = json_decode(json_encode($newFinalSuppliers));

        //containers merge
        $mergeContainers = (is_array(json_decode($findShipment->containers_group_bookings))) ? json_decode($findShipment->containers_group_bookings, true) : [];

        $mergeToContainerBookings = (is_array(json_decode($findShipment->containers_group))) ? json_decode($findShipment->containers_group, true) : [];

        $newFinalContainers = $this->processDuplicate($mergeContainers, $mergeToContainerBookings);
        $newFinalContainers = json_decode(json_encode($newFinalContainers));

        $suppliers = $newFinalSuppliers;
        $po_number = [];
        $po_ids = [];
        
        $attachment = [];

        if (count($suppliers) > 0) {

            foreach ($suppliers as $key => $supplier) {
                if (isset($supplier->po_id) && $supplier->po_id!==null && $supplier->po_id!=='') {
                    $po = \App\PurchaseOrder::find(intval($supplier->po_id));
                    array_push($po_number,(isset($po->name)) ? $po->name : '');
                    $suppliers[$key]->po_name = (isset($po->name)) ? $po->name : '';
                } else {
                    $suppliers[$key]->po_name = (isset($supplier->po_num)) ? $supplier->po_num : '';
                    array_push($po_number, (isset($supplier->po_num)) ? $supplier->po_num : '');
                }

                if ($supplier->hbl_copy && !is_object($supplier->hbl_copy)) {
                    $path = storage_path('/app/public/'.$supplier->hbl_copy);
                    if (file_exists($path)) {
                        array_push($attachment, $path);
                    }
                }
                if ($supplier->packing_list && !is_object($supplier->packing_list)) {
                    $path = storage_path('/app/public/'.$supplier->packing_list);
                    if (file_exists($path)) {
                        array_push($attachment, $path);
                    }
                }
                if ($supplier->commercial_documents && !is_object($supplier->commercial_documents)) {
                    $path = storage_path('/app/public/'.$supplier->commercial_documents);
                    if (file_exists($path)) {
                        array_push($attachment, $path);
                    }
                }
                if ($supplier->commercial_invoice && !is_object($supplier->commercial_invoice)) {
                    $arr_file = (array)$supplier->commercial_invoice;
                    $path = storage_path('/app/public/'.$supplier->commercial_invoice);
                    if (file_exists($path)) {
                        array_push($attachment, $path);
                    }
                }

            }
        }

        $po_num_join = implode('/', $po_number);
        $containers = $newFinalContainers;
        

        if (count($newFinalSchedules) > 0) {
            
            $cheapest = 0;
            $cheapestKey = 0;
            foreach ($newFinalSchedules as $key => $newFinalSchedule) {

                if (isset($newFinalSchedule->etd) && isset($newFinalSchedule->eta)) {
                    $etd = Carbon::parse($newFinalSchedule->etd);
                    $eta = Carbon::parse($newFinalSchedule->eta);
                    $newFinalSchedules[$key]->transit = $etd->diffInDays($eta);

                } else {
                    $newFinalSchedules[$key]->transit = '';
                }
                $totalAmount = 0;
                if (isset($newFinalSchedule->sell_rates) && $newFinalSchedule->sell_rates!==null && $newFinalSchedule->sell_rates!=='' && count($newFinalSchedule->sell_rates) > 0) {
                    
                    foreach ($newFinalSchedule->sell_rates as $keySecond => $sell_rate) {
                        $totalAmount = $totalAmount + ($sell_rate->qty * $sell_rate->amount);
                        $newFinalSchedules[$key]->sell_rates[$keySecond]->total = ($sell_rate->qty * $sell_rate->amount);
                    }
                }
                if ($cheapest==0) {
                    $cheapest = $totalAmount;
                } else {
                    if ($totalAmount < $cheapest) {
                        $cheapest = $totalAmount;
                        $cheapestKey = $key;
                    }
                }
                $newFinalSchedules[$key]->total_amount = $totalAmount;
               
            }
        }


        $content = [
            "cheapest_key" => $cheapestKey,
            "type" => $shipmentType,
            "selected_schedule" => $selectedSchedule,
            "CompanyName" => "Shifl",
            "InvDate" => date("m/d/Y", strtotime(date("Y-m-d"))),
            "customer" => $shipment->customer->company_name,
            "po_number" => $po_number,
            "shifl_ref" => $shipment->shifl_ref,
            "schedule" => $newFinalSchedules,
            "status" => $shipment->shipment_status,
            "suppliers_group" => $suppliers,
            "containers_group" =>$newFinalContainers,
            //"carrier" => isset($shipment->carrier->name) ? $shipment->carrier->name : '',
            "carrier" => $carrierName,
            "memo_customer" => (isset($shipment->memo_customer)) ? $shipment->memo_customer : '',
            "vessel" => $shipment->vessel,
            "mbl_num" => $shipment->mbl_num,
            "account_representative_email" => (isset($shipment->customer)) ? (isset($shipment->customer->manager)) ? $shipment->customer->manager->email : 'No match' : 'No match',
            "account_representative_name" => (isset($shipment->customer)) ? (isset($shipment->customer->manager)) ? $shipment->customer->manager->name : 'No match' : 'No match'
            //"type" => "dn"
        ];

        return view('custom/testView', [
            'content' => $content
        ]);
    }

    public function sendEmailTest(Request $request) {

        $response = ['status' => 'ok', 'error' => null];
        $shipment = Shipment::find(134);
        $attachment = [];
        $terminalRegions = TerminalRegion::all();

        $rateSizes = [
            [
                'id' => 1,
                'name' => 20
            ],
            [
                'id' => 2,
                'name' => 40
            ],
            [
                'id' => 3,
                'name' => '40HC'
            ],
            [
                'id' => 4,
                'name' => 45
            ],
            [
                'id' => 5,
                'name' => 'Shipment'
            ],
            [
                'id' => 6,
                'name' => 'CBM'
            ],
            [
                'id' => 7,
                'name' => 'KG (chargeable)'
            ],
            [
                'id' => 8,
                'name' => 'KG'
            ]
        ];

        $services = [
            [
                'id' => 1,
                'name' => 'Freight'
            ],
            [
                'id' => 2,
                'name' => 'Customs Handling'
            ],
            [
                'id' => 3,
                'name' => 'ISF'
            ],
            [
                'id' => 4,
                'name' => 'Trucking'
            ],
            [
                'id' => 5,
                'name' => 'Handling Fee'
            ]
        ];

        $schedules = [];
        $containers = [];
        $suppliers = [];

        $subject = '';
        if (isset($shipment->id)) {

            //sales representative
            $checkSalesRepresentative = (isset($shipment->customer) && isset($shipment->customer->salesRepresentative)) ? $shipment->customer->salesRepresentative : 'Not available';

            //$subject = $air_text . "Departure Notice: ID#". $shipment->shifl_ref . " - PO#";
            //$subject = 'Test email';

            //schedule merge
            $mergeSchedules = (is_array(json_decode($shipment->schedules_group))) ? json_decode($shipment->schedules_group) : [];

            $mergeToScheduleBookings = (is_array(json_decode($shipment->schedules_group_bookings))) ? json_decode($shipment->schedules_group_bookings) : [];

            $newSchedules = [];

            if(count($mergeSchedules) > 0) {
                foreach($mergeSchedules as $mergeSchedule) {
                    
                    $hasSimilarity = false;
                    if (count($mergeToScheduleBookings) > 0) {
                        
                        foreach($mergeToScheduleBookings as $mergeToScheduleBooking) {
                            if (isset($mergeToScheduleBooking->id) && isset($mergeSchedule->id)) {
                                if ($mergeToScheduleBooking->id==$mergeSchedule->id) {
                                    $hasSimilarity = true;
                                }
                            }
                        }
                    }

                    if (!$hasSimilarity) {
                        array_push($newSchedules, $mergeSchedule);
                    }
                }
            }

            if (count($newSchedules) > 0) {
                $schedules = array_merge($newSchedules, $mergeToScheduleBookings);
            } else {
                $schedules = $mergeToScheduleBookings;
            }


            //container merge
            $mergeContainers = (is_array(json_decode($shipment->containers_group))) ? json_decode($shipment->containers_group) : [];

            $mergeToContainerBookings = (is_array(json_decode($shipment->containers_group_bookings))) ? json_decode($shipment->containers_group_bookings) : [];

            $newContainers = [];
            if(count($mergeContainers) > 0) {
                foreach($mergeContainers as $mergeContainer) {
                    
                    $hasSimilarity = false;
                    if (count($mergeToContainerBookings) > 0) {
                        
                        foreach($mergeToContainerBookings as $mergeToContainerBooking) {
                            if (isset($mergeToContainerBooking->id) && isset($mergeContainer->id)) {
                                if ($mergeToContainerBooking->id==$mergeContainer->id) {
                                    $hasSimilarity = true;
                                }
                            }
                        }
                    }

                    if (!$hasSimilarity) {
                        array_push($newContainers, $mergeContainer);
                    }
                }
            }

            if (count($newContainers) > 0) {
                $containers = array_merge($newContainers, $mergeToContainerBookings);
            } else {
                $containers = $mergeToContainerBookings;
            }

            //suppliers
            $mergeSuppliers = (is_array(json_decode($shipment->suppliers_group))) ? json_decode($shipment->suppliers_group) : [];

            $mergeToSupplierBookings = (is_array(json_decode($shipment->suppliers_group_bookings))) ? json_decode($shipment->suppliers_group_bookings) : [];

            $newSuppliers = [];

            if(count($mergeSuppliers) > 0) {
                foreach($mergeSuppliers as $mergeSupplier) {
                    
                    $hasSimilarity = false;
                    if (count($mergeToSupplierBookings) > 0) {
                        
                        foreach($mergeToSupplierBookings as $mergeToSupplierBooking) {
                            if (isset($mergeToSupplierBooking->id) && isset($mergeSupplier->id)) {
                                if ($mergeToSupplierBooking->id==$mergeSupplier->id) {
                                    $hasSimilarity = true;
                                }
                            }
                        }
                    }

                    if (!$hasSimilarity) {
                        array_push($newSuppliers, $mergeSupplier);
                    }
                }
            }

            if (count($newSuppliers) > 0) {
                $suppliers = array_merge($newSuppliers, $mergeToSupplierBookings);
            } else {
                $suppliers = $mergeToSupplierBookings;
            }


            if (count($newSuppliers) > 0) {
                foreach ($newSuppliers as $key => $newSupplier) {

                    if ($newSupplier->hbl_copy) {
                        $path = storage_path('/app/public/'.$newSupplier->hbl_copy);
                        if (file_exists($path)) {
                            array_push($attachment, $path);
                        }
                    }
                    if ($newSupplier->packing_list) {
                        $path = storage_path('/app/public/'.$newSupplier->packing_list);
                        if (file_exists($path)) {
                            array_push($attachment, $path);
                        }
                    }
                    if ($newSupplier->commercial_documents) {
                        $path = storage_path('/app/public/'.$newSupplier->commercial_documents);
                        if (file_exists($path)) {
                            array_push($attachment, $path);
                        }
                    }
                    if ($newSupplier->commercial_invoice) {
                        $path = storage_path('/app/public/'.$newSupplier->commercial_invoice);
                        if (file_exists($path)) {
                            array_push($attachment, $path);
                        }
                    }
                }

            }


            $isAir = false;
            $airText = '';
            $selectedSchedule = null;
            $selected = false;
            //get now our selected schedule
            if ( count($schedules)>0 ) {
                foreach($schedules as $schedule) {
                    if (!$selected && isset($schedule->is_confirmed) && $schedule->is_confirmed==1) {
                        
                        $selectedSchedule = $schedule;

                        if ($selectedSchedule->mode=='Air') {
                            $isAir = true;
                            $airText = 'Air ';
                        } else {
                            if ($shipment->type=='Air') {
                                $isAir = true;
                                $airText = 'Air ';
                            }
                        }

                        $selected = true;
                    }
                }

                //if there is still no selected schedule then let's try to check the status of the shipment
                if (!$selected && $shipment->booking_confirmed==1) {

                    //if the booking is confirmed then let's auto select the first schedule added
                    $selectedSchedule = $schedules[0];

                    if ($selectedSchedule->mode=='Air') {
                        $isAir = true;
                        $airText = 'Air ';
                    } else {
                        if ($shipment->type=='Air') {
                            $isAir = true;
                            $airText = 'Air ';
                        }
                    }

                }
            }

            $subject = sprintf('%s Departure Notice: ID#%s - PO#',$airText,$shipment->shifl_ref);

            $content = [
                'CompanyName' => 'Shifl',
                'InvDate' => date('m/d/Y', strtotime(date('Y-m-d'))),
                'shifl_ref' => (isset($shipment->shifl_ref)) ? $shipment->shifl_ref : '',
                'customer' => (isset($shipment->customer) && isset($shipment->customer->company_name)) ? $shipment->customer->company_name: 'Customer',
                'status' => $shipment->shipment_status,
                'carrier' => (isset($shipment->carrier) && isset($shipment->carrier->name)) ? $shipment->carrier->name : '',
                'salesRepresentative' => $checkSalesRepresentative,
                'vessel' => $shipment->vessel,
                'mbl_num' => $shipment->mbl_num,
                'type' => 'dn',
                'schedule' => $selectedSchedule,
                'suppliers_group' => $suppliers,
                'containers_group' => $containers
            ];


            //ss
            /*
            $content = [
            "CompanyName" => "Shifl",
            "InvDate" => date("m/d/Y", strtotime(date("Y-m-d"))),
            "customer" => $shipment->customer->company_name,
            "shifl_ref" => $shipment->shifl_ref,
            "schedule" => json_decode($shipment->schedules_group),
            "status" => $shipment->shipment_status,
            "suppliers_group" =>json_decode($event->resource->suppliers_group),
            "containers_group" =>json_decode($event->resource->containers_group),
            "carrier" => isset($shipment->carrier->name) ? $shipment->carrier->name : '',
            "vessel" => $shipment->vessel,
            "mbl_num" => $shipment->mbl_num,
            "type" => "dn"
            ];*/
            //ee
            $to = 'kenjos75@yahoo.com';
            //$to = 'shabsie@shifl.com';

            try {
                //Mail::to($to)->send(new TestMail($subject, $content, $attachment));
                 Mail::to($to)->send(new TestNewNewMail($subject, $content, $attachment));
            }catch(Exception $e) {
                $response['error'] = $e;
                $response['status'] = 'not ok';
            }   
        }
        

        return response()->json($response);
    }

    public function processSingleShipment(Request $request, $shipment_id) {

        $response = [
            'status' => 'not ok',
            'message' => ''
        ];

        $checkShipment = Shipment::where('id', $shipment_id)
                                 ->first();

        $noBookings = false;
        $foundShipment = false;
        if (isset($checkShipment->id)) {

            $foundShipment = true;
            $checkSchedulesBookings = $checkShipment->schedules_group_bookings;

            if ($checkSchedulesBookings=='') {
                $noBookings = true;
            } else {
                $checkSchedulesBookings = json_decode($checkShipment->schedules_group_bookings);
                if (count($checkSchedulesBookings)==0) {
                    $noBookings = true;
                }
            }

            if ($noBookings) {
                $checkSchedules = json_decode($checkShipment->schedules_group);

                if (isset($checkSchedules[0])) {
                    $updateSchedules = $checkSchedules;

                    foreach($updateSchedules as $key => $updateSchedule) {

                        if ($key==0) {
                            $updateSchedules[$key]->legs = [];
                            $updateSchedules[$key]->type = '';
                            $updateSchedules[$key]->carrier_name = '';
                            $updateSchedules[$key]->size_id = 0;
                            $updateSchedules[$key]->price = null;
                            $updateSchedules[$key]->selling_size_id = 0;
                            $updateSchedules[$key]->selling_price = null;
                            $updateSchedules[$key]->sell_rates = [];
                            $updateSchedules[$key]->buy_rates = [];
                            $updateSchedules[$key]->is_confirmed = ($checkShipment->booking_confirmed==1) ? 1 : 0;

                            $checkFromTerminal = TerminalRegion::find(intval($updateSchedule->location_from));

                            if (isset($checkFromTerminal->name)) {
                                $checkFromTerminal = $checkFromTerminal->name;
                            } else {
                                $checkFromTerminal = '';
                            }

                            $checkToTerminal = TerminalRegion::find(intval($updateSchedule->location_to));

                            if (isset($checkToTerminal->name)) {
                                $checkToTerminal = $checkToTerminal->name;
                            } else {
                                $checkToTerminal = '';
                            }

                            $updateSchedules[$key]->location_from_name = $checkFromTerminal;
                            $updateSchedules[$key]->location_to_name = $checkToTerminal;

                            $updateSchedules[$key]->size_name = '';
                            $updateSchedules[$key]->selling_size_name = '';

                            \DB::table('shipment_schedules')
                            ->where('unique_id', $updateSchedule->id)
                            ->update(
                            [
                                'is_new' => 1
                                ]
                            );

                        }
                        
                    }

                    \DB::table('shipments')
                       ->where('id', $shipment_id)
                       ->update(
                        [
                            'schedules_group_bookings' => json_encode($updateSchedules)
                        ]
                    );

                }
            }

        }


        return response()->json([
            'shipment_id' => $shipment_id,
            'no_bookings' => $noBookings,
            'found_shipment' => $foundShipment
        ]);
    }
    public function getAll(Request $request) {
        
        $response = [];

        //get all shipments
        $getAllShipments = Shipment::all();

        //if there are shipments
        if (count ($getAllShipments) > 0) {

            //look for the shipment with schedules that are old
            //$checkAllSchedules =  \DB::table('shipment_schedules')->where('is_new', 0)->paginate(10);
            $checkAllSchedules = \DB::table('shipment_schedules')
                                 ->where('is_new', 0)
                                 ->get();

            $response = $checkAllSchedules;
        }
        return response()->json($response);

    }


    public function synchronize(Request $request) {


    }

    public function processBugView(Request $request) {
        return view('custom/bugs');
    }


    public function process(Request $request) {
        return view('custom/process');
    }
}
