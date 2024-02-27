<?php

namespace App\Http\Resources\ScribeResources;

use Illuminate\Http\Resources\Json\JsonResource;

class TerminalRegion extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return parent::toArray($request);
    }
}
