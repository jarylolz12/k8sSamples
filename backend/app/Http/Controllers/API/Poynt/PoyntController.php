<?php

namespace App\Http\Controllers\API\Poynt;

use App\Http\Controllers\Controller;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Firebase\JWT\JWT;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use App\Card;
use App\Invoice;
use App\Customer;
use Carbon\Carbon;
use App\Events\InvoiceUpdateEvent;
use App\Rules\CheckOwnCard;
use App\Rules\IfAll;
/**
 *
 * @group Poynt
 *
 * APIs to manage the Poynt resource
 */
class PoyntController extends Controller
{

    /**
     * Display for PoyntFactory Charge Multiple Invoices
     *
     * @authenticated
     *
     * @response 200 {
     *    "status": "success"
     * }
     *
     * @response status=401 scenario="Validation error" {
     *    "errors": {
     *        "card_id": [
     *            "Please make sure to enter a valid card."
     *        ],
     *        "type": [
     *            "The type field is required."
     *        ],
     *        "all": [
     *            "The all field is required."
     *        ]
     *    }
     * }
     *
     * @response status=404 {
     *    "error": "No invoices was found."
     * }
     *
     * @response status=400 scenario="Unauthenticated" {
     *     "message": "Unauthenticated."
     * }
     *
     */


    public function tokenizeChargeMultipleInvoices(Request $request) {
        $validator = Validator::make($request->all(), [
            'card_id' => ['required', new CheckOwnCard],
            'type' => ['required'],
            'all' => ['required'],
            'invoice_ids' => [new IfAll]
        ]);

        if ($validator->fails()) {
            return response()->json(
            [
                'errors' => $validator->errors()
            ]
            ,400);
        } else {
            $get_authenticated_user = Auth::user();
            $args = $request->all();
            $customers = Auth::user()->customersApi->pluck('id');
            $customer = ( $get_authenticated_user->default_customer_id!==null ) ? $get_authenticated_user->default_customer_id : $customers[0];

            $check_customer = Customer::findOrFail($customer);
            $check_qbo_customers = [$check_customer->qbo_customer];
            $final_qbo_customers = [];

            if (count($check_qbo_customers)>0) {
                foreach($check_qbo_customers as $check_qbo_customer) {
                    if ($check_qbo_customer!==null && $check_qbo_customer!=='') {
                        $obj = json_decode($check_qbo_customer);
                        if (isset($obj->customer)) {
                            if (isset($obj->customer->Id)) {
                                array_push($final_qbo_customers, intval($obj->customer->Id));
                            }
                        }
                    }
                }
            }

            $invoices = Invoice::whereIn('qbo_customer_id', $final_qbo_customers)
                                ->where('balance','<>','0.00')
                                ->where('due_date','<=', now())
                                ->where('is_email_sent', 1);

            if ( $args['all'] === "true" ) {
                $invoices = $invoices->get();
            } else {
                $invoices = $invoices->whereIn('id', $args['invoice_ids'])->get();
            }

            if ( count($invoices) > 0) {
                $get_card = Card::find($args['card_id']);

                if (isset($get_card->id)) {
                    $key = PoyntFactory::getKey();
                    $payload = [
                        "iss" => "urn:aid:1f9bd647-f933-4446-af5f-6a79c3539b52",
                        "sub" => "urn:aid:1f9bd647-f933-4446-af5f-6a79c3539b52",
                        "iat" => time(),
                        "exp" => time() + 86400,
                        "aud" => "https://services.poynt.net"
                    ];

                    $jwt = JWT::encode($payload, $key, 'RS256');

                    try {
                        $response = Http::asForm()
                        ->post('https://services.poynt.net/token', [
                            'grantType' => 'urn:ietf:params:oauth:grant-type:jwt-bearer',
                            'assertion' => $jwt
                        ]);

                        $final_response = $response;

                        if (isset($final_response['accessToken'])) {
                            $business_id = PoyntFactory::getBusinessId();
                            $poynt_token = $final_response['accessToken'];
                            $payment_request_id = rand(111,99999).'to'.time();

                            $total_amount = 0;
                            foreach($invoices as $invoice) {
                                $total_amount = round(($total_amount + $invoice->total_amount), 2);
                            }

                            //dollars to cents
                            $total_amount_to_cents = round(($total_amount * 100), 2);

                            $payload = [
                                'action' => $request->input('type'),
                                'context' => [
                                    'businessId' => $business_id
                                ],
                                'amounts' => [
                                    'transactionAmount' => $total_amount_to_cents,
                                    'orderAmount' => $total_amount_to_cents,
                                    'currency' => 'USD'
                                ],
                                'fundingSource' => [
                                    'cardToken' => $get_card->poynt_token,
                                    'entryDetails' => [
                                    'customerPresenceStatus' => 'ECOMMERCE',
                                    'entryMode' => 'KEYED'
                                    ],
                                    'type' => 'CREDIT_DEBIT'
                                ],
                                'emailReceipt' => false,
                            ];

                            try {

                                $response = Http::withHeaders([
                                            "Authorization" => sprintf('Bearer %s', $poynt_token),
                                            "Content-type" => "application/json",
                                            'Poynt-Request-Id' => $payment_request_id
                                        ])
                                        ->post(sprintf('https://services.poynt.net/businesses/%s/cards/tokenize/charge', $business_id), $payload);

                                $last_response = $response;

                                if ( isset($last_response['status']) && $last_response['status']=='CAPTURED' ) {
                                    foreach( $invoices as $invoice ) {
                                        $invoice->payment_method = $get_card->number_masked;
                                        $invoice->paid_on = Carbon::now();
                                        event(new InvoiceUpdateEvent($invoice));
                                    }

                                    return response()->json([
                                        'status' => 'success'
                                    ]);

                                } else {
                                   return $response->json();
                                }

                            } catch(Exception $e) {
                                return response()->json($e);
                            }
                        }
                    } catch(Exception $e) {
                        return response()->json($e);
                    }

                } else {
                    return response()->json([
                        'errors' => [
                            'card_id' => ['Please make sure to enter a valid card.']
                        ]
                    ], 401);
                }
            } else {
                return response()->json([
                    'errors' => ['No invoices was found.']
                ]);
            }
        }


    }

    /**
     *
     * Display a listing tokenize Charge resource
     *
     * @authenticated
     *
     * @response 200 {
     *    "status": "Ok"
     * }
     * @response status=500 {
     *     "message": "Something went wrong."
     * }
     *
     * @response status=400 scenario="Validation error" {
     *    "message": "Unauthenticated",
     *    "errors": {
     *        "card_id": [
     *            "Please make sure to provide a valid card id."
     *        ],
    *         "type": [
     *            "The type field is required."
     *        ],
    *         "invoice_id": [
     *            "Please make sure to enter a valid invoice id."
     *        ]
     *    }
     * }
     *
     * @response status=404 {
     *    "error": "No invoices was found."
     * }
     *
     * @response status=500 {
     *    "error": "Something went wrong."
     * }
    */

    public function tokenizeCharge(Request $request) {

        $validator = Validator::make($request->all(), [
            'card_id' => ['required', new CheckOwnCard],
            'type' => ['required'],
            'invoice_id' => ['required']
        ]);

        if ($validator->fails()) {
            return response()->json(
            [
                'errors' => $validator->errors()
            ]
            ,400);
        }

        $get_card = Card::find($request->input('card_id'));

        if (isset($get_card->id)) {
            $key = PoyntFactory::getKey();
            $payload = [
                "iss" => "urn:aid:1f9bd647-f933-4446-af5f-6a79c3539b52",
                "sub" => "urn:aid:1f9bd647-f933-4446-af5f-6a79c3539b52",
                "iat" => time(),
                "exp" => time() + 86400,
                "aud" => "https://services.poynt.net"
            ];

            $jwt = JWT::encode($payload, $key, 'RS256');
            $check_invoice = Invoice::find($request->input('invoice_id'));
            $dollars_to_cent = round((($check_invoice->total_amount) * 100), 2);

            if (isset($check_invoice->id)) {
                try {
                    $response = Http::asForm()
                    ->post('https://services.poynt.net/token', [
                        'grantType' => 'urn:ietf:params:oauth:grant-type:jwt-bearer',
                        'assertion' => $jwt
                    ]);

                    $final_response = $response;

                    if (isset($final_response['accessToken'])) {
                        $business_id = PoyntFactory::getBusinessId();
                        $poynt_token = $final_response['accessToken'];
                        $payment_request_id = rand(111,99999).'to'.time();

                        $payload = [
                            'action' => $request->input('type'),
                            'context' => [
                                'businessId' => $business_id
                            ],
                            'amounts' => [
                                'transactionAmount' => $dollars_to_cent,
                                'orderAmount' => $dollars_to_cent,
                                'currency' => 'USD'
                            ],
                            'fundingSource' => [
                                'cardToken' => $get_card->poynt_token,
                                'entryDetails' => [
                                    'customerPresenceStatus' => 'ECOMMERCE',
                                    'entryMode' => 'KEYED'
                                ],
                                'type' => 'CREDIT_DEBIT'
                            ],
                            'emailReceipt' => false,
                        ];

                        try {

                            $response = Http::withHeaders([
                                        "Authorization" => sprintf('Bearer %s', $poynt_token),
                                        "Content-type" => "application/json",
                                        'Poynt-Request-Id' => $payment_request_id
                                    ])
                                    ->post(sprintf('https://services.poynt.net/businesses/%s/cards/tokenize/charge', $business_id), $payload);


                            $last_response = $response;

                            if ( isset($last_response['status']) && $last_response['status']=='CAPTURED' ) {
                                $check_invoice->payment_method = $get_card->number_masked;
                                $check_invoice->paid_on = Carbon::now();
                                event(new InvoiceUpdateEvent($check_invoice));

                                return response()->json([
                                    'status' => 'success'
                                ]);
                            } else {
                               return $response->json();
                            }

                        } catch(Exception $e) {
                            return response()->json($e);
                        }
                    } else {
                        throw new Exception("Something went wrong", 500);
                    }
                } catch(Exception $e) {
                    return response()->json($e);
                }
            } else {
                return response()->json(
                [
                    'errors' => [
                        'invoice_id' => ['Please make sure to enter a valid invoice id.']
                    ]
                ]
                ,400);
            }

        } else {
            return response()->json([
                'errors' => [
                    'card_id' => ['Please make sure to provide a valid card id.']
                ]
            ]);
        }
    }

    /**
     * Display a listing tokenize resource
     *
     * @authenticated
     *
     *
     * @apiResource App\Http\Resources\Standard
     * @apiResourceModel App\Card
     *
     *
     * @response 200 {
     *    "status": "Ok"
     * }
     * @response status=500 {
     *     "message": "Something went wrong."
     * }
     *
     * @response status=400 scenario="Validation error" {
     *    "message": "Unauthenticated",
     *    "errors": {
     *        "nonce": [
     *            "The nonce is required."
     *        ]
     *    }
     * }
     *
     * @response status=404 {
     *    "error": "No invoices was found."
     * }
     *
     * @response status=500 {
     *    "error": "Something went wrong."
     * }
     *
     *
     */

    public function tokenize(Request $request) {

        $validator = Validator::make($request->all(), [
            'nonce' => ['required'],
            'default_customer_id' => ['required']
        ]);

        if ($validator->fails()) {
            return response()->json(
            [
                'errors' => $validator->errors()
            ]
            ,400);
        }

        $default_customer_id = $request->input('default_customer_id');
        $key = PoyntFactory::getKey();
        $payload = [
            "iss" => "urn:aid:1f9bd647-f933-4446-af5f-6a79c3539b52",
            "sub" => "urn:aid:1f9bd647-f933-4446-af5f-6a79c3539b52",
            "iat" => time(),
            "exp" => time() + 86400,
            "aud" => "https://services.poynt.net"
        ];

        $jwt = JWT::encode($payload, $key, 'RS256');

        try {
            $response = Http::asForm()
            ->post('https://services.poynt.net/token', [
                'grantType' => 'urn:ietf:params:oauth:grant-type:jwt-bearer',
                'assertion' => $jwt
            ]);

            $final_response = $response;

            if (isset($final_response['accessToken'])) {
                $poynt_token = $final_response['accessToken'];
                $payment_request_id = rand(111,99999).'to'.time();
                $business_id = PoyntFactory::getBusinessId();
                $payload = [
                    'nonce' => $request->input('nonce')
                ];
                try {
                    $response = Http::withHeaders([
                                "Authorization" => sprintf('Bearer %s', $poynt_token),
                                "Content-type" => "application/json",
                                'Poynt-Request-Id' => $payment_request_id
                            ])
                            ->post(sprintf('https://services.poynt.net/businesses/%s/cards/tokenize', $business_id), $payload);

                    $get_response = $response;

                    if (isset($get_response['cvvResponse']) && $get_response['cvvResponse']==='MATCH') {
                        if (isset($get_response['card'])) {

                            $get_card = $get_response['card'];

                            $check_card = Card::where('type', $get_card['type'])
                                              ->where('source', $get_card['source'])
                                              ->where('expiration_month', $get_card['expirationMonth'])
                                              ->where('expiration_year', $get_card['expirationYear'])
                                              ->where('number_masked', $get_card['numberMasked'])
                                              ->where('number_hashed', $get_card['numberHashed'])
                                              ->where('first_name', $get_card['cardHolderFirstName'])
                                              ->where('last_name', $get_card['cardHolderLastName'])
                                              ->where('customer_admin_id', Auth::user()->id)
                                              ->where('default_customer_id', intval($default_customer_id))
                                              ->first();

                            if (isset($check_card->id)) {
                                $check_card->card_id = $get_card['id'];
                                $check_card->card_card_id = $get_card['cardId'];
                                $check_card->poynt_token = $get_response['paymentToken'];
                                $check_card->save();
                            } else {
                                Card::create([
                                    'type' => $get_card['type'],
                                    'source' => $get_card['source'],
                                    'expiration_month' => $get_card['expirationMonth'],
                                    'expiration_year' => $get_card['expirationYear'],
                                    'card_id' => $get_card['id'],
                                    'number_masked' => $get_card['numberMasked'],
                                    'number_hashed' => $get_card['numberHashed'],
                                    'first_name' => $get_card['cardHolderFirstName'],
                                    'last_name' => $get_card['cardHolderLastName'],
                                    'card_card_id' => $get_card['cardId'],
                                    'poynt_token' => $get_response['paymentToken'],
                                    'customer_admin_id' => Auth::user()->id,
                                    'default_customer_id' => intval($default_customer_id)
                                ]);
                            }
                        }
                    }
                    return $get_response->json();
                } catch(Exception $e) {
                    return response()->json($e);
                }
            }
        } catch(Exception $e) {
           return response()->json($e);
        }
    }
    /**
     * Get Access Token
     *
     * @authenticated
     *
     * @response 200 {
     *    "status": "Ok"
     * }
     * @response status=400 {
     *    "message": "Unauthenticated",
     * }
     *
    */

    public function getAccessToken(Request $request) {

        $key = PoyntFactory::getKey();
        $payload = [
            "iss" => "urn:aid:1f9bd647-f933-4446-af5f-6a79c3539b52",
            "sub" => "urn:aid:1f9bd647-f933-4446-af5f-6a79c3539b52",
            "iat" => time(),
            "exp" => time() + 86400,
            "aud" => "https://services.poynt.net"
        ];

        $jwt = JWT::encode($payload, $key, 'RS256');

        try {
            $response = Http::asForm()
            ->post('https://services.poynt.net/token', [
                'grantType' => 'urn:ietf:params:oauth:grant-type:jwt-bearer',
                'assertion' => $jwt
            ]);

            $final_response = $response->json();
            return response()->json($final_response);
        }catch(Exception $e) {
           return response()->json($e);
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
    *
    * @authenticated
    *
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
    *
    * @authenticated
    *
    *
    * Display for PoyntFactory
    *
    *
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
     * @authenticated
     *
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */

    public function destroy($id)
    {
        //
    }
}
