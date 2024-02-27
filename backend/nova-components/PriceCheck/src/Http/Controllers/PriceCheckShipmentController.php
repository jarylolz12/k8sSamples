<?php

namespace Vishalmarakana\PriceCheck\Http\Controllers;

use App\ShiflOffice;
use Illuminate\Routing\Controller;
use Illuminate\Http\Request;
use App\Shipment;

class PriceCheckShipmentController extends Controller
{
    public function index(Request $request)
    {
        $shipment_query = Shipment::select(
            'shipments.id as shipment_id',
            'shipments.shifl_ref',
            'shipments.etd',
            'shipments.rate_confirmed',
            'shipments.schedules_group_bookings',
            'shifl_offices.name as shifl_office_from',
            'carriers.name as carrierName',
            'customers.company_name as customerName',
            'customers.id as customerId'
        )
            ->where('booking_confirmed', 1)
            ->leftJoin('carriers', 'shipments.carrier_id', '=', 'carriers.id')
            ->leftJoin('customers', 'shipments.customer_id', '=', 'customers.id')
            ->leftJoin('shifl_offices', 'shipments.shifl_office_origin_from_id', '=', 'shifl_offices.id')
            ->where('cancelled', 0)
            ->orderBy($request->field, $request->orderBy);
        if(!is_null($request->rateConfirmed)){
            if($request->rateConfirmed != 'all') $shipment_query->where('shipments.rate_confirmed', $request->rateConfirmed);
        }
        if(!is_null($request->shiflOfficeFrom)){
            if($request->shiflOfficeFrom != 'all') $shipment_query->where('shipments.shifl_office_origin_from_id', $request->shiflOfficeFrom);
        }
        $results = (is_null($request->per_page)) ? $shipment_query->paginate(50) : $shipment_query->paginate(intval($request->per_page));

        $resultsArray = $results->toArray();
        $dataCollection = collect($resultsArray['data']);
        $modifiedData = $dataCollection->map(function ($item, $key) {
            $item['schedules_group_bookings'] = $this->getData($item['schedules_group_bookings']);

            return $item;
        });
        $resultsArray['data'] = $modifiedData;
        return response()->json([
            'results' => $resultsArray
        ]);

    }

    /**
     * Tests, if the given $value parameter is a JSON string.
     * When it is a valid JSON value, the decoded value is returned.
     * When the value is no JSON value (i.e. it was decoded already), then
     * the original value is returned.
     */
    function getData( $value, $as_object = false ) {
        if ( is_numeric( $value ) ) { return 0 + $value; }
        if ( ! is_string( $value ) ) { return $value; }
        if ( strlen( $value ) < 2 ) { return $value; }
        if ( 'null' === $value ) { return null; }
        if ( 'true' === $value ) { return true; }
        if ( 'false' === $value ) { return false; }
        if ( '{' != $value[0] && '[' != $value[0] && '"' != $value[0] ) { return $value; }

        $json_data = json_decode( $value, $as_object );
        if ( is_null( $json_data ) ) { return []; }
        return $json_data;
    }

    public function updateRateConfirmed(Request $request){
        $shipmentIds = $request->get('shipment_id_list');
        if($shipmentIds){
            return Shipment::whereIn('id', $shipmentIds)->update(['rate_confirmed' => 1]);
        }
        return true;
    }

    public function shiflOffices(){
        $shiflOffices = ShiflOffice::all();

        return response()->json([
            'results' => $shiflOffices
        ]);
    }
}
