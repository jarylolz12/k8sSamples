<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;
use Illuminate\Support\Facades\Http;
use GuzzleHttp\Client;

class CheckVendorNameBrex implements Rule
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

        $valid = true;

        $brex_api_url = 'https://platform.brexapis.com/v1/vendors';
        $brex_token = 'bxt_81zGTa64Rf0Ypt4Wc6ahB2pC83pGbqP6Uam3';


        $letters = 'abcdefghjiklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $code = '';

        for( $x=0;$x<6;$x++ ) {
            $code .=$letters[rand(0, strlen($letters) - 1)];
        }

        $ikey = $code;
        
        $response = Http::withHeaders([
            "Authorization" => sprintf('Bearer %s', $brex_token),
            "Content-type" => "application/json",
            "Idempotency-Key" => $ikey
        ])->get($brex_api_url);


        $response = $response->json();
        $names = [];

        if ( isset($response['items']) ) {
            if ( count($response['items']) > 0 ) {
                foreach($response['items'] as $item) {
                    if (isset($item['company_name']))
                        array_push($names, $item['company_name']);
                }
            }
        }

        if (in_array($value, $names))
            $valid = false;

        return $valid;

    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'Vendor company name already exists in the Brex.';
    }
}