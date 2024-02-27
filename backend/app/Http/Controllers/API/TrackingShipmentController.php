<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Shipment;
use Illuminate\Support\Facades\Validator;
use App\Terminal49ShippingLine;
use App\Custom\Traits\Terminal49Helpers;
use Auth;
/**
 *
 * @group Tracking Shipment
 *
 * APIs to manage the tracking shipment
 *
 */
class TrackingShipmentController extends Controller
{
    use Terminal49Helpers;

    /**
     * Show the form for creating a new Tracking Shipment resources
     *
     * @authenticated
     *
     * @bodyParam mbl_num string required
     * @bodyParam po_num string required
     *
     * @response status=400 {
     *      "errors": {
     *          "mbl_num": [
     *              "The mbl_num is required.",
     *          ]
     *      },
     *     "message": "Unauthenticated.",
     *     "is_scac_validation_failed": true
     * }
     *
    */


    public function create(Request $request)
    {

      $authenticatedUser = Auth::user();
      $customers = $authenticatedUser->customersApi->pluck('id');

      try {
          $customer_id = ($authenticatedUser->default_customer_id !== null) ? $authenticatedUser->default_customer_id : $customers[0];
      }catch (\Exception $exception){
          return response([
              'message'=>'No results found'
          ], 404);
      }
      // validate
      $validator = Validator::make($request->all(), [
        'mbl_num'=> 'required|min:4',
      ]);

      if ($validator->fails()) {
          return response()->json($validator->errors(), 400);
      }

      // // validate scac
      // if(!$this->validateScac($request->mbl_num)){
      //     return response()->json([
      //         "is_scac_validation_failed" => true
      //     ],400);
      // }
      // //

      $shipment = Shipment::create([
        'mbl_num' => $request->mbl_num,
        'po_num' => $request->po_num,
        'is_tracking_shipment' => true,
        'customer_id' => $customer_id
      ]);

      return response()->json($shipment);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @authenticated
     *
     * @urlParam shipment_id int required Shipment ID
     *
     * @response 200 {
     *   "message": "Shipment Deleted Successfully"
     * }
     *
     * @response 400 {
     *   "message": ["Shipment Doesn't exists or Shipment is not a tracking shipment", "Unauthenticated"]
     * }
     *
    */
    public function delete($shipment_id){
          $shipment = Shipment::find($shipment_id);
          if($shipment && $shipment->is_tracking_shipment){
            
              $shipment->cancelled = 1;
              $shipment->save();

              return response()->json([
                  'message' => "Shipment Deleted Successfully",
              ],200);
          }else{
              return response()->json([
                  'message' => "Shipment Doesn't exists or Shipment is not a tracking shipment",
              ], 400);

          }
    }
}
