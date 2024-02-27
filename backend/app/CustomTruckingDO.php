<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

// Please use this Model with precaution

class CustomTruckingDO extends Model
{
    //
    protected $table = 'delivery_orders';

    protected $casts = [
        'emails' => 'array'
    ];
    /**
     * The database connection that should be used by the model.
     *
     * @var string
     */

    protected $connection = 'mysql-trucking';

    public static function boot()
    {
        parent::boot();
        static::created(function (CustomTruckingDO $item) {           
            $item->shifl_ref = 5000+CustomTruckingDO::max('id');
            $item->save();           
        });
    }
   
}
