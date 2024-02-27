<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class CustomerBuyer extends Model
{
    protected $table = 'customer_buyer';

    protected $fillable = ['customer_id', 'buyer_id'];

    public $timestamps = false;
}
