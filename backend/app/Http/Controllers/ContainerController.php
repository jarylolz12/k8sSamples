<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Container;

class ContainerController extends Controller
{

    public function whereIn(Request $request) {
        $response = ['status' => 'ok','results' => []];
        $containers = [];

        if ($request->has('ids')) {
            $ids = json_decode($request->input('ids'));
            $containers = Container::select('unique_id', 'container_num')
                                 ->whereIn('unique_id', $ids)
                                 ->get();
        }
        
        if (count($containers)>0) {
            foreach($containers as $key => $container) {
                $containers[$key]->unique_id = intval($container->unique_id);
            }
            
        }

        $response['results'] = $containers;
        return response()->json($response);
    }

    //
    public function getAll(Request $request)
    {

        $response = ['status' => 'ok','results' => []];
        $containers = Container::all();
        
        if (count($containers)>0) {
            $response['results'] = $containers;
        }
        
        return response()->json($response);
    }
}
