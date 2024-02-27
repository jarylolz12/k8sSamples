<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Tanvirofficials\UntrackedShipments\Http\Controllers\ShipmentController;
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

Route::get('/shipments', ShipmentController::class.'@index');
Route::get('/shipments/{id}', ShipmentController::class.'@show');
Route::post('/shipments/{id}', ShipmentController::class.'@update');
Route::post('/shipments/{id}/move-to-tracked', ShipmentController::class.'@moveToTracked');
Route::post('/shipments/{id}/save-notes', ShipmentController::class.'@saveNotes');
Route::get('/shifl-offices', ShipmentController::class.'@getOffices');
Route::get('/terminals', ShipmentController::class.'@getTerminals');
