<?php

namespace App\Http\Controllers\API\Poynt;

use App\Http\Controllers\Controller;
use Exception;
use Illuminate\Http\Request;
use App\Http\Controllers\API\Poynt\PoyntFactory;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Http;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use App\Card;
use App\Rules\CheckOwnCard;

/**
 *
 * @authenticated
 *
 * @group Card
 *
 * APIs to manage the Card resource
 *
 */
class CardController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @urlParam default_customer_id int required
     *
     * @apiResource App\Http\Resources\ScribeResources\CardResource
     * @apiResourceModel App\Card
     *
     * @response status=400 scenario="Unauthenticated" {
     *     "message": "Unauthenticated."
     * }
     *
     * @return Response
     */
    public function index($default_customer_id)
    {
        try {
            $cards = Card::where('default_customer_id', $default_customer_id)->get();
        } catch (Exception $exception) {
            return response([
                'messsage' => 'No results found'
            ], 500);
        }
        return response()->json($cards);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param Request $request
     * @return Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     *
     * @urlParam id int required Card ID. Example: 65
     *
     * @apiResource App\Http\Resources\ScribeResources\CardResource
     * @apiResourceModel App\Card
     *
     * @response status=400 scenario="Unauthenticated" {
     *     "message": "Unauthenticated."
     * }
     *
     * @param int $id
     * @return Response
     */
    public function show($id)
    {
        //

        $check_user_id = Auth::user()->id;
        $check_card = Card::where('customer_admin_id', $check_user_id)
            ->where('id', $id)
            ->first();

        return response()->json($check_card);
    }

    /**
     * Show the form for editing the specified resource.
     *
     *
     * @bodyParam card_id int required user card ID.
     * @bodyParam first_name string first name of the card user.
     * @bodyParam last_name string last name of the card user.
     * @bodyParam is_default tinyint required is_default set to zero. Example: 0
     *
     * @apiResource App\Http\Resources\ScribeResources\CardResource
     * @apiResourceModel App\Card
     *
     * @response status=401 scenario="Validation error" {
     *    "errors": {
     *        "card_id": [
     *            "The card id is required.",
     *        ],
     *        "first_name": [
     *            "The first name field is required.",
     *        ],
     *        "last_name": [
     *            "The last name field is required.",
     *        ],
     *        "is_default": [
     *            "The is_default field is required.",
     *            "The is_default field must be true or false.",
     *        ],
     *        "status": "not ok"
     *    }
     * }
     *
     * @response status=400 scenario="Unauthenticated" {
     *     "message": "Unauthenticated."
     * }
     *
     * @param int $id
     * @return Response
     */
    public function edit(Request $request)
    {
        $response = ['status' => 'ok'];
        //
        $validator = Validator::make($request->all(), [
            'card_id' => ['required', new CheckOwnCard],
            'first_name' => ['required'],
            'is_default' => ['required', 'boolean'],
        ]);

        if ($validator->fails()) {
            return response()->json(
                [
                    'errors' => $validator->errors()
                ]
                , 401);
        }
        $args = $request->all();

        $check_user_id = Auth::user()->id;
        $check_card = Card::where('customer_admin_id', $check_user_id)
            ->where('id', $args['card_id'])
            ->first();

        if (isset($check_card->id)) {
            $check_card->first_name = $args['first_name'];
            $check_card->is_default = ($args['is_default']) ? 1 : 0;
            $check_card->save();

            if ($args['is_default']) {
                Card::where('id', '<>', $check_card->id)
                    ->where('customer_admin_id', $check_user_id)
                    ->update([
                        'is_default' => 0
                    ]);
            }
            return response()->json($response);
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param Request $request
     * @param int $id
     * @return Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     *
     * @bodyParam card_id int required Delete Card card_id
     *
     * @response 200 {
     *       "status": "ok"
     * }
     *
     * @response 401 {
     *       "status": "not ok"
     * }
     *
     * @response status=400 scenario="Unauthenticated" {
     *     "message": "Unauthenticated."
     * }
     * @param int $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy(Request $request)
    {
        try {
            $response = ['status' => 'not ok'];
            $validator = Validator::make($request->all(), [
                'card_id' => ['required', 'numeric'],
                'default_customer_id' => ['required', 'numeric'],
            ]);
            if ($validator->fails()) {
                throw new Exception($validator->errors(), 401);
            }

            $check_card = Card::where('default_customer_id', $request->input('default_customer_id'))
                ->where('id', $request->input('card_id'))
                ->first();
            if (!isset($check_card->id))
                throw new Exception("Something went wrong..!", 403);

            $check_card->delete();
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
