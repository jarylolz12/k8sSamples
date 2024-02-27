<?php

namespace App;

use App\Mail\NetchbWebhookEntryMail;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Spatie\WebhookClient\Models\WebhookCall;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;
use Spatie\WebhookClient\WebhookConfig;


class EntryWebhook extends WebhookCall
{
    public static function storeWebhook(WebhookConfig $config, Request $request): WebhookCall
    {
        // $fileName = $request->file('sample_pdf')->getClientOriginalName();
        // $path = Storage::put('public/netchb_entry_pdf', $request->file('sample_pdf'));
        // logger("*******ENTRY WEBHOOK LOG STARTS HERE....*******");
        // logger($request->all());
        // logger($request->getContent());
        // logger($request->headers);
        // logger("*******ENTRY WEBHOOK LOG ENDS HERE....*******");

        $currentTimeStamp = Carbon::now()->timestamp;
        $requestType = $request->headers->get('Content-Type');
        $fileExtension = '.pdf';
        if ($requestType != "application/pdf") {
            $fileExtension = '.xml';
        }

        $fileName = '/public/shipment/netchb/netchb_' . $currentTimeStamp . $fileExtension;

        Storage::disk('local')->put($fileName, $request->getContent());

        if ($fileExtension == ".pdf") {
            $pdfParser = new \Smalot\PdfParser\Parser();
            $pdfText    = $pdfParser->parseFile(storage_path('/app' . $fileName));
            $findShiflReference = preg_match('/(85B-\d+-\d+)/', $pdfText->getText(), $matches);
            if ($findShiflReference == 1) {
                $shipmentEntryNo = $matches[0];
                $shipment = Shipment::firstWhere('entry_netchb_no', $shipmentEntryNo);
                if($shipment){
                    Storage::disk('local')->put('/public/shipment/netchb/' . $shipment->shifl_ref . '.pdf', $request->getContent());
                    Storage::delete(storage_path('/app' . $fileName));
                    $shipment->netchb_pdf = '/shipment/netchb/' . $shipment->shifl_ref . '.pdf';
                    $shipment->save();
                }
                
            }
        }

        if ($fileExtension == ".xml") {
            // $attachment = [storage_path('/app' . $fileName)];
            // Mail::to("cyrusrome22@gmail.com")->send(new NetchbWebhookEntryMail($attachment));
            $xmlFile = storage_path('/app' . $fileName);
            $xml = simplexml_load_file($xmlFile);
            $shipmentEntryNo = (string)$xml->{'entry-no'};

            $shipment = Shipment::firstWhere('entry_netchb_no', $shipmentEntryNo);
            if ($shipment) {
                Storage::disk('local')->put('/public/shipment/netchb/' .  $shipment->shifl_ref . '.xml', $request->getContent());
                Storage::delete(storage_path('/app' . $fileName));
                $shipment->netchb_xml = '/shipment/netchb/' .  $shipment->shifl_ref . '.xml';
                $shipment->save();
            }
        }

        return WebhookCall::create([
            'name' => $config->name,
            'payload' => "PAYLOAD"
        ]);
    }
}
