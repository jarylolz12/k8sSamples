<?php

namespace App\Listeners;

use App\Events\InsertBillAttachmentEvent;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\Request;
use QuickBooksOnline\API\Data\IPPReferenceType;
use QuickBooksOnline\API\Data\IPPAttachableRef;
use QuickBooksOnline\API\Data\IPPAttachable;
use App\BillAttachment;

class InsertBillAttachmentEventListener
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
     * @param  InsertBillAttachmentEvent  $event
     * @return void
     */
    public function handle(InsertBillAttachmentEvent $event)
    {

        $resource = $event->resource;
        $file = $resource['resource']['file'];
        $args = $resource['args'];
        $hash_file = md5($file->getClientOriginalName() . now());
        $final_display_name = $hash_file . '.' . $file->guessExtension();
        $final_name = 'shipment/bill/attachment/'.$final_display_name;
        Storage::disk('local')->putFileAs('/public', $file, $final_name);
        $file_mimetype = $file->getMimeType();
        $file_base_64 = base64_encode(file_get_contents($file));
        $final_upload = null;

        $entity_ref = new IPPReferenceType(['value'=>$args['qbo_bill_id'], 'type'=>'Bill']);
        $attachable_ref = new IPPAttachableRef(['EntityRef'=>$entity_ref, 'IncludeOnSend'=>true]);

        $objAttachable = new IPPAttachable();
        $objAttachable->FileName = $final_display_name;
        $objAttachable->AttachableRef = $attachable_ref;
        $quickbooks = app('QuickBooks');
        $result = $quickbooks->getDataService()
                    ->Upload($file_base_64,$objAttachable->FileName,$file_mimetype,$objAttachable);

        $bill_attachment = new BillAttachment;
        $bill_attachment->bill_id = $args['bill_id'];
        $bill_attachment->qbo_bill_id = $args['qbo_bill_id'];
        $bill_attachment->shipment_id = $args['shipment_id'];
        $bill_attachment->qbo_attachable_id = $result->Attachable->Id;
        $bill_attachment->filename = $final_display_name;
        $bill_attachment->path = $final_name;
        $bill_attachment->save();

    }
}