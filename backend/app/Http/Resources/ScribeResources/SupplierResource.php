<?php

namespace App\Http\Resources\ScribeResources;

use Illuminate\Http\Resources\Json\JsonResource;

class SupplierResource extends JsonResource
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
            'id'=>$this->id,
            'company_name'=>$this->company_name,
            'address'=>$this->address,
            'phone'=>$this->phone,
            'emails'=>$this->emails,
            'admin_user_id'=>$this->admin_user_id
        ];

        //return parent::toArray($request);
    }
}
