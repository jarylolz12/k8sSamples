<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;
use Illuminate\Support\Facades\Http;
use GuzzleHttp\Client;
use Illuminate\Support\Facades\Auth;
use App\Customer;
use App\Shipment;

class CheckOwnShipment implements Rule
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

        $customers = Auth::user()->customersApi->pluck('id');
        $get_authenticated_user = Auth::user();

        $customer = ( $get_authenticated_user->default_customer_id!==null ) ? $get_authenticated_user->default_customer_id : $customers[0];
        
        $check_shipment = Shipment::where('id', $value)
                                  ->where('customer_id', $customer)
                                  ->first();

        return (isset($check_shipment->id));

    }


    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'Please make sure you that you are associated to that shipment.';
    }
}