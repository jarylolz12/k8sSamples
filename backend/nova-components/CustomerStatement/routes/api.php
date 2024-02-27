<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Tool API Routes
|--------------------------------------------------------------------------
|
| Here is where you may register API routes for your tool. These routes
| are loaded by the ServiceProvider of your tool. They are protected
| by your tool's "Authorize" middleware by default. Now, go build!
|
*/

Route::get('/statement/{contact_id}','\Juliverdevshifl\CustomerStatement\Http\Controllers\CustomerStatementController@list');
Route::post('/statement/save','\Juliverdevshifl\CustomerStatement\Http\Controllers\CustomerStatementController@createStatement');
Route::post('/statement/delete','\Juliverdevshifl\CustomerStatement\Http\Controllers\CustomerStatementController@deleteStatement');
Route::get('/customer/{id}','\Juliverdevshifl\CustomerStatement\Http\Controllers\CustomerStatementController@customers');
Route::get('/company/active','\Juliverdevshifl\CustomerStatement\Http\Controllers\CustomerStatementController@company');
Route::get('/customer-invoices/{id}','\Juliverdevshifl\CustomerStatement\Http\Controllers\CustomerStatementController@invoices');
Route::get('/financial-date/get','\Juliverdevshifl\CustomerStatement\Http\Controllers\CustomerStatementController@getFinancialDate');
Route::get('/auto-send/status/get','\Juliverdevshifl\CustomerStatement\Http\Controllers\CustomerStatementController@getAutoSendStatus');
Route::post('/auto-send/status/save','\Juliverdevshifl\CustomerStatement\Http\Controllers\CustomerStatementController@saveAutoSendStatus');
Route::post('/opening-balance/get','\Juliverdevshifl\CustomerStatement\Http\Controllers\CustomerStatementController@getStatementOpeningBalance');
Route::get('/test','\Juliverdevshifl\CustomerStatement\Http\Controllers\TestController@handle');
Route::get('/download/statement/{id}','\Juliverdevshifl\CustomerStatement\Http\Controllers\CustomerStatementController@downloadInitPage');
Route::get('/download/statement-doc/{id}','\Juliverdevshifl\CustomerStatement\Http\Controllers\CustomerStatementController@handleDownload');
Route::get('/mail/test',function(){
    function readableStatementDate($date){
        $a = date('F dS Y', strtotime($date));

        if( strpos($a,'0') == 0 ){
            $b = explode(' ',$a);
            $b[1] = str_replace('0','',$b[1]);

            return implode(' ',[$b[1],$b[0].', ',$b[2]]);
        }

        return $a;
    }

    $data = [
        'shifl_linkedin_url' => 'https://www.linkedin.com/company/shifl?original_referer=https%3A%2F%2Fwww.google.com%2F',
        'shifl_whatsapp_url' => '888-447-4435',
        'download_statement_url' => '#',
        'statement_date' => date('F d, Y'),
        'statement_date_readable' => readableStatementDate(date('F d, Y')),
        'payment_link' => 'https://poynt.godaddy.com/checkout/57fa9f63-6419-423b-89b7-a4589f394dae/shifl-payment',
        'customer_data' => [
            'name' => 'GreenTouch Solutions Corp',
            'email' => 'greentouchs@gg.com',
            'phone' => '',
            'address' => 'Boston Post Road #1051, East Lyme, CT 06333, Connecticut 0633'
        ],
        'company_data' => [
            'name' => 'Shifl',
            'email' => 'hello@shifl.com',
            'phone' => '',
            'address' => '343 Spook Rock Rd Suffern NY 10901'
        ],
        'google_app_url' => 'https://play.google.com/store/apps/details?id=com.shifl.app.twa',
        'apple_app_url' => '',
        'statement_id' => 'SOA314-4',
        'opening_balance' => 53980,
        'total_amount' => 222,
        'invoice_amount' => 222,
        'total_paid_amount' => 80,
        'closing_balance' => 53980,
        'total_balance' => 53980,
        'total_due' => 142,
        'currency' => 'USD',
        'invoices' => [],
        'due1' => 53980,
        'due2' => 0,
        'due3' => 0,
        'due4' => 0,
        'email_template' => 'mails.customer-statement-step1'
    ];

    $invoices_data = [
        [
            'invoice_num' => '704719-1',
            'invoice_date' => '2021-10-20',
            'due_date' => '2021-10-20',
            'mbl_num' => 'COSU6883190570',
            'total_amount' => 86,
            'total_paid_amount' => 80,
            'opening_balance' => 6
        ],
        [
            'invoice_num' => '701830-1',
            'invoice_date' => '2021-10-20',
            'due_date' => '2021-10-20',
            'mbl_num' => 'HDMUYNWB2274973',
            'total_amount' => 50,
            'total_paid_amount' => 0,
            'opening_balance' => 50
        ],
        [
            'invoice_num' => '704718-1',
            'invoice_date' => '2021-10-20',
            'due_date' => '2021-10-20',
            'mbl_num' => 'COSU6883190510',
            'total_amount' => 86,
            'total_paid_amount' => 0,
            'opening_balance' => 86
        ]
    ];

    foreach( $invoices_data as $item ){
        $q = new \stdClass();
        $q->invoice_num = $item['invoice_num'];
        $q->invoice_date = $item['invoice_date'];
        $q->due_date = $item['due_date'];
        $q->mbl_num = $item['mbl_num'];
        $q->total_amount = $item['total_amount'];
        $q->total_paid_amount = $item['total_paid_amount'];
        $q->opening_balance = $item['opening_balance'];

        $data['invoices'][] = $q;
    }

    return new App\Mail\CustomerStatement($data);
});
