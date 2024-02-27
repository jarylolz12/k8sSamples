<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class Shipment extends JsonResource
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
            'id' => $this->id ,
            'po_num' => $this->po_num,
            'mbl_num' => $this->mbl_num,
            'type' => $this->type,
            'term' => $this->term,
            'shipment_status' => $this->shipment_status,
            'shifl_ref' => $this->shifl_ref,
            'etd' => $this->etd,
            'eta' => $this->eta,
            'custom_notes' => $this->custom_notes,
            'total_cbm' => $this->total_cbm,
            'total_kg' => $this->total_kg,
            'total_cartons' => $this->total_cartons,
            'suppliers_group' => $this->suppliers_group,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
