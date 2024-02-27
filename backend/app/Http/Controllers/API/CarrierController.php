<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Carrier;
use App\Http\Resources\Standard as StandardResource;
/**
 *
 * @group Carrier
 *
 * APIs to manage the carrier resource
 *
 */
class CarrierController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @authenticated
     *
     * @queryParam per_page int required Size per page. Default to 5. Example: 5
     * @queryParam page int required Page to view. Example: 1
     *
     * @apiResourceCollection 201 App\Http\Resources\ScribeResources\CarrierResource
     * @apiResourceModel App\Carrier paginate=5
     *
     * @response status=400 scenario="Unauthenticated" {
     *     "message": "Unauthenticated."
     * }
     *
     * @return \Illuminate\Http\Response
     *
     */

    public function index()
    {
        //Contents of the Carrier
        return StandardResource::collection(Carrier::all()->paginate(200));
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
     * @urlParam id int required Carrier ID. Example: 1
     *
     * @apiResource App\Http\Resources\ScribeResources\CarrierResource
     * @apiResourceModel App\Carrier
     *
     * @response status=404 scenario="No query results"{
     *     "message": "No query results"
     * }
     *
     * @response status=400 scenario="Unauthenticated" {
     *     "message": "Unauthenticated."
     * }
     *
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        try {
            return new StandardResource(Carrier::findOrFail($id));
        }catch (\Exception $exception) {
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
