<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Terminal49Shipment extends Model
{

    /**
     * The primary key associated with the table.
     *
     * @var string
     */
    protected $primaryKey = 'mbl_num';
    public $incrementing = false;


    /**
     * @var string
     */
    protected $guarded = ["id","created_at"];
    /**
     * Generates created_at and updated_at automatically
     *
     * @var boolean
     */
    public $timestamps = true;

    public function shipment(){
        return $this->hasOne(Shipment::class, 'mbl_num', 'mbl_num');
    }
}
