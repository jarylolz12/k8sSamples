<?php

namespace Juliverdevshifl\CustomerStatement\Models;

use Illuminate\Database\Eloquent\Model;

class CustomerStatementAutomation extends Model
{
	protected $guarded = ['id'];
    protected $fillable = ['sched_enabled','sched_type','sched_day','sched_time','created_by','updated_by'];


    public function getCreatedByAttribute($value){
        return \App\User::find($value);
    }

    public function getUpdatedByAttribute($value){
        return \App\User::find($value);
    }
}