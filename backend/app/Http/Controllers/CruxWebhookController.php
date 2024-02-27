<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Carbon\Carbon;
use App\Shipment;
use Illuminate\Support\Facades\Mail;
use App\Mail\ShipmentLeave;
use App\Mail\Test;
use App\CruxContainer;

class CruxWebhookController extends Controller
{
    public function handle(Request $request)
    {
        Mail::to(["anthony@shifl.com"])->cc(['shabsie@shifl.com'])->send(new Test("Crux Webhook Accessed", "mails.test_crux_webhook"));


        if ($request->vessel['eta'] &&  $request->container['number']) {
            $eta = Carbon::parse($request->vessel['eta'])->format('Y-m-d');

            // \Log::info($eta);
            // \Log::info($request->container['number']);
            $shipments = Shipment::where("delivered", false)->get()->filter(function ($shipment) use ($request) {
                $containers = json_decode($shipment->containers_group);
                if ($containers) {
                    foreach ($containers as $container) {
                        if ($container->container_num == $request->container['number']) {
                            return true;
                            break;
                        }
                    }
                }
            });
            if ($shipments) {
                foreach ($shipments as $shipment) {
                    $schedules = json_decode($shipment->schedules_group);
                    // \Log::info($schedules);
                    if ($schedules) {
                        foreach ($schedules as $schedule) {
                            if (Carbon::parse($schedule->eta)->format('Y-m-d') != $eta) {
                                $schedule->eta = $eta;
                                $shipment->schedules_group = json_encode($schedules);
                                $shipment->save();
                                // $this->send_eta_update_alert($shipment);
                                // \Log::info("Eta updated");
                            }
                            break;
                        }
                    }

                    $schedules_bookings = json_decode($shipment->schedules_group_bookings);
                    // \Log::info($schedules);
                    if ($schedules_bookings) {
                        foreach ($schedules_bookings as $schedule_booking) {
                            if (Carbon::parse($schedule_booking->eta)->format('Y-m-d') != $eta) {
                                $schedule_booking->eta = $eta;
                                $shipment->schedules_group_bookings = json_encode($schedules_bookings);
                                $shipment->save();
                                // $this->send_eta_update_alert($shipment);
                                // \Log::info("Eta updated");
                            }
                            break;
                        }
                    }
                }
            }
        }
        // \Log::info($schedules);
        // \Log::info("email sent ");

        if ($request->container['number']) {
            // CruxContainer::insert([
            //     'container_number' => $request->container['number'],
            //     'event_type' => $request->event_type,
            //     'container' => json_encode($request->container),
            //     'vessel' => json_encode($request->vessel),
            //     'terminal' => json_encode($request->terminal),
            //     'events' => json_encode($request->events)
            // ]);
            CruxContainer::updateOrCreate(
                ['container_number' => $request->container['number'] ],
                [
                    // 'container_number' => $request->container['number'],
                    'event_type' => $request->event_type,
                    'container' => json_encode($request->container),
                    'vessel' => json_encode($request->vessel),
                    'terminal' => json_encode($request->terminal),
                    'events' => json_encode($request->events)
                ]
            );
        }
    }
}
