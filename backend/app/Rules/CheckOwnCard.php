<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;
use Illuminate\Support\Facades\Http;
use GuzzleHttp\Client;
use App\Card;
use Illuminate\Support\Facades\Auth;
class CheckOwnCard implements Rule
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
        $valid = true;
        $check_card = Card::where('id', $value)
                          ->where('customer_admin_id', $check_user_id)
                          ->first();

        return (isset($check_card->id));

    }


    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'Please make sure you own or that card exists.';
    }
}