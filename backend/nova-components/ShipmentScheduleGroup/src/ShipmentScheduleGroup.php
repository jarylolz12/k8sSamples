<?php

namespace Kenetashi\ShipmentScheduleGroup;

use Laravel\Nova\Fields\Field;
use Laravel\Nova\Http\Requests\NovaRequest;
class ShipmentScheduleGroup extends Field
{
    /**
     * The field's component.
     *
     * @var string
     */
    public $component = 'shipment-schedule-group';

    protected function fillAttributeFromRequest(NovaRequest $request,
                                                $requestAttribute,
                                                $model,
                                                $attribute)
    {

      if($request->has('schedules_group')) {
        if($request->input('schedules_group')!=null) {
            $schedules_group = json_decode($request->input('schedules_group'));
            if(count($schedules_group)>0) {
              foreach($schedules_group as $key => $schedule) {
                if($key==0) {
                  $model->etd = (isset($schedule->etd) && $schedule->etd!='') ? $schedule->etd : null;
                  $model->eta = (isset($schedule->eta) && $schedule->eta!='') ? $schedule->eta : null;
                }
              }

              $model->schedules_group = json_encode($schedules_group);

            }else {
              $model->schedules_group = json_encode([]);
            }

        }else {
            $model->schedules_group = json_encode([]);
        }

      }else {
        $model->schedules_group = json_encode([]);
      }
    }
}
