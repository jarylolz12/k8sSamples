<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
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

Route::get('/get/{id}',function(Request $request){
	$name = $request->id.'_cities.json';

	if( !file_exists(base_path('nova-components/SelectCity/src/cities/'.$name)) ){
		$data = [];
	}else{
		$data = json_decode( file_get_contents( base_path( 'nova-components/SelectCity/src/cities/'.$name ) ) );
	}

	return response()->json($data);
});