<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class InvoiceService extends Model
{
    protected $fillable = [
        'invoice_id',
        'qbo_service_id',
        'description',
        'quantity',
        'rate',
        'qbo_service_name',
        'qbo_tax_code',
    ];

    public function invoice()
    {
        return $this->belongsTo('App\Invoice');
    }

}
