<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Laravel\Scout\Searchable;

class Buyer extends Model
{

    protected $table = 'buyer';
    protected $fillable = ['company_name', 'address', 'phone', 'emails', 'connected_customer', 'connection_generated'];
    private static $custom_customers = [];

    public static function boot()
    {
        parent::boot();

        static::saving(function (Buyer $buyer) {
            if (isset($buyer->custom_customers)) {
                self::$custom_customers = $buyer->custom_customers;
                unset($buyer->custom_customers);
            }
        });

        static::created(function (Buyer $buyer) {
            if (count(self::$custom_customers) > 0) {
                $buyer->customers()->attach(
                    self::$custom_customers
                );
            }
            self::$custom_customers = [];
        });
    }

    public function customers()
    {
        return $this->belongsToMany(Customer::class, 'customer_buyer', 'buyer_id', 'customer_id');
    }
}
