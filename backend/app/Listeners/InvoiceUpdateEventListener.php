<?php

namespace App\Listeners;

use App\Events\InvoiceUpdateEvent;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Http;
use QuickBooksOnline\API\DataService\DataService;
use App\User;
use QuickBooksOnline\API\Facades\Invoice;
use QuickBooksOnline\API\Core\OAuth\OAuth2\OAuth2LoginHelper;
use QuickBooksOnline\API\Facades\Payment;

class InvoiceUpdateEventListener
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
    public function handle(InvoiceUpdateEvent $event)
    {
        $resource = $event->resource;
        $get_user = User::where('email', 'shabsie@shifl.com')->first();
        $realm_id = 123146157027444;
        $oauth2LoginHelper = new OAuth2LoginHelper(config('quickbooks.data_service.client_id'),config('quickbooks.data_service.client_secret'));
        $accessTokenObj = $oauth2LoginHelper->
                            refreshAccessTokenWithRefreshToken($get_user->quickbookstoken->refresh_token);
        $accessTokenValue = $accessTokenObj->getAccessToken();
        $refreshTokenValue = $accessTokenObj->getRefreshToken();

        \DB::table('quickbooks_tokens')->where('user_id', $get_user->id)
                                    ->update([
                                        'access_token' => $accessTokenValue,
                                        'refresh_token' => $refreshTokenValue
                                    ]);

        $data_service = DataService::Configure([
            'auth_mode' => 'oauth2',
            'ClientID' => config('quickbooks.data_service.client_id'),
            'ClientSecret' => config('quickbooks.data_service.client_secret'),
            'accessTokenKey' => $accessTokenValue,
            'refreshTokenKey' => $refreshTokenValue,
            'QBORealmID' => $get_user->quickbookstoken->realm_id,
            'baseUrl' => 'Production'
        ]);
        
        $invoice = $data_service->FindById("Invoice", $resource['qbo_id']);
        if (isset($invoice->DocNumber) && $realm_id === $resource['qbo_company_realmid']) {
            $check_invoice = $invoice;
            if (isset($check_invoice->Id)) {
                $build_lines = [];
                array_push($build_lines, [
                    'Amount' => $check_invoice->Balance,
                    'LinkedTxn' => [
                        'TxnId' => $check_invoice->Id,
                        'TxnType' => 'Invoice'
                    ]
                ]);

                //create payment
                $ob = Payment::create([
                    'TotalAmt' => $check_invoice->Balance,
                    'CustomerRef' => [
                        'value' => $check_invoice->CustomerRef
                    ],
                    'Line' => $build_lines
                ]);

                $final_invoice = $data_service->Update($ob);
                $resource['balance'] = strval(number_format((float)0, 2, '.', ''));
                $resource->save();
            }
        }

    }
}