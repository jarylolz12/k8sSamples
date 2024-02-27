<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Document extends Model
{
    protected $table = 'documents';

    protected $fillable = [
        'name',
        'type',
        'path',
        'supplier_ids',
        'shipment_id',
        'is_added_by_customer'
    ];

    public function suppliers() {
        return $this->belongsToMany(Supplier::class, 'documents_suppliers', 'document_id', 'supplier_id');
    }

    public function shipment() {
        return $this->belongsTo(Shipment::class, 'shipment_id');
    }


}
