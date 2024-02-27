<?php

/**
  * @author Kenneth
*/

namespace App\Http\Controllers\API\Shipments;

use App\Http\Controllers\Controller;
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
use App\Http\Controllers\API\Shipments\Traits\ShipmentSearchTrait;
use App\Http\Controllers\API\Shipments\Traits\ShipmentFunctionsTrait;


class ShipmentBaseController extends Controller
{   
	use ShipmentSearchTrait;
	use ShipmentFunctionsTrait;

	protected function array_unique_multidimensional($input)
    {
        $serialized = array_map('serialize', $input);
        $unique = array_unique($serialized);
        return array_intersect_key($input, $unique);
    }

    protected function merge_group($array_first, $array_second) {

        $merge_first = (is_array(json_decode($array_first))) ? json_decode($array_first) : [];
        $merge_to_second = (is_array(json_decode($array_second))) ? json_decode($array_second) : [];
        $merge_arrays = array_merge($merge_first, $merge_to_second);
        $final_data = $this->array_unique_multidimensional($merge_arrays);
        return json_encode($final_data);
    }

    protected function ifNull($firstValue, $secondValue, $nullValue = null){
        return (empty($firstValue) ? (empty($secondValue) ? $nullValue : $secondValue) : $firstValue);
    }

    protected function getAis($shipment)
    {
        if ($shipment->mbl_num ?? false) {
            //$terminal49_shipment = Terminal49Shipment::find($shipment->mbl_num);
            $terminal49_shipment = $shipment->terminal_fortynine;
            if ($terminal49_shipment ?? false) {
                $attributes = json_decode($terminal49_shipment->attributes);
                if ($attributes ?? false) {
                    if ($attributes->pod_vessel_imo ?? false && !empty($attributes->pod_vessel_imo)) {
                        return "https://www.vesselfinder.com/?imo=" . $attributes->pod_vessel_imo;
                    }
                }
            }
        }
        return '';
    }
}