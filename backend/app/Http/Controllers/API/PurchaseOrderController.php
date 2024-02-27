<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\PurchaseOrder;
use App\Http\Resources\Standard as StandardResource;
use Illuminate\Support\Facades\Validator;

/**
 *  
 * @group PurchaseOrder
 * 
 * APIs to manage the PurchaseOrder resource
 * 
 */
class PurchaseOrderController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @authenticated
     * 
     * Get a list of Purchase Order
     *
     * @queryParam per_page int required Size per page. Default to 5. Example: 5
     * @queryParam page int required Page to view. Example: 1
     * 
     * @apiResource App\Http\Resources\ScribeResources\PurchaseOrderResource
     * @apiResourceModel App\PurchaseOrder paginate=5 
     * 
     * @response status=400 scenario="Unauthenticated" {
     *     "message": "Unauthenticated."
     * }
     * 
     * 
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return StandardResource::collection(PurchaseOrder::all()->paginate(5));
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
     * @authenticated
     *
     * @urlParam id int required Purchase Order ID
     * 
     * @apiResource App\Http\Resources\ScribeResources\PurchaseOrderResource
     * @apiResourceModel App\PurchaseOrder 
     * @apiResourceAdditional result=success message="Display data"
     *  
     * @response status=404 {
     *    "message": "Purchase order not found"
     * }
     * 
     * @response status=400 scenario="Unauthenticated" {
     *     "message": "Unauthenticated."
     * }
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request, $id)
    {
        try{
            return new StandardResource(PurchaseOrder::findOrFail($id));
        }catch (\Exception $exception){
            return response([
                'message'=>'No results found'
            ],404);
        }
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
