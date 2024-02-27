<?php

namespace App\Listeners;

use App\Events\SendForwarderEmailAdminEvent;
use Illuminate\Support\Facades\Mail;
use App\Mail\ForwarderMailAdmin;
use App\ShipmentMeta;

class SendForwarderEmailAdminEventListener
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {

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
     * @param  SendForwarderEmailAdminEvent  $event
     * @return void
     */
    public function handle(SendForwarderEmailAdminEvent $event)
    {

        $resource = $event->resource;
        $content = $event->content;
        $subject = 'You\'ve receive a Booking Request';
        $groupBookings = json_decode($resource->suppliers_group_bookings) ?? [];
        $manualOrders = collect($groupBookings)->filter(function ($booking) {
            return !empty($booking->po_num);
        })->map(function($booking) { return (array)$booking; })->toArray();

        $shipmentMeta = ShipmentMeta::where('shipment_id', $event->resource->id)->first();

        if ( isset($shipmentMeta->id)) {
            $containers = json_decode($shipmentMeta->containers);
        }


        //get purchase orders
        $purchase_orders = $content['final_purchase_orders'];

        $total_cartons = 0;
        $total_volumes = 0;
        $total_weights = 0;

        foreach ($purchase_orders as $purchase_order ) {
            $total_cartons = $total_cartons + $purchase_order['total_cartons'];
            $total_volumes = $total_volumes + $purchase_order['total_volumes'];
            $total_weights = $total_weights + $purchase_order['total_weights'];
        }

        foreach($manualOrders as $manualOrder){
            $total_cartons += intval($manualOrder['total_cartons']);
            $total_volumes += intval($manualOrder['volume']);
            $total_weights += intval($manualOrder['kg']);
        }

        $content['manual_orders'] = $manualOrders;

        $content['total_cartons'] = $total_cartons;
        $content['total_volumes'] = $total_volumes;
        $content['total_weights'] = $total_weights;

        $content['containers'] = $containers;
        $content['shipment_id'] = $resource['id'];
        $cc = ['levan@shifl.com'];
        
        // Add email once forwarder is shifl
        if(isset($content['is_review']) && $content['is_review'] === true) {
            array_push($cc, 'teams@shifl.com');
        }
        $to = $content['forwarders'];
        $shipment = json_decode($content['shipment'], true);
        $shifRef = isset($shipment['shifl_ref']) ? $shipment['shifl_ref'] : '';

        $subject = $subject . ' - ' . $shifRef;

        Mail::to($to)->cc($cc)->send(new ForwarderMailAdmin($subject, 'mails.forwarderMail', $content));

    }
}
