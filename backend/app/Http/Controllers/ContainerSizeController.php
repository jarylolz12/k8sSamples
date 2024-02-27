<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\ContainerSize;

class ContainerSizeController extends Controller
{


    public function whereIn(Request $request) {
        $response = ['status' => 'ok','results' => []];
        $containerSizes = [];

        if ($request->has('ids')) {
            $ids = json_decode($request->input('ids'));
            $containerSizes = ContainerSize::select('id', 'name')
                                 ->whereIn('id', $ids)
                                 ->get();
        }

        $response['results'] = $containerSizes;
        return response()->json($response);
    }

    //
    public function getAll(Request $request)
    {

        $response = ['status' => 'ok','results' => []];
        $sizes = ContainerSize::all();
        
        if (count($sizes)>0) {
            $response['results'] = $sizes;
        }
        
        return response()->json($response);
    }

    public function findById(Request $request)
    {

        $response = ['status' => 'not ok','result' => null];

        $findSize = ContainerSize::find($request->input('id'));

        if (isset($findSize->id)) {
            $response['status'] = 'ok';
            $response['result'] = $findSize;
        }

        return response()->json($response);
    }
}
