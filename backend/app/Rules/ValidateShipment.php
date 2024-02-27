<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;
use Illuminate\Support\Facades\Http;
use GuzzleHttp\Client;
use Illuminate\Support\Facades\Auth;
use App\Shipment;

class ValidateShipment implements Rule
{

    private $errorMessage = '';

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
        $customer_id = 0;
        $customers = Auth::user()->customersApi->pluck('id');
        $get_authenticated_user = Auth::user();

        if (count($customers) > 0) {
            $customer_id = ( $get_authenticated_user->default_customer_id!==null ) ? $get_authenticated_user->default_customer_id : $customers[0];
        }

        //check if that shipment exists
        $checkShipment = Shipment::find(intval($value));

        if ( isset($checkShipment->id)) {
            //check now if allowed to edit or delete this shipment
            if ( $checkShipment->customer_id === $customer_id ) {
                return true;
            } else {
                $this->errorMessage = 'Please make sure your current selected customer is associated with this shipment.';
                return false;
            }
 
        } else {
            $this->errorMessage = 'Please make sure that this shipment exists.';
            return false;
        }


    }


    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return $this->errorMessage;
    }
}