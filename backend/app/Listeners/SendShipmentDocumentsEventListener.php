<?php

namespace App\Listeners;

use App\Events\SendShipmentDocumentsEvent;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

use Illuminate\Support\Facades\Mail;
use App\Mail\SendShipmentDocumentsEmail;

use App;
use Carbon\Carbon;

use Illuminate\Support\Facades\Storage;

class SendShipmentDocumentsEventListener
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  SendShipmentDocumentsEvent  $event
     * @return void
     */
    public function handle(SendShipmentDocumentsEvent $event)
    {
        //\Debugbar::info('hello');
        $shipment = $event->resource;
        $documents = $shipment->customerDocuments;
        $cc = [];
        array_push($cc, 'teams@shifl.com');
        $to = [];
        $attachments = [];
        $newDocuments = [];
        if (count($documents) > 0) {
            if (isset($shipment->send_documents) && count($shipment->send_documents) > 0) {
                foreach($shipment->send_documents as $send_document) {
                    foreach($documents as $key => $document) {
                        $hasPush = false;
                        
                        if (isset($document->path) && $document->id==$send_document) {
                            $path = storage_path('/app/public/'.$document->path);
                            $explode_path =  explode('/', $document->path);
                            $documents[$key]->path_name = $explode_path[count($explode_path) - 1];
                            if (file_exists($path)) {
                                array_push($attachments, $path);
                            }
                        }

                        if ($document->id==$send_document) {
                            $checkSuppliers = json_decode($document->supplier_ids);
                            $documents[$key]->suppliers = $checkSuppliers;
                            array_push($newDocuments, $documents[$key]);
                        }    

                    }   
                }    
            }
        }
        
        $content = [
            'shifl_ref' => $shipment->shifl_ref,
            'mbl_num' => $shipment->mbl_num,
            'documents' => $newDocuments
        ];

        
        if ( isset($shipment->customer)) {
            if (isset($shipment->customer->offices_managers)) {
                try {
                    $allManagers = json_decode($shipment->customer->offices_managers);

                    if ( is_array($allManagers) && count($allManagers) > 0 ) {
                        $checkAccountManager = null;

                        foreach ($allManagers as $key => $allManager) {
                            
                            if (isset($shipment->officeFrom) && isset($shipment->officeFrom->id)) {
                                if ( $allManager->office_id==$shipment->officeFrom->id ) {
                                    $setManager = \App\AccountManager::find($allManager->manager_id);
                                    
                                    if (isset($setManager->id)) {
                                        array_push($cc, $setManager->email);
                                    }
                                }    
                            }
                            
                        }
                    }

                } catch (Exception $e) {

                }

            }

            if (isset($shipment->customer->manager) && isset($shipment->customer->manager->email)) {
                array_push($to, $shipment->customer->manager->email);
            }
        }

        if (count($to) > 0) {
            $subjects = sprintf('Submitted Documents: ID# %s, MBL# %s', $shipment->shifl_ref, $shipment->mbl_num);
            Mail::to($to)->cc($cc)->send(new SendShipmentDocumentsEmail($subjects, $content, $attachments));
        }

        
        
    }
}
