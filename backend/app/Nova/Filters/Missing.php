<?php

namespace App\Nova\Filters;

use Illuminate\Http\Request;
use Laravel\Nova\Filters\Filter;
use OptimistDigtal\NovaMultiselectFilter\MultiselectFilter;

class Missing extends MultiselectFilter
{
    /**
     * The filter's component.
     *
     * @var string
     */
    // public $component = 'select-filter';

    /**
     * Apply the filter to the given query.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @param  mixed  $value
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function apply(Request $request, $query, $value)
    {

        if (in_array("is_booking_email_sent", $value))
        {
            $query->where('is_booking_email_sent', '0');
        }
        if (in_array("booking_confirmed", $value))
        {
            $query->where('booking_confirmed', '0');
        }
        if (in_array("so_received", $value))
        {
            $query->where('so_received', 0);
        }
        if (in_array("so_received", $value))
        {
            $query->where('so_received', 0);
        }
        if (in_array("isf_done", $value))
        {
            $query->where('isf_done', 0);
        }
        if (in_array("ams_done", $value))
        {
            $query->where('ams_done', 0);
        }
        if (in_array("rate_confirmed", $value))
        {
            $query->where('rate_confirmed', 0);
        }
        if (in_array("ams_done", $value))
        {
            $query->where('ams_done', 0);
        }
        if (in_array("gate_full_in", $value))
        {
            $query->where('gate_full_in', 0);
        }
        if (in_array("gate_full_in", $value))
        {
            $query->where('gate_full_in', 0);
        }
        if (in_array("is_containers_loaded", $value))
        {
            $query->where('is_containers_loaded', 0);
        }
        if (in_array("mbl_released_confirmed", $value))
        {
            $query->where('mbl_released_confirmed', 0);
        }
        if (in_array("arrival_notice", $value))
        {
            $query->where('arrival_notice', 0);
        }
        if (in_array("an_sent_at", $value))
        {
            $query->where('an_sent_at', 0);
        }
        if (in_array("arrival_notice_confirmed", $value))
        {
            $query->where('arrival_notice_confirmed', 0);
        }
        if (in_array("entry_netchb_submitted", $value))
        {
            $query->where('entry_netchb_submitted', 0);
        }
        if (in_array("customs_processed", $value))
        {
            $query->where('customs_processed', 0);
        }
        if (in_array("freight_released_processed", $value))
        {
            $query->where('freight_released_processed', 0);
        }
        if (in_array("delivery_order_left", $value))
        {
            $query->where('delivery_order_left', 0);
        }
        if (in_array("do_sent_at", $value))
        {
            $query->where('do_sent_at', 0);
        }
        if (in_array("DO_confirmed", $value))
        {
            $query->where('DO_confirmed', 0);
        }
        if (in_array("freight_confirmed", $value))
        {
            $query->where('freight_confirmed', 0);
        }
        if (in_array("customs_released", $value))
        {
            $query->where('customs_released', 0);
        }
        if (in_array("released_at_terminal", $value))
        {
            $query->where('released_at_terminal', 0);
        }
        if (in_array("pending_refund", $value))
        {
            $query->where('pending_refund', 0);
        }
        if (in_array("delivered", $value))
        {
            $query->where('delivered', 0);
        }
        if (in_array("billed", $value))
        {
            $query->where('billed', 0);
        }
        if (in_array("cancelled", $value))
        {
            $query->where('cancelled', 0);
        }
        return $query;
    }

    /**
     * Get the filter's available options.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function options(Request $request)
    {
        return [
            'is_booking_email_sent'=>'Booking Sent to Customer',
//            2=>'Booking Sent to Agent',
            'booking_confirmed'=>'Booking Confirmed',
            'so_received'=>'SO Received',
            'so_released'=>'SO/BC Sent/Released to Shipper',
            'isf_done'=>'ISF Done',
            'ams_done'=>'AMS/ENS Done',
            'rate_confirmed'=>'Rate Confirmed',
            'gate_full_in'=>'Gate In/Full In',
            'is_containers_loaded'=>'Containers Loaded',
            'mbl_released_confirmed'=>'MBL Released Confirmed',
            'arrival_notice'=>'Arrival Notice Sent',
            'an_sent_at'=>'Arrival Notice Sent At',
            'arrival_notice_confirmed'=>'Arrival Notice Received',
//            16=>'HBL Released Confirmed',
//            17=>'Sufficient Commercial Docs',
            'entry_netchb_submitted'=>'Customs Sent',
            'customs_processed'=>'Customs Processed',
            'freight_released_processed'=>'Freight Released Processed',
            'delivery_order_left'=>'DO Sent',
            'do_sent_at'=>'DO Sent At',
            'DO_confirmed'=>'DO Confirmed',
            'freight_confirmed'=>'Freight Released Confirmed',
            'customs_released'=>'Customs Released Confirmed',
            'released_at_terminal'=>'Released At Terminal',
            'pending_refund'=>' ending Refund',
            'delivered'=>'Delivered',
            'billed'=>'Billed',
            'cancelled'=>'Cancelled',
        ];
    }
}
