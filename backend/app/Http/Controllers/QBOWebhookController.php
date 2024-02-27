<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use QuickBooksOnline\API\DataService\DataService;
use QuickBooksOnline\API\WebhooksService\WebhooksService;
use Log;

class QBOWebhookController extends Controller
{
    public function handle(Request $request){
        $reqHeaderSignature = $request->header('intuit-signature');
        $verifierKey = env('QUICKBOOKS_WEBHOOK_VERIFIER');
        if($reqHeaderSignature !== '' || $reqHeaderSignature !== null){
            $verified = $this->verifyRequest($verifierKey,json_encode($request->all()),$reqHeaderSignature);
            if($verified){
                $eventNotifications = $request->all();
                // foreach ($payleventNotificationsoad as $eventNotif) {
                    
                // }
                Log::info($eventNotifications);
            }
        }
        else{
            Log::info('Invalid Verifier key!');
        }
    }

    private function verifyRequest($token,$payload,$headerSignature){
        $verified =  WebhooksService::verifyPayload($token,$payload,$headerSignature);
        if($verified){
            $payload_data = json_decode($payload, true);
            return true;
        }else{
            return false;
        }
    }
}
