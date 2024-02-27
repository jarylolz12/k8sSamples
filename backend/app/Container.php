<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Laravel\Scout\Searchable;

class Container extends Model
{

    protected $fillable = ['container_num', 'type', 'seal_num','cartons','cbm','kg','chargeable_weight','unique_id','size'];

    public function shipments()
    {
        return $this->belongsToMany(Shipment::class, 'containers', 'id', 'shipment_id');
    }
    public function container_size(){
        return $this->hasOne(ContainerSize::class,'id','size');
    }
}
