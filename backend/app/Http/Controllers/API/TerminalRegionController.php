<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Auth;
use App\TerminalRegion;
use App\Http\Resources\Standard as StandardResource;
use Illuminate\Support\Collection;
/**
 *  
 * @authenticated
 * 
 * @group Terminal Region
 * 
 * APIs to manage the terminal region resource
 * 
*/
class TerminalRegionController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        //
        $response = ['status' => 'ok','results' => []];

        $q = ($request->has('q')) ? $request->input('q') : '';
        $is_paginate = ($request->has('is_paginate')) ? $request->input('is_paginate') : 1;

        $terminalRegions = [];
        if ( $q!=='') {
            $query_splits = explode(' ', $q);
            $terminalRegions = TerminalRegion::where(function ($query) use ($query_splits){
                foreach ( $query_splits as $query_split ) {
                    $query->orWhere('name', 'LIKE', '%'. $query_split .'%');
                }
            });
            $terminalRegions = ( $is_paginate === 1 ) ? $terminalRegions->paginate(35) : $terminalRegions->all();
        } else {

            $terminalRegions = ( $is_paginate === 1 ) ? TerminalRegion::paginate(35) : TerminalRegion::get();
        }

        $response['results'] = $terminalRegions;
        return response()->json($response);
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
     * @urlParam id int required Terminal Region ID. No-example
     *
     * @apiResource App\Http\Resources\ScribeResources\TerminalRegion
     * @apiResourceModel App\TerminalRegion
     * 
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $terminal = TerminalRegion::find($id);

        try{
            if ($terminal->id == $id) {
                return new StandardResource($terminal);
            }
        }catch(\Exception $exception){
            return response([
                'message'=>'No results found'
            ], 404);
        }

        return abort(404);
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
