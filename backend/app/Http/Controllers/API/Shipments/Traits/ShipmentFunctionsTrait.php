<?php

/**
  * @author Kenneth
*/

namespace App\Http\Controllers\API\Shipments\Traits;

use App\Http\Resources\Customer;
use Illuminate\Http\Request;
use Auth;
use App\Http\Resources\Standard as StandardResource;
use App\Shipment;
use Illuminate\Support\Collection;
use App\TerminalRegion;
use App\Supplier;
use App\Carrier;
use Carbon\Carbon;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Http;
use App\Events\SendNewBookingEmailEvent;
use App\Terminal49Shipment;
use App\Custom\CustomJWTGenerator;
use App\Rules\CheckIfOwnCustomer;

trait ShipmentFunctionsTrait {

    protected function formatDate( $getDate ) {
        $date_create = date_create($getDate);
        $date_format = date_format($date_create, 'Y-m-d');
        $output = Carbon::createFromFormat('Y-m-d', $date_format)->format('Y-m-d H:i:s');
        return $output;
    }

    protected function processTrackingShipments($findShipment, &$shipments, $key, $context, &$isSpecial) {

        //if tracking, process schedule with different logic
        //$tracking_status = $shipments[$key]->getTrackingStatus();

        //set valid tracking labels
        /*
        $validTrackings = ['Auto Tracking', 'Auto Tracked', 'Manual Tracking', 'Manual Tracked'];
        $isValidTracking = false;
        if ( in_array($tracking_status, $validTrackings) ) {
            $isValidTracking = true;
        }*/

        if($findShipment['is_tracking_shipment']){
            if(!empty($findShipment['mbl_num'])){

                $isSpecial = true;
                $shipments[$key]['booking_confirmed'] = 1;
                $shipments[$key]['is_tracking_shipment'] = 1;
                $terminal49_shipment = $shipments[$key]->terminal_fortynine;
                if ( isset ($terminal49_shipment->attributes) ) {
                    $attributes = json_decode($terminal49_shipment->attributes,true);
                    $shipments[$key]['location_from_name'] = $attributes['port_of_lading_name'];
                    $shipments[$key]['location_to_name'] = $attributes['port_of_discharge_name'];
                    $shipments[$key]['eta'] = $context->ifNull($attributes['pod_eta_at'],$attributes['pod_ata_at']);
                    $shipments[$key]['etd'] = $context->ifNull($attributes['pol_etd_at'],$attributes['pol_atd_at']);
                }

            }
        }
    }

    protected function processShipmentSchedules($getShipmentSchedules, &$shipments, $key, $context) {
        if (is_countable($getShipmentSchedules) && count($getShipmentSchedules)>0) {
          $hasConfirm = false;
          $setKey = 0;
          $shipments[$key]['selected_schedule_type'] = '';
          foreach ($getShipmentSchedules as $kk => $getShipmentSchedule) {
              if (!isset($getShipmentSchedule->sell_rates)) {
                  if ($shipments[$key]['schedule_has_pricing']) {
                      $shipments[$key]['schedule_has_pricing'] = false;
                  }
              } else {
                  if (isset($getShipmentSchedule->sell_rates) && is_array($getShipmentSchedule->sell_rates) && count($getShipmentSchedule->sell_rates)==0) {
                      if ($shipments[$key]['schedule_has_pricing']) {
                          $shipments[$key]['schedule_has_pricing'] = false;
                      }
                  }
              }

              if (isset($getShipmentSchedule->is_confirmed) && $getShipmentSchedule->is_confirmed==1 && !$hasConfirm) {
                  $hasConfirm = true;
                  $setKey = $kk;

                  if (isset($getShipmentSchedule->type)) {
                      $shipments[$key]['selected_schedule_type'] = $getShipmentSchedule->type;
                  }
              }


          }
          if(!$hasConfirm && $shipments[$key]['selected_schedule_type'] == '' && is_countable($getShipmentSchedules) && count($getShipmentSchedules) > 0){
              $shipments[$key]['selected_schedule_type'] = $getShipmentSchedules[0]->type;
          }

          if (!$hasConfirm) {
              $shipments[$key]['shipment_status'] = 'Pending Approval';
              $shipments[$key]['eta'] = null;
              $shipments[$key]['etd'] = null;
          }

          $shipments[$key]['location_to_name'] = (isset($getShipmentSchedules[$setKey])) ? $context->getTerminal($getShipmentSchedules[$setKey]->location_to) : '';
          $shipments[$key]['location_from_name'] = (isset($getShipmentSchedules[$setKey])) ? $context->getTerminal($getShipmentSchedules[$setKey]->location_from) : '';
        }
      }

    protected function cleanField($field) {
        return (isset($field) && $field!=null && $field!=='') ? $field : null;
    }

	protected function getMilestones($container_id, $standard = false)
    {
        $response = null;
        try {
            $response = shell_exec('curl -H "Content-Type: application/json" -H "Authorization: Bearer '.env("TERMINAL49_API_KEY", "DkUUMTgo2kBif1eFe9LGq88t").'" -X GET "https://api.terminal49.com/v2/containers/'.$container_id. ($standard ? '/transport_events' : '/raw_events').'"');

            $response = json_decode($response);

            if (isset($response->data)) {
                $response = $response->data;
            }
        } catch (Exception $e) {
        }


        return $response;
    }
    protected function getSupplierName($supplier, $customerId = null)
    {
        if(!empty($supplier->supplier_id)){
            $s = Supplier::find($supplier->supplier_id);
        }else{
            if(!empty($supplier->buyer_id) && !empty($customerId)){
                $s = \App\Customer::findOrFail($customerId);
            }
        }
        return $s->company_name ?? '';
    }

	protected function getTerminal($id)
    {
        $response = null;
        $terminal = TerminalRegion::find($id);

        if (isset($terminal->id) && $terminal->id == $id) {
            $terminalResource = new StandardResource($terminal);
            if (isset($terminalResource['name'])) {
                $response = $terminalResource['name'];
            }
        }
        return $response;
    }

    protected function getCurrentSelectedCustomer() {

        $customer_id = 0;
        $customers = Auth::user()->customersApi->pluck('id');
        $get_authenticated_user = Auth::user();

        if (count($customers) > 0) {
            $customer_id = ( $get_authenticated_user->default_customer_id!==null ) ? $get_authenticated_user->default_customer_id : $customers[0];
        }
        return $customer_id;
    }
}
