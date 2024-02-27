<?php

namespace App\Http\Resources\ScribeResources;

use Illuminate\Http\Resources\Json\JsonResource;

use App\Carrier;

class CarrierResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {

        // return Carrier::all()->paginate(5);
        return [
            'id'=> $this->id,
            'name' => $this->name,  
        ];
 
        //return parent::toArray($request);
    }
}
