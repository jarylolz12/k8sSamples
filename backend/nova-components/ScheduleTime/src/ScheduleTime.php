<?php

namespace Gale\ScheduleTime;

use Laravel\Nova\Fields\Field;

class ScheduleTime extends Field
{
    /**
     * The field's component.
     *
     * @var string
     */
    public $component = 'schedule-time';

    public function initFields($fields = null)
    {
        return $this->withMeta(['schedule' => $fields]);

        //this.field.schedule.schedules_group_bookings
    }
}
