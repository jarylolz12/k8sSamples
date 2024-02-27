<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class BillItem extends Model
{
    protected $table = 'bill_items';

    protected $fillable = [
        'bill_id',
        'qbo_item_value',
        'qbo_customer_id',
        'qbo_item_name',
        'qbo_customer_name',
        'qbo_line_type',
        'qbo_description',
        'qbo_billable_status',
        'qbo_amount',
        'qbo_tax_code',
    ];

    public function bill(){
        return $this->belongsTo(Bill::class);
    }
}
