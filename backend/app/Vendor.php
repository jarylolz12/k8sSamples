<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use QuickBooksOnline\API\Facades\Vendor as QVendor;
use Illuminate\Support\Facades\Http;
use GuzzleHttp\Client;
use Illuminate\Support\Facades\Auth;

class Vendor extends Model
{

    protected $attributes = [
        'payment_accounts' => '[]'
    ];

    protected $fillable = [
    	'company_name',
    	'email',
    	'phone',
    	'payment_accounts',
    	'suffix',
    	'title',
    	'family_name',
    	'tax_identifier',
    	'account_number',
		'country',
		'city',
		'line3',
		'line2',
		'line1',
		'postal_code',
		'country_subdivision_code',
		'given_name',
		'print_on_check_name',
        'qb_id',
        'brex_id',
        'realm_id'
    ];


    public static function boot()
    {

        parent::boot();

        static::created(function (Vendor $item) {

            /*
            \DB::table('vendors')->update([
                'realm_id' =>  $currentUserRealmId
            ])->where('id', $item->id);
            */

            //quickbooks
        	$qb = app('QuickBooks');

            $vendorData = [
            	'PrimaryEmailAddr' => [
            		'Address' => strval($item->email),
            	],
            	'PrimaryPhone' => [
            		'FreeFormNumber' => strval($item->phone)
            	],
            	'DisplayName' => strval($item->company_name),
            	//'Suffix' => $item->suffix,
            	//'Title' => $item->title,
            	'Mobile' => [
            		'FreeFormNumber' => strval($item->phone)
            	],
            	//'FamilyName' => $item->family_name,
            	//'TaxIdentifier' => $item->tax_identifier,
            	//'AccNum' => $item->account_number,
            	'CompanyName' => $item->company_name,
            	'BillAddr' => [
            		'City' => strval($item->city),
            		'Country' => strval($item->country),
            		'Line3' => strval($item->line3),
            		'Line2' => strval($item->line2),
            		'Line1'=> strval($item->line1),
            		'PostalCode' => strval($item->postal_code),
            		'CountrySubDivisionCode' => strval($item->country_subdivision_code),
            	],
            	//'GivenName' => $item->given_name,
            	//'PrintOnCheckName' => $item->print_on_check_name
            ];

            $vendorObj = QVendor::create($vendorData);
            $vendorQb = $qb->getDataService()->Add($vendorObj);

            //settings
            //name
            //token

            //Do not attempt to put it in the environment variables
            //This token is a very important one
            //This token should not be seen any directories
            //settings page is underway for the token


            $brex_token = 'bxt_81zGTa64Rf0Ypt4Wc6ahB2pC83pGbqP6Uam3';
            $brex_api_url = 'https://platform.brexapis.com/v1/vendors';

            /*
            //brex
            $brex_payment_accounts = [];

            //convert payment accounts to array
            if (isset($item->payment_accounts)) {

                $set_brex_payment_accounts = json_decode($item->payment_accounts, true);

                foreach ($set_brex_payment_accounts as $key => $value) {
                    $value = $value['details'];

                    array_push( $brex_payment_accounts, [
                        'details' => [
                            'type' => (isset($value['type'])) ? $value['type'] : '',
                            'routing_number' => (isset($value['routing_number'])) ? strval($value['routing_number']) : '',
                            'account_number' => (isset($value['account_number'])) ? strval($value['account_number']) : '',
                            'address' => [
                                'line1' => (isset($value['address']['line1'])) ? strval($value['address']['line1']) : '',
                                'line2' => (isset($value['address']['line2'])) ? strval($value['address']['line2']) : '',
                                'city' => (isset($value['address']['city'])) ? strval($value['address']['city']) : '',
                                'state' => (isset($value['address']['state'])) ? strval($value['address']['state']) : '',
                                'country' => (isset($value['address']['country'])) ? strval($value['address']['country']) : '',
                                'postal_code' => (isset($value['address']['postal_code'])) ? strval($value['address']['postal_code']) : '',
                                'phone_number' => (isset($value['address']['number'])) ? strval($value['address']['number']) : ''
                            ]
                        ]
                    ]);
                }
            }

            $brex_data = [
                'company_name' => $item->company_name,
                'email' => $item->email,
                'phone' => $item->phone,
                'payment_accounts' => $brex_payment_accounts
            ];

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
                        ->post($brex_api_url, $brex_data);

            $get_data = $response->json();

            if (isset($get_data)) {

                if ( isset($get_data['id']) ) {
                    $item->brex_id = $get_data['id'];
                }

            }*/

            if (isset($vendorQb->Id)) {
                $item->qb_id = $vendorQb->Id;
            }
           

        });

        

        static::saved(function (Vendor $item) {
            
        });

        static::updated(function (Vendor $item) {
            /*
            $currentUserRealmId = Auth::user()->quickbookstoken->realm_id;
            \DB::table('vendors')->update([
                'realm_id' =>  $currentUserRealmId
            ])->where('id', $item->id);
            */
            $qb = app('QuickBooks');
            $vendor_id = strval($item->qb_id);
            $vendorObj = $qb->getDataService()->FindbyId('Vendor',$vendor_id);

            if (isset($vendorObj->Id)) {
                $vendorData = [
                    'Id' => $item->qb_id,
                    'PrimaryEmailAddr' => [
                        'Address' => strval($item->email),
                    ],
                    'PrimaryPhone' => [
                        'FreeFormNumber' => strval($item->phone)
                    ],
                    'DisplayName' => strval($item->company_name),
                    'Mobile' => [
                        'FreeFormNumber' => strval($item->phone)
                    ],
                    'CompanyName' => $item->company_name,
                    'BillAddr' => [
                        'City' => strval($item->city),
                        'Country' => strval($item->country),
                        'Line3' => strval($item->line3),
                        'Line2' => strval($item->line2),
                        'Line1'=> strval($item->line1),
                        'PostalCode' => strval($item->postal_code),
                        'CountrySubDivisionCode' => strval($item->country_subdivision_code),
                    ],
                ];

                try {
                    $vendorQb = QVendor::update($vendorObj, $vendorData);
                    $qb->getDataService()->Update($vendorQb);

                    //brex update
                    /*
                    $brex_token = 'bxt_81zGTa64Rf0Ypt4Wc6ahB2pC83pGbqP6Uam3';
                    $brex_api_url = sprintf('https://platform.brexapis.com/v1/vendors/%s',$item->brex_id);

                    //brex
                    $brex_payment_accounts = [];

                    //convert payment accounts to array
                    if (isset($item->payment_accounts)) {

                        $set_brex_payment_accounts = json_decode($item->payment_accounts, true);

                        foreach ($set_brex_payment_accounts as $key => $value) {
                            $value = $value['details'];

                            array_push( $brex_payment_accounts, [
                                'details' => [
                                    'type' => (isset($value['type'])) ? $value['type'] : '',
                                    'routing_number' => (isset($value['routing_number'])) ? strval($value['routing_number']) : '',
                                    'account_number' => (isset($value['account_number'])) ? strval($value['account_number']) : '',
                                    'address' => [
                                        'line1' => (isset($value['address']['line1'])) ? strval($value['address']['line1']) : '',
                                        'line2' => (isset($value['address']['line2'])) ? strval($value['address']['line2']) : '',
                                        'city' => (isset($value['address']['city'])) ? strval($value['address']['city']) : '',
                                        'state' => (isset($value['address']['state'])) ? strval($value['address']['state']) : '',
                                        'country' => (isset($value['address']['country'])) ? strval($value['address']['country']) : '',
                                        'postal_code' => (isset($value['address']['postal_code'])) ? strval($value['address']['postal_code']) : '',
                                        'phone_number' => (isset($value['address']['number'])) ? strval($value['address']['number']) : ''
                                    ]
                                ]
                            ]);
                        }
                    }

                    $brex_data = [
                        'company_name' => $item->company_name,
                        'email' => $item->email,
                        'phone' => $item->phone,
                        'payment_accounts' => $brex_payment_accounts
                    ];

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
                            ->put($brex_api_url, $brex_data);*/
                } catch(Exception $e) {
                    echo var_dump($e);
                }
                

                

            }



        });

    }

    public function getCountryAttribute($value){
        return empty($value) ? '' : $value;
    }

    public function getCityAttribute($value){
        return empty($value) ? '' : $value;
    }

}
