<?php

namespace App\Http\Controllers\API\CLIENT\v1;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Shipment;
use App\Http\Resources\CLIENT\v1\Shipment as ShipmentResource;
use App\Http\Resources\CLIENT\v1\Status as ShipmentStatusResource;
use Auth;
use Laravel\Passport\Token;
use Lcobucci\JWT\Parser;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use App\Custom\CustomJWTGenerator;
use Illuminate\Support\Facades\Http;

/**  
 * 
 * @group Client Shipment
 * 
 * APIs to manage the client shipment
 * 
 */

class ShipmentController extends Controller
{

    /**
     * Display a listing of the resource. 
     *  
     * @authenticated
     * 
     * @urlParam po int required. Example: 111000020
     * 
     * @apiResource App\Http\Resources\Shipment
     * @apiResourceModel App\Shipment
     *  
     * @response 404 {
     *     "status": "error.",
     *     "message": "Shipment Not Found"
     * } 
     * 
     * @response status=400 scenario="Unauthenticated" {
     *     "message": "Unauthenticated."
     * } 
     * 
     */

    public function shipmentByPo(Request $request, $po)
    {
        //Auth::loginUsingId(3);

        $customers = auth()->user()->customersApi()->get();
        $customer_ids =  $customers->pluck(['id']);
        $shipments = new Collection();

        foreach ($customers as $customer) {
            $shipments = $shipments->merge($customer->shipments);
        }

        // check po by old way
        if ($shipments) {
            $shipments = $shipments->filter(function ($shipment) use ($po) {
                $suppliers = json_decode($shipment->suppliers_group_bookings);
                if ($suppliers) {
                    foreach ($suppliers as $supplier) {
                        if ($supplier->po_num === $po) {
                            return true;
                        }
                    }
                }
                return false;
            });
        }
        // return $shipments;
        // check po by new way
        $jwtToken = CustomJWTGenerator::generateToken();

        $response = Http::withHeaders([
                        'Content-Type' => 'application/json',
                        'Accept'     => 'application/json',
                        'Authorization'  => 'Bearer ' . $jwtToken,
                    ])->baseUrl(getenv('PO_URL'))
                    ->get("/api/po/shipments/$po?customer_ids=".json_encode($customer_ids ?? []));

        if($response->status() == 200){
            $shipmentDistribution = $response->json()['data'];
            if(count($shipmentDistribution)){
              $shipments = $shipments->merge(Shipment::whereIn('id',collect($shipmentDistribution)->pluck(['shipment_id']))->whereNotIn('id',$shipments->pluck(['id']))->get());
            }
        }

        // check for tracking shipments
        $shipments = $shipments->merge(Shipment::where('is_tracking_shipment',1)->where('po_num', $po)->whereNotIn('id',$shipments->pluck(['id']))->get());
        // return $shipments;
        if ($shipments->first()) {
            return ShipmentResource::Collection($shipments);
        } else {
            return response()->json(['status'=>'error','message'=>'Shipment Not Found'], 404);
        }
    }
    /**
     * Shipment Status 
     * 
     * @authenticated
     * 
     * @urlParam id int required Shipment ID. 
     * 
     * @response 200 {
     *      "status": "200"
     * } 
     * @response 404 {
     *      "message": "Shipment Not found!"
     * } 
     * @response 403 {
     *      "message": "You don't have access to this shipment!"
     * } 
     * 
     * @response status=400 scenario="Unauthenticated" {
     *     "message": "Unauthenticated."
     * } 
     * 
    */

    public function shipmentStatus(Request $request, $id){
        $shipment = Shipment::find($id);
        if(empty($shipment)){
            return response([
                'message' => 'Shipment Not found!'
            ],404);
        }
        $customers = auth()->user()->customersApi()->get();
        $customer_ids =  $customers->pluck(['id']);

        if(in_array($shipment->customer_id ?? -1,$customer_ids->toArray())){
            return new ShipmentStatusResource($shipment);
        }
        return response([
            'message' => "You don't have access to this shipment!"
        ],403);
    }
 
    /** 
     * Download
     * 
     * @authenticated
     *
     * @response status=200 scenario="ok"{
     *      "message": ""
     * }
     * 
     * @response 403 {
     *      "message": "You don't have access to this shipment!"
     * }
     * 
     * @response 404 {
     *      "message": [
     *          "Shipment not found", 
     *          "document not found"
     *      ]
     * }
     * 
     * @response status=400 scenario="Validation error" { 
     *    "message": null,
     *    "errors": {
     *        "document_type": [
     *            "The document type field is required.", 
     *        ],
     *        "document_path": [
     *            "The document path field is required.",
     *            "The document path must be a string."
     *        ],
     *        "shipment_id": [
     *            "The shipment id field is required.",
     *            "The shipment id must be a string.",
     *            "The shipment id must be at least 1."
     *        ]  
     *    } 
     * }
     * 
     * @response status=400 scenario="Unauthenticated" {
     *     "message": "Unauthenticated."
     * } 
     *  
     */
    
    public function download(Request $request)
    {
        $validator = \Validator::make($request->all(), [
            'document_type' => 'required|in:hbl_copy,packing_list,commercial_documents,commercial_invoice,customs_documents',
            'document_path' => 'required|string',
            'shipment_id' =>   'integer|required|min:1',
        ]);

        if ($validator->fails()) {
            return response($validator->errors(),400);
        }

        $shipment = Shipment::find($request->shipment_id);

        if($shipment){
          $customers = auth()->user()->customersApi()->get();
          $customer_ids =  $customers->pluck(['id']);

          if(in_array($shipment->customer_id ?? -1,$customer_ids->toArray())){
              if($request->document_type == 'customs_documents'){
                  $docs = json_decode($shipment->suppliers_commercial_docs ?? '[]');
                  foreach ($docs ?? [] as $key => $doc) {
                      // code...
                      foreach ($doc->commercial_documents ?? [] as $key => $cd) {
                          // code...
                          if($cd->file == $request->document_path){
                              if (Storage::exists("public/".$request->document_path)) {
                                  return Storage::download("public/".$request->document_path);
                              } else {
                                  return response([
                                    'message' => 'document not found'
                                  ], 404);
                              }
                          }
                      }

                  }
              }else {
                $suppliers = json_decode($shipment->suppliers_group_bookings);
                foreach ($suppliers ?? [] as $supplier) {
                    if ($supplier->{$request->document_type} == $request->document_path) {
                        if (Storage::exists("public/".$request->document_path)) {
                            return Storage::download("public/".$request->document_path);
                        } else {
                            break;
                        }
                    }
                }
              }
          }else{
              return response([
                'message' => "You don't have access to this shipment!"
              ],403);
          }
        }else{
            return response([
                'message' => "Shipment not found"
            ],404);
        }
        return response([
          'message' => 'document not found'
        ], 404); 
    } 
}
