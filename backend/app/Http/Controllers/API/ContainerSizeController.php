<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\ContainerSize;
use App\Http\Resources\Standard as StandardResource;
/**
 *  
 * @authenticated
 * 
 * @group Container Size
 * 
 * APIs to manage the container size resource
 * 
*/
class ContainerSizeController extends Controller
{
    /**
     * Display a listing of the resource.
     * 
     * @authenticated
     * 
     * @queryParam per_page int required Size per page. Default to 5. Example: 5
     * @queryParam page int required Page to view. Example: 1
     * 
     * @apiResource App\Http\Resources\ScribeResources\ContainerSizeResource
     * @apiResourceModel App\ContainerSize paginate=5 
     * 
     * @response status=400 {
     *    "message": "Unauthenticated"
     * }
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $per_page = $request->has('per_page') ? intval($request->input('per_page')) : 5;
        return StandardResource::collection(ContainerSize::all()->paginate($per_page));
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
     * @urlParam id int required The container size ID
     *  
     * @apiResource App\Http\Resources\ScribeResources\ContainerSizeResource
     * @apiResourceModel App\ContainerSize states=editor,verified
     * @apiResourceAdditional result=success message=""
     * 
     * @response status=400 {
     *    "message": "Unauthenticated",
     * }
     * 
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        try{
            return new StandardResource(ContainerSize::findOrFail($id));
        }catch(\Exception $exception){
            return response([
                'message'=>'No results found.'
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
