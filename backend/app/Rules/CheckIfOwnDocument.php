<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;
use Illuminate\Support\Facades\Http;
use GuzzleHttp\Client;
use Illuminate\Support\Facades\Auth;
use App\Document;
class CheckIfOwnDocument implements Rule
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
        $customers = Auth::user()->customersApi->pluck('id');
        $check_document = Document::where('id', $value)->first();
        
        return ( isset($check_document->id) && in_array($check_document->shipment->customer->id, $customers));
    }


    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'Please make sure you are associated to that document.';
    }
}