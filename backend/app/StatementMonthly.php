<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class StatementMonthly extends Model
{

    protected $casts = ['statement_date' => 'date', 'pres_date' => 'date'];

    public $table = 'statement_monthly';

    public $timestamps = false;

    protected $guarded = [];

}
