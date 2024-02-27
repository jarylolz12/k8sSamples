<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ShipmentNotes extends Model
{
    protected $guarded = ['id'];
    protected $fillable = ['subject','description', 'privacy', 'shipment_id','created_by'];

    public function users()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public static function boot()
    {
        parent::boot();

        static::creating(function ($shipmentNotes) {
            $shipmentNotes->created_by = auth()->user()->id;
        });
    }
}
