<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Carrier;
use App\Http\Resources\Standard as StandardResource;
use Illuminate\Support\Collection;
use Validator;
use App\Shipment;

class CarrierController extends Controller
{
    public function whereIn(Request $request)
    {
        $response = ['status' => 'ok','results' => []];
        $carriers = [];

        if ($request->has('ids')) {
            $ids = json_decode($request->input('ids'));
            $carriers = Carrier::select('id', 'name')
                                 ->whereIn('id', $ids)
                                 ->get();
        }

        $response['results'] = $carriers;
        return response()->json($response);
    }


    public function getAll(Request $request)
    {
        $response = ['status' => 'ok','results' => []];
        $carriers = Carrier::all();

        $newCarriers = [];
        if (count($carriers) > 0) {
            foreach ($carriers as $key => $carrier) {
                array_push($newCarriers, [
                    'name' => $carrier->name,
                    'id' => $carrier->id,
                    'type' => $carrier->carrierType->name ?? null
                ]);
            }
        }

        if (count($carriers)>0) {
            $response['results'] = $newCarriers;
        }

        return response()->json($response);
    }


    public function save(Request $request)
    {
        $requestData = $request->json()->all();
        $response = ['status' => 'not ok','messages' => []];

        $rules = [
            'carrier' => 'required'
        ];

        $messages = [
            'carrier.required' => 'Carrier is required.'
        ];


        $validator = Validator::make($requestData, $rules, $messages);

        if ($validator->fails()) {
            $response['messages'] = $validator->messages()->toArray();
        } else {
            $response['status'] = 'ok';
            $response['id'] = $requestData['id'];
            if ($request['id']==null) {
                \DB::table('shipments')
                ->insert([
                    'carrier_id' => $requestData['carrier'],
                    'suppliers_group' => '[]',
                    'containers_group' => '[]',
                    'schedules_group' => '[]',
                    'custom_content' => ''
                ]);
            } else {
                $findShipment = Shipment::find($requestData['id']);
                if (isset($findShipment->id)) {
                    $findShipment->carrier_id = $requestData['carrier'];
                    $findShipment->save();
                }
            }
        }
        return response()
               ->json($response);
    }


    public function search(Request $request)
    {
        $response = ['status' => 'not ok','results' => null];

        if ($request->has('search')) {
            $response['results'] = Carrier::where('name', 'LIKE', '%'.$request->input('search').'%')
                                  ->get();

            if (count($response['results'])>0) {
                $newResults = [];
                foreach ($response['results'] as $key => $result) {
                    array_push($newResults, [
                        'label' => $result->name,
                        'value' => $result->id
                    ]);
                }

                if (is_null($request->per_page)) {
                    $response['results'] = StandardResource::collection((new Collection($newResults)));
                }
                if (is_numeric($request->per_page)) {
                    $response['results'] = StandardResource::collection((new Collection($newResults))->paginate($request->per_page));
                }
            }

            $response['status'] = 'ok';
        }

        return response()->json($response);
    }
}
