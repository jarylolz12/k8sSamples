<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Shipment;

class Statements extends Model
{
    protected $fillable = ['statement_date','statement_no','entry_no','reference_no','importer_of_record','process_port','amount'];

    public function shipment()
    {
        return $this->belongsTo(Shipment::class, 'reference_no', 'shifl_ref');
    }
}
