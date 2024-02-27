<?php

namespace App\Listeners;

use App\Events\SendForwarderEmailEvent;
use Illuminate\Support\Facades\Mail;
use App\Mail\ForwarderMail;
use App\ShipmentMeta;

class SendForwarderEmailEventListener
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


    private function array_unique_multidimensional($input)
    {
        $serialized = array_map('serialize', $input);
        $unique = array_unique($serialized);
        return array_intersect_key($input, $unique);
    }

    private function merge_group($array_first, $array_second) {

        $merge_first = (is_array(json_decode($array_first))) ? json_decode($array_first) : [];
        $merge_to_second = (is_array(json_decode($array_second))) ? json_decode($array_second) : [];
        $merge_arrays = array_merge($merge_first, $merge_to_second);
        $final_data = $this->array_unique_multidimensional($merge_arrays);
        return json_encode($final_data);
    }

    /**
     * Handle the event.
     *
     * @param  InsertBrexEvent  $event
     * @return void
     */
    public function handle(SendForwarderEmailEvent $event)
    {
        $resource = $event->resource;
        $content = $event->content;
        $groupBookings = json_decode($resource->suppliers_group_bookings) ?? [];
        $manualOrders = collect($groupBookings)->filter(function ($booking) {
            return !empty($booking->po_num);
        })->map(function($booking) { return (array)$booking; })->toArray();

        $subject = $content['email_subject'];

        $shipmentMeta = ShipmentMeta::where('shipment_id', $event->resource->id)->first();

        if ( isset($shipmentMeta->id)) {
            $containers = json_decode($shipmentMeta->containers);
        }

        $content['manual_orders'] = $manualOrders;

        $content['containers'] = $containers;
        $content['shipment_id'] = $resource['id'];
        $shipment = json_decode($content['shipment'], true);
        $shifRef = isset($shipment['shifl_ref']) ? $shipment['shifl_ref'] : '';
        $content['shifRef'] = $shifRef;
        $cc = $content['email_cc'];
        $to = $content['email_to'];

        $subject = $subject . ' - ' . $shifRef;

        Mail::to($to)->cc($cc)->send(new ForwarderMail($subject, 'mails.forwarderMail', $content));

    }
}
