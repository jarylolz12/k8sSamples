<?php

namespace App\Http\Controllers\API\Payment;

use App\Card;
use App\PaymentMethod;
use App\Customer;
use App\Events\InvoiceEditEvent;
use App\Http\Controllers\API\PaymentMethod\CardknoxFactory;
use App\Http\Controllers\API\Poynt\PoyntFactory;
use App\Invoice;
use App\Http\Controllers\Controller;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Carbon\Carbon;

/**
 * @authenticated
 *
 * @group Make Payment
 *
 * Class MakePaymentController
 * @package App\Http\Controllers\API\Payment
 */
class MakePaymentController extends Controller
{
    protected $basicDetails;

    public function __construct()
    {
        $this->basicDetails['switchCardLimit'] = 25000;
    }

    /**
     * Charge Payment
     *
     * @bodyParam default_customer_id int required. Example: 2
     * @bodyParam card_id int required. Example: 81
     * @bodyParam ACH_id int required
     * @bodyParam invoice_ids array required
     * @bodyParam all string required. Example: true
     *
     *
     *
     * @response status=401 {
     *     "message": "Unauthenticated."
     * }
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     *
     */
    public function chargePayment(Request $request)
    {
        Log::debug(__FUNCTION__ . ":" . __LINE__ . ":<----------------- Charge Payment Log Starts ------------------>");
        try {
            $statusCode = 400;
            $error = $this->chargePaymentRequestValidation($request);
            if ($error !== true)
                throw new Exception($error, $statusCode);
            $paymentThroughCC = false;
            Log::debug(__FUNCTION__ . ":" . __LINE__ . ": " . json_encode($request->All()));
            if ($request->input('card_id') !== 0) {
                $paymentThroughCC = true;
                Log::debug(__FUNCTION__ . ":" . __LINE__ . ": Payment By: Credit card");
                $getCard = Card::where('default_customer_id', $request->input('default_customer_id'))
                    ->where('id', $request->input('card_id'))
                    ->first();
                if (!isset($getCard->id))
                    throw new Exception("No payment method found with given card id", 404);
                $paymentMethodId = $getCard->id;
            } else {
                Log::debug(__FUNCTION__ . ":" . __LINE__ . ": Payment By: ACH");
                $paymentMethod = PaymentMethod::where('default_customer_id', $request->input('default_customer_id'))
                    ->where('id', $request->input('ACH_id'))
                    ->first();
                if (!isset($paymentMethod->id))
                    throw new Exception("No payment method found with given Payment method id.", 400);
                $paymentMethodId = $paymentMethod->id;
            }
            $get_authenticated_user = Auth::user();
            $customers = Auth::user()->customersApi->pluck('id');
            $customer = ($get_authenticated_user->default_customer_id !== null) ? $get_authenticated_user->default_customer_id : $customers[0];

            $check_customer = Customer::findOrFail($customer);
            $check_qbo_customers = [$check_customer->qbo_customer];
            $final_qbo_customers = $qbo_company_realmid = [];

            if (count($check_qbo_customers) > 0) {
                foreach ($check_qbo_customers as $check_qbo_customer) {
                    if ($check_qbo_customer !== null && $check_qbo_customer !== '') {
                        $obj = json_decode($check_qbo_customer);
                        if (isset($obj->customer)) {
                            if (isset($obj->customer->Id)) {
                                array_push($final_qbo_customers, intval($obj->customer->Id));
                            }
                            if (isset($obj->realm_id)) {
                                array_push($qbo_company_realmid, $obj->realm_id);
                            }
                        }
                    }
                }
            }

            $invoices = Invoice::where('is_email_sent', 1)
                ->whereIn('qbo_company_realmid', $qbo_company_realmid)
                ->where('balance', '<>', 0)
                ->whereIn('qbo_customer_id', $final_qbo_customers);

            if ($request->input('all') === true) {
                $invoices = $invoices->get();
            } else {
                $invoices = $invoices->whereIn('id', $request->input('invoice_ids'))->get();
            }
            if (count($invoices) <= 0)
                throw new Exception("No invoice found with given invoice id's.", 400);

            Log::debug(__FUNCTION__ . ":" . __LINE__ . ": Invoice Count: " . count($invoices));
            $invoiceNumber = '';
            $totalAmount = 0;
            foreach ($invoices as &$invoice) {
                Log::debug(__FUNCTION__ . ":" . __LINE__ . ":---------------------------------");
                Log::debug(__FUNCTION__ . ":" . __LINE__ . ": Invoice Id: " . $invoice->id);
                Log::debug(__FUNCTION__ . ":" . __LINE__ . ": Invoice Number: " . $invoice->invoice_num);
                Log::debug(__FUNCTION__ . ":" . __LINE__ . ": Invoice Balance: " . $invoice->balance);
                Log::debug(__FUNCTION__ . ":" . __LINE__ . ": Payment Through CC: " . ($paymentThroughCC ? 'yes' : 'no'));
                if(!$paymentThroughCC){
                    $totalAmount = round($totalAmount + (($invoice->balance)?: 0), 2);
                    $invoice->remaining_balance = 0;
                }else{
                    $totalDutyAmount = $invoice->total_duty_amount;
                    $invoiceAmount = $invoice->balance ?: 0;
                    Log::debug(__FUNCTION__ . ":" . __LINE__ . ": Total Duty Amount: " . $totalDutyAmount);
                    Log::debug(__FUNCTION__ . ":" . __LINE__ . ": Process Duty: " . $request->input('process_duty'));
                    if($request->input('process_duty')){
                        $processingFee = floatval(((3.2 / 100) * $totalDutyAmount));
                        $totalAmount = round(($totalAmount + $invoiceAmount + $processingFee), 2);
                        $invoice->remaining_balance = 0;
                        Log::debug(__FUNCTION__ . ":" . __LINE__ . ": Processing Fees: " . $processingFee);
                    }else{
                        $totalAmount = round(($totalAmount + ($invoiceAmount - $totalDutyAmount)), 2);
                        $invoice->remaining_balance = $totalDutyAmount;
                    }
                }
                $invoiceNumber .= $invoice->invoice_num . ',';
                Log::debug(__FUNCTION__ . ":" . __LINE__ . ": Total Amount: " . $totalAmount);
                Log::debug(__FUNCTION__ . ":" . __LINE__ . ":---------------------------------");
            }
            Log::debug(__FUNCTION__ . ":" . __LINE__ . ": Total Amount To Charge: " . $totalAmount);
            $invoiceNumber = rtrim($invoiceNumber, ',');
            if ($request->input('all') === true)
                $invoiceNumber = 'Clear All Due';

            $paymentMethodProcessor = 'CC';
            if ($request->input('card_id') === 0) {
                $chargeACHSale = $this->chargeACHSale($paymentMethod->name, $paymentMethod->routing, $paymentMethod->account, $totalAmount, $invoiceNumber);
                if (!$chargeACHSale)
                    throw new Exception("Error while charging ACH sale Cardknox token.", 500);
                $returnData = $chargeACHSale;
                $paymentMethodProcessor = 'ACH';
            } else {
                if ($totalAmount <= $this->basicDetails['switchCardLimit'] && !empty($getCard->poynt_token)) {
                    $poyntAccessToken = PoyntFactory::getAccessToken();
                    if (empty($poyntAccessToken['accessToken']))
                        throw new Exception("Error while generating poynt access token.", 500);

                    $dollarsToCent = round(($totalAmount * 100), 2);
                    $charge = $this->chargePoyntToken($poyntAccessToken['accessToken'], $dollarsToCent, $getCard->poynt_token, $invoiceNumber);
                    if (!$charge)
                        throw new Exception("Could not process payment.", 500);
                } else {
                    $charge = $this->chargeCardknox($getCard->cardknox_token, $totalAmount, $invoiceNumber);
                    if (!$charge)
                        throw new Exception("Could not process payment through cardknox.", 500);
                }
                $returnData = $charge;
            }

            $invoices->process_duty = $request->input('process_duty');
            $invoices->payment_method = $paymentMethodId;
            $invoices->payment_method_processor = $paymentMethodProcessor;
            $invoices->paid_on = Carbon::now();
            event(new InvoiceEditEvent($invoices));

            $status = 'success';
            $statusCode = 200;
        } catch (Exception $e) {
            Log::Error(__FUNCTION__ . ":" . __LINE__ . ": " . $e->getMessage());
            $returnData = $e->getMessage();
            $status = 'error';
            $statusCode = 400;
        }
        Log::debug(__FUNCTION__ . ":" . __LINE__ . ":<----------------- Charge Payment Log Ends -------------------->");
        return response()->json(['status' => $status, 'data' => $returnData, 'code' => $statusCode]);
    }

    /**
     * @param $request
     * @return bool|string
     */
    private function chargePaymentRequestValidation($request)
    {
        $validator = Validator::make($request->all(), [
            'card_id' => ['required', 'numeric'],
            'ACH_id' => ['required', 'numeric'],
            'default_customer_id' => ['required', 'numeric'],
            'invoice_ids' => ['required'],
            'all' => ['required'],
            'process_duty' => ['required']
        ]);

        if ($validator->fails())
            return implode(",", $validator->messages()->all());

        return true;
    }
    private function chargeACHSale($xName, $xRouting, $xAccount, $xAmount, $invoiceNumber)
    {
        try {
            $payload = CardknoxFactory::checkSalePayload($xName, $xRouting, $xAccount, $xAmount);

            $response = Http::withHeaders([
                "Content-type" => "application/json",
            ])->post(CardknoxFactory::gatewayURL(), $payload);

            $result = json_decode($response);

            if (!isset($result->xResult) || $result->xResult == 'E' || empty($result->xToken))
                throw new Exception("Could not process payment through ACH sale cardknox: Error Code: $result->xErrorCode, Error: $result->xError, Invoice Number:: $invoiceNumber");

            Log::debug("ACH sale Cardknox Payment Log : " . $response->body() . ", Invoice Number:: $invoiceNumber , Amount:: $xAmount");
            return $result;
        } catch (Exception $e) {
            Log::Error(__FUNCTION__ . ":" . __LINE__ . ": " . $e->getCode() . ":" . $e->getMessage());
            return false;
        }
    }

    private function chargePoyntToken($poyntToken, $transactionAmount, $cardToken, $invoiceNumber)
    {
        try {
            $businessId = PoyntFactory::getBusinessId();
            $paymentRequestId = rand(111, 99999) . 'to' . time();
            $payload = [
                'action' => 'SALE',
                'context' => [
                    'businessId' => $businessId
                ],
                'amounts' => [
                    'transactionAmount' => $transactionAmount,
                    'orderAmount' => $transactionAmount,
                    'currency' => 'USD'
                ],
                'fundingSource' => [
                    'cardToken' => $cardToken,
                    'entryDetails' => [
                        'customerPresenceStatus' => 'ECOMMERCE',
                        'entryMode' => 'KEYED'
                    ],
                    'type' => 'CREDIT_DEBIT'
                ],
                'emailReceipt' => false,
            ];

            $response = Http::withHeaders([
                "Authorization" => sprintf('Bearer %s', $poyntToken),
                "Content-type" => "application/json",
                'Poynt-Request-Id' => $paymentRequestId
            ])
                ->post(sprintf('https://services.poynt.net/businesses/%s/cards/tokenize/charge', $businessId), $payload);

            if (!isset($response['status']) || $response['status'] != 'CAPTURED')
                throw new Exception("Could not charge poynt token: " . $response->body() . ", invoiceNumber:: " . $invoiceNumber, 500);

            Log::debug("Poynt Payment Log : " . $response->body() . ", invoiceNumber:: " . $invoiceNumber);
            return true;
        } catch (Exception $e) {
            Log::Error(__FUNCTION__ . ":" . __LINE__ . ": " . $e->getCode() . ":" . $e->getMessage());
            return false;
        }
    }

    private function chargeCardknox($xToken, $xAmount, $invoiceNumber)
    {
        try {
            $payload = CardknoxFactory::ccSalePayload($xToken, $xAmount, $invoiceNumber);

            $response = Http::withHeaders([
                "Content-type" => "application/json",
            ])->post(CardknoxFactory::gatewayURL(), $payload);

            $result = json_decode($response);

            if (!isset($result->xResult) || $result->xResult !== 'A' || $result->xStatus !== 'Approved' || empty($result->xToken))
                throw new Exception("Could not process payment through cardknox: Error Code: $result->xErrorCode, Error: $result->xError, Invoice Number:: $invoiceNumber");

            Log::debug("Cardknox Payment Log : " . $response->body() . ", Invoice Number:: " . $invoiceNumber);
            return $result->xToken;
        } catch (Exception $e) {
            Log::Error(__FUNCTION__ . ":" . __LINE__ . ": " . $e->getCode() . ":" . $e->getMessage());
            return false;
        }
    }
}
