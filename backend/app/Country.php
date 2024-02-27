<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Country extends Model
{

    //
    protected $fillable = ['name'];

    public function office()
    {
    	return $this->belongsTo(ShiflOffice::class, 'shifl_office_id');
    	//return $this->belongsToMany(ShiflOffice::class, 'shifl_offices_countries', 'country_id', 'shifl_office_id');
    }

}
