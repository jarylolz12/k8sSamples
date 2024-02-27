<?php
namespace App\Custom\Traits;

use Carbon\Carbon;
use Illuminate\Support\Facades\Http;
use GuzzleHttp\Client;
use App\Terminal49Shipment;

trait Resyncable{

    //
    private function sendErrorLogMail($errors){
        $html = "";
        foreach ($errors as $key => $error) {
            $html .= "<h5> " . $error["title"] . "</h5> <br> <pre> " . $error["trace"] . "</pre> <br>";
        }
        \Mail::
              to(["tanvir@shifl.com"])
              ->send(new \App\Mail\SimpleString("Resync Failure Log - " . $this->option("frequency"), $html));
    }

    private function twiceDaily()
    {
        //
        $shipments = Terminal49Shipment::where('etd', '<', now())->where('eta', '>', now())->where('is_completed', 0)->orWhere('eta', null)->orWhere('etd', null)->get();
        // $shipments = $this->filter($shipments);
        $bar = $this->output->createProgressBar(count($shipments));
        $errors = [];
        foreach ($shipments as $shipment) {
            // code...
            try{
                $this->resync($shipment);
            } catch (\Exception $e) {
                array_push($errors, ['title' => $e->getMessage(), 'trace' => $e->getTraceAsString()]);
                \Log::info($e->getTraceAsString());
            }

            $bar->advance();
        }
        $bar->finish();

        if(count($errors) > 0) $this->sendErrorLogMail($errors);
    }

    private function everyTwoHours()
    {
        //
        $shipments = Terminal49Shipment::where('is_completed', 0)->where(function($q){
            $q->where('etd', '>', now())
              ->orWhere('eta', '<', now());
        })->get();
        // $shipments = $this->filter($shipments);
        $bar = $this->output->createProgressBar(count($shipments));
        $errors = [];
        foreach ($shipments as $shipment) {
            // code...
            try{
                $this->resync($shipment);
            } catch (\Exception $e) {
                array_push($errors, ['title' => $e->getMessage(), 'trace' => $e->getTraceAsString()]);
                \Log::info($e->getTraceAsString());
            }

            $bar->advance();
        }
        $bar->finish();

        if(count($errors) > 0) $this->sendErrorLogMail($errors);
    }

    private function filter($shipments)
    {
        return $shipments->filter(function ($shipment) {
            if (empty($shipment->eta) || empty($shipment->etd)) {
                return true;
            }
            $containers = empty($shipment->containers) ? [] : json_decode($shipment->containers ?? '[]');
            foreach ($containers ?? [] as $container) {
                // code...
                $released_date =  $container->attributes->pod_full_out_at ?? $container->attributes->empty_terminated_at;
                if (empty($released_date)) {
                    return true;
                } elseif (!(Carbon::parse($released_date)->gt(Carbon::parse($shipment->eta)))) {
                    return true;
                }
            }
            return count($containers ?? []) == 0;
        });
    }
    // resync the shipment
    private function resync($shipment)
    {
        if ($shipment) {
            // resync the shipment
            $resynced_shipment = $this->getShipmentApi($shipment->ts_id);
            if (!empty($resynced_shipment)) {
                $shipment->attributes = json_encode($resynced_shipment['data']['attributes']);
                $shipment->relationships = json_encode($resynced_shipment['data']['relationships']);
                $shipment->containers = json_encode($this->getContainers($resynced_shipment['included']));
                // transport events
                $transport_events =[];
                $containers = $this->getContainers($resynced_shipment['included']);
                if (!empty($containers)) {
                    //
                    foreach ($containers as $container) {
                        // code...
                        $transport_event = $this->fetch_standard_events($container['id']);
                        if (!empty($transport_event)) {
                            $transport_events[$container['id']] = $transport_event;
                        }
                    }
                }
                $shipment->transport_events = json_encode($transport_events);
                //
                $shipment->save();
            }
        }
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
    //
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
}
