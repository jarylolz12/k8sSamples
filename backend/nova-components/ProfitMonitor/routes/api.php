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

// Route::get('/endpoint', function (Request $request) {
//     //
// });

//Route::get('/profit-monitor/getShipments', 'ProfitMonitorController@getCalculatedShipment');
// Route::get('/get-shipments', \Ezea\ProfitMonitor\Http\Controllers\ProfitMonitorController::class.'@getCalculatedShipment');
Route::get('/get-shipments-v2', \Ezea\ProfitMonitor\Http\Controllers\ProfitMonitorController::class.'@getCalculatedShipmentV2');
// Route::get('/get-shifl-offices', \Ezea\ProfitMonitor\Http\Controllers\ProfitMonitorController::class.'@getShiflOffices');
Route::get('/get-qbo-companies', \Ezea\ProfitMonitor\Http\Controllers\ProfitMonitorController::class.'@getQBOCompanies');
//
Route::get('/get-sales-representatives', \Ezea\ProfitMonitor\Http\Controllers\ProfitMonitorController::class.'@getSalesRepresentatives');
Route::get('/get-profit-by-request', \Ezea\ProfitMonitor\Http\Controllers\ProfitMonitorController::class.'@getTotalProfitByRequest');
Route::get('/get-profit-by-request2', \Ezea\ProfitMonitor\Http\Controllers\ProfitMonitorController::class.'@getProfit');
Route::post('/save-notes', \Ezea\ProfitMonitor\Http\Controllers\ProfitMonitorController::class.'@saveNotes');
