<?php

namespace App\Http\Resources\ScribeResources;

use Illuminate\Http\Resources\Json\JsonResource;

class CardResource extends JsonResource
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
            'type' => $this->type,
            'source' => $this->source,
            'expiration_month' => $this->expiration_month,
            'expiration_year' => $this->expiration_year,
            'card_id' => $this->card_id,
            'number_masked' => $this->number_masked,
            'first_name' => $this->first_name,
            'last_name' => $this->last_name,
            'card_card_id' => $this->card_card_id,
            'poynt_token' => $this->poynt_token,
            'customer_admin_id' => $this->customer_admin_id,
            'is_default' => $this->is_default
        ];
        //return parent::toArray($request);
    }
}
