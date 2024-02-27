<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ShipmentSchedule extends Model
{
    protected $table = 'shipment_schedules';

    public function shipmentScheduleSellRates()
    {
        return $this->hasMany(ShipmentScheduleSellRates::class, 'schedule_unique_id', 'unique_id');
    }
}