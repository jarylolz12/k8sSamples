<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Item;
use App\Http\Resources\Standard as StandardResource;
/** 
 * @group Item
 * 
 * APIs to manage the Item resource 
 */
class ItemController extends Controller
{
    /**
     * Display a listing of the resource.
     * 
     * @authenticated 
     *
     * @queryParam per_page int Size per page. Default to 5. Example: 5
     * @queryParam page int Page to view. Example: 1
     * 
     * @apiResourceCollection App\Http\Resources\ScribeResources\ItemResource
     * @apiResourceModel App\Item paginate=5
     *  
     * @response status=400 {
     *  "message": "Unauthenticated"
     * }
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return StandardResource::collection(Item::all()->paginate(5));
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
     * @urlParam id int required Item ID
     * 
     * @apiResource App\Http\Resources\ScribeResources\ItemResource
     * @apiResourceModel App\Item 
     * 
     * @response status=404 {
     *  "message": "No query results for model [App\Item]"
     * } 
     * 
     * @response status=400 {
     *  "message": "Unauthenticated"
     * }
     * 
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        try{
            return new StandardResource(Item::findOrFail($id));
        }catch (\Exception $exception){
            return response([
                'message'=>'No results found'
            ], 404);
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
