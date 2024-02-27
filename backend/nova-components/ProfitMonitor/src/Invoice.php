<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Laravel\Scout\Searchable;

class Invoice extends Model
{
    protected $fillable = [
        'qbo_customer_id', 
        'qbo_customer_name', 
        'invoice_num',
        'qbo_bill_to_email',
        'qbo_bill_to_address',
        'term',
        'invoice_date',
        'due_date',
        'shipment_id',
        'qbo_id',
        'qbo_term_id',
        'qbo_term_name',
        'qbo_term_days',
        'qbo_customer_memo',
        'total_amount',
    ];

    protected $casts = ['invoice_date' => 'date', 'due_date' => 'date'];

    /* public function customer()
    {
        return $this->belongsTo('App\Customer');
    } */
 
    public function shipment()
    {
        return $this->belongsTo('App\Shipment');
    }

    public function invoiceServices()
    {
        return $this->hasMany(InvoiceService::class);
    }

    public static function boot() {
        parent::boot();
        self::deleting(function($invoice) {
             $invoice->invoiceServices()->each(function($invoice) {
                $invoice->delete();
             });
        });
    }
}
