<?php
namespace App\Custom;

/**
 * Helps to add Shipments to terminal49
 */
use App\Shipment;
use Illuminate\Support\Facades\Http;
use GuzzleHttp\Client;
use Mail;
use App\Terminal49Shipment;
use App\Custom\Traits\Resyncable;

class AddShipmentToTerminal49
{
    private $shipment;
    private $mbl;

    use Resyncable;

    private function __construct($shipment)
    {
        // \Log::info("Entered AddShipmentToTerminal49 " . $shipment->mbl_num . " mbl");
        $this->shipment = $shipment;
        $this->mbl = trim($shipment->mbl_num);

        if ($this->isChanged()) {
            $this->add();
        }

        // $this->handle();
    }

    public static function build($shipment)
    {
        (new AddShipmentToTerminal49($shipment));
    }

    private function isChanged()
    {
        // if (trim($this->shipment->getOriginal("mbl_num")) != $this->mbl && $this->mbl != "") {
        //     return true;
        // }
        return $this->mbl != "";
    }

    private function add()
    {
        if(Terminal49Shipment::where('mbl_num', $this->mbl)->exists()){
            \DB::table('shipments')->where('mbl_num', $this->mbl)->update(['retry_tracking_request' => 0]);
            return;
        }

        $scac = $this->getScac();

        // send track request here.

        // \Log::info("here");
        $key = config('terminal49.terminal49key');
        $response = Http::withHeaders([
          "Authorization" => 'Token '. $key,
          "Content-type" => "application/json"
        ])
        ->withBody(
            '{
                "data": {
                  "attributes": {
                      "request_type": "bill_of_lading",
                      "request_number": "'.$this->mbl.'",
                      "scac": "'.$scac.'"
                    },
                    "type": "tracking_request"
                  }
              }',
            'json'
        )
        ->post('https://api.terminal49.com/v2/tracking_requests');

        // \Log::info($response->json());
        // \Log::info($response->status());

        if ($response->status() == 201) {
            //handle data here
            $tracking_request_id = $response->json()['data']['id'] ?? null;

            // store the tracking request id
            // \DB::table('shipments')->where('id', '=', $this->shipment->id)->update(['tracking_request_id' => $tracking_request_id]);
            \DB::table('shipments')->where('id', '=', $this->shipment->id)->increment('retry_tracking_request',1,['tracking_request_id' => $tracking_request_id]);
        } elseif ($response->status() == 422) {
            //handle error here
            // \Log::info("Errors");
            Mail::to(['tanvir@shifl.com'])->cc(['shabsie@shifl.com','eric@shifl.cn'])
                  ->send(new \App\Mail\ErrorLogging(
                      'Tracking Request Failed, Shipment : '.$this->mbl,
                      $response->json()['errors'] ?? [],
                      'mails.error.tracking'
                  ));
            //
            if ($this->isDuplicate($response->json()['errors'])) {
                //
                if (!Terminal49Shipment::where('mbl_num', $this->mbl)->exists()) {
                    $tracking_request_id = $this->getTrackingId($response->json()['errors']);
                    if($tracking_request_id){
                        $tracking_response = Http::withHeaders([
                            "Authorization" => 'Token '. $key,
                            "Content-type" => "application/json"
                        ])->get('https://api.terminal49.com/v2/tracking_requests/'.$tracking_request_id);

                        if($tracking_response->status() == 200){
                            if(isset($tracking_response->json()['data']['relationships']['tracked_object']['data'])){
                                $tracking_object = $tracking_response->json()['data']['relationships']['tracked_object']['data'];
                                if(isset($tracking_object['type']) && $tracking_object['type'] == 'shipment' && isset($tracking_object['id'])){
                                    $ts_id = $tracking_object['id'];
                                    if($ts_id){
                                        $tracking_shipment = new Terminal49Shipment();
                                        $tracking_shipment->ts_id = $ts_id;
                                        $tracking_shipment->tr_id = $tracking_request_id;
                                        $tracking_shipment->mbl_num = $this->mbl;

                                        $this->resync($tracking_shipment);

                                        \DB::table('shipments')->where('id', '=', $this->shipment->id)->update([
                                            'tracking_request_id' => $tracking_request_id,
                                            'retry_tracking_request' => 0
                                        ]);

                                        return;
                                    }

                                }
                            }
                        }
                    }
                    \DB::table('shipments')->where('id', '=', $this->shipment->id)->increment('retry_tracking_request');
                    return;
                }
            }
            \DB::table('shipments')->where('id', '=', $this->shipment->id)->update(['retry_tracking_request' => 0]);
            //
            // \Log::info($response->json()['errors']);
        }
    }

    private function getScac()
    {
        //
        $replace_default_scac = [
          "MEDU" => "MSCU"
        ];
        //
        $scac = substr($this->mbl, 0, 4);

        if (array_key_exists($scac, $replace_default_scac)) {
            return $replace_default_scac[$scac];
        }

        return $scac;
    }

    private function isDuplicate($errors)
    {
        foreach ($errors ?? [] as $key => $error) {
            // code...
            if ($error['code'] == 'duplicate') {
                return true;
            }
        }
        return false;
    }

    private function getTrackingId($errors){
        foreach ($errors ?? [] as $key => $error) {
            // code...
            if(isset($error['meta']['tracking_request_id'])){
              return $error['meta']['tracking_request_id'];
            }
            return null;
        }
        return null;
    }
}
