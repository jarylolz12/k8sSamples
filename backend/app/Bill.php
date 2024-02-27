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
        'qbo_vendor_name',
        'qbo_mailing_address',
        'qbo_bill_date',
        'qbo_due_date',
        'qbo_line',
        'qbo_term_days',
        'total_amount',
        'qbo_country',
        'qbo_tax_detail',
        'total_tax',
        'shifl_office_from_id',
        'shifl_office_to_id',
        'qbo_company',
        'qbo_company_realmid',
        'home_total_amount',
        'balance',
        'home_balance',
        'currency_ref',
        'home_currency_ref',
        'exchange_rate',
        'exchange_rate_info',
        'global_tax_calculation',
        'sync_token'
    ];

    public function shipment() {
        return $this->belongsTo(Shipment::class, 'shipment_id');
    }

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

    public function billPaymentList() {
        return $this->hasOne(BillPaymentList::class);
    }



}
