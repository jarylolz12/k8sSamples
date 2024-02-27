<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class TerminalRegion extends Model
{
    //
    protected $fillable = ['name','code'];

    public function shipments()
    {
        return $this->belongsToMany(Shipment::class, 'shipments_terminal_regions', 'terminal_region_id', 'shipment_id');
    }
    public function country()
    {
        return $this->belongsTo(Country::class, 'country_id');
    }


}
