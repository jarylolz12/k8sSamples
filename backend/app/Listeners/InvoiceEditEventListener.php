<?php


namespace App\Listeners;

use App\Events\InvoiceEditEvent;
use Illuminate\Support\Facades\DB;
use Exception;
use Illuminate\Support\Facades\Log;
use QuickBooksOnline\API\DataService\DataService;
use App\User;
use App\Invoice;
use QuickBooksOnline\API\Core\OAuth\OAuth2\OAuth2LoginHelper;
use QuickBooksOnline\API\Facades\Payment;

class InvoiceEditEventListener
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param InsertBrexEvent $event
     * @return void
     * @throws Exception
     */
    public function handle(InvoiceEditEvent $event)
    {
        try {
            $quickBooksAccountEmail = env('QUICKBOOKS_ACCOUNT_EMAIL', 'shabsie@shifl.com');
            $get_user = User::where('email', $quickBooksAccountEmail)->first();
            $realm_id = env('QUICKBOOKS_COMPANY_REALMID', 123146157027444);
            $oauth2LoginHelper = new OAuth2LoginHelper(config('quickbooks.data_service.client_id'), config('quickbooks.data_service.client_secret'));
            $accessTokenObj = $oauth2LoginHelper->
            refreshAccessTokenWithRefreshToken($get_user->quickbookstoken->refresh_token);
            $accessTokenValue = $accessTokenObj->getAccessToken();
            $refreshTokenValue = $accessTokenObj->getRefreshToken();

            DB::table('quickbooks_tokens')->where('user_id', $get_user->id)
                ->update([
                    'access_token' => $accessTokenValue,
                    'refresh_token' => $refreshTokenValue
                ]);

            $dataService = DataService::Configure([
                'auth_mode' => 'oauth2',
                'ClientID' => config('quickbooks.data_service.client_id'),
                'ClientSecret' => config('quickbooks.data_service.client_secret'),
                'accessTokenKey' => $accessTokenValue,
                'refreshTokenKey' => $refreshTokenValue,
                'QBORealmID' => $get_user->quickbookstoken->realm_id,
                'baseUrl' => config('quickbooks.data_service.base_url')
            ]);

            $resource = $event->resource;
            $paymentMethodId = $event->resource->payment_method;
            $processDuty = $event->resource->process_duty;
            $paymentMethodProcessor = $event->resource->payment_method_processor;
            $paid_on = $event->resource->paid_on;

            $build_lines = [];
            $totalAmt = $customerRef = 0;
            $qboIds = [];
            $processingFee = 0;
            foreach ($resource as $key => $value) {
                $invoice = $dataService->FindById("Invoice", $value['qbo_id']);
                Log::debug(__FUNCTION__ . ":" . __LINE__ . ":QBO Invoice: " . json_encode($invoice));
                if (isset($invoice->DocNumber) && $realm_id == $value['qbo_company_realmid']) {
                    $check_invoice = $invoice;
                    if (isset($check_invoice->Id)) {
                        if($paymentMethodProcessor !== 'CC'){
                            $totalAmt += $check_invoice->Balance;
                            $invoiceBalance = $check_invoice->Balance;
                        }else{
                            $totalDutyAmount = $value['total_duty_amount'];
                            $invoiceAmount = $value['balance'] ?: 0;
                            if($processDuty){
                                $processingFee += floatval(((3.2 / 100) * $totalDutyAmount));
                                $totalAmt = round(floatval($totalAmt + $invoiceAmount), 2);
                                $invoiceBalance = round(floatval($invoiceAmount), 2);
                            }else{
                                $totalAmt = round(($totalAmt + ($invoiceAmount - $totalDutyAmount)), 2);
                                $invoiceBalance = round(floatval($invoiceAmount - $totalDutyAmount), 2);
                            }
                        }

                        $customerRef = $check_invoice->CustomerRef;
                        array_push($build_lines, [
                            'Amount' => $invoiceBalance,
                            'LinkedTxn' => [
                                'TxnId' => $check_invoice->Id,
                                'TxnType' => 'Invoice'
                            ]
                        ]);
                        $qboIds[$value['qbo_id']] = $value['remaining_balance'];
                    }
                }
            }

            Log::debug(__FUNCTION__ . ":" . __LINE__ . ":QBO IDs". json_encode($qboIds));
            Log::debug(__FUNCTION__ . ":" . __LINE__ . ":QBO Processing Fees: " . $processingFee);
            Log::debug(__FUNCTION__ . ":" . __LINE__ . ":QBO Total Amount: " . $totalAmt);
            Log::debug(__FUNCTION__ . ":" . __LINE__ . ":QBO Build Lines: " . json_encode($build_lines));

            $privateNote = 'Transaction from Shifl';
            if($processingFee > 0){
                $privateNote .= "\nTotal Processing Fee :". $processingFee;
            }
            //create payment
            $ob = Payment::create([
                "PrivateNote" => $privateNote,
                'TotalAmt' => $totalAmt,
                'CustomerRef' => [
                    'value' => $customerRef
                ],
                'Line' => $build_lines,
                "CustomField" => [
                    "DefinitionId" => "1",
                    "StringValue" => "shifl_app",
                    "Type" => "StringType",
                    "Name" => "source"
                ]
            ]);
            $dataService->Update($ob);

            foreach ($qboIds as $id => $balance){
                Invoice::where('qbo_id', $id)->update([
                    "balance" => $balance,
                    "payment_method" => $paymentMethodId,
                    "payment_method_processor" => $paymentMethodProcessor,
                    "paid_on" => $paid_on
                ]);
                Log::debug(__FUNCTION__ . " : " . __LINE__ . " : Invoice with qbo_id ".$id." Updated with balance: $balance");
            }
        } catch (Exception $exception) {
            Log::debug(__FUNCTION__ . " : " . __LINE__ . " : Error while quickbooks payment create and update invoices: " . $exception->getMessage());
        }
    }
}
