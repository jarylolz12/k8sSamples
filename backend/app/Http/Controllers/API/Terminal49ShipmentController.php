<?php

namespace App\Http\Controllers\API;

use App\Custom\CustomJWTGenerator;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Terminal49Shipment;
use App\Shipment;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

/**
 *
 * @group Terminal 149 Shipment
 *
 * APIs to manage the supplier resource
 *
 */
class Terminal49ShipmentController extends Controller
{
    /**
     *
     * Display a listing of the Termenal 49 Shipment
     *
     * @authenticated
     *
     * @urlParam mbl_num string required Termenal 49 Shipment MBL_NUM
     *
     * @apiResource App\Http\Resources\Standard
     * @apiResourceModel App\Terminal49Shipment
     *
     * @response status=400 scenario="Unauthenticated" {
     *     "message": "Unauthenticated."
     * }
     *
     */

    public function index($mbl_num)
    {
        $alreadyExistResponse = [
            'is_already_exists' => false
        ];

        $isAlreadyExists = Shipment::where('mbl_num', $mbl_num)->first();
        if (!empty($isAlreadyExists)) {
            $schedules_group = json_decode($isAlreadyExists->schedules_group);

            $alreadyExistResponse = [
                'is_already_exists' => true,
                'shipment_id' => $isAlreadyExists->id,
                'type' => $schedules_group ? $schedules_group[0]->type : ""
            ];
        }

        $shipment = [];
        $shipment_terminal49 = Terminal49Shipment::find($mbl_num);
        if ($shipment_terminal49) {

            /* Call Get Terminal Api */
            if ($shipment_terminal49->ts_id) {
                $podTerminalResponse = Http::withHeaders([
                    'Content-Type' => 'application/json',
                    'Authorization' => 'Token ' . env('TERMINAL49_API_KEY'),
                ])->get('https://api.terminal49.com/v2/shipments/' . $shipment_terminal49->ts_id . '?include=containers,pod_terminal');

                if ($podTerminalResponse->status() == 200) {
                    $getData = $podTerminalResponse->json()["included"];
                    $terminalKey = array_search('terminal', array_column($getData, 'type'));
                    $terminalName = $getData[$terminalKey]['attributes']['name'];
                } else {
                    $terminalName = false;
                }
            }
            /* Call Get Terminal Api */

            $attributes = json_decode($shipment_terminal49->attributes, true);
            $shipment['attributes'] = $attributes;
            $shipment['terminal_name'] = $terminalName;
            
            $containers = $getData;
            if (!empty($containers)) {
                $shipment['containers'] = [];
                foreach ($containers as $key => $container) {
                    if ($key != $terminalKey) {
                        $shipment['containers'][$container['attributes']['number']] = [
                            "id" => $container['id'],
                            "last_free_day" => $container['attributes']['pickup_lfd'],
                            "released_at_terminal" => $container['attributes']['available_for_pickup'],
                            "holds" => $container['attributes']['holds_at_pod_terminal'],
                            "fees" => $container['attributes']['fees_at_pod_terminal'],
                            "pickup_appointment_at" => $container['attributes']['pickup_appointment_at'],
                            "container_details" => $container['attributes'],
                        ];
                    }
                }
            }
        }
        $shipment['is_already_exists'] = $alreadyExistResponse;
        return response($shipment, 200);
    }
}
