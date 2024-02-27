<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Incoterm;

class IncoTermController extends Controller
{

    public function whereIn(Request $request) {
        $response = ['status' => 'ok','results' => []];
        $incoTerms = [];

        if ($request->has('ids')) {
            $ids = json_decode($request->input('ids'));
            $incoTerms = Incoterm::select('id', 'name')
                                 ->whereIn('id', $ids)
                                 ->get();

        }
        
        if (count($incoTerms)>0) {
            $response['results'] = $incoTerms;
        }
        
        return response()->json($response);
    }

    //
    public function getAll(Request $request)
    {

        $response = ['status' => 'ok','results' => []];
        $incoTerms = Incoterm::all();
        
        if (count($incoTerms)>0) {
            $response['results'] = $incoTerms;
        }

        return response()->json($response);
    }

    public function findById(Request $request)
    {

        $response = ['status' => 'not ok','result' => null];

        $findIncoTerm = Incoterm::find($request->input('id'));

        if (isset($findIncoTerm->id)) {
            $response['status'] = 'ok';
            $response['result'] = $findIncoTerm;
        }

        return response()->json($response);
    }
}
