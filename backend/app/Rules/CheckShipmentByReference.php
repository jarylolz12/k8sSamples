<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;
use Illuminate\Support\Facades\Http;
use GuzzleHttp\Client;
use Illuminate\Support\Facades\Auth;
use App\Shipment;

class CheckShipmentByReference implements Rule
{
    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Determine if the validation rule passes.
     *
     * @param  string  $attribute
     * @param  mixed  $value
     * @return bool
     */
    public function passes($attribute, $value)
    {
        $checkShipment = Shipment::where('shifl_ref', $value)->first();
        return (!isset($checkShipment->id));
    }


    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'That po number matches one of the shipments in the central.';
    }
}