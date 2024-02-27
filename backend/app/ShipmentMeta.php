<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ShipmentMeta extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'shipment_metas';

    protected $fillable = ['consignee_id', 'shipper_id', 'shipper_details_info', 'consignee_details_info', 'location_from', 'location_to', 'mode', 'type', 'incoterm', 'notify_details_info', 'notify_party','forwarders'];


    public function shipment()
    {
        return $this->belongsTo('App\Shipment');
    }
    
}