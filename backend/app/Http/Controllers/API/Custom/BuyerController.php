<?php

namespace App\Http\Controllers\API\Custom;

use App\Http\Controllers\Controller;
use App\Buyer;

/**
 * @authenticated
 *
 * @group Custom Buyers
 *
 * Class BuyerController
 * @package App\Http\Controllers\API\Custom
 */
class BuyerController extends Controller
{
    /**
     *
     * Get Custom Buyers
     *
     * @urlParam id int required
     *
     * @response status=200 {
     *      "results": {
     *          "id": 2,
     *          "company_name": "Test Buyer One",
     *          "address": "sdfgsdfgsdfg",
     *          "phone": "9738557737",
     *          "emails": "[\"chana@shifl.com\"]",
     *          "admin_user_id": null,
     *          "created_at": "2022-04-21T01:45:30.000000Z",
     *          "updated_at": "2022-04-21T01:45:30.000000Z",
     *          "connected_customer": 0,
     *          "connection_generated": null
     *      }
     *  }
     *
     * @response status=401 {
     *     "message": "Unauthenticated."
     * }
     *
     *
     * @param $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function show($id)
    {
        //
        $buyer = Buyer::find($id);
        return response()->json(["results" => $buyer]);
    }

    /**
     * Get Connected Buyer
     *
     * @urlParam id int required
     *
     * @response status=200 {
      *      "results": [
      *          2
      *      ]
      *  }
     *
     * @response status=401 {
     *     "message": "Unauthenticated."
     * }
     *
     * @param $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function getConnectedBuyer($id)
    {
        $buyers = Buyer::where('connected_customer', $id)->pluck('id');
        return response()->json(["results" => $buyers]);
    }
}
