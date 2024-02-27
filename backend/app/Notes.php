<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Notes extends Model
{
    protected $guarded = ['id'];

    function noteable(){
        return $this->morphTo();
    }
}
