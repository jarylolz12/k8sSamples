<?php
namespace App\Custom\Traits;

/**
 * Validate Terminal
 */

use App\Terminal49Shipment;
use App\Terminal;
use Illuminate\Support\Facades\Http;
use GuzzleHttp\Client;

trait ValidateTerminal
{
    private function validateByTerminal49($shipment, $terminal_id)
    {
        $findTerminal = Terminal::find($terminal_id);
        if ($findTerminal) {
            if (!empty($findTerminal->firms_code)) {
                $firms_code = $findTerminal->firms_code;
                if (!empty($shipment->mbl_num)) {
                    $terminal49_shipment = Terminal49Shipment::find($shipment->mbl_num);
                    if ($terminal49_shipment) {
                        $terminals = $this->get_terminals($shipment->mbl_num);
                        if ($terminals) {
                            if (!empty($terminals)) {
                                $terminals = json_decode($terminals, true);

                                if (count($terminals) > 0) {
                                    foreach ($terminals as $key => $terminal) {
                                        // code...
                                        if ($terminal['attributes']['firms_code'] == $firms_code) {
                                            return false;
                                        }
                                    }
                                    return true;
                                }
                            }
                        }
                    }
                }
            }
        }

        return false;
    }
    private function get_terminals($mbl_num)
    {
        $shipment = Terminal49Shipment::find(trim($mbl_num));

        $terminals = [];

        if ($shipment) {
            // check if terminal data already exist or not

            if ($shipment->terminals) {
                // return already saved data
                return $shipment->terminals;
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
            return $shipment->terminals;
        }
        return null;
    }

    private function fetch_terminal($container_id)
    {
        $key = config('terminal49.terminal49key');
        $response = Http::withHeaders([
                              "Authorization" => 'Token '.$key,
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
}
