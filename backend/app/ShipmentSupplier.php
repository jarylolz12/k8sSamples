<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Laravel\Scout\Searchable;

class ShipmentSupplier extends Model
{
  
    protected $fillable = ['po_num', 'commodity', 'cargo_ready_date','bl_num','bl_status','ams_num','total_cartons','total_size','total_weight','cbm','kg','hbl_num','product_description','marks','bol_shipper_address','bol_shipper_phone_number','bol_consignee_address','bol_consignee_phone_number','bol_notify_party','bol_notify_address','bol_notify_phone_number'];

    public function shipments()
    {
        return $this->belongsToMany(Shipment::class, 'shipment_suppliers', 'supplier_id', 'shipment_id');
    }
}
