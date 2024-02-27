<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Kenetashi\OfficeEmailGroup\Http\Controllers\OfficeController;

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
Route::get('/office/{id}',[OfficeController::class, 'findById']);
Route::get('/create-office-emails',[OfficeController::class,'createOfficeEmails']);