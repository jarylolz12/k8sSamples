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

Route::get('/shipments', \Tanvirofficials\LfdTool\Http\Controllers\LfdToolController::class.'@index');
Route::post('/shipments/mark-as-reviewed/{mbl_num}/{container_num}', \Tanvirofficials\LfdTool\Http\Controllers\LfdToolController::class.'@markAsReviewed');
Route::post('/shipments/add-notes', \Tanvirofficials\LfdTool\Http\Controllers\LfdToolController::class.'@addNotes');
Route::post('/shipments/notify-demurrage/{id}', \Tanvirofficials\LfdTool\Http\Controllers\LfdToolController::class.'@notifyDemurrage');
