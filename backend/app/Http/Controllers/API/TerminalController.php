<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Auth;
use App\Terminal;
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
class TerminalController extends Controller
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

        $terminals = [];
        if ( $q!=='') {
            $query_splits = explode(' ', $q);
            $terminals = Terminal::where(function ($query) use ($query_splits){
                foreach ( $query_splits as $query_split ) {
                    $query->orWhere('name', 'LIKE', '%'. $query_split .'%');
                }
            });
            $terminals = ( $is_paginate === 1 ) ? $terminals->paginate(35) : $terminals->all();
        } else {

            $terminals = ( $is_paginate === 1 ) ? Terminal::paginate(35) : Terminal::get();
        }

        $response['results'] = $terminals;
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
     * @apiResource App\Http\Resources\ScribeResources\Terminal
     * @apiResourceModel App\Terminal
     * 
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $terminal = Terminal::find($id);

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
