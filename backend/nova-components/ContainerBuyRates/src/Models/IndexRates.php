<?php

namespace Juliverdevshifl\ContainerBuyRates\Models;

use Illuminate\Database\Eloquent\Model;

class IndexRates extends Model
{
    public $incrementing = false;

    protected $guarded = ['id'];
    protected $fillable = ['data_date','location_from','location_to','terminal','amount','container'];
    protected $casts = [];

    function setDataDateAttribute($value){
        $this->attributes['data_date'] = date_format(date_create($value),'Y-m-d');
    }
    
}
