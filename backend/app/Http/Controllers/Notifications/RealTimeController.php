<?php

namespace App\Http\Controllers\Notifications;

use Illuminate\Http\Request;
use App\Http\Resources\Standard as StandardResource;
use Illuminate\Support\Collection;
use App\User;
use QuickBooksOnline\API\DataService\DataService;
use Illuminate\Support\Facades\Auth;
use QuickBooksOnline\API\Core\OAuth\OAuth2\OAuth2LoginHelper;
use App\Invoice;
use App\Http\Controllers\Notifications\BaseController;
use Illuminate\Support\Facades\Validator;
use Carbon\Carbon;
use Illuminate\Support\Facades\Storage;
use App\InvoiceAttachment;
use App\Customer;

class RealTimeController extends BaseController
{

    public function updateWebhooks(Request $request) {

        $secret = 'secr3tHere10123!';
        
        $validator = Validator::make($request->all(), [
            'secret' => ['required'],
            'notification' => ['required'],
        ]);

        if ( $validator->fails() ) {
            return false;
        }

        $args = $request->all();

        //realm id live
        $realm_id = '123146157027444';

        if ( $args['secret'] == $secret ) {
            $notification = json_decode($args['notification']);
            $notifications = [];
            if ( isset($notification->eventNotifications) ) {
                $notifications = $notification->eventNotifications;
                $first_notification = $notifications[0];

                if ( $first_notification->realmId === $realm_id ) {
                    $get_entities = $first_notification->dataChangeEvent->entities;
                    $first_entity = $get_entities[0];
                    $data_service = $this->getService();
        
                    if ( $first_entity->name === 'Invoice' && $first_entity->operation === 'Update') {
                        
                        $checkInvoice = $data_service->FindById("Invoice", $first_entity->id);
                        if (isset($checkInvoice->TotalAmt) && isset($checkInvoice->Balance)) {
        
                            //update invoice data in the system
                            $updateInvoice = Invoice::where('qbo_id', intval($first_entity->id))
                                                    ->where('qbo_company_realmid', $realm_id)
                                                    ->first();
                            $updateInvoice->total_amount = $checkInvoice->TotalAmt;
                            $updateInvoice->balance = $checkInvoice->Balance;
                            $updateInvoice->save();
                        }
                    } elseif ( $first_entity->name === 'Customer' && $first_entity->operation === 'Update') {
                        
                        //check quickbooks customer record
                        $checkCustomer = $data_service->FindById("Customer", $first_entity->id);
                        if ( isset($checkCustomer->PrimaryEmailAddr) && isset($checkCustomer->PrimaryEmailAddr->Address)) {
                            
                            //get customer's qb email address
                            $setEmailAddress = $checkCustomer->PrimaryEmailAddr->Address;
        
                            //get company info
                            $companyInfo = $data_service->getCompanyInfo();
                            
                            //check all invoices under that customer that were still not sent and update those email addresses
                            //avoid the invoice observer
                            \DB::table('invoices')->where('qbo_customer_id', intval($first_entity->id))
                                                ->where(function ($q) {
                                                    $q->where('is_email_sent', 0);
                                                    $q->orWhereNull('email_sent_at');
                                                })
                                                ->where('qbo_company_realmid',intval($realm_id))
                                                ->update([
                                                    'qbo_bill_to_email' => $setEmailAddress
                                                ]);
        
                            //check local customer via customer id and realm_id
                            $checkLocalCustomers = Customer::whereJsonContains('qbo_customer->customer->Id', strval($first_entity->id))
                                                        ->whereJsonContains('qbo_customer->realm_id', strval($realm_id))
                                                        ->get();
        
                            if ( count($checkLocalCustomers) > 0 ) {
                                
                                $checkLocalCustomersIds = $checkLocalCustomers->pluck('id')
                                                                            ->toArray();

                                //build customer's meta
                                $customerMeta = [
                                    'customer' => [
                                        'Id' => $checkCustomer->Id,
                                        'DisplayName' => $checkCustomer->DisplayName,
                                        'FullyQualifiedName' => $checkCustomer->FullyQualifiedName,
                                        'Balance' => $checkCustomer->Balance,
                                        'PrimaryEmailAddr' => $checkCustomer->PrimaryEmailAddr
                                    ],
                                    'company' => $companyInfo->CompanyName,
                                    'realm_id' => $realm_id
                                ];
                                
                                //avoid customer observer
                                \DB::table('customers')->whereIn('id', $checkLocalCustomersIds)
                                                    ->update([
                                                        'qbo_customer' => json_encode($customerMeta)
                                                    ]);
                            }
                        }
        
                    } elseif ( $first_entity->name === 'Invoice' && $first_entity->operation === 'Delete') {
        
                        $deleteInvoice = Invoice::where('qbo_id', intval($first_entity->id))
                                                    ->where('qbo_company_realmid', $realm_id)
                                                    ->first();
        
                        //delete invoice in the system
                        if ( isset($deleteInvoice->id) ) {
        
                            $attachments = InvoiceAttachment::where('invoice_id',$deleteInvoice->id)->get();
                            if (count($attachments) > 0) {
                                foreach ($attachments as $attachment) {
                                    if( Storage::disk('public')->exists($attachment['file_path']) ){
                                        InvoiceAttachment::where('id',$attachment['id'])->delete();
                                        if( $attachment['is_invoice_origin'] ){
                                            $deleteOnStorage = Storage::disk('public')->delete($attachment['file_path']);
                                        }
                                    }
                                }
                            }
                            $deleteInvoice->delete();
                        }
        
                    } elseif ( $first_entity->name === 'Payment' && ($first_entity->operation === 'Create' ||$first_entity->operation === 'Update')) {
                        $checkPayment = $data_service->FindById('Payment', $first_entity->id);
                        if ( isset($checkPayment->Id) && isset($checkPayment->Line)) {
        
                            if ( isset($checkPayment->Line->LinkedTxn)) {
                                $linkedTxn = $checkPayment->Line->LinkedTxn;
                                if ( isset($linkedTxn->TxnId) && $linkedTxn->TxnType === 'Invoice' ) {
                                    $checkInvoice = $data_service->FindById("Invoice", $linkedTxn->TxnId);
                                    if ( isset($checkInvoice->DocNumber) ) {
                                        $updateInvoice = Invoice::where('qbo_id', intval($linkedTxn->TxnId))
                                                                ->where('qbo_company_realmid', $realm_id)
                                                                ->first();
                                        $updateInvoice->total_amount = $checkInvoice->TotalAmt;
                                        $updateInvoice->balance = $checkInvoice->Balance;
                                        $updateInvoice->paid_on = Carbon::now();
                                        $updateInvoice->save();
                                    }
                                }
                            }
                        }
        
                    } elseif ( $first_entity->name === 'BillPayment' && $first_entity->operation === 'Create' ) {
        
                        $check_bill_payment = $data_service->FindById('BillPayment', $first_entity->id);
        
                        if ( isset($check_bill_payment->Id) && isset($check_bill_payment->Line)) {
        
                            if ( isset($check_bill_payment->Line->LinkedTxn)) {
        
                                $linked_txn = $check_bill_payment->Line->LinkedTxn;
        
                                $checkBill = \App\Bill::where('qbo_bill_id', abs($linked_txn->TxnId))->where(function($q) {
                                        $q->where('payment_status', 1);
                                        $q->orWhere('payment_status', 3);
                                        return $q;
                                    })
                                    ->where('qbo_company_realmid', abs($realm_id))
                                    ->first();
        
        
                                if ( isset($checkBill->id)) {
                                    //check if part of bill paylists
                                    $checkBillPayList = \App\BillPaymentList::where('bill_id', $checkBill->id)->first();
        
                                    if ( isset($checkBillPayList->id)) {
        
                                        if ( $checkBill->qbo_company_realmid === abs($realm_id)) {
        
                                            $qbill_obj = $data_service->FindById("Bill", $checkBill->qbo_bill_id);
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
                                                $checkBill->save();
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