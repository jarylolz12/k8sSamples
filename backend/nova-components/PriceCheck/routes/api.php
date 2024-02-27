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

Route::get('/shipments', [\Vishalmarakana\PriceCheck\Http\Controllers\PriceCheckShipmentController::class, 'index']);
Route::post('/shipments/update-rate-confirmed', [\Vishalmarakana\PriceCheck\Http\Controllers\PriceCheckShipmentController::class, 'updateRateConfirmed']);
Route::get('/shipments/shifl-offices', [\Vishalmarakana\PriceCheck\Http\Controllers\PriceCheckShipmentController::class, 'shiflOffices']);
