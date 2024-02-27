<?php

namespace App\Http\Resources\ScribeResources;

use Illuminate\Http\Resources\Json\JsonResource;

class ContainerSizeResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'id'=> $this->id,
            'name' => $this->name,  
        ]; 
    }
}
