<?php

namespace App\Listeners;

use App\Events\UpdateDocumentEvent;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Http;
use App\Document;
use App\DocumentSupplier;

use Illuminate\Support\Facades\Storage;
use Illuminate\Http\Request;
class UpdateDocumentEventListener
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
     * @param  UpdateDocumentEvent  $event
     * @return void
     */
    public function handle(UpdateDocumentEvent $event)
    {

        $resource = $event->resource;
        $supplier_ids = [];

        if ( isset($resource['supplier_ids']) && $resource['supplier_ids']!==null ) {
            $supplier_ids = json_decode($resource['supplier_ids']);

            $insert_documents_suppliers = [];
            if ( count($supplier_ids) > 0) {
                foreach ( $supplier_ids as $supplier_id) {
                    $document_supplier = DocumentSupplier::where('supplier_id', intval($supplier_id))
                                    ->where('document_id', intval($resource['id']))
                                    ->first();

                    if ( !isset($document_supplier->id) ) {
                        array_push($insert_documents_suppliers, [
                            'document_id' => $resource['id'],
                            'supplier_id' => $supplier_id
                        ]);
                    }
                }
                if ( count ($insert_documents_suppliers) > 0 ) {
                    \DB::table('documents_suppliers')->insert($insert_documents_suppliers);
                }
            }   
        }

    }
}