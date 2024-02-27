<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Terminal49Shipment;
use Illuminate\Support\Facades\Http;
use GuzzleHttp\Client;
/**
 *
 * @group  Milestones
 *
 * APIs to manage the milestones resource
 *
*/
class ContainerMilestonesController extends Controller
{
    /**
     *
     * Display the specified resource.
     *
     * @authenticated
     *
     * @urlParam mbl_num string required The Terminal 49 Shipments mbl_num
     *
     * @apiResource App\Http\Resources\Standard
     * @apiResourceModel App\Terminal49Shipment
     *
	 * @response status=400 scenario="Unauthenticated" {
     *     "message": "Unauthenticated."
     * }
     *
     *
    */

    public function index(Request $request, $mbl_num)
    {
        $milestones = [];

        $shipment = Terminal49Shipment::find(trim($mbl_num));

        if ($shipment ?? false) {
            $containers = json_decode($shipment->containers);

            if ($containers ?? false) {
                foreach ($containers as $container) {
                    // code...
                    if (!empty($container->id)) {
                        $fetch_stored_data = false;
                        try {
                            $events = $this->fetch_events($container->id, $request->standard);
                        } catch (\Exception $e) {
                            \Log::info($e);
                            $fetch_stored_data = true;
                        }
                        if($fetch_stored_data || $events == null){
                            if($request->standard){
                              $events_array = json_decode($shipment->transport_events ?? '[]');
                              $events = [];
                              foreach ($events_array as $key => $value) {
                                  if($key == $container->id) {
                                      $events = $value;
                                      break;
                                  }
                              }
                            }else{
                              $events = [];
                            }
                        }
                        if ($events ?? false) {
                            $milestones[$container->attributes->number] = $events;
                        }
                    }
                }
            }
        }
        return response($milestones, 200);
    }


    private function fetch_events($container_id, $standard = false)
    {
        $response = Http::withHeaders([
                              "Authorization" => 'Token '.env("TERMINAL49_API_KEY", "DkUUMTgo2kBif1eFe9LGq88t"),
                              "Content-type" => "application/json"
                          ])
                          ->get('https://api.terminal49.com/v2//containers/'.$container_id. ($standard ? '/transport_events' : '/raw_events'));
        if ($response->status() == 200) {
            return $response->json();
        } else {
            \Log::info($response->json());
        }
        return null;
    }
}
