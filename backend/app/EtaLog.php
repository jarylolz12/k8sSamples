<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class EtaLog extends Model
{
    // nothing is updateable for now.
    protected $fillable = [];
    protected $casts = ['old_eta' => 'date', 'eta' => 'date'];
}
