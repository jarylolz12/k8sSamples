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

Route::post('/preliminary-statement', 'App\Http\Controllers\AchReportController@postViewPreliminaryStatement');
Route::get('/preliminary-statement', 'App\Http\Controllers\AchReportController@viewPreliminaryStatement');
Route::get('/preliminary-statement/{statement_no}', 'App\Http\Controllers\AchReportController@viewStatementNumber');
Route::get('/preliminary-statement-monthly', 'App\Http\Controllers\AchReportController@viewMonthly');

Route::get('/report/{customer_id}/{statement_no}', 'App\Http\Controllers\AchReportController@generateReport');
Route::get('/customer-statements', 'App\Http\Controllers\AchReportController@viewStatements');
Route::get('/customer-statements-daily', 'App\Http\Controllers\AchReportController@viewStatementsDaily');
Route::get('/customer-statements/statement-daily/customer/{customer_id}', 'App\Http\Controllers\AchReportController@viewStatementsDailyByCustomer');
Route::get('/customer-statements/{customer_id}/monthly', 'App\Http\Controllers\AchReportController@viewMonthlyStatements');
// Route::get('/customer-statements/{customer_id}', 'App\Http\Controllers\AchReportController@viewCustomerStatements');
Route::get('/customer-statements/daily/{customer_id}', 'App\Http\Controllers\AchReportController@viewCustomerStatements');

Route::get('/customer-statements/statement-monthly/{statement_no}', 'App\Http\Controllers\AchReportController@viewMonthlyStatementsByStatementNo');
Route::get('/customer-statements/statement-daily/{statement_no}', 'App\Http\Controllers\AchReportController@viewDailyStatements');


// monthly
Route::post('/monthly-statement', 'App\Http\Controllers\AchReportController@postStatementMonthly');
