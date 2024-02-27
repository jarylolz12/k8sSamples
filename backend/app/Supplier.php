<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Laravel\Scout\Searchable;

class Supplier extends Model
{

    protected $fillable = ['company_name', 'address', 'phone', 'emails', 'connected_customer', 'connection_generated'];
    private static $custom_customers = [];

    public static function boot()
    {
        parent::boot();

        static::saving(function (Supplier $supplier) {
            if (isset($supplier->custom_customers)) {
                self::$custom_customers = $supplier->custom_customers;
                unset($supplier->custom_customers);
            }
        });

        static::created(function (Supplier $supplier) {
            if (count(self::$custom_customers) > 0) {
                $supplier->customers()->attach(
                    self::$custom_customers
                );
            }
            self::$custom_customers = [];
        });

        // //once created/inserted successfully this method fired, so I tested foo
        // static::created(function (AccountCreation $item) {
        //     echo "<br /> {$item->foo} ===== <br /> successfully fired created";
        // });

    }

    public function documents() {
        return $this->belongsToMany(Document::class, 'documents_suppliers', 'supplier_id', 'document_id');
    }

    public function customers()
    {
        return $this->belongsToMany(Customer::class, 'customer_supplier', 'supplier_id', 'customer_id');
    }

    public function shipments()
    {
        return $this->belongsToMany(Shipment::class, 'shipment_suppliers', 'supplier_id', 'shipment_id');
    }
}
