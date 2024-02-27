<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Shipment;

class ImportNames extends Model
{

    protected $fillable = ['import_name', 'ein', 'customer_id', 'address', 'phone_number','image','country','state','city','zipcode','email'];


    public static function boot()
    {
        parent::boot();

        static::saving(function (ImportNames $importName) {
        
            if (isset($importName->country_state_city)) {
                $countryStateCity = json_decode($importName->country_state_city);
                $importName->country = $countryStateCity->country;
                $importName->state = $countryStateCity->state;
                $importName->city = $countryStateCity->city;
                unset($importName->country_state_city);
            }

        });

     
    }
    
    public function customer()
    {
        return $this->belongsTo(Customer::class, 'customer_id');
    }

    public function getImportNameByCustomerId($id)
    {
        return $this->where('customer_id', '=', $id)->where('status', 1);
    }

    public function displayImportName($id)
    {
        $imports = $this->getImportNameByCustomerId($id)->get();
        $results = [];
        foreach($imports as $import){
            $url = '<a href="/administrator/resources/import-names/'.$import['id'] .'" class="no-underline font-bold dim text-primary">'. $import['import_name'] .'</a>';
            array_push($results, $url);
        }
        return $results;
    }

    public function getImportNameByShipmentImportNameId(Shipment $shipment)
    {
        return $this->where('id', $shipment->import_name_id)
            ->where('status', 1);
    }
    
    public function shipments()
    {
        return $this->hasMany(Shipment::class);
    }
}