<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Terminal49Shipment;
use Illuminate\Support\Facades\Http;
use GuzzleHttp\Client;
use App\Terminal49Changelog;

class StatusTabController extends Controller
{

    /**
     *  @return shipment_details
     */
    public function index($mbl_num)
    {
        $shipment = Terminal49Shipment::find(trim($mbl_num));
        if ($shipment) {
            return response([
            'mbl_num' => $shipment->mbl_num,
            'tr_id' => $shipment->tr_id,
            'ts_id' => $shipment->ts_id,
            'attributes' => $shipment->attributes,
            'containers' => $shipment->containers,
            'terminals' => $shipment->terminals,
            'ignore_demurrage' => $shipment->ignore_demurrage
          ], 200);
        }
        return response([
          "message" => "Shipment details not found."
        ], 404);
    }


    public function raw_events(Request $request)
    {
        $raw_events = [];

        if (!empty($request->container_ids)) {
            foreach ($request->container_ids ?? [] as $container_id) {
                // code...
                $events = $this->fetch_raw_events($container_id);
                if ($events) {
                    $raw_events[$container_id] = $events;
                }
            }
        }

        return response($raw_events, 200);
    }

    public function standard_events(Request $request)
    {
        $standard_events = [];

        if (!empty($request->container_ids)) {
            foreach ($request->container_ids ?? [] as $container_id) {
                // code...
                $events = $this->fetch_standard_events($container_id);
                if ($events) {
                    $standard_events[$container_id] = $events;
                }
            }
        }

        return response($standard_events, 200);
    }

    public function terminals(Request $request)
    {
        $shipment = Terminal49Shipment::find(trim($request->mbl_num));

        $terminals = [];

        if ($shipment) {
            // check if terminal data already exist or not

            if ($shipment->terminals) {
                // return already saved data
                return response($shipment->terminals, 200);
            } else {
                $containers = json_decode($shipment->containers);
                if (!empty($containers)) {
                    //
                    foreach ($containers as $container) {
                        // code...
                        // \Log::info($container->id);
                        $terminal = $this->fetch_terminal($container->id);
                        if (!empty($terminal)) {
                            $terminals[$container->id] = $terminal;
                        }
                    }
                }
            }
            $shipment->terminals = json_encode($terminals);
            $shipment->save();
            return response($terminals, 200);
        }
        return response([
          "message" => "Shipment not found."
        ], 404);
    }

    private function fetch_raw_events($container_id)
    {
        $key = config('terminal49.terminal49key');
        $response = Http::withHeaders([
                              "Authorization" => 'Token '.$key,
                              "Content-type" => "application/json"
                          ])
                          ->get('https://api.terminal49.com/v2/containers/'.$container_id.'/raw_events');
        if ($response->status() == 200) {
            return $response->json();
        } else {
            \Log::info($response->json());
        }
        return null;
    }

    private function fetch_standard_events($container_id)
    {
        $response = Http::withHeaders([
                              "Authorization" => 'Token '.env("TERMINAL49_API_KEY", null),
                              "Content-type" => "application/json"
                          ])
                          ->get('https://api.terminal49.com/v2/containers/'.$container_id.'/transport_events');
        if ($response->status() == 200) {
            return $response->json();
        } else {
            \Log::info($response->json());
        }
        return null;
    }

    private function fetch_terminal($container_id)
    {
        $response = Http::withHeaders([
                              "Authorization" => 'Token '.env("TERMINAL49_API_KEY", null),
                              "Content-type" => "application/json"
                          ])
                          ->get('https://api.terminal49.com/v2/containers/'.$container_id.'?include=pod_terminal');
        if ($response->status() == 200) {
            // \Log::info($response->json());
            return $this->getIncludedObject($response->json()["included"], "terminal", $response->json()["data"]['relationships']['pod_terminal']);
        } else {
            \Log::info($response->json());
        }
        return null;
    }

    // resync the shipment
    public function resync(Request $request)
    {
        $shipment = Terminal49Shipment::find(trim($request->mbl_num));
        if ($shipment) {
            // resync the shipment
            $resynced_shipment = $this->getShipmentApi($shipment->ts_id);
            if (!empty($resynced_shipment)) {
                $shipment->attributes = json_encode($resynced_shipment['data']['attributes']);
                $shipment->relationships = json_encode($resynced_shipment['data']['relationships']);
                $shipment->containers = json_encode($this->getContainers($resynced_shipment['included']));
                $shipment->terminals = null;
                $shipment->save();
            }
        }
        return response([], 200);
    }
    // Helper functions

    /**
     *  @return array
     */

    private function getIncludedObject($included, $type, $pod_terminal)
    {
        foreach ($included as $included_object) {
            // code...
            if ($included_object['type'] == $type && !empty($pod_terminal) && !empty($pod_terminal['data']) && $included_object['id'] == $pod_terminal['data']['id']) {
                return $included_object;
            }
        }
        return [];
    }

    /**
     * @return array
     */

    private function getContainers($included)
    {
        $containers=[];
        foreach ($included as $included_object) {
            // code...
            if ($included_object['type'] == "container") {
                array_push($containers, $included_object);
            }
        }
        return $containers;
    }

    /**
     * @return array
     */
    private function getShipmentApi($id)
    {
        if (!empty($id)) {
            // fetch the container data from terminal49
            $response = Http::withHeaders([
                  "Authorization" => 'Token '.env("TERMINAL49_API_KEY", "DkUUMTgo2kBif1eFe9LGq88t"),
                  "Content-type" => "application/json"
                ])
                ->get('https://api.terminal49.com/v2/shipments/'.$id."?include=containers");

            // if the status is ok ~ 200
            if ($response->status() == 200) {
                return $response->json();
            }
        }
        return null;
    }

    //
    public function ignoreDemurrage(Request $request, $mbl_num)
    {
        if ($request->ignore_demurrage) {
            $terminal49_shipment = Terminal49Shipment::find(trim($mbl_num));
            if($terminal49_shipment){
                $terminal49_shipment->ignore_demurrage = $request->ignore_demurrage;
                $terminal49_shipment->save(); // triggers the ovbserver
            }
            // Terminal49Shipment::where('mbl_num', $mbl_num)->update(['ignore_demurrage' => $request->ignore_demurrage]);
            return response()->json([], 200);
        }
        return response()->json([$request->ignore_demurrage], 500);
    }

    public function changelogs($mbl_num)
    {
        $changelogs = [];
        if (!empty($mbl_num)) {
            $changelogs = Terminal49Changelog::where("mbl_num", $mbl_num)
                               ->orderBy('id', 'asc')
                               ->get();
        }
        return response($changelogs, 200);
    }
}
