<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Mail;
use App\Terminal49Shipment;
use App\Shipment;
use Illuminate\Support\Facades\Http;
use GuzzleHttp\Client;

class Terminal49WebhookController extends Controller
{
    private $data;
    private $included;

    /**
     * handle webhook request
     * @return void
     */

    public function handle(Request $request)
    {
        // \Log::info($request->json());
        $this->data = $request->data;
        $this->included = $request->included;
        //
        // \Log::info("type xyz : ". gettype($this->data));
        if ($this->data['type'] == "webhook_notification") {
            $this->trafficRouting();
        }
    }

    /**
     * handle webhook traffic Routing
     * @return void
     */


    private function trafficRouting()
    {
        switch ($this->data["attributes"]["event"]) {
        // handle webhook while tracking request failed
        case 'tracking_request.failed':
          $this->trackingRequestFailed();
          break;

        // handle webhook while tracking request succeeded
        case 'tracking_request.succeeded':
            $this->trackingRequestSucceeded();
            break;

        // handle webhook shipment estimated arrival
        case 'shipment.estimated.arrival':
            $this->shipmentEstimatedArrival();
            break;

        // handle webhook shipment estimated arrival
        case 'container.updated':
            $this->containerUpdated();
            break;

        default:
          //
          break;
      }
    }

    // handle webhooks.

    /**
     * handle tracking_request.succeeded - type of webhook response
     * @return void
     */

    private function trackingRequestSucceeded()
    {
        $tracking_request = $this->getIncludedObject("tracking_request");
        $shipment = $this->getIncludedObject("shipment");
        $containers = $this->getContainers();


        if (!empty($tracking_request)) {
            $tr_id = $tracking_request['id'];
            $mbl_num = $shipment["attributes"]["bill_of_lading_number"];

            if (!empty($shipment)) {
                $ts_id = $shipment["id"];
                $shipment_attributes = $shipment['attributes'];
                $shipment_relationships = $shipment['relationships'];

                Terminal49Shipment::updateOrCreate([
                    'mbl_num' => $mbl_num,
                    'ts_id' => $ts_id,
                ],[
                    'tr_id' => $tr_id,
                    'attributes' => json_encode($shipment_attributes),
                    'relationships' => json_encode($shipment_relationships),
                    'containers' => json_encode($containers)
                ]);

                //
                $shipment_table = Shipment::where("mbl_num", $mbl_num)->first();
                $shipment_table->retry_tracking_request = 0;
                $shipment_table->save();
                //
                $this->updateShipmentEta($mbl_num, true);
            }
        }
    }

    /**
     * handle tracking_request.failed - type of webhook response
     * @return void
     */

    private function trackingRequestFailed()
    {
        $tracking_request = $this->getIncludedObject("tracking_request");
        if (!empty($tracking_request)) {
            $shipment_table = Shipment::where("mbl_num", $tracking_request["attributes"]["request_number"])->first();
            $send_failed_mail = false;
            if ($shipment_table) {
                if ($shipment_table->etd) {
                    $etd = \Carbon\Carbon::parse($shipment_table->etd);
                    $today = \Carbon\Carbon::now();




                    // if ($etd->gt($today)) {
                    //     // \Log::info("etd is greater that today");
                    //     if ($etd->diffInDays($today) > 1) {
                    //         // continue retrying.
                    //         // \Log::info("retry tracking request");
                    //         $shipment_table->retry_tracking_request++;
                    //     } else {
                    //         // \Log::info("Do not retry anymore");
                    //         $send_failed_mail = true;
                    //     }
                    // } else {
                    //     // \Log::info("etd is not greater that today");
                    //     $send_failed_mail = true;
                    // }



                    // moving up the criteria from 2 days before etd to 2 days after etd.

                    // if ($today->gt($etd) && $today->diffInDays($etd) > 2) {
                    //     $send_failed_mail = true;
                    // } else {
                    //     $shipment_table->retry_tracking_request++;
                    // }
                    if ($shipment_table->eta > now()) {
                        $shipment_table->retry_tracking_request++;
                    // \Log::info("D-001");
                    } else {
                        // \Log::info("D-002");
                        $send_failed_mail = true;
                    }
                    //
                    if ($etd->format("Y-m-d") == now()->format("Y-m-d")) {
                        // \Log::info("D-003");
                        $send_failed_mail = true;
                    }
                    //






                    if ($send_failed_mail) {
                        // \Log::info("sending tracking failed mail");
                        $this->sendTrackingFailedMail($tracking_request, $shipment_table->retry_tracking_request);
                        // $shipment_table->retry_tracking_request = 0;
                    }
                    $shipment_table->tracking_request_id = null;
                    $shipment_table->save();
                }
            }
        }
    }


    /**
     * handle shipment.estimated.arrival- type of webhook response
     * @return void
     */

    private function shipmentEstimatedArrival()
    {
        $shipment = $this->getIncludedObject("shipment");

        if (!empty($shipment)) {
            $ts_id = $shipment["id"];
            $shipment_attributes = $shipment['attributes'];
            $shipment_relationships = $shipment['relationships'];
            $mbl_num = $shipment_attributes['bill_of_lading_number'];

            $shipment_terminal49 = Terminal49Shipment::find($mbl_num);

            if ($shipment_terminal49) {
                $shipment_terminal49->attributes = json_encode($shipment_attributes);
                $shipment_terminal49->relationships = json_encode($shipment_relationships);
                $shipment_terminal49->save();
            } else {
                Terminal49Shipment::create([
                    'mbl_num' => $mbl_num,
                    'ts_id' => $ts_id,
                    'attributes' => json_encode($shipment_attributes),
                    'relationships' => json_encode($shipment_relationships),
                ]);
                $containers = [];

                if (!empty($shipment_relationships['containers'])) {
                    foreach ($shipment_relationships['containers']['data'] ?? [] as $container) {
                        // code...
                        if ($container['type'] == "container") {
                            $container_api = $this->getContainerApi($container['id']);
                            if ($container_api) {
                                array_push($containers, $container_api);
                            }
                        }
                    }
                    if (!empty($containers)) {
                        $shipment_t49 = Terminal49Shipment::find($mbl_num);
                        $shipment_t49->containers = json_encode($containers);
                        // \Log::info($shipment_t49->containers);
                        $shipment_t49->save();
                    }
                }
            }


            $this->updateShipmentEta($mbl_num);
        }
    }



    /**
     * handle container.updated- type of webhook response
     * @return void
     */

    private function containerUpdated()
    {
        $container = $this->getIncludedObject("container");

        if (!empty($container)) {
            // $container_num = $container['attributes']['number'] ?? null;
            $container_id = $container['id'];
            $container_shipment = $container['relationships']['shipment'] ?? null;
            if ($container_shipment) {
                $ts_id = $container_shipment['data']['id'] ?? null;
                if ($ts_id) {
                    $shipment_t49 = Terminal49Shipment::where("ts_id", $ts_id)->first();
                    if ($shipment_t49) {
                        $shipment_t49_containers = json_decode($shipment_t49->containers);
                        foreach ($shipment_t49_containers ?? [] as $key => $shipment_t49_container) {
                            // code...
                            // $shipment_t49_container_number = $shipment_t49_container->attributes->number ?? null;

                            $shipment_t49_container_id = $shipment_t49_container->id ?? null;

                            // if ($shipment_t49_container_number && $container_num ) {
                            //     if ($shipment_t49_container_number == $container_num) {
                            //         $shipment_t49_containers[$key] = $container;
                            //         break;
                            //     }
                            // }

                            if ($shipment_t49_container_id && $container_id) {
                                if ($shipment_t49_container_id == $container_id) {
                                    $shipment_t49_containers[$key] = $container;
                                    // \Log::info("updated from 001");
                                    break;
                                }
                            }
                        }
                        $shipment_t49->containers = json_encode($shipment_t49_containers);

                        // Check for Terminals Update
                        $container_updated_event = $this->getIncludedObject("container_updated_event");
                        if (!empty($container_updated_event)) {
                            if (data_get($container_updated_event, 'attributes.changeset.pod_terminal', false)) {
                                //
                                $terminal = $this->getIncludedObject("terminal");
                                if (!empty($terminal)) {
                                    $pod_terminal_id = data_get($container, 'relationships.pod_terminal.data.id', false);
                                    if ($pod_terminal_id && $pod_terminal_id == $terminal['id']) {
                                        if (!empty($shipment_t49->terminals)) {
                                            $terminals= json_decode($shipment_t49->terminals, true);
                                            $terminals[$container['id']] = $terminal;
                                            $shipment_t49->terminals = json_encode($terminals);
                                        } else {
                                            $terminals=[];
                                            $terminals[$container['id']] = $terminal;
                                            $shipment_t49->terminals = json_encode($terminals);
                                        }
                                    }
                                }
                            }
                        }

                        //

                        $shipment_t49->save();
                        return;
                    }
                }
            }

            // $shipment_t49 = Terminal49Shipment::all()->filter(function ($shipment) use ($container_num) {
            //     $containers_t49_filter = json_decode($shipment->containers);
            //     foreach ($containers_t49_filter ?? [] as $c_t49) {
            //         // code...
            //         if ($container_num && $c_t49->attributes->number == $container_num) {
            //             return true;
            //         }
            //         return false;
            //     }
            // })->first();

            $shipment_t49 = Terminal49Shipment::all()->filter(function ($shipment) use ($container_id) {
                $containers_t49_filter = json_decode($shipment->containers);
                foreach ($containers_t49_filter ?? [] as $c_t49) {
                    // code...
                    if ($container_id && $c_t49->id == $container_id) {
                        return true;
                    }
                    return false;
                }
            })->first();

            if ($shipment_t49) {
                $shipment_t49_containers = json_decode($shipment_t49->containers);
                foreach ($shipment_t49_containers ?? [] as $key => $shipment_t49_container) {
                    // code...
                    // $shipment_t49_container_number = $shipment_t49_container->attributes->number ?? null;

                    $shipment_t49_container_id = $shipment_t49_container->id ?? null;

                    // if ($shipment_t49_container_number && $container_num) {
                    //     if ($shipment_t49_container_number == $container_num) {
                    //         $shipment_t49_containers[$key] = $container;
                    //         break;
                    //     }
                    // }

                    if ($shipment_t49_container_id && $container_id) {
                        if ($shipment_t49_container_id == $container_id) {
                            $shipment_t49_containers[$key] = $container;
                            // \Log::info("updated from 002");
                            break;
                        }
                    }
                }
                $shipment_t49->containers = json_encode($shipment_t49_containers);
                $shipment_t49->save();
                return;
            }
        }
    }




    // Custom functions

    /**
     *  @return void
     */

    private function updateShipmentEta($mbl_num, $syncing = false)
    {
        if ($syncing) {
            $eta_object = $this->getIncludedObject("shipment");
        } else {
            $eta_object = $this->getIncludedObject("estimated_event");
        }
        $etd_object = $this->getIncludedObject("shipment");

        if (!empty($eta_object)) {
            if ($syncing) {
                $eta = $eta_object['attributes']['pod_eta_at'];
            } else {
                $eta = $eta_object['attributes']['estimated_timestamp'];
            }
            if (!empty($etd_object)) {
                $etd = $etd_object['attributes']['pol_atd_at'];
                if (empty($etd)) {
                    $etd = $etd_object['attributes']['pol_etd_at'];
                }
            } else {
                $etd = "";
            }

            if (!empty($eta)) {
                $eta = \Carbon\Carbon::parse($eta)->format('Y-m-d');

                if (!empty($etd)) {
                    $etd = \Carbon\Carbon::parse($etd)->format('Y-m-d');
                }

                $shipment_table = Shipment::where("mbl_num", $mbl_num)->first();

                if ($shipment_table) {
                    //
                    if (!empty($shipment_table->schedules_group_bookings)) {
                        $schedules_group_bookings = json_decode($shipment_table->schedules_group_bookings);
                        if (!empty($schedules_group_bookings)) {
                            foreach ($schedules_group_bookings ?? [] as $schedule) {
                                // code...
                                if (isset($schedule) && isset($schedule->is_confirmed) && $schedule->is_confirmed) {
                                    //
                                    $app_eta = empty($schedule->eta) ? 'XXXX-XX-XX' : \Carbon\Carbon::parse($schedule->eta)->format("Y-m-d");
                                    $app_etd = empty($schedule->etd) ? 'XXXX-XX-XX' : \Carbon\Carbon::parse($schedule->etd)->format("Y-m-d");

                                    if (!empty($etd)) {
                                        if (empty($schedule->etd) || $app_etd != $etd) {
                                            // eta changed
                                            $schedule->etd = \Carbon\Carbon::parse($etd)->format("Y-m-d H:i:s");
                                            $shipment_table->etd = $etd;
                                            //Mail::to(['tanvir@shifl.com'])->cc(['shabsie@shifl.com'])
                                            Mail::to(['tanvir@shifl.com'])->cc([])
                                                ->send(new \App\Mail\Test("ETD was changed from ".$app_etd." to ".$etd." by Terminal49 | "."shifl Ref#". $shipment_table->shifl_ref . " MBL#".$mbl_num.".", "mails.eta_updated_by_terminal49", "ETD"));

                                            // break;
                                        }
                                    }
                                    if (empty($schedule->eta) || $app_eta != $eta) {
                                        // eta changed
                                        $schedule->eta = \Carbon\Carbon::parse($eta)->format("Y-m-d H:i:s");
                                        $shipment_table->eta = $eta;

                                        //Mail::to(['tanvir@shifl.com'])->cc(['shabsie@shifl.com'])
                                        Mail::to(['tanvir@shifl.com'])->cc([])
                                              ->send(new \App\Mail\Test("ETA was changed from ".$app_eta." to ".$eta." by Terminal49 | "."shifl Ref#". $shipment_table->shifl_ref . " MBL#".$mbl_num.".", "mails.eta_updated_by_terminal49"));

                                        break;
                                    }
                                }
                            }
                        }
                    }

                    $shipment_table->schedules_group_bookings = json_encode($schedules_group_bookings);
                    $shipment_table->save();
                }
            }
        }
    }


    // Helper functions

    /**
     *  @return array
     */

    private function getIncludedObject($type)
    {
        foreach ($this->included as $included_object) {
            // code...
            if ($included_object['type'] == $type) {
                return $included_object;
            }
        }
        return [];
    }

    /**
     * @return array
     */

    private function getContainers()
    {
        $containers=[];
        foreach ($this->included as $included_object) {
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
    private function getContainerApi($id)
    {
        if (!empty($id)) {
            // fetch the container data from terminal49
            $response = Http::withHeaders([
              "Authorization" => 'Token '.env("TERMINAL49_API_KEY", null),
              "Content-type" => "application/json"
            ])
            ->get('https://api.terminal49.com/v2//containers/'.$id);

            // if the status is ok ~ 200
            if ($response->status() == 200) {
                return $response->json()['data'];
            }
        }
        return null;
    }

    /**
     * send tracking failed mail.
     * @return void
     */

    private function sendTrackingFailedMail($tracking_request, $retry_count)
    {
        Mail::to(['tanvir@shifl.com'])->cc(['shabsie@shifl.com','eric@shifl.cn'])
        // Mail::to(['tanvir@shifl.com'])->cc([])
         ->send(new \App\Mail\ErrorLogging(
             'Tracking Request Failed, Shipment : '.$tracking_request["attributes"]["request_number"],
             [
              [
                'title' => 'Tracking Request '. $tracking_request["attributes"]['status'],
                'code' => $tracking_request["attributes"]["failed_reason"] ,
                'detail' => 'Shipment '.$tracking_request["attributes"]["request_number"].' tracking request failed. Failed Reason : '.$tracking_request["attributes"]["failed_reason"],
                'retry_count' => $retry_count
              ]
            ],
             'mails.error.tracking'
         ));
    }
}
