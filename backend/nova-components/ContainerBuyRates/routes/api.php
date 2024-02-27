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

Route::get('/all','\Juliverdevshifl\ContainerBuyRates\Http\Controllers\ContainerBuyRatesController@index');
Route::get('/terminal','\Juliverdevshifl\ContainerBuyRates\Http\Controllers\ContainerBuyRatesController@terminals');
// Route::get('/index-rates/generate','\Juliverdevshifl\ContainerBuyRates\Http\Controllers\ContainerBuyRatesController@generate');
Route::get('/index-rates/all','\Juliverdevshifl\ContainerBuyRates\Http\Controllers\ContainerBuyRatesController@indexRates');
Route::post('/index-rates/save','\Juliverdevshifl\ContainerBuyRates\Http\Controllers\ContainerBuyRatesController@manualImport');
Route::post('/index-rates/delete','\Juliverdevshifl\ContainerBuyRates\Http\Controllers\ContainerBuyRatesController@deleteManualImport');
Route::get('/containers/all','\Juliverdevshifl\ContainerBuyRates\Http\Controllers\ContainerBuyRatesController@getContainers');
Route::get('/offices/all','\Juliverdevshifl\ContainerBuyRates\Http\Controllers\ContainerBuyRatesController@getOffices');
Route::get('/terminals/all','\Juliverdevshifl\ContainerBuyRates\Http\Controllers\ContainerBuyRatesController@getTerminals');