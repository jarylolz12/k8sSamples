<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Http;
use Illuminate\Http\Request;
use App\Shipment;
use App\Terminal49Shipment;
use Mail;

class AutomatedTrackingController extends Controller
{
    public function updateShipmentById($id, Request $request)
    {
        $shipment = Shipment::find($id);

        if($shipment){
            $shipment->is_automated_tracking = $request->id;
            $shipment->automated_request_id = $request->requestId ?? '';
            $shipment->update();
        }

        return response()->json([
            'status' => $shipment->is_automated_tracking == 1 ? 200 : 400, 
            'is_automated_tracking' => $shipment->is_automated_tracking
        ], 200);

    }

    public function getAllShipment() 
    {
        $url = 'https://us-central1-fifth-compiler-357712.cloudfunctions.net/nbl_function2/api/v2/tracking_requests';
        
        
        $shipments = Shipment::whereNotNull('mbl_num')
                        ->where('type', '!=', 'Air')
                        ->where('cancelled', '!=', 1)
                        ->get();
        $result = [];               
      
        $shipments = $shipments->filter->isShipmentActive()->values();
        $shipments = $shipments->paginate(100);
      
        foreach($shipments as $shipment){

            //check if shipment is already on automated tracking
            $getResponse = Http::withHeaders([
                "Content-type" => "application/json"
                
            ])->get('https://us-central1-fifth-compiler-357712.cloudfunctions.net/nbl_function2/api/v3/shipments/?bl='.$shipment->mbl_num);

            if($getResponse->status() == 200){
                $requestId = $getResponse->json()['data']['id'] ?? null;
                \DB::table('shipments')->where('id', '=', $shipment->id)->update([
                    'automated_request_id' => $requestId,
                    'is_automated_tracking' => 1
                ]);
            }   


            $terminal_fortynine = \App\Terminal49Shipment::find(trim($shipment->mbl_num));
            $attribute = (isset($terminal_fortynine) && isset($terminal_fortynine->attributes)) ? json_decode($terminal_fortynine->attributes) : [];
            $scac = $attribute->shipping_line_scac ?? $this->getScac($shipment->mbl_num);
            
            
            $data = [
                'request_type' => "bill_of_lading",
                'request_number' => "$shipment->mbl_num",
                'scac' => "$scac"
            ];

           
            $response = Http::withBody(json_encode($data), 'application/json')
                ->post($url);       

            array_push($result, $response->json());
            array_push($result, $response->status());
        
            if($response->status() == 201){
                $tracking_request_id = $response->json()['data']['id'] ?? null;
                \DB::table('shipments')->where('id', '=', $shipment->id)->update([
                    'automated_request_id' => $tracking_request_id,
                    'is_automated_tracking' => 1
                ]);
                
            }else if($response->status() == 422){

                if($this->isDuplicate($response->json()['errors'])) {
                        $tracking_request_id = $this->getTrackingId($response->json()['errors']);

                        if($tracking_request_id){
                            $tracking_response = Http::withHeaders([
                                "Content-type" => "application/json"
                            ])->get($url.'/'.$tracking_request_id);
    
                            if($tracking_response->status() == 200){
                                \DB::table('shipments')->where('id', '=', $shipment->id)->update([
                                    'automated_request_id' => $tracking_request_id,
                                    'is_automated_tracking' => 1
                                ]);

                            }
                           
                        }

                        \DB::table('shipments')->where('id', '=', $shipment->id)->update([
                            'automated_request_id' => $this->getCode($response->json()['errors']),
                            'is_automated_tracking' => 2
                        ]);
                        

                }    

            }      


        }
        
        return $shipments;
        // return $result;
        

    }

    public function getAllShipment0325() 
    {
        $url = 'https://us-central1-fifth-compiler-357712.cloudfunctions.net/nbl_function2/api/v2/tracking_requests';
        
        $shipments = Shipment::where('is_automated_tracking', 0)
                        ->whereNotNull('mbl_num')
                        ->where('type', '!=', 'Air')
                        ->where('cancelled', '!=', 1)
                        ->orderBy('id', 'desc')
                        ->paginate(100);
         $result = [];               
        
        foreach($shipments as $shipment){

            //check if shipment is already on automated tracking
            $getResponse = Http::withHeaders([
                "Content-type" => "application/json"
                
            ])->get('https://us-central1-fifth-compiler-357712.cloudfunctions.net/nbl_function2/api/v3/shipments/?bl='.$shipment->mbl_num);

            if($getResponse->status() == 200){
                $requestId = $getResponse->json()['data']['id'] ?? null;
                \DB::table('shipments')->where('id', '=', $shipment->id)->update([
                    'automated_request_id' => $requestId,
                    'is_automated_tracking' => 1
                ]);
            }   


            $terminal_fortynine = \App\Terminal49Shipment::find(trim($shipment->mbl_num));
            $attribute = (isset($terminal_fortynine) && isset($terminal_fortynine->attributes)) ? json_decode($terminal_fortynine->attributes) : [];
            $scac = $attribute->shipping_line_scac ?? $this->getScac($shipment->mbl_num);
            
            
            $data = [
                'request_type' => "bill_of_lading",
                'request_number' => "$shipment->mbl_num",
                'scac' => "$scac"
            ];

           
            $response = Http::withBody(json_encode($data), 'application/json')
                ->post($url);       

            array_push($result, $response->json());
            array_push($result, $response->status());
        
            if($response->status() == 201){
                $tracking_request_id = $response->json()['data']['id'] ?? null;
                \DB::table('shipments')->where('id', '=', $shipment->id)->update([
                    'automated_request_id' => $tracking_request_id,
                    'is_automated_tracking' => 1
                ]);
                
            }else if($response->status() == 422){

                if($this->isDuplicate($response->json()['errors'])) {
                        $tracking_request_id = $this->getTrackingId($response->json()['errors']);

                        if($tracking_request_id){
                            $tracking_response = Http::withHeaders([
                                "Content-type" => "application/json"
                            ])->get($url.'/'.$tracking_request_id);
    
                            if($tracking_response->status() == 200){
                                \DB::table('shipments')->where('id', '=', $shipment->id)->update([
                                    'automated_request_id' => $tracking_request_id,
                                    'is_automated_tracking' => 1
                                ]);

                            }
                           
                        }

                        \DB::table('shipments')->where('id', '=', $shipment->id)->update([
                            'automated_request_id' => $this->getCode($response->json()['errors']),
                            'is_automated_tracking' => 2
                        ]);
                        

                }    

            }      


        }
        
        return $shipments;
        // return $result;


    }

    private function getScac($mbl)
    {
        $replace_default_scac = [
          "MEDU" => "MSCU"
        ];
        $scac = substr($mbl, 0, 4);

        if (array_key_exists($scac, $replace_default_scac)) {
            return $replace_default_scac[$scac];
        }

        return $scac;
    }

    private function isDuplicate($errors)
    {
        
        foreach($errors ?? [] as $key => $error){
            if($error['code'] == 'duplicate') return true;
        }

        return false;
    }


    private function getCode($errors)
    {
        
        foreach($errors ?? [] as $key => $error){
            return $error['code'];
        }

        return false;
    }

    private function getTrackingId($errors)
    {
       
        foreach($errors ?? [] as $key => $error){
            if(isset($error['meta']['tracking_request_id'])){
                return $error['meta']['tracking_request_id'];
            }
        }
    }

}
