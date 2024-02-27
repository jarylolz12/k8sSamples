<?php

namespace App\Listeners;

use App\Events\InsertBrexEvent;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Http;
use App\Vendor;

class InsertBrexEventListener
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  InsertBrexEvent  $event
     * @return void
     */
    public function handle(InsertBrexEvent $event)
    {

        $resource = $event->resource;

        $brex_token = 'bxt_81zGTa64Rf0Ypt4Wc6ahB2pC83pGbqP6Uam3';
        $brex_api_url = 'https://platform.brexapis.com/v1/vendors';

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
                ])
                ->post($brex_api_url, $resource);

        $get_data = $response->json();
        if (isset($get_data)) {

            $findVendor = Vendor::where('email', $resource['email'])->first();
            if ( isset($findVendor->id) && isset($get_data['id']) ) {

                \DB::table('vendors')
                    ->where('email', $resource['email'])
                    ->update([
                        'brex_id' => $get_data['id']
                    ]);
            }

        }

    }
}