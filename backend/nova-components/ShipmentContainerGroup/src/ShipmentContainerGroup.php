<?php

namespace Kenetashi\ShipmentContainerGroup;

use Laravel\Nova\Fields\Field;
use App\Shipment;
//use App\ContainerSize;

use Laravel\Nova\Http\Requests\NovaRequest;

class ShipmentContainerGroup extends Field
{
    /**
     * The field's component.
     *
     * @var string
     */
    public $component = 'shipment-container-group';

    public function initFields($id) {

        $r = new NovaRequest();

        if (!$r->isResourceIndexRequest()) {
            $findShipment = ($id!=null) ? Shipment::find($id) : [];
        //$sizes = ContainerSize::all();
        if (isset($findShipment->id)) {

            //container merge
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

            /*
            if (count($newFinalContainers)>0) {
                foreach ($newFinalContainers as $key => $newFinalContainer) {
                    if (count($sizes)>0) {
                        foreach ($sizes as $keySecond => $size) {
                            if (isset($newFinalContainer->size) && $newFinalContainer->size==$size->id) {
                                $newFinalContainers[$key]->size_name = $size->name;
                            }
                        }
                    }
                }
            }*/

            $findShipment->containers_group_bookings = json_encode($newFinalContainers);
            $findShipment->containers_group = json_encode($newFinalContainers);
            
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
            $findShipment->containers_group = $finalNewContainers;*/
            
        }
        

        return $this->withMeta([
            'shipment' => $findShipment,
           // 'sizes' => $sizes,
            'baseUrl' => url('/')
        ]);
        } else {
            return null;

        }
        
    }
}
