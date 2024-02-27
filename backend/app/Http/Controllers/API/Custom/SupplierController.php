<?php

namespace App\Http\Controllers\API\Custom;

use App\Http\Controllers\Controller;
use App\Supplier;
use Illuminate\Http\Request;
/**
 * @authenticated
 *
 * @group Custom Supplier
 *
 * APIs to manage the custom supplier resources
 *
 */

class SupplierController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
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
     *
     * Display the specified resource.
     *
     * @urlParam id int required Supplier ID
     *
     * @apiResource App\Http\Resources\Standard
     * @apiResourceModel App\Supplier
     *
     * @response status=404 scenario="Supplier not found" {
     *    "message": "Supplier not found"
     * }
     *
     * @response status=400 scenario="Unauthenticated" {
     *     "message": "Unauthenticated."
     * }
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
        $supplier = Supplier::find($id);
        return response()->json(["results" => $supplier]);
    }

    /**
     * Get Connected Supplier
     *
     * @urlParam id int required
     *
     * @response status=200 {
     *      "results": [
     *          10
     *      ]
     *  }
     *
     * @response status=401 {
     *     "message": "Unauthenticated."
     * }
     *
     *
     * @param $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function getConnectedSupplier($id)
    {
        $suppliers = Supplier::where('connected_customer', $id)->pluck('id');
        return response()->json(["results" => $suppliers]);
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
