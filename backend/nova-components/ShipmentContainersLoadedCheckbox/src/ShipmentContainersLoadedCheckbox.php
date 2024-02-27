<?php

namespace Vishalmarakana\ShipmentContainersLoadedCheckbox;

use App\Shipment;
use App\Terminal49Shipment;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Laravel\Nova\Fields\Field;
use Laravel\Nova\Http\Requests\NovaRequest;

class ShipmentContainersLoadedCheckbox extends Field
{
    /**
     * The field's component.
     *
     * @var string
     */
    public $component = 'shipment-containers-loaded-checkbox';


    public function initFields($mbl_num, $so_received, $is_agent_booking_confirm, $id)
    {
        $r = new NovaRequest();

        $is_containers_loaded = 0;
        if (!$r->isResourceIndexRequest()) {
            $findShipment = ($id!=null) ? Shipment::find($id) : [];
            $findContainerSizes = \App\ContainerSize::all();
            $rateSizes = [];

            if (count($findContainerSizes) > 0) {
                foreach ($findContainerSizes as $key => $findContainerSize) {
                    array_push($rateSizes, [
                        'id' => $findContainerSize->id,
                        'name' => $findContainerSize->name
                    ]);
                }
            }

            if (isset($findShipment->id)) {

                $scheduleBookings = (is_array(json_decode($findShipment->schedules_group_bookings))) ? json_decode($findShipment->schedules_group_bookings) : [];
                $containersGroupUntracked = json_decode($findShipment->containers_group_untracked, true) ?? [];

                if (is_countable($scheduleBookings) && count($scheduleBookings) > 0) {

                    foreach ($scheduleBookings as $key => $scheduleBooking) {

                        if (!isset($scheduleBooking->carrier_name)) {
                            $scheduleBookings[$key]->carrier_name = [
                                'id' => 0
                            ];
                        } else {
                            if ( $scheduleBooking->carrier_name==null ) {
                                $scheduleBookings[$key]->carrier_name = [
                                    'id' => 0
                                ];
                            }
                        }
                        if (!isset($scheduleBooking->vendor_id)) {
                            $scheduleBookings[$key]->vendor_id = 0;
                        }

                        if (!isset($scheduleBooking->sell_rates)) {
                            $scheduleBookings[$key]->sell_rates = [];
                        } else {
                            if (isset($scheduleBooking->sell_rates) && $scheduleBooking->sell_rates!=='' && $scheduleBooking->sell_rates!==null) {
                                if (count($scheduleBooking->sell_rates) > 0) {

                                    foreach($scheduleBooking->sell_rates as $k => $sr) {
                                        if (!isset($scheduleBooking->sell_rates[$k]->valid_from)) {
                                            $scheduleBookings[$key]->sell_rates[$k]->valid_from = null;
                                        }

                                        if (!isset($scheduleBooking->sell_rates[$k]->valid_to)) {
                                            $scheduleBookings[$key]->sell_rates[$k]->valid_to = null;
                                        }

                                    }
                                }
                            }
                        }


                        if (!isset($scheduleBooking->buy_rates)) {
                            $scheduleBookings[$key]->buy_rates = [];
                        } else {
                            if (isset($scheduleBooking->buy_rates) && $scheduleBooking->buy_rates!=='' && $scheduleBooking->buy_rates!==null) {
                                if (count($scheduleBooking->buy_rates) > 0) {

                                    foreach($scheduleBooking->buy_rates as $k => $sr) {
                                        if (!isset($scheduleBooking->buy_rates[$k]->valid_from)) {
                                            $scheduleBookings[$key]->buy_rates[$k]->valid_from = null;
                                        }

                                        if (!isset($scheduleBooking->buy_rates[$k]->valid_to)) {
                                            $scheduleBookings[$key]->buy_rates[$k]->valid_to = null;
                                        }

                                    }
                                }
                            }
                        }

                        if (!isset($scheduleBooking->legs)) {
                            $scheduleBookings[$key]->legs = [];
                        }

                        foreach($rateSizes as $size) {

                            if (isset($scheduleBooking->selling_size_id)) {
                                if ($size['id']==$scheduleBooking->selling_size_id) {
                                    $scheduleBookings[$key]->selling_size_name = $size['name'];
                                }
                            }
                            if (isset($scheduleBooking->size_id)) {
                                if ($size['id']==$scheduleBooking->size_id) {
                                    $scheduleBookings[$key]->size_name = $size['name'];
                                }
                            }

                            if (isset($scheduleBooking->sell_rates[0]) && count($scheduleBooking->sell_rates) > 0) {
                                foreach ($scheduleBooking->sell_rates as $keySecond => $sell_rate) {
                                    if ($sell_rate->container_size_id == $size['id']) {
                                        $scheduleBooking->sell_rates[$keySecond]->container_size_name = $size['name'];
                                    }
                                }
                            }

                            if (isset($scheduleBooking->buy_rates[0]) && count($scheduleBooking->buy_rates) > 0) {
                                foreach ($scheduleBooking->buy_rates as $keySecond => $buy_rate) {
                                    if ($buy_rate->container_size_id == $size['id']) {
                                        $scheduleBooking->buy_rates[$keySecond]->container_size_name = $size['name'];
                                    }
                                }
                            }

                        }

                    }
                }

                //schedule merge
                $mergeScheduleBookings = $scheduleBookings;

                $finalMergeScheduleBookings = [];
                //$hasConfirmed = false;
                $hasBookingsRecord = false;
                if (count($mergeScheduleBookings) > 0) {
                    $hasBookingsRecord = true;
                }

                if ($hasBookingsRecord) {
                    $findShipment->schedules_group = $mergeScheduleBookings;
                } else {
                    $findShipment->schedules_group = (is_array(json_decode($findShipment->schedules_group))) ? json_decode($findShipment->schedules_group) : [];
                }

                //supplier merge
                $mergeSuppliers = (is_array(json_decode($findShipment->suppliers_group_bookings))) ? json_decode($findShipment->suppliers_group_bookings) : [];

                $mergeToSupplierBookings = (is_array(json_decode($findShipment->suppliers_group))) ? json_decode($findShipment->suppliers_group) : [];

                //ss
                $newSuppliers = [];

                foreach ($mergeSuppliers as $key => $value) {
                    array_push($newSuppliers, $value->id);
                }

                foreach ($mergeToSupplierBookings as $key => $value) {
                    if (!in_array($value->id, $newSuppliers)) {
                        array_push($newSuppliers, $value->id);
                    }
                }



                $newFinalSuppliers = [];

                foreach($newSuppliers as $n) {

                    $setId = null;
                    foreach ($mergeSuppliers as $key => $value) {
                        if ($n==$value->id) {
                            array_push($newFinalSuppliers, $value);
                            $setId = $value->id;
                        }
                    }

                    foreach ($mergeToSupplierBookings as $key => $value) {
                        if ($n==$value->id && $setId!=$value->id) {
                            array_push($newFinalSuppliers, $value);
                        }
                    }
                }


                if (count($newFinalSuppliers)>0) {
                    foreach ($newFinalSuppliers as $key => $value) {
                        if (!isset($value->containers)) {
                            $newFinalSuppliers[$key]->containers = [];
                        }

                        if (!isset($value->cargo_ready_date)) {
                            $newFinalSuppliers[$key]->cargo_ready_date = '';
                        }
                    }
                }

                $findShipment->suppliers_group = json_encode($newFinalSuppliers);

                //container merge
                $mergeContainers = (is_array(json_decode($findShipment->containers_group_bookings))) ? json_decode($findShipment->containers_group_bookings) : [];
                $mergeToContainerBookings = (is_array(json_decode($findShipment->containers_group))) ? json_decode($findShipment->containers_group) : [];

                $newContainers = [];

                foreach ($mergeContainers as $key => $value) {
                    array_push($newContainers, $value->id);
                }

                foreach ($mergeToContainerBookings as $key => $value) {
                    if (!in_array($value->id, $newContainers)) {
                        array_push($newContainers, $value->id);
                    }
                }

                $newFinalContainers = [];

                foreach($newContainers as $n) {

                    $setId = null;
                    foreach ($mergeContainers as $key => $value) {
                        if ($n==$value->id) {
                            array_push($newFinalContainers, $value);
                            $setId = $value->id;
                        }
                    }

                    foreach ($mergeToContainerBookings as $key => $value) {
                        if ($n==$value->id && $setId!=$value->id) {
                            array_push($newFinalContainers, $value);
                        }
                    }
                }
                $findShipment->containers_group = json_encode($newFinalContainers);

                if(is_array($newFinalContainers) && count($newFinalContainers) > 0) {
                    $statusUpdated = false;
                    if ($findShipment->containers_group) {
                        $containers_group = json_decode($findShipment->containers_group);
                        if (is_array($containers_group)) {
                            if (count($containers_group) == 1) {
                                $t49shipment = Terminal49Shipment::find(trim($mbl_num));
                                if ($t49shipment) {
                                    $t49containers = $t49shipment->containers;
                                    $containers_group = json_decode($t49containers);
                                    if (!empty($containers_group)) {
                                        foreach ($containers_group ?? [] as $container_id) {
                                            $events = $this->fetch_standard_events($container_id->id);
                                            if ($events) {
                                                $standard_events[$container_id->id] = $events;
                                            }
                                        }
                                    }
                                }


                                if ( isset($standard_events) ) {
                                    $standard_events = collect($standard_events)->first();
                                    foreach ($standard_events ?? [] as $event_id_row) {
                                        foreach ($event_id_row ?? [] as $event_id) {
                                            if (is_array($event_id)) {
                                                if (Arr::exists($event_id, 'attributes')) {
                                                    if ($event_id['attributes']['event'] == 'container.transport.vessel_loaded') {
                                                        $is_containers_loaded = 1;
                                                        Shipment::where('id', $id)->update(['is_containers_loaded' => 1]);
                                                        $statusUpdated = true;
                                                    }
                                                }
                                            }
                                        }
                                    }
                                }
                            }
                            if($statusUpdated == false){
                                $containersGroupUntrackedKey = [];
                                if(is_array($containersGroupUntracked)){
                                    if(Arr::exists($containersGroupUntracked, 'containers') ) {
                                        foreach ($containersGroupUntracked['containers'] ?? [] as $containerUntracked) {
                                            $containersGroupUntrackedKey[$containerUntracked['container_num']] = $containerUntracked;
                                        }
                                    }

                                    $allnewFinalContainers = collect($newFinalContainers)->filter(function ($newFinalContainer) use($containersGroupUntrackedKey) {
                                        return ( isset ($containersGroupUntrackedKey[$newFinalContainer->container_num]) && isset($containersGroupUntrackedKey[$newFinalContainer->container_num]['vessel_loaded']) && $this->IsNullOrEmptyString($containersGroupUntrackedKey[$newFinalContainer->container_num]['vessel_loaded']));
                                    });

                                    if($allnewFinalContainers->count() == 0){
                                        $is_containers_loaded = 1;
                                        Shipment::where('id', $id)->update(['is_containers_loaded' => 1]);
                                    }
                                }
                            }
                        }
                    }
                }


            }


            return $this->withMeta([
                'is_containers_loaded_auto' => $is_containers_loaded,
            ]);
        } else {
            return $this->withMeta([]);
        }
    }

    public function IsNullOrEmptyString($str){
        return ($str === null || trim($str) === '');
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
            \Log::info('https://api.terminal49.com/v2/containers/'.$container_id.'/transport_events');
            \Log::info($response->json());
        }
        return null;
    }
}
