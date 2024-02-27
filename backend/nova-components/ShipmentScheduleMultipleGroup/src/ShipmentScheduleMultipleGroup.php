<?php

namespace Kenetashi\ShipmentScheduleMultipleGroup;

use Laravel\Nova\Fields\Field;
use Laravel\Nova\Http\Requests\NovaRequest;
use App\Shipment;

class ShipmentScheduleMultipleGroup extends Field
{
    /**
     * The field's component.
     *
     * @var string
     */
    public $component = 'shipment-schedule-multiple-group';



    public function initFields($id) {

        $findShipment = ($id!=null) ? Shipment::find($id) : [];

        if (isset($findShipment->id)) {

            //schedule merge
            $mergeSchedule = (is_array(json_decode($findShipment->schedules_group))) ? json_decode($findShipment->schedules_group) : [];

            $mergeToScheduleBookings = (is_array(json_decode($findShipment->schedules_group_bookings))) ? json_decode($findShipment->schedules_group_bookings) : [];
            
            $findShipment->schedules_group_bookings = json_encode(array_merge($mergeSchedule, $mergeToScheduleBookings));
            
        }
        

        return $this->withMeta([
            'shipment' => $findShipment,
            'baseUrl' => url('/')
        ]);
    }

    protected function fillAttributeFromRequest(NovaRequest $request,
                                                $requestAttribute,
                                                $model,
                                                $attribute)
    {

      if($request->has('schedules_group_bookings')) {
        if($request->input('schedules_group_bookings')!=null) {
            $schedules_group = json_decode($request->input('schedules_group_bookings'));
            if(count($schedules_group)>0) {
              /*
              foreach($schedules_group as $key => $schedule) {
                if($key==0) {
                  $model->etd = (isset($schedule->etd) && $schedule->etd!='') ? $schedule->etd : null;
                  $model->eta = (isset($schedule->eta) && $schedule->eta!='') ? $schedule->eta : null;
                }
              }*/
              $model->schedules_group_bookings = json_encode($schedules_group);

            }else {
              $model->schedules_group_bookings = json_encode([]);
            }

        }else {
            $model->schedules_group_bookings = json_encode([]);
        }

      }else {
        $model->schedules_group_bookings = json_encode([]);
      }
    }
}
