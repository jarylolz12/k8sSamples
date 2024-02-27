<?php

namespace App\Http\Controllers\API\Custom;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
/**
 *  
 * @group Custom Admin
 * 
 * APIs to manage the custom admin resources
 * 
 */
class CustomerAdminController extends Controller
{
    /**
     * Display a listing of the resource.
     * 
     * @authenticated
     *
     *  
	 * @response status=400 scenario="Unauthenticated" {
     *     "message": "Unauthenticated."
     * }
     * 
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
       //Auth::loginUsingId(11);
        $user = Auth::user();
        $suppliers = $user->getCustomerAdminSuppliers(); 
        return response()->json(["results" => $suppliers]);

    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
