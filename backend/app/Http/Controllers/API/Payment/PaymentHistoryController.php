<?php

namespace App\Http\Controllers\API\Payment;

use App\Card;
use App\Http\Controllers\Controller;
use App\Http\Controllers\API\Quickbooks\QuickbooksFactory;
use App\Customer;
use App\PaymentMethod;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Helpers\Helper;
use PDF;
use Exception;
use App\Invoice;

/**
 * @authenticated
 *
 *
 * @group Payment History
 *
 * Class PaymentHistoryController
 * @package App\Http\Controllers\API\Payment
 */

class PaymentHistoryController extends Controller
{
    protected $details;

    /**
     * Download Receipt
     *
     * @response status=200 {
     *      "status": "downloadable files"
     * }
     *
     * @response status=401 {
     *     "message": "Unauthenticated."
     * }
     *
     * @param $defaultCustomerId
     * @param $paymentId
     * @return \Illuminate\Http\JsonResponse
     */
    public function downloadReceipt($defaultCustomerId, $paymentId)
    {
        try {
            $dataService = QuickbooksFactory::quickbooksDataService();
            Log::debug("dataService:: " . json_encode($dataService));
            if (!$dataService)
                throw new Exception("Something went wrong.", 500);

            $payment = $dataService->FindById("Payment", $paymentId);
            Log::debug(__FUNCTION__ . " : " . __LINE__ . " : Payment query response ==" . json_encode($payment));

            $cardIcon = $cardNumber = $cardHolderName = $cardExp = $imgClass = '';
            $this->details['invoices'] = [];
            if ($payment) {
                $qboIds = [];
                if ($payment->Line && is_array($payment->Line)) {
                    foreach ($payment->Line as $invoiceKey => $invoiceValue) {
                        $qboIds[] = $invoiceValue->LinkedTxn ? $invoiceValue->LinkedTxn->TxnId ?? '-1' : '-1';
                    }
                } else {
                    if ($payment->Line !== null)
                        $qboIds[] = $payment->Line->LinkedTxn ? $payment->Line->LinkedTxn->TxnId ?? '-1' : '-1';
                }
                $invoices = Invoice::whereIn('qbo_id', $qboIds)->get();
                if (count($invoices) > 0) {
                    if ($invoices[0]['payment_method_processor'] == "ACH") {
                        $paymentMethod = PaymentMethod::where('default_customer_id', $defaultCustomerId)
                            ->where('id', $invoices[0]['payment_method'])
                            ->first();
                        if ($paymentMethod) {
                            $cardNumber = $paymentMethod->name;
                            $cardIcon = 'ach-icon';
                            $cardHolderName = 'Acc ' . Helper::accountNumberMasked($paymentMethod->account);
                            $cardExp = '';
                        }
                    } else {
                        $getCard = Card::where('default_customer_id', $defaultCustomerId)
                            ->where('id', $invoices[0]['payment_method'])
                            ->first();
                        if ($getCard) {
                            $cardNumber = $getCard->number_masked;
                            $cardHolderName = $getCard->first_name;
                            $cardExp = ', ' . sprintf("%02d", $getCard->expiration_month) . '/' . $getCard->expiration_year;

                            $cardType = strtolower($getCard->type);
                            switch ($cardType) {
                                case "visa":
                                    $cardIcon = "visa-icon";
                                    break;
                                case "mastercard":
                                    $cardIcon = 'master-icon';
                                    break;
                                case "amex":
                                    $cardIcon = 'amex-icon';
                                    break;
                            }
                        }
                    }
                }
                $this->details['invoices'] = $invoices;
            }
            $this->details['cardIcon'] = $cardIcon ? $cardIcon . '.png' : null;
            $this->details['cardNumber'] = $cardNumber ?? null;
            $this->details['cardHolderName'] = $cardHolderName ?? '-';
            $this->details['cardExp'] = $cardExp ?? '-';
            $this->details['paymentData'] = $payment;
            $this->details['transactionDate'] = Helper::dateFormatFrontend(($payment->MetaData) ? ($payment->MetaData->CreateTime ?? '') : '');
            $this->details['transactionTime'] = Helper::timeFormatFrontend(($payment->MetaData) ? ($payment->MetaData->CreateTime ?? '') : '');

            $pdf = PDF::loadView('payment-history.receipt', $this->details);
            return $pdf->download('payment-history-receipt-' . rand(111, 999) . '.pdf');
        } catch (Exception $e) {
            Log::Error(__FUNCTION__ . ":" . __LINE__ . ": " . $e->getMessage());
            return response()->json([
                "status" => 'error',
                "message" => "Something went wrong",
                "code" => $e->getCode() ?? 400
            ]);
        }
    }

    /**
     * Get Payment History
     *
     * @urlParam default_customer_id int required
     *
     * @response status=200 {
     *      "status": "error",
     *      "message": "Can't get OAuth 2 Client ID. It is not set.",
     *      "code": 500
     *  }
     *
     *
     * @response status=401 {
     *     "message": "Unauthenticated."
     * }
     *
     * @param Request $request
     * @param $defaultCustomerId
     * @return \Illuminate\Http\JsonResponse
     */
    public function getPaymentHistory(Request $request, $defaultCustomerId)
    {
        try {
            $customer = Customer::where('id', $defaultCustomerId)->first();
            if (!$customer)
                throw new Exception("Default customer id not found while connecting Quickbooks.", 404);

            if (!$customer->qbo_customer || $customer->qbo_customer == '')
                throw new Exception("QBO customer not found..", 404);

            $qboCustomer = json_decode($customer->qbo_customer);
            if (empty($qboCustomer->customer) || !$qboCustomer->customer || !$qboCustomer->customer->Id)
                throw new Exception("QBO customer Id not found while getting QBO customer details.", 404);

            $dataService = QuickbooksFactory::quickbooksDataService();
            Log::debug("dataService:: " . json_encode($dataService));
            if (!$dataService)
                throw new Exception("Something went wrong.", 500);

            $customerRef = $qboCustomer->customer->Id;
            $startPosition = !empty($request->offset)? $request->offset : 1;
            $maximumResultPerPage = !empty($request->limit)? $request->limit : 10;
            Log::debug(__FUNCTION__ . " : " . __LINE__ . " : customerRef ==" . $customerRef);

            $query = "Select * From Payment WHERE CustomerRef = '$customerRef' STARTPOSITION $startPosition MAXRESULTS $maximumResultPerPage";
            $payments = $dataService->Query($query);
            Log::debug(__FUNCTION__ . " : " . __LINE__ . " : limit with get data query::" . $query);
            Log::debug(__FUNCTION__ . " : " . __LINE__ . " : limit with get data query Result::" . json_encode($payments));

            $queryCount = "Select COUNT(*) From Payment WHERE CustomerRef = '$customerRef'";
            $totalRecords = $dataService->Query($queryCount);
            Log::debug(__FUNCTION__ . " : " . __LINE__ . " : count data query::" . $queryCount);
            Log::debug(__FUNCTION__ . " : " . __LINE__ . " : count data query result::" . json_encode($totalRecords));

            $paymentHistoryArray = [];
            if($payments) {
                Log::debug(__FUNCTION__ . " : " . __LINE__ . " : Inside IF::" . json_encode($payments));
                foreach ($payments as $key => $value) {
                    $qboIds = $invoices = [];
                    $cardIcon = $cardNumber = $cardHolderName = $cardExp = $imgClass = '';

//                    if ($value->Line == null)
//                        continue;

                    if ($value->Line && is_array($value->Line)) {
                        foreach ($value->Line as $invoiceKey => $invoiceValue) {
                            $qboIds[] = $invoiceValue->LinkedTxn ? $invoiceValue->LinkedTxn->TxnId ?? '-1' : '-1';
                        }
                    } else {
                        if ($value->Line !== null)
                            $qboIds[] = $value->Line->LinkedTxn ? $value->Line->LinkedTxn->TxnId ?? '-1' : '-1';
                    }

                    $invoices = Invoice::whereIn('qbo_id', $qboIds)->get();
                    Log::debug(__FUNCTION__ . " : " . __LINE__ . " : qboIds::" . json_encode($qboIds));
                    Log::debug(__FUNCTION__ . " : " . __LINE__ . " : invoices collection::" . json_encode($invoices));
                    if (count($invoices) > 0) {
                        if ($invoices[0]['payment_method_processor'] == "ACH") {
                            $paymentMethod = PaymentMethod::where('default_customer_id', $defaultCustomerId)
                                ->where('id', $invoices[0]['payment_method'])
                                ->first();
                            if ($paymentMethod) {
                                $cardNumber = $paymentMethod->name;
                                $cardIcon = 'ach';
                                $cardHolderName = 'Acc ' . Helper::accountNumberMasked($paymentMethod->account);
                                $cardExp = '';
                            }
                        } else {
                            $getCard = Card::where('default_customer_id', $defaultCustomerId)
                                ->where('id', $invoices[0]['payment_method'])
                                ->first();
                            if ($getCard) {
                                $cardNumber = $getCard->number_masked;
                                $cardHolderName = $getCard->first_name;
                                $cardExp = ', ' . sprintf("%02d", $getCard->expiration_month) . '/' . $getCard->expiration_year;

                                $cardType = strtolower($getCard->type);
                                switch ($cardType) {
                                    case "visa":
                                        $cardIcon = "visa-with-border";
                                        break;
                                    case "mastercard":
                                        $cardIcon = 'mastercard';
                                        break;
                                    case "amex":
                                        $cardIcon = 'amex-with-border';
                                        break;
                                }
                            }
                        }
                    }

                    $transactionTime = ($value->MetaData) ? ($value->MetaData->CreateTime ?? '') : '';

                    $paymentHistoryArray[] = [
                        'receipt_no' => $value->Id,
                        'CustomerRef' => $value->CustomerRef,
                        'amount' => $value->TotalAmt,
                        'date_time' => Helper::dateFormatFrontend($transactionTime),
                        'transactionTime' => Helper::timeFormatFrontend($transactionTime),
                        'PrivateNote' => $value->PrivateNote,
                        'card_icon' => ($cardIcon !== '') ? $cardIcon . '.svg' : null,
                        'card_number' => $cardNumber ?? null,
                        'card_holder_name' => ($cardHolderName == '') ? '-' : $cardHolderName,
                        'card_exp' => ($cardExp == '') ? '-' : $cardExp,
                        'invoice_list' => $invoices,
                        "invoice_reference" => Invoice::whereIn('qbo_id', $qboIds)->pluck('invoice_num'),
                        "row_data" => json_encode($value),
                    ];
                    Log::debug(__FUNCTION__ . " : " . __LINE__ . "Id:: " . $value->Id . " : qboIds ==" . json_encode($qboIds) . " : " . json_encode($invoices) . " : paymentHistoryArray ::" . json_encode($paymentHistoryArray));
                }
            }

            $response = [
                "status" => 'success',
                "data" => $paymentHistoryArray,
                "totalRecords" => $totalRecords,
                "code" => 200
            ];
        } catch (Exception $e) {
            Log::error(__FUNCTION__ . ":" . __LINE__ . ", Error Code:: " . $e->getCode() . ", Error Message : " . $e->getMessage());
            $response = [
                "status" => 'error',
                "message" => $e->getMessage(),
                "code" => ($e->getCode() && $e->getCode() !== 0) ? $e->getCode() : 400
            ];
        }
        return response()->json($response);
    }
}
