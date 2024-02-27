<?php

namespace App\Http\Controllers\PO_INSTANCE;

use App\Http\Controllers\Controller;
use App\Shipment;
use Illuminate\Http\Request;

class ShipmentController extends Controller
{
    /**
     * @group Shipment
     *
     * Get shipment
     *
     * @authenticated
     *   
     * @urlParam shipment_id int required The shipment ID
     * 
     * @apiResourceCollection App\Http\Resources\Shipment
     * @apiResourceModel App\Shipment  
     *   
     * @response status=400 scenario="Unauthenticated" {
     *     "message": "Unauthenticated."
     * }
     * 
    */
 
    public function getShipment($shipment_id){

        $shipmentId = Shipment::find($shipment_id);

        return response()->json($shipmentId);

    }
}
