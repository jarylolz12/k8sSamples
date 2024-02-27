<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Customer;
/*
|--------------------------------------------------------------------------
| Tool API Routes
|--------------------------------------------------------------------------
|
| Here is where you may register API routes for your tool. These routes
| are loaded by the ServiceProvider of your tool. You're free to add
| as many additional routes to this file as your tool may require.
|
*/

Route::get('/', function (Request $request) {
    if($request->customer_id){
        return response(Customer::find($request->customer_id)->getConnectedTruckers(),200);
    }
    return response(['message' => 'Something went wrong!'],500);
});
