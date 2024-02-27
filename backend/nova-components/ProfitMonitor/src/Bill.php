<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Bill extends Model
{
    protected $table = 'bills';

    protected $fillable = [
        'qbo_bill_id',
        'shipment_id',
        'qbo_vendor_id',
        'qbo_term_id',
        'qbo_bill_num',
        'qbo_vendor_name',
        'qbo_mailing_address',
        'qbo_bill_date',
        'qbo_due_date',
        'qbo_line',
        'qbo_term_days',
        'total_amount',
    ];

    public function bill_items(){
        return $this->hasMany(BillItem::class);
    }

    public static function boot() {
        parent::boot();
        self::deleting(function($bill) {
             $bill->bill_items()->each(function($item) {
                $item->delete();
             });
        });
    }

}
