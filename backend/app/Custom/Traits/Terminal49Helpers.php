<?php
namespace App\Custom\Traits;
use Illuminate\Support\Facades\Http;
use App\Terminal49ShippingLine;

trait Terminal49Helpers{
    private function syncShippingLines(){
        $response = Http::withHeaders([
          "Authorization" => 'Token '.env("TERMINAL49_API_KEY", "DkUUMTgo2kBif1eFe9LGq88t"),
          "Content-type" => "application/json"
        ])
        ->get('https://api.terminal49.com/v2/shipping_lines');

        if($response->status() == 200){
            foreach ($response->json()['data'] ?? [] as $shipping_line) {
                $attributes = $shipping_line['attributes'];
                $t49_shipping_line = Terminal49ShippingLine::where('scac' , $attributes['scac'])->first();

                if($t49_shipping_line){
                    $t49_shipping_line->update([
                      'type' => $shipping_line['type'],
                      "name" => $attributes['name'],
                      "short_name" => $attributes['short_name'],
                      "bill_of_lading_tracking_support" => $attributes['bill_of_lading_tracking_support'],
                      "booking_number_tracking_support" => $attributes['booking_number_tracking_support'],
                      "container_number_tracking_support" => $attributes['container_number_tracking_support'],
                    ]);
                }else{
                   Terminal49ShippingLine::insert([
                     'scac' => $attributes['scac'],
                     'type' => $shipping_line['type'],
                     't49_uuid' => $shipping_line['id'],
                     "name" => $attributes['name'],
                     "short_name" => $attributes['short_name'],
                     "bill_of_lading_tracking_support" => $attributes['bill_of_lading_tracking_support'],
                     "booking_number_tracking_support" => $attributes['booking_number_tracking_support'],
                     "container_number_tracking_support" => $attributes['container_number_tracking_support'],
                     "created_at" => now(),
                     "updated_at" => now()
                   ]);
                }
            }
        }
    }

    private function getScac($mbl)
    {
        //
        $replace_default_scac = [
          "MEDU" => "MSCU"
        ];
        //
        $scac = substr($mbl, 0, 4);
        if (array_key_exists($scac, $replace_default_scac)) {
            return $replace_default_scac[$scac];
        }
        return $scac;
    }

    private function validateScac($mbl){
        $scac = $this->getScac($mbl);
        if(Terminal49ShippingLine::where('scac', $scac)->where('bill_of_lading_tracking_support',true)->exists()){
            return true;
        }
        return false;
    }

    // utility functions
    private function getTransportEvent($container_id, $transport_events, $event_name)
    {
        //
        if (!empty(trim($transport_events))) {
            $transport_events = json_decode($transport_events, true);
            if (!empty($transport_events) && count($transport_events) > 0) {
                if (isset($transport_events[$container_id]['data'])) {
                    foreach ($transport_events[$container_id]['data'] ?? [] as $transport_event) {
                        // code...
                        if (isset($transport_event['attributes']['event']) && $transport_event['attributes']['event'] == $event_name) {
                            return $transport_event;
                        }
                    }
                }
            }
        }
        return false;
    }


}
