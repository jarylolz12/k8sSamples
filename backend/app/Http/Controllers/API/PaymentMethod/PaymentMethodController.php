<?php

namespace App\Http\Controllers\API\PaymentMethod;

use App\Card;
use App\Http\Controllers\Controller;
use App\Http\Controllers\API\Poynt\PoyntFactory;
use App\PaymentMethod;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Http;

/**
 * @authenticated
 *
 * @group Payment Method
 *
 * Class PaymentMethodController
 * @package App\Http\Controllers\API\PaymentMethod
 */
class PaymentMethodController extends Controller
{
    protected $uuid, $cardData;

    public function __construct()
    {
        $this->uuid = (string)Str::uuid();
    }

    /**
     * Get All Payment Method
     *
     * @urlParam default_customer_id int required
     *
     * @response status=200 {
     *  "status": "success",
     *  "data": {
     *      "cards": [],
     *      "ACHMethods": [
     *          {
     *              "id": 1,
     *              "default_customer_id": 22,
     *              "customer_admin_id": 3,
     *              "name": "Anthony",
     *              "routing": null,
     *              "account": null,
     *              "token": null,
     *              "is_default": "0",
     *              "created_at": null,
     *              "updated_at": null
     *          }
     *      ]
     *  },
     *  "code": 200
     * }
     *
     * @response status=401 {
     *     "message": "Unauthenticated."
     * }
     *
     *
     * @param $default_customer_id
     * @return \Illuminate\Http\JsonResponse
     *
     */
    public function getAllPaymentMethod($default_customer_id)
    {
        try {
            $returnData['cards'] = Card::where('default_customer_id', $default_customer_id)->get();
            $returnData['ACHMethods'] = PaymentMethod::where('default_customer_id', $default_customer_id)->get();
            $countCards = $returnData['cards'];
            $countACH = $returnData['ACHMethods'];
            if (count($countCards) <= 0 && count($countACH) <= 0)
                throw new Exception("No payment method exist. Cards: ". $countCards.", ACH: ".$countACH);

            $status = 'success';
            $statusCode = 200;
        } catch (Exception $exception) {
            Log::Error(__FUNCTION__ . ":" . __LINE__ . ": " . $exception->getMessage());
            $returnData = $exception->getMessage();
            $status = 'error';
            $statusCode = 400;
        }

        return response()->json(['status' => $status, 'data' => $returnData, 'code' => $statusCode]);
    }

    /**
     * Add Payment Method
     *
     *
     * @bodyParam number int required
     * @bodyParam expiration_month string required
     * @bodyParam expiration_year string required
     * @bodyParam cardHolder_first_name string required
     * @bodyParam cv_data string required
     * @bodyParam default_customer_id int required
     *
     * @response status=400 {
     *      "errors": {
     *          "number": [
     *              "The number field is required."
     *          ],
     *          "expiration_month": [
     *              "The expiration month field is required."
     *          ],
     *          "expiration_year": [
     *              "The expiration year field is required."
     *          ],
     *          "cardHolder_first_name": [
     *              "The card holder first name field is required."
     *          ],
     *          "cv_data": [
     *              "The cv data field is required."
     *          ],
     *          "default_customer_id": [
     *              "The default customer id field is required."
     *          ]
     *      }
     * }
     *
     * @response status=200 {
     *      "status": "error",
     *      "data": "Please provide valid card details.",
     *      "code": 400
     *  }
     *
     * @response status=401 {
     *     "message": "Unauthenticated."
     * }
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     *
     */
    public function addPaymentMethod(Request $request)
    {
        $statusCode = 400;
        $error = $this->paymentMethodRequestValidation($request);
        if ($error !== true)
            return response()->json(['errors' => $error], $statusCode);

        try {
            $this->cardData = PaymentMethodFactory::createCardData($request);

            $nonce = PoyntFactory::getNonce($request, $this->uuid);
            if ($nonce) {
                $accessTokenResponse = PoyntFactory::getAccessToken();
                if (!isset($accessTokenResponse['accessToken']))
                    throw new Exception("Error generating access token for poynt");

                $poyntPaymentToken = $this->getPoyntPaymentToken($accessTokenResponse['accessToken'], $nonce);
                if ($poyntPaymentToken)
                    $this->cardData['poynt_token'] = $poyntPaymentToken;
            }

//            if (empty($this->cardData['poynt_token']))
//                throw new Exception("Please provide valid card details.");

            $cardknoxPaymentToken = $this->getCardknoxPaymentToken($request);
            if (!$cardknoxPaymentToken)
                throw new Exception("Error while getting Cardknox token.");

            $this->cardData['cardknox_token'] = $cardknoxPaymentToken;

            if (empty($this->cardData['cardknox_token']) && empty($this->cardData['poynt_token']))
                throw new Exception("You have entered wrong card details.");

            $returnData = $this->saveCard();
            if (!$returnData)
                throw new Exception("Error while save card data.");

            $status = 'success';
            $statusCode = 200;
        } catch (Exception $e) {
            Log::Error(__FUNCTION__ . ":" . __LINE__ . ": " . $e->getMessage());
            $returnData = $e->getMessage();
            $status = 'error';
            $statusCode = 400;
        }

        return response()->json(['status' => $status, 'data' => $returnData, 'code' => $statusCode]);
    }

    /**
     * Request Validation Peyment Method
     *
     * @param $request
     * @return bool|\Illuminate\Support\MessageBag
     */
    private function paymentMethodRequestValidation($request)
    {
        $validator = Validator::make($request->all(), [
            'number' => ['required'],
            'expiration_month' => ['required'],
            'expiration_year' => ['required'],
            'cardHolder_first_name' => ['required'],
            'cv_data' => ['required'],
            'default_customer_id' => ['required'],
        ]);

        if ($validator->fails()) {
            return $validator->errors();
        }
        return true;
    }

    /**
     * Get Poynt Payment Token
     *
     * @param $poyntToken
     * @param $nonce
     * @return bool|mixed
     */
    private function getPoyntPaymentToken($poyntToken, $nonce)
    {
        try {
            $getResponse = Http::withHeaders([
                "Authorization" => sprintf('Bearer %s', $poyntToken),
                "Content-type" => "application/json",
                'Poynt-Request-Id' => $this->uuid
            ])->post(PoyntFactory::cardTokenizeURL(), [
                'nonce' => $nonce
            ]);

            Log::info("getPoyntPaymentToken :". json_encode($getResponse->body()));

            if (!(isset($getResponse['card'])))
                throw new Exception("Error generating token in exchange of nonce");

            $this->cardData['type'] = $getResponse['card']['type'];
            return $getResponse['paymentToken'];

        } catch (Exception $e) {
            Log::Error(__FUNCTION__ . ":" . __LINE__ . ": " . $e->getMessage());
            return false;
        }
    }

    /**
     * Get Card Knox Payment Token
     *
     * @param $request
     * @return bool
     *
     */
    private function getCardknoxPaymentToken($request)
    {
        try {
            $payload = CardknoxFactory::ccSavePayload($request->input('number'), $request->input('expiration_month'), substr($request->input('expiration_year'), -2), $request->input('cardHolder_first_name'));

            $response = Http::withHeaders([
                "Content-type" => "application/json",
            ])->post(CardknoxFactory::gatewayURL(), $payload);

            $result = json_decode($response);

            if (!isset($result->xResult) || $result->xResult == 'E' || empty($result->xToken))
                throw new Exception("Error while saving card in cardknox, xError:: " . $result->xError);

            $this->cardData['type'] = strtoupper($result->xCardType);
            return $result->xToken;
        } catch (Exception $e) {
            Log::Error(__FUNCTION__ . ":" . __LINE__ . ": " . $e->getMessage());
            return false;
        }
    }

    /**
     * Save Card
     *
     * @return bool
     */
    private function saveCard()
    {
        try {
            $card = Card::where('number_hashed', $this->cardData['number_hashed'])->first();
            if ($card) {
                $card->poynt_token = ($this->cardData['poynt_token']) ?? NULL;
                $card->poynt_token = ($this->cardData['cardknox_token']) ?? NULL;
                $card->is_default = ($this->cardData['is_default']) ?? NULL;
                $card->save();
                return Card::where('number_hashed', $this->cardData['number_hashed'])->first();
            }
            return Card::create([
                'type' => ($this->cardData['type']) ?? NULL,
                'expiration_month' => $this->cardData['expiration_month'],
                'expiration_year' => $this->cardData['expiration_year'],
                'number_masked' => $this->cardData['number_masked'],
                'number_hashed' => $this->cardData['number_hashed'],
                'first_name' => $this->cardData['first_name'],
                'poynt_token' => ($this->cardData['poynt_token']) ?? NULL,
                'cardknox_token' => ($this->cardData['cardknox_token']) ?? NULL,
                'customer_admin_id' => Auth::user()->id,
                'default_customer_id' => $this->cardData['default_customer_id'],
                'is_default' => $this->cardData['is_default']
            ]);
        } catch (Exception $e) {
            Log::Error(__FUNCTION__ . ":" . __LINE__ . ": " . $e->getMessage());
            return false;
        }
    }

    /**
     * ACH Save
     *
     *
     * @bodyParam xName string required
     * @bodyParam xRouting string required
     * @bodyParam xAccount string required
     * @bodyParam default_customer_id int required
     *
     * @response status=400 {
     *      "errors": {
     *          "xName": [
     *              "The x name field is required."
     *          ],
     *          "xRouting": [
     *              "The x routing field is required."
     *          ],
     *          "xAccount": [
     *              "The x account field is required."
     *          ],
     *          "default_customer_id": [
     *              "The default customer id field is required."
     *          ]
     *      }
     * }
     *
     * @response status=200 {
     *      "status": "error",
     *      "data": "Error while getting ACH save Cardknox token.",
     *      "code": 400
     *  }
     * @response status=401 {
     *     "message": "Unauthenticated."
     * }
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     *
     */
    public function ACHSave(Request $request)
    {
        try {
            $statusCode = 400;
            $error = $this->ACHSaveRequestValidation($request);
            if ($error !== true)
                return response()->json(['errors' => $error], $statusCode);

            $xToken = $this->getACHSaveToken($request);
            if (!$xToken)
                throw new Exception("Error while getting ACH save Cardknox token.");

            $returnData = $this->ACHSaveToDB($request, $xToken);
            if (!$returnData)
                throw new Exception("Error while save ACH data in DB.");

            $status = 'success';
            $statusCode = 200;
        } catch (Exception $e) {
            Log::Error(__FUNCTION__ . ":" . __LINE__ . ": " . $e->getMessage());
            $returnData = $e->getMessage();
            $status = 'error';
            $statusCode = 400;
        }

        return response()->json(['status' => $status, 'data' => $returnData, 'code' => $statusCode]);
    }

    private function ACHSaveRequestValidation($request)
    {
        $validator = Validator::make($request->all(), [
            'xName' => ['required'],
            'xRouting' => ['required'],
            'xAccount' => ['required'],
            'default_customer_id' => ['required', 'numeric'],
        ]);

        if ($validator->fails())
            return $validator->errors();

        return true;
    }

    private function getACHSaveToken($request)
    {
        try {
            $payload = CardknoxFactory::ACHSavePayload($request->input('xName'), $request->input('xRouting'), $request->input('xAccount'));

            $response = Http::withHeaders([
                "Content-type" => "application/json",
            ])->post(CardknoxFactory::gatewayURL(), $payload);

            $result = json_decode($response);

            if (!isset($result->xResult) || $result->xResult == 'E' || empty($result->xToken))
                throw new Exception("Error while saving ACH in cardknox, xError:: " . $result->xError);

            return $result->xToken;
        } catch (Exception $e) {
            Log::Error(__FUNCTION__ . ":" . __LINE__ . ": " . $e->getMessage());
            return false;
        }
    }

    private function ACHSaveToDB($request, $xToken)
    {
        try {
            $card = PaymentMethod::where('default_customer_id', $request->input('default_customer_id'))
                ->where('customer_admin_id', Auth::user()->id)
                ->where('routing', $request->input('xRouting'))
                ->where('account', $request->input('xAccount'))
                ->first();
            if (!$card) {
                $card = new PaymentMethod();
                $card->default_customer_id = $request->input('default_customer_id') ?? NULL;
                $card->customer_admin_id = Auth::user()->id;
                $card->routing = $request->input('xRouting') ?? NULL;
                $card->account = $request->input('xAccount') ?? NULL;
            }
            $card->name = $request->input('xName') ?? NULL;
            $card->token = $xToken;

            if ($request->input('is_default'))
                $card->is_default = $request->input('is_default');

            $card->save();

            return $card;
        } catch (Exception $e) {
            Log::Error(__FUNCTION__ . ":" . __LINE__ . ": " . $e->getMessage());
            return false;
        }
    }

    /**
     * Get ACH Payment Method
     *
     * @urlParam default_customer_id string required
     *
     * @response status=200 {
     *      [
     *          {
     *              "id": 1,
     *              "default_customer_id": 22,
     *              "customer_admin_id": 3,
     *              "name": "Anthony",
     *              "routing": null,
     *              "account": null,
     *              "token": null,
     *              "is_default": "0",
     *              "created_at": null,
     *              "updated_at": null
     *          }
     *      ]
     * }
     *
     * @response status=401 {
     *     "message": "Unauthenticated."
     * }
     *
     * @param $defaultCustomerId
     * @return \Illuminate\Contracts\Routing\ResponseFactory|\Illuminate\Http\JsonResponse|\Illuminate\Http\Response
     */
    public function getACHPaymentMethod($defaultCustomerId)
    {
        try {
            $returnData = PaymentMethod::where('default_customer_id', $defaultCustomerId)->get();
        } catch (Exception $e) {
            Log::Error(__FUNCTION__ . ":" . __LINE__ . ": " . $e->getMessage());
            return response([
                'message' => 'No results found'
            ], 500);
        }

        return response()->json($returnData);
    }

    /**
     * ACH Update
     *
     *
     * @bodyParam xName string required
     * @bodyParam xRouting string required
     * @bodyParam xAccount string required
     * @bodyParam default_customer_id int required
     *
     *
     * @response status=401 {
     *     "message": "Unauthenticated."
     * }
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     *
     *
     */
    public function ACHUpdate(Request $request)
    {
        try {
            $statusCode = 400;
            $error = $this->ACHEditRequestValidation($request);
            if ($error !== true)
                throw new Exception($error, $statusCode);

            $args = $request->all();

            $check_user_id = Auth::user()->id;
            $returnData = PaymentMethod::where('customer_admin_id', $check_user_id)
                ->where('default_customer_id', $args['default_customer_id'])
                ->where('id', $args['record_id'])
                ->first();
            if (!isset($returnData->id))
                throw new Exception("Can not found requested payment method for update..!", 500);

            $xToken = $this->getACHSaveToken($request);
            if (!$xToken)
                throw new Exception("Error while getting ACH save Cardknox token.", 500);

            $returnData->name = $args['xName'];
            $returnData->routing = $args['xRouting'];
            $returnData->account = $args['xAccount'];
            $returnData->save();

            $status = 'success';
            $statusCode = 200;
            $error = '';
        } catch (Exception $e) {
            Log::Error(__FUNCTION__ . ":" . __LINE__ . ": " . $e->getMessage());
            $returnData = $error = $e->getMessage();
            $status = 'error';
            $statusCode = $e->getCode();
        }

        return response()->json(['status' => $status, 'data' => $returnData, 'error' => $error, 'code' => $statusCode]);
    }

    private function ACHEditRequestValidation($request)
    {
        $validator = Validator::make($request->all(), [
            'record_id' => ['required', 'numeric'],
            'xName' => ['required'],
            'xRouting' => ['required'],
            'xAccount' => ['required'],
            'default_customer_id' => ['required', 'numeric'],
        ]);

        if ($validator->fails())
            return implode(",", $validator->messages()->all());

        return true;
    }

    /**
     *
     * ACH Delete
     *
     *
     * @response status=200 {
     *      "status": "ok"
     * }
     * @response status=401 {
     *     "message": "Unauthenticated."
     * }
     *
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function ACHDelete(Request $request)
    {
        try {
            $response = ['status' => 'not ok'];
            $validator = Validator::make($request->all(), [
                'record_id' => ['required', 'numeric'],
                'default_customer_id' => ['required', 'numeric'],
            ]);
            if ($validator->fails())
                throw new Exception($validator->errors(), 401);

            $checkACHPayment = PaymentMethod::where('default_customer_id', $request->input('default_customer_id'))
                ->where('id', $request->input('record_id'))
                ->first();
            if (!isset($checkACHPayment->id))
                throw new Exception("Something went wrong..!", 403);

            $checkACHPayment->delete();
            $response['status'] = 'ok';

            return response()->json($response, 200);
        } catch (Exception $e) {
            Log::Error(__FUNCTION__ . ":" . __LINE__ . " : " . $e->getCode() . ":" . $e->getMessage());
            return response()->json([
                'status' => 'error',
                'errors' => $e->getMessage(),
            ], $e->getCode() != 0 ? $e->getCode() : 500);
        }

    }

}
