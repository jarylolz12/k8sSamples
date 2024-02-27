<?php

namespace App\Http\Controllers;

use App\Service;
use Illuminate\Http\Request;

class ServiceController extends Controller
{

    public function whereIn(Request $request) {
        
        $response = ['status' => 'ok','results' => []];
        $services = [];

        if ($request->has('ids')) {
            $ids = json_decode($request->input('ids'));
            $services = Service::select('id', 'name')
                                 ->whereIn('id', $ids)
                                 ->get();
        }

        $response['results'] = $services;
        return response()->json($response);
    }

    public function getAll(Request $request)
    {

        $response = ['status' => 'ok','results' => []];

        $services = Service::all();
        $newServices = [];

        if (count($services) > 0 ) {
            foreach ($services as $key => $service) {
                array_push($newServices, [
                    'name' => $service->name,
                    'id' => $service->id
                ]);
            }
            $services = $newServices;
        }

        if (count($services)>0) {
            $response['results'] = $newServices;
        }

        return response()->json($response);
    }
}