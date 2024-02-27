<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class CustomerSupplier extends Model
{
    protected $table = 'customer_supplier';

    protected $fillable = ['customer_id', 'supplier_id'];

    public $timestamps = false;
}
