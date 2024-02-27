<?php

namespace App\Listeners;

use App\Events\InsertDocumentEvent;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Http;
use App\Document;
use App\DocumentSupplier;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\Request;
class InsertDocumentEventListener
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
     * @param  InsertDocumentEvent  $event
     * @return void
     */
    public function handle(InsertDocumentEvent $event)
    {

        $resource = $event->resource;
        $supplier_ids = [];

        if (isset($resource['supplier_ids'])) {
            foreach( $resource['supplier_ids'] as $supplier_id) {
                array_push($supplier_ids, $supplier_id);
            }
        }
        
        $createDocument = Document::create([
            'name' => $resource['name'],
            'is_added_by_customer' => $resource['is_added_by_customer'],
            'type' => ucwords(strtolower($resource['type'])),
            'supplier_ids' => json_encode($supplier_ids),
            'shipment_id' => $resource['shipment_id'],
            'path' => (isset($resource['path']) && $resource['path']!=='') ? $resource['path'] : 'temp'
        ]);



        $insert_documents_suppliers = [];
        if ( count($supplier_ids) > 0) {
            foreach ( $supplier_ids as $supplier_id) {
                $document_supplier = DocumentSupplier::where('supplier_id', intval($supplier_id))
                                ->where('document_id', intval($createDocument->id))
                                ->first();

                if ( !isset($document_supplier->id) ) {
                    array_push($insert_documents_suppliers, [
                        'document_id' => $createDocument->id,
                        'supplier_id' => $supplier_id
                    ]);
                }
            }
            if ( count ($insert_documents_suppliers) > 0 ) {
                \DB::table('documents_suppliers')->insert($insert_documents_suppliers);
            }
        }


        if (isset($resource['has_file'])) {
            $file = $resource['file'];
            //$final_display_name = '';
            $hash_file = md5($file->getClientOriginalName() . now());
            $final_display_name = $hash_file . '.' . $file->guessExtension();
            $final_name = 'shipment/documents-customer/'.$final_display_name;
            Storage::disk('local')->putFileAs('/public', $file, $final_name);
            $createDocument->path = $final_name;
            $createDocument->save();
        }
        

    }
}