<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\CarrierType;
use App\Http\Resources\Standard as StandardResource;
use Auth;
/**
 *   
 * @group CarrierType
 * 
 * APIs to manage the CarrierType resource
 * 
 */
class CarrierTypeController extends Controller
{
    /**
     * Display a listing of the resource.  
     *
     * @authenticated
     * 
     * @queryParam per_page int Size per page. Default to 5. Example: 5
     * @queryParam page int Page to view. Example: 1
     * 
     * @response 200 { 
     *    "data": [
     *          {
     *              "id": 1,
     *              "name": "Air",
     *              "created_at": "2020-02-28T07:04:28.000000Z",
     *              "updated_at": "2020-02-28T07:04:28.000000Z"
     *          },
     *          {
     *              "id": 2,
     *              "name": "Ocean",
     *              "created_at": "2020-02-28T07:04:48.000000Z",
     *              "updated_at": "2020-02-28T07:04:48.000000Z"
    *           }
     *       ], 
     *       "links": {
     *          "first": "/?page=1",
     *          "last": "/?page=1",
     *          "prev": null,
     *          "next": null
     *       },
     *       "meta": {
     *          "current_page": 1,
     *          "from": 1,
     *          "last_page": 1,
     *          "path": "/api/carrier-types",
     *          "per_page": 5,
     *          "to": 2,
     *          "total": 2
     *       }
     *   } 
     * }
     *  
     * @response status=400 scenario="Unauthenticated" {
     *     "message": "Unauthenticated."
     * } 
     * 
     * @return \Illuminate\Http\Response
     */
    public function index()
    {

        // Auth::loginUsingId(3);
        return StandardResource::collection(CarrierType::all()->paginate(5));
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
     * @urlParam id int required CarrierType ID
     * 
     * @apiResource App\Http\Resources\ScribeResources\CarrierTypeResource
     * @apiResourceModel App\CarrierType
     *
     * @response status=404 scenario="No query results"{
     *     "message": "No query results"
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
        // Auth::loginUsingId(3);
        try{
            return new StandardResource(CarrierType::findOrFail($id));
        }catch (\Exception $exception){
            return response()->json([
                'message' => 'No results found.'
            ],  404);
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
