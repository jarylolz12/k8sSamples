<?php

namespace App\Http\Resources\CLIENT\v1;

use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Http;

class Status extends JsonResource
{
    private $terminal49_shipment;
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        $status = [];

        $this->terminal49_shipment = $this->terminal_fortynine;
        if($this->terminal49_shipment){
            $containers = json_decode($this->terminal49_shipment->containers ?? '[]',true);
            foreach ($containers  as $key => $container) {
                // code...
                array_push($status,$this->getContainerStatus($container));
            }
        }
        return $status;
    }

    private function getContainerStatus($container){
        return [
          'container_num' =>  $container['attributes']['number'],
          "last_free_day" => $container['attributes']['pickup_lfd'],
          "released_at_terminal" => $container['attributes']['available_for_pickup'],
          "holds" => $container['attributes']['holds_at_pod_terminal'],
          "fees" => $container['attributes']['fees_at_pod_terminal'],
          "transport_events" => $this->fetch_transport_events($container['id'])
        ];
    }

    private function fetch_transport_events($container_id)
    {
        $response = Http::withHeaders([
                              "Authorization" => 'Token '.env("TERMINAL49_API_KEY", "DkUUMTgo2kBif1eFe9LGq88t"),
                              "Content-type" => "application/json"
                          ])
                          ->get("https://api.terminal49.com/v2//containers/$container_id/transport_events");
        if ($response->status() == 200) {
            $events = [];

            foreach ($response->json()['data'] as $key => $event) {
                // code...
                array_push($events,$event['attributes']);
            };
            return $events;
        } else {
            \Log::info($response->json());
        }
        return null;
    }
}
