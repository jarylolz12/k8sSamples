<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Terminal;
use App\Http\Resources\Standard as StandardResource;
use Illuminate\Support\Collection;
use Validator;
use App\Shipment;
use App\Custom\Traits\ValidateTerminal;

class TerminalController extends Controller
{
    use ValidateTerminal;

    public function save(Request $request)
    {
        $requestData = $request->json()->all();
        $response = ['status' => 'not ok','messages' => []];

        $rules = [
            'terminal' => 'required',
        ];

        $messages = [
            'terminal.required' => 'Terminal is required.',
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
                    'terminal_id' => $requestData['terminal'],
                    'customs_broker_id' => $requestData['customs_broker'],
                    'carrier_arrival_notice_eta' => $requestData['carrier_arrival_notice_eta'],
                    'suppliers_group' => '[]',
                    'containers_group' => '[]',
                    'schedules_group' => '[]',
                    'custom_content' => ''
                ]);
            } else {
                $findShipment = Shipment::find($requestData['id']);
                if (isset($findShipment->id)) {
                    //
                    if ($findShipment->type == "FCL") {
                        // code here
                        if ($this->validateByTerminal49($findShipment, $requestData['terminal'])) {
                            // $response['status'] = 'not ok';
                            $response['messages'] = ['terminal'=> ['Please Note that, Firms Code Doesn\'t match with Terminal49']];
                            // return response()
                            //           ->json($response);
                        }
                    }
                    //
                    $findShipment->terminal_id = $requestData['terminal'];
                    $findShipment->carrier_arrival_notice_eta   = $requestData['carrier_arrival_notice_eta'];
                    $findShipment->carrier_arrival_notice_firms = $requestData['carrier_arrival_notice_firms'];
                    $findShipment->customs_broker_id            = $requestData['customs_broker'];
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
            $response['results'] = Terminal::where('name', 'LIKE', '%'.$request->input('search').'%')
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
