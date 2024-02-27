<?php

namespace Vishalmarakana\ShipmentSoReceivedCheckbox;

use App\Shipment;
use Laravel\Nova\Fields\Field;
use Laravel\Nova\Http\Requests\NovaRequest;

class ShipmentSoReceivedCheckbox extends Field
{
    /**
     * The field's component.
     *
     * @var string
     */
    public $component = 'shipment-so-received-checkbox';

    public function initFields($so_received, $is_agent_booking_confirm, $id)
    {
        $r = new NovaRequest();

        if (!$r->isResourceIndexRequest()) {
            $officeFrom = '';

            $findShipment = ($id!=null) ? Shipment::find($id) : [];

            if (isset($findShipment->id)) {
                $officeFrom = $findShipment->officeFrom;

                $scheduleBookings = (is_array(json_decode($findShipment->schedules_group_bookings))) ? json_decode($findShipment->schedules_group_bookings) : [];

                if (is_countable($scheduleBookings) && count($scheduleBookings) > 0) {

                    foreach ($scheduleBookings as $key => $scheduleBooking) {

                        if (!isset($scheduleBooking->carrier_name)) {
                            $scheduleBookings[$key]->carrier_name = [
                                'id' => 0
                            ];
                        } else {
                            if ( $scheduleBooking->carrier_name==null ) {
                                $scheduleBookings[$key]->carrier_name = [
                                    'id' => 0
                                ];
                            }
                        }
                        if (!isset($scheduleBooking->vendor_id)) {
                            $scheduleBookings[$key]->vendor_id = 0;
                        }

                        if (!isset($scheduleBooking->sell_rates)) {
                            $scheduleBookings[$key]->sell_rates = [];
                        } else {
                            if (isset($scheduleBooking->sell_rates) && $scheduleBooking->sell_rates!=='' && $scheduleBooking->sell_rates!==null) {
                                if (count($scheduleBooking->sell_rates) > 0) {

                                    foreach($scheduleBooking->sell_rates as $k => $sr) {
                                        if (!isset($scheduleBooking->sell_rates[$k]->valid_from)) {
                                            $scheduleBookings[$key]->sell_rates[$k]->valid_from = null;
                                        }

                                        if (!isset($scheduleBooking->sell_rates[$k]->valid_to)) {
                                            $scheduleBookings[$key]->sell_rates[$k]->valid_to = null;
                                        }

                                    }
                                }
                            }
                        }


                        if (!isset($scheduleBooking->buy_rates)) {
                            $scheduleBookings[$key]->buy_rates = [];
                        } else {
                            if (isset($scheduleBooking->buy_rates) && $scheduleBooking->buy_rates!=='' && $scheduleBooking->buy_rates!==null) {
                                if (count($scheduleBooking->buy_rates) > 0) {

                                    foreach($scheduleBooking->buy_rates as $k => $sr) {
                                        if (!isset($scheduleBooking->buy_rates[$k]->valid_from)) {
                                            $scheduleBookings[$key]->buy_rates[$k]->valid_from = null;
                                        }

                                        if (!isset($scheduleBooking->buy_rates[$k]->valid_to)) {
                                            $scheduleBookings[$key]->buy_rates[$k]->valid_to = null;
                                        }

                                    }
                                }
                            }
                        }

                        if (!isset($scheduleBooking->legs)) {
                            $scheduleBookings[$key]->legs = [];
                        }

                    }
                }

                //schedule merge
                $mergeScheduleBookings = $scheduleBookings;

                $hasBookingsRecord = false;
                if (count($mergeScheduleBookings) > 0) {
                    $hasBookingsRecord = true;
                }

                if ($hasBookingsRecord) {
                    $findShipment->schedules_group = $mergeScheduleBookings;
                } else {
                    $findShipment->schedules_group = (is_array(json_decode($findShipment->schedules_group))) ? json_decode($findShipment->schedules_group) : [];
                }

            }

            return $this->withMeta([
                'shipment' => $findShipment,
                'office_from' => $officeFrom,
                'so_received' => $so_received,
                'is_agent_booking_confirm' => $is_agent_booking_confirm
            ]);
        } else {
            return $this->withMeta([
                'so_received' => $so_received,
                'is_agent_booking_confirm' => $is_agent_booking_confirm
            ]);
        }
    }

}
