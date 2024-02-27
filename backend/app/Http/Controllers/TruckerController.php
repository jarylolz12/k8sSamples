<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Trucker;
use App\Http\Resources\Standard as StandardResource;
use Illuminate\Support\Collection;
use Validator;
use App\Shipment;

class TruckerController extends Controller
{


    public function save(Request $request)
    {

        $requestData = $request->json()->all();
        $response = ['status' => 'not ok','messages' => []];

        $rules = [
            'trucker' => 'required',
            'trucker_custom_note' => 'required'
        ];

        $messages = [
            'trucker.required' => 'Trucker is required.',
            'trucker_custom_note.required' => 'Delivery Notes is required.'
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
                    'trucker_id' => $requestData['trucker'],
                    'trucker_custom_note' => $requestData['trucker_custom_note'],
                    'copy_customer' => $requestData['copy_customer'],
                    'suppliers_group' => '[]',
                    'containers_group' => '[]',
                    'schedules_group' => '[]',
                    'custom_content' => ''
                ]);
            } else {
                
                $findShipment = Shipment::find($requestData['id']);
                if (isset($findShipment->id)) {

                    $updates = [];

                    if (isset($requestData['trucker'])) {
                        $updates['trucker_id'] = $requestData['trucker'];
                    }

                    if (isset($requestData['trucker_custom_note'])) {
                        $updates['trucker_custom_note'] = $requestData['trucker_custom_note'];
                    }

                    /* if ($requestData->has('copy_customer')) {
                        $updates['copy_customer'] = $requestData->input('copy_customer');
                    } */

                    if (isset($requestData['copy_customer'])) {
                        $updates['copy_customer'] = $requestData['copy_customer'];
                    }

                    if (isset($updates['trucker_id']) || isset($updates['trucker_custom_note']) || isset($updates['copy_customer'])) {
                        \DB::table('shipments')
                        ->where('id',$findShipment->id)
                        ->update($updates);
                    }

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
            $response['results'] = Trucker::where('name', 'LIKE', '%'.$request->input('search').'%')
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
