<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Carrier extends Model
{
    //
    protected $fillable = ['name','carrier_type_id'];

    public function carrierType()
    {
        return $this->belongsTo(CarrierType::class);
    }
}
