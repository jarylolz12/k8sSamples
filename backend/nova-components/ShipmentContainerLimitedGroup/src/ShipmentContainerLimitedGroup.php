<?php

namespace Kenetashi\ShipmentContainerLimitedGroup;

use Laravel\Nova\Fields\Field;
use Laravel\Nova\Http\Requests\NovaRequest;
use App\Shipment;
use App\ContainerSize;

class ShipmentContainerLimitedGroup extends Field
{
    /**
     * The field's component.
     *
     * @var string
     */
    public $component = 'shipment-container-limited-group';

     public function initFields($id) {


        $r = new NovaRequest();

        if (!$r->isResourceIndexRequest()) {
            $sizes = ContainerSize::all();

        $findShipment = ($id!=null) ? Shipment::find($id) : [];

        if (isset($findShipment->id)) {

            //containers merge
            $mergeContainers = (is_array(json_decode($findShipment->containers_group))) ? json_decode($findShipment->containers_group) : [];
            $mergeToContainerBookings = (is_array(json_decode($findShipment->containers_group_bookings))) ? json_decode($findShipment->containers_group_bookings) : [];

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

            $finalNewContainers = $newFinalContainers;

            if (count($finalNewContainers)>0) {
                foreach ($finalNewContainers as $key => $finalNewContainer) {
                    if (count($sizes)>0) {
                        foreach ($sizes as $keySecond => $size) {
                            if (isset($finalNewContainer->size) && $finalNewContainer->size==$size->id) {
                                $finalNewContainers[$key]->size_name = $size->name;
                            }
                        }
                    }
                }
            }

            $finalNewContainers = json_encode($finalNewContainers);
            
            $findShipment->containers_group_bookings = $finalNewContainers;
            $findShipment->containers_group = $finalNewContainers;

            /*
            $mergeContainers = (is_array(json_decode($findShipment->containers_group))) ? json_decode($findShipment->containers_group) : [];

            $mergeToContainerBookings = (is_array(json_decode($findShipment->containers_group_bookings))) ? json_decode($findShipment->containers_group_bookings) : [];
            

            $newContainers = [];
            if(count($mergeContainers) > 0) {
                foreach($mergeContainers as $mergeContainer) {
                    
                    $hasSimilarity = false;
                    if (count($mergeToContainerBookings) > 0) {
                        
                        foreach($mergeToContainerBookings as $mergeToContainerBooking) {
                            if (isset($mergeToContainerBooking->id) && isset($mergeContainer->id)) {
                                if ($mergeToContainerBooking->id==$mergeContainer->id) {
                                    $hasSimilarity = true;
                                }
                            }
                        }
                    }
                    if (!$hasSimilarity) {
                        array_push($newContainers, $mergeContainer);
                    }
                }
            }

            $finalNewContainers = (count($newContainers) > 0) ? array_merge($newContainers, $mergeToContainerBookings) : $mergeToContainerBookings;

            if (count($finalNewContainers)>0) {
                foreach ($finalNewContainers as $key => $finalNewContainer) {
                    if (count($sizes)>0) {
                        foreach ($sizes as $keySecond => $size) {
                            if (isset($finalNewContainer->size) && $finalNewContainer->size==$size->id) {
                                $finalNewContainers[$key]->size_name = $size->name;
                            }
                        }
                    }
                }
            }

            $finalNewContainers = json_encode($finalNewContainers);

            $findShipment->containers_group_bookings = $finalNewContainers;
            $findShipment->containers_group = $finalNewContainers; */

            //$findShipment->containers_group_bookings = json_encode(array_merge($mergeContainer, $mergeToContainerBookings));
            
        }
        

        return $this->withMeta([
            'shipment' => $findShipment,
            'baseUrl' => url('/')
        ]);
        } else {
            return null;
        }
        
    }

    protected function fillAttributeFromRequest(
        NovaRequest $request,
        $requestAttribute,
        $model,
        $attribute
    ) {
    	if ($request->has('containers_group_bookings')) {
            if ($request->input('containers_group_bookings')!=null) {
                $containers_group = json_decode($request->input('containers_group_bookings'));
                //$model->suppliers_group = $request->input('suppliers_group');

                if (count($containers_group)>0) {
                    $model->containers_group_bookings = json_encode($containers_group);
                } else {
                    $model->containers_group_bookings = json_encode([]);
                }
            } else {
                $model->containers_group_bookings = json_encode([]);
            }
        } else {
            $model->containers_group_bookings = json_encode([]);
        }
    }
}
