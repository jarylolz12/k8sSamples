<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\TerminalRegion;

class TerminalRegionController extends Controller
{

    public function whereIn(Request $request) {
        $response = ['status' => 'ok','results' => []];
        $terminalRegions = [];

        if ($request->has('ids')) {
            $ids = json_decode($request->input('ids'));
            $terminalRegions = TerminalRegion::select('id', 'name')
                                 ->whereIn('id', $ids)
                                 ->get();

        }
        
        if (count($terminalRegions)>0) {
            $response['results'] = $terminalRegions;
        }
        
        return response()->json($response);
    }


    //
    public function getAll(Request $request)
    {

        $response = ['status' => 'ok','results' => []];
        $terminalRegions = TerminalRegion::all();
        
        if (count($terminalRegions)>0) {
            $response['results'] = $terminalRegions;
        }
        
        return response()->json($response);
    }

    public function findById(Request $request)
    {

        $response = ['status' => 'not ok','result' => null];

        $findTerminalRegion = TerminalRegion::find($request->input('id'));

        if (isset($findTerminalRegion->id)) {
            $response['status'] = 'ok';
            $response['result'] = $findTerminalRegion;
        }

        return response()->json($response);
    }
}
