<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Coloaders extends Model
{
    protected $casts = [
        'emails' => 'array'
    ];

    protected $guarded = ['id',  'created_at'];

    public function country(){
        return $this->belongsTo(Country::class);
    }

    public function getEmailsAttribute($value){
        if( !empty($value)){
            return json_decode($value);
        }else{
            return [];
        }
    }
}
