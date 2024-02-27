<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Trucker extends Model
{
    //guarded field
    protected $guarded=['id','created_at'];
    protected $casts = [
        'email_recipient' => 'array'
    ];
}
