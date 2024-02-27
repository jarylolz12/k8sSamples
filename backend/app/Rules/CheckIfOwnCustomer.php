<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;
use Illuminate\Support\Facades\Http;
use GuzzleHttp\Client;
use Illuminate\Support\Facades\Auth;
class CheckIfOwnCustomer implements Rule
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
        $check_user_id = Auth::user()->id;
        $customers = Auth::user()->customersApi->pluck('id')->toArray();
        return (in_array($value, $customers));

    }


    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'Please make sure you are associated to that company.';
    }
}