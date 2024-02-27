<?php

/**
  * @author Kenneth
*/
namespace App\Http\Controllers\API\Shipments\v4;

use App\Http\Controllers\Controller;
use Dompdf\Exception;
use Illuminate\Http\Request;
use Auth;
use App\Http\Resources\Standard as StandardResource;
use App\Shipment;
use Illuminate\Support\Collection;
use App\TerminalRegion;
use App\Supplier;
use App\Carrier;
use App\Document;

use Carbon\Carbon;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Http;
use App\Events\SendNewBookingEmailEvent;
use App\Terminal49Shipment;
use App\Custom\CustomJWTGenerator;
use App\Rules\CheckIfOwnCustomer;
use App\Rules\CheckShipmentByReference;
use App\Rules\ValidateShipment;
use App\Custom\Traits\HelperMethods;
use Illuminate\Validation\Rule;
use App\Http\Controllers\API\Shipments\ShipmentBaseController;

/**
 * @authenticated
 *
 * @group Shipment
 *
 * APIs to manage the Shipment resource
 *
 */
class ShipmentController extends ShipmentBaseController
{
    /**
     *
     * Snooze Shipment v2
     *
     * @queryParam per_page int
     *
     * @param Request $request
     * @return \Illuminate\Http\Resources\Json\AnonymousResourceCollection|void
     */
    public function indexSnooze(Request $request)
    {
        $customers = Auth::user()->customersApi->pluck('id');
        $get_authenticated_user = Auth::user();
        $shipments = [];
        $newShipments = [];
        

        if (count($customers) > 0) {

            $customers = ( $get_authenticated_user->default_customer_id!==null ) ? [$get_authenticated_user->default_customer_id] : [$customers[0]];
            
            $shipments = Shipment::whereHas('customer', function($q) use ($customers){
                $q->whereIn('id', $customers);
            })
            ->where(function ($qq) {
                $qq->whereNotNull('snooze_date_at');
                $qq->where('snooze_date_at','>', now());
            })
            ->where('booking_confirmed', 0)
            ->where('cancelled', 0)
            ->get();

            if (count($shipments) > 0) {

                foreach ($shipments as $key => $shipment) {

                    $should_push = true;

                    //schedule merge
                    //merge group fields start
                    $findShipment = $shipment;

                    $getCarrier = Carrier::find(intval($findShipment['carrier_id']));
                    $shipments[$key]['carrier'] = (isset($getCarrier->id)) ? $getCarrier : null;

                    $shipments[$key]['schedules_group'] = $this->merge_group($findShipment['schedules_group_bookings'], $findShipment['schedules_group']);
                    $shipments[$key]['schedules_group_bookings'] = $shipments[$key]['schedules_group'];

                    $shipments[$key]['schedule_has_pricing'] = true;

                    if (isset($shipments[$key]['schedules_group']) && $shipments[$key]['schedules_group']!=='') {
                        $getShipmentSchedules = json_decode($shipments[$key]['schedules_group']);
                        
                        //process schedules and others
                        $this->processShipmentSchedules($getShipmentSchedules, $shipments, $key, $this);
                    }

                    $shipments[$key]['location_from_name'] = $this->cleanField($shipments[$key]['location_from_name']);

                    $shipments[$key]['location_to_name'] = $this->cleanField($shipments[$key]['location_to_name']);

                    $shipments[$key]['suppliers_group'] = $this->merge_group($findShipment['suppliers_group_bookings'], $findShipment['suppliers_group']);
                    $shipments[$key]['suppliers_group_bookings'] = $shipments[$key]['suppliers_group'];

                    $suppliers_group = json_decode($shipments[$key]['suppliers_group']);
                    $suppliers_name = [];
                    foreach ($suppliers_group ?? [] as $supplier) {
                        array_push($suppliers_name, $this->getSupplierName($supplier));
                    }
                    $shipments[$key]['suppliers_name'] = $suppliers_name;
                    $shipments[$key]['containers_group'] = $this->merge_group($findShipment['containers_group_bookings'], $findShipment['containers_group']);
                    $shipments[$key]['containers_group_bookings'] = $shipments[$key]['containers_group'];

                    // new statuses
                    $shipments[$key]['status_v2'] = $shipment->getStatusV2();


                    // check if tracking shipments
                    $isSpecial = false;
                    $this->processTrackingShipments($findShipment, $shipments, $key, $this, $isSpecial);

                    if ($shipments[$key]['booking_confirmed']==1) {
                        $should_push = false;
                    }
                    //
                    if ($should_push) {
                        
                        // current day minus eta

                        //$diff_days = Carbon::parse($shipments[$key]['eta'])->diffInDays(now(), false);
                        $shipments[$key]['diff_days'] = 100000;
                        $proceed = false;

                        if ($shipments[$key]['status_v2']==null || $shipments[$key]['status_v2']=='No Status') {
                            /*
                            if ($diff_days<20) {
                                $proceed = true;   
                            }*/
                            $proceed = true;

                        } else {
                            if (strpos($shipments[$key]['status_v2'], 'In transit')) {
                                $proceed = false;
                            }
                        }

                        if ( $proceed ){
                            if (isset($shipments[$key]['schedules_group_bookings']) && $shipments[$key]['schedules_group_bookings']!=='' && $shipments[$key]['schedules_group_bookings']!==null) {
                                
                                $schedules = json_decode($shipments[$key]['schedules_group_bookings']);
                                if (count($schedules)>0) {

                                    foreach($schedules as $keySecond => $schedule) {
                                        $hasUnset = false;
                                        /*
                                        if (isset($schedule->sell_rates) && $schedule->sell_rates!=='' && $schedule->sell_rates!==null) {
                                            if (count($schedule->sell_rates)>0) {
                                                unset($schedules[$keySecond]);
                                                $hasUnset = true;
                                            }
                                        }*/
                                        
                                        /*
                                        if ( !$hasUnset ) {
                                            $hasUnset = true;
                                            $etd = Carbon::parse($schedule->etd)->diffInDays(now(), false);
                                            if ($etd>=-4) {
                                                unset($schedules[$keySecond]);
                                            }
                                        }

                                        if ( !$hasUnset ) {
                                            $hasUnset = true;
                                            $eta = Carbon::parse($schedule->eta)->diffInDays(now(), false);
                                            if ($eta>30) {
                                                unset($schedules[$keySecond]);
                                            }
                                        }*/
                                    }

                                    if (count($schedules)>0) {
                                        $shipments[$key]['schedules_group'] = json_encode($schedules);

                                        $shipments[$key]['snooze_date_at_readable'] = Carbon::parse($shipment->snooze_date_at)->format('F d, Y');

                                        array_push($newShipments, $shipments[$key]);
                                        /*
                                        if ($diff_days<20){
                                            array_push($newShipments, $shipments[$key]);
                                        }*/
                                    }

                                }   
                            }
                               
                        }
                        
                    }
                    //e

                    }
                }
            }


        if (is_null($request->per_page)) {
            //return StandardResource::collection((new Collection($newShipments)));
            return StandardResource::collection((new Collection($newShipments))->paginate(30));
        }
        if (is_numeric($request->per_page)) {
            // return StandardResource::collection((new Collection($newShipments))->paginate($request->per_page));
            return StandardResource::collection((new Collection($newShipments))->paginate($request->per_page));
        }
        return abort(404);
    }


    /**
     * Pending Quote
     *
     * @authenticated
     *
     * @queryParam per_page int Size per page. Default to 30. Example: 30
     *
     * @apiResourceCollection App\Http\Resources\Shipment
     * @apiResourceModel App\Shipment with=customer
     *
     * @response status=400 scenario="Unauthenticated" {
     *     "message": "Unauthenticated."
     * }
     *
    */
    public function indexPendingQuote(Request $request) {
        
        $customers = Auth::user()->customersApi->pluck('id');
        $order_by = $request->has('sort') ? $request->input('sort') : 'eta';
        $order = ($request->has('order')) ? $request->input('order') : 'desc';
        $shipments = [];
        $isSpecial = false;
        $per_page = $request->has('per_page') ? $request->input('per_page') : 30;

        $get_authenticated_user = Auth::user();
        $customers = ( $get_authenticated_user->default_customer_id!==null ) ? [$get_authenticated_user->default_customer_id] : [$customers[0]];

        $shipments = Shipment::with(['shipmentSchedules','carrier'])
                            ->whereIn('customer_id', $customers)
                            ->where('booking_confirmed', 0)
                            ->where('cancelled', 0)
                            ->whereHas('shipmentSchedules', function($q) {
                                $q->whereDate('eta_date', '>', Carbon::now()->subDays(60));
                                $q->whereNotNull('etd_date');
                                $q->whereNotNull('eta_date');
                                $q->doesntHave('shipmentScheduleSellRates');
                            })
                            ->where(function ($qq) {
                                $qq->where('snooze_date_at', NULL);
                                $qq->orWhere('snooze_date_at','<=', now());
                            })
                            ->paginate($per_page);           
        
        if ( count($shipments) > 0 ) {
            foreach( $shipments as $key => $shipment ) {
                $findShipment = $shipment;
                //s
                $shipments[$key]['schedules_group'] = $this->merge_group($findShipment['schedules_group_bookings'], $findShipment['schedules_group']);
                $shipments[$key]['schedules_group_bookings'] = $shipments[$key]['schedules_group'];
                $shipments[$key]['schedule_has_pricing'] = true;

                if (isset($shipments[$key]['schedules_group']) && $shipments[$key]['schedules_group']!=='') {
                    $getShipmentSchedules = json_decode($shipments[$key]['schedules_group']);
                    $this->processShipmentSchedules($getShipmentSchedules, $shipments, $key, $this);
                }

                $shipments[$key]['location_from_name'] = $this->cleanField($shipments[$key]['location_from_name']);
                $shipments[$key]['location_to_name'] = $this->cleanField($shipments[$key]['location_to_name']);
                $shipments[$key]['suppliers_group'] = $this->merge_group($findShipment['suppliers_group_bookings'], $findShipment['suppliers_group']);

                $shipments[$key]['suppliers_group_bookings'] = $shipments[$key]['suppliers_group'];
                $suppliers_group = json_decode($shipments[$key]['suppliers_group']);
                $suppliers_name = [];

                foreach ($suppliers_group ?? [] as $supplier) {
                    array_push($suppliers_name, $this->getSupplierName($supplier));
                }

                $shipments[$key]["suppliers_name"] = $suppliers_name;
                $shipments[$key]['containers_group'] = $this->merge_group($findShipment['containers_group_bookings'], $findShipment['containers_group']);
                $shipments[$key]['containers_group_bookings'] = $shipments[$key]['containers_group'];
                
                // new statuses
                $shipments[$key]['status_v2'] = $shipment->getStatusV2();
                
                // check if tracking shipments
                $this->processTrackingShipments($findShipment, $shipments, $key, $this, $isSpecial);

                //tracking status
                $shipments[$key]['tracking_status'] = $findShipment->getTrackingStatus();

                if ($shipments[$key]['status_v2']==null || $shipments[$key]['status_v2']=='No Status') {

                    $schedules = json_decode($shipments[$key]['schedules_group']);
                    if (count($schedules)>0) {
                        $shipments[$key]['schedules_group'] = json_encode($schedules);
                        $shipments[$key]['snooze_date_at_readable'] = Carbon::parse($shipment->snooze_date_at)->format('F d, Y');
                    }
                }
                //e
            }
        }
        return response()->json($shipments);
    
    }
    /**
     * Search customer order
     *
     * @authenticated
     * @queryParam q string
     * @queryParam tab string Tab can be expired, pending, completed, shipments. Example: completed
     * @queryParam order string required desc or asc. Example: desc
     * @queryParam sort string required desc or asc. Example: eta
     * @queryParam per_page int required Example: 30
     *
     * @apiResourceCollection App\Http\Resources\Standard
     * @apiResourceModel App\User with=customersApi
     *
     * @response 404 {
     *    "status":"Abort"
     * }
     *
     * @response status=400 scenario="Unauthenticated" {
     *     "message": "Unauthenticated."
     * }
     *
     */

    public function search(Request $request) {

        $customers = Auth::user()->customersApi->pluck('id');

        if (!$request->has('q') && !$request->has('tab'))
            return abort(404);

        $order = ($request->has('order')) ? $request->input('order') : 'desc';
        $sort = ($request->has('sort')) ? $request->input('sort') : 'eta';
        $is_app_test = ($request->has('app_test')) ? $request->input('app_test') : 0;

        $tab = $request->input('tab');

        $parameters = [
            'q' => $request->input('q'),
            'order' => $order,
            'sort' => $sort,
            'per_page' => $request->has('per_page') ? $request->per_page : 30
        ];

        if ( $tab === 'expired') {
            try{
                return $this->expiredSearch($parameters);
            }catch (\Exception $exception){
                return response([
                    'message'=>'No results found.'
                ], 404);
            }
        } elseif ( $tab === 'pending') {
            try{
                return $this->pendingSearch($parameters);
            }catch (Exception $exception){
                return response([
                    'message'=>'No results found.'
                ], 404);
            }
        } elseif ( $tab === 'completed') {
            try{
                return $this->completedSearch($parameters);
            }catch (Exception $exception){
                return response([
                    'message'=>'No results found.'
                ], 404);
            }
        } elseif ($tab==='shipments') {
            try {
                return $this->transitSearch($parameters, 0);
            }catch (Exception $exception){
                return response([
                    'message'=>'No results found.'
                ], 404);
            }
        } elseif ($tab==='shipments-completed-merge') {
            try {
                return $this->transitSearch($parameters, 1);
            }catch (Exception $exception){
                return response([
                    'message'=>'No results found.'
                ], 404);
            }

        } elseif ($tab==='pendingQuote') {
            try{
                return $this->pendingQuoteSearch($parameters);
            }catch (Exception $exception){
                return response([
                    'message'=>'No results found.'
                ], 404);
            }
        } elseif ($tab==='snooze') {
            try{
                return $this->snoozeSearch($parameters);
            }catch (\Exception $exception){
                return response([
                    'message'=>'No results found.'
                ], 404);
            }
        } else {
            return abort(404);
           // return $this->transitSearch($parameters);
        }
    }

    private function isJson($string) {
        json_decode($string);
        return (json_last_error() == JSON_ERROR_NONE);
    }

  /**
     *
     * Display Details
     *
     * @authenticated
     *
     * @urlParam id int required Shipment ID
     *
     * @apiResource App\Http\Resources\Shipment
     * @apiResourceModel App\Shipment
     *
     *
     * @response status=400 scenario="Unauthenticated" {
     *     "message": "Unauthenticated."
     * }
     */
    public function details( $id ) {

        /*$shipment = Shipment::where('id',$id)
                            ->with('customer', 'containers', 'suppliers', 'carrier', 'terminalRegions', 'shipmentSuppliers', 'shipmentSchedules', 'terminal_fortynine')
                            ->first();*/

        $shipment = Shipment::where('id',$id)
                            ->with('customer', 'containers', 'suppliers', 'carrier', 'terminalRegions', 'shipmentSchedules', 'terminal_fortynine')
                            ->first();


        $ais_link = $this->getAis($shipment) ?? '';
        $status_v2 = $shipment->getStatusV2();
        $shipment->status_v2 = $status_v2;
        $shipment->ais_link = $ais_link;

        if ( isset($shipment->id) ) {
            $schedules = [];
            $suppliers = [];
            $containers = [];

            $shipment->carrier = '';
            if ( isset($shipment->schedules_group_bookings) && $shipment->schedules_group_bookings!=='') {
                if ( $this->isJson($shipment->schedules_group_bookings)) {
                    $schedules = json_decode($shipment->schedules_group_bookings);

                    if ( count ($schedules) > 0) {
                        foreach( $schedules as $key => $schedule ) {
                            $shipment->location_to_name = $this->getTerminal($schedule->location_to);
                            $shipment->location_from_name = $this->getTerminal($schedule->location_from);

                            if (isset($schedule->carrier_name) && isset($schedule->carrier_name->id)) {
                                $c = Carrier::find(intval($schedule->carrier_name->id));
                                $shipment->carrier = (isset($c->name)) ? $c->name : '';
                            }
                        }
                    }
                }
            }

            if ( isset($shipment->container_group_bookings) && $shipment->container_group_bookings!=='') {
                if ( $this->isJson($shipment->container_group_bookings)) {
                    $containers = json_decode($shipment->container_group_bookings);
                }
            }

            if ( isset($shipment->suppliers_group_bookings) && $shipment->suppliers_group_bookings!=='') {
                if ( $this->isJson($shipment->suppliers_group_bookings)) {
                    $suppliers = json_decode($shipment->suppliers_group_bookings);
                }
            }

            $suppliers_name = [];
            $shipment->obl_filled = false;
            $shipment->commercial_documents_filled = false;

            $obl_counter = 0;
            
            /* customer supplier */
            $check_supplier_documents = Document::where('shipment_id', $id)->get();

            if (count($suppliers) > 0) {
                foreach ($suppliers as $key => $supplier) {

                    $commercial_documents_counter = 0;
                    $commercial_invoice_counter = 0;
                    $packing_list_counter = 0;
                    $ilist_counter = 0;

                    $suppliers[$key]->obl_filled = true;
                    $suppliers[$key]->commercial_documents_filled = true;
                    array_push($suppliers_name, $this->getSupplierName($supplier));
                    $suppliers[$key]->name = $this->getSupplierName($supplier);
                    
                    if (isset($supplier->bl_status) && $supplier->bl_status === 'Telex Released') {
                        $obl_counter++;
                    } else {
                        $suppliers[$key]->obl_filled = false;
                    }
                    
                    $get_supplier = Supplier::find($supplier->supplier_id);
                    if ( isset ($get_supplier->id)) {
                        $get_supplier_documents = $get_supplier->documents;

                        if ( count ($get_supplier_documents) > 0) {
                            foreach ( $get_supplier_documents as $get_supplier_document ) {

                                if ( $get_supplier_document->is_added_by_customer == 1) {
                                    if ( $get_supplier_document->type === 'Other Commercial Docs') {
                                        //$commercial_documents_counter++;
                                    } elseif ( $get_supplier_document->type === 'Commercial Invoice') {
                                        $commercial_invoice_counter++;
                                    } elseif ( $get_supplier_document->type === 'Invoice And Packing List') {
                                        $ilist_counter++;
                                    } elseif ($get_supplier_document->type === 'Packing List') {
                                        $packing_list_counter++;
                                    }    
                                }
                                
                            }

                            if ( $commercial_documents_counter == 0 ) {
                                $suppliers[$key]->commercial_documents_filled = false;
                            }

                            //case 1
                            //check for packing list and commercial invoice
                            if ( $commercial_invoice_counter>0 && $packing_list_counter > 0 ) {
                                $suppliers[$key]->commercial_documents_filled = true;
                            }

                            //case 2
                            //check for invoice and packing list
                            if ( $ilist_counter > 0 ) {
                                $suppliers[$key]->commercial_documents_filled = true;
                            }

                        } else {
                            $suppliers[$key]->commercial_documents_filled = false;
                        }

                        $suppliers[$key]->document_custom = $get_supplier->documents;
                    }

                    /*
                    if (isset($supplier->commercial_documents) && $supplier->commercial_documents !== null && $supplier->commercial_documents!=='') {
                        $commercial_documents_counter++;
                    } else {
                        $suppliers[$key]->commercial_documents_filled = false;
                    }*/
                }

                if ( $obl_counter >= count($suppliers) && count($suppliers) > 0) {
                    $shipment->obl_filled = true;
                }
                if ( count ($suppliers) > 0) {
                    $final_counter = 0;
                    foreach ($suppliers as $key => $supplier) {
                        if ( $supplier->commercial_documents_filled )
                            $final_counter++;
                    }

                    if ( $final_counter > 0 && $final_counter === count($suppliers)) {
                        $suppliers[$key]->commercial_documents_filled = true;
                    }
                }


                /*
                if ( $commercial_documents_counter >= count($suppliers) && count($suppliers) > 0) {
                    $shipment->commercial_documents_filled = true;
                }

                if (($packing_list_counter > 0 && $commercial_invoice_counter > 0 || $ilist_counter==count($suppliers)) && count($suppliers) > 0) {
                    $shipment->commercial_documents_filled = true;
                    $shipment->obl_filled = true;

                    foreach ($suppliers as $key => $supplier) {
                        $suppliers[$key]->obl_filled = true;
                        $suppliers[$key]->commercial_documents_filled = true;
                    }
                }*/
            }

            $shipment->suppliers_name = $suppliers_name;

            if($shipment->is_tracking_shipment){
                if(!empty($shipment->mbl_num)){
                    $shipment->booking_confirmed = 1;
                    $terminal49_shipment = $shipment->terminal_fortynine;

                    if( isset($terminal49_shipment['id']) ){
                        $attributes = json_decode($terminal49_shipment['attributes'],true);
                        $shipment->location_from_name = $attributes['port_of_lading_name'];
                        $shipment->location_to_name = $attributes['port_of_discharge_name'];

                        $shipment->eta = $this->ifNull($attributes['pod_eta_at'],$attributes['pod_ata_at']);
                        $shipment->etd = $this->ifNull($attributes['pol_etd_at'],$attributes['pol_atd_at']);
                    }
                }
            }
            
            //auto filled to dos if the shipment is older than july 31, 2022
            if ( Carbon::parse($shipment->created_at)->lt(Carbon::parse('2022-07-31'))) {
                $this->autoFilledToDos($suppliers, $shipment, 1);
            }
            
            if ( $shipment->booking_confirmed === 1 ) {

                $diff_days = Carbon::parse($shipment->eta)->diffInDays(now(), false);
                $shipment->shipment_status = 'Shipments';

                if ( $shipment->status_v2 === null || $shipment->status_v2 === 'No Status') {

                    if (Carbon::parse($shipment->etd)->gt(now())) {
                        $shipment->shipment_status = 'Awaiting Departure';
                    }

                    if (Carbon::parse($shipment->etd)->lt(now())) {
                        $shipment->shipment_status = 'In Transit';
                    }

                    if ($diff_days > 60) {
                        $shipment->shipment_status = 'Completed';
                    }

                } else {

                    if ($shipment->status_v2==='Full Out' || $shipment->status_v2==='Empty In') {
                        $shipment->shipment_status = 'Completed';
                        //$shipment->shipment_status = $status_v2;
                    } else {

                        $shipment->original_shipment_status = $shipment->status_v2;
                        $shipment->shipment_status = $shipment->status_v2;

                        if ( $shipment->status_v2 === 'In transit - hold' ) {
                            $shipment->shipment_status = 'In Transit - Pending Release';
                        } elseif ( $shipment->status_v2 === 'Past last free day' ) {
                            $shipment->shipment_status = 'Past last free day';
                        } elseif ( $shipment->status_v2 === 'In transit - released' ) {
                            $shipment->shipment_status = 'In Transit - Released';
                        } elseif($shipment->status_v2 === 'Discharged - hold') {
                            $shipment->shipment_status = 'Discharged - Hold';
                        }

                        if ( $diff_days > 60 ) {
                            $shipment->shipment_status = 'Completed';
                        }
                    }
                }

            } else {

                //auto filled to dos
                $this->autoFilledToDos($suppliers, $shipment, 0);

                $shipment->shipment_status = 'Pending Approval';

                $is_pending_quote_counter = 0;
                if (count($schedules) > 0) {
                    foreach($schedules as $key => $schedule) {
                        $hasUnset = false;

                        $eta = Carbon::parse($schedule->eta)->diffInDays(now(), false);
                        if ( $eta >60 ) {
                            $hasUnset = true;
                            unset($schedules[$key]);
                        }
                        if ( !$hasUnset ) {
                            if (isset($schedule->sell_rates) && $schedule->sell_rates!=='' && $schedule->sell_rates!==null) {
                                if (count($schedule->sell_rates)==0) {
                                    $is_pending_quote_counter++;
                                    //unset($schedules[$key]);
                                    //$hasUnset = true;
                                }
                            } else {
                                $is_pending_quote_counter++;
                            }
                        }


                        
                        /*
                        if ( !$hasUnset ) {
                            $hasUnset = true;
                            $etd = Carbon::parse($schedule->etd)->diffInDays(now(), false);
                            if ($etd>=-4) {
                                unset($schedules[$key]);
                            }
                        }*/
                        
                    }
                }

                if (count($schedules)==0) {
                    $shipment->shipment_status = 'Expired';
                } else {
                    if ($shipment->snooze_date_at === null || $shipment->snooze_date_at <= now()) {
                        if ($is_pending_quote_counter > 0) {
                            $shipment->shipment_status = 'Pending Quote';
                        }
                    } else {
                        $shipment->shipment_status = 'Snoozed';
                    }
                }
            }

            //$shipment->schedules_group = json_encode($schedules);
            //$shipment->containers_group = json_encode($containers);
            //$shipment->suppliers_group = json_encode($suppliers);
            $shipment->schedules_group_bookings = json_encode($schedules);
            $shipment->containers_group_bookings = json_encode($containers);
            $shipment->suppliers_group_bookings = json_encode($suppliers);

            //tracking status
            $shipment->tracking_status = $shipment->getTrackingStatus();
            $shipment->shipment_suppliers = $suppliers;
        } else {
            return abort(404);
        }

        return new StandardResource($shipment);

    }

    /**
     * Expired
     *
     * @authenticated
     *
     * @queryParam per_page int Size per page. Default to 30. Example: 30
     *
     * @apiResourceCollection App\Http\Resources\Shipment
     * @apiResourceModel App\Shipment with=customer
     *
     * @response status=400 scenario="Unauthenticated" {
     *     "message": "Unauthenticated."
     * }
     *
     */

    public function indexExpired(Request $request)
    {
        //$customers = Auth::loginUsingId(11);

        $customers = Auth::user()->customersApi->pluck('id');

        $get_authenticated_user = Auth::user();
        try{
            $customers = ( $get_authenticated_user->default_customer_id!==null ) ? [$get_authenticated_user->default_customer_id] : [$customers[0]];
        }catch (\Exception $exception){
            return response([
                'message'=>'No results found.'
            ], 404);
        }

        $shipments = [];

        $newShipments = [];
        if (count($customers) > 0) {

            $shipments = Shipment::whereHas('customer', function($q) use ($customers){
                $q->whereIn('id', $customers);
            })
            ->where(function ($qq) {
                $qq->where('snooze_date_at', NULL);
                $qq->orWhere('snooze_date_at','<=', now());
            })
            ->where('booking_confirmed', 0)
            ->where('cancelled', 0)
            ->get();


            if (count($shipments) > 0) {

                foreach ($shipments as $key => $shipment) {

                    $should_push = true;

                    //schedule merge
                    //merge group fields start
                    $findShipment = $shipment;

                    $getCarrier = Carrier::find(intval($findShipment['carrier_id']));
                    $shipments[$key]['carrier'] = (isset($getCarrier->id)) ? $getCarrier : null;
                    $shipments[$key]['schedules_group'] = $this->merge_group($findShipment['schedules_group_bookings'],$findShipment['schedules_group']);
                    $shipments[$key]['schedules_group_bookings'] = $shipments[$key]['schedules_group'];
                    $shipments[$key]['schedule_has_pricing'] = true;

                    if (isset($shipments[$key]['schedules_group']) && $shipments[$key]['schedules_group']!=='') {
                        $getShipmentSchedules = json_decode($shipments[$key]['schedules_group']);
                        $this->processShipmentSchedules($getShipmentSchedules, $shipments, $key, $this);
                    }

                    $shipments[$key]['location_from_name'] = $this->cleanField($shipments[$key]['location_from_name']);
                    $shipments[$key]['location_to_name'] = $this->cleanField($shipments[$key]['location_to_name']);
                    $shipments[$key]['suppliers_group'] = $this->merge_group($findShipment['suppliers_group_bookings'], $findShipment['suppliers_group']);
                    $shipments[$key]['suppliers_group_bookings'] = $shipments[$key]['suppliers_group'];
                    $suppliers_group = json_decode($shipments[$key]['suppliers_group']);

                    $suppliers_name = [];
                    foreach ($suppliers_group ?? [] as $supplier) {
                        array_push($suppliers_name, $this->getSupplierName($supplier));
                    }
                    $shipments[$key]["suppliers_name"] = $suppliers_name;
                    $shipments[$key]['containers_group'] = $this->merge_group($findShipment['containers_group_bookings'], $findShipment['containers_group']);
                    $shipments[$key]['containers_group_bookings'] = $shipments[$key]['containers_group'];

                    // new statuses
                    $shipments[$key]['status_v2'] = $shipment->getStatusV2() ?? null;
                    //
                    $isSpecial = false;
                    // check if tracking shipments
                    $this->processTrackingShipments($findShipment, $shipments, $key, $this, $isSpecial);
                    if ($shipments[$key]['booking_confirmed']==1) {
                        $should_push = false;
                    }
                    //
                    if ($should_push) {

                        // current day minus eta
                        //$diff_days = Carbon::parse($shipments[$key]['eta'])->diffInDays(now(), false);
                        //$shipments[$key]['diff_days'] = $diff_days;
                        $shipments[$key]['diff_days'] = 100000;
                        $proceed = false;

                        if ($shipments[$key]['status_v2']==null || $shipments[$key]['status_v2']=='No Status') {
                            /*
                            if ($diff_days<20) {
                                $proceed = true;
                            }*/
                            $proceed = true;
                        } else {
                            if (strpos($shipments[$key]['status_v2'], 'In transit')) {
                                $proceed = false;
                            }
                        }

                        if ( $proceed ){
                            if (isset($shipments[$key]['schedules_group_bookings']) && $shipments[$key]['schedules_group_bookings']!=='' && $shipments[$key]['schedules_group_bookings']!==null) {

                                $schedules = json_decode($shipments[$key]['schedules_group_bookings']);
                                if (count($schedules)>0) {

                                    foreach($schedules as $keySecond => $schedule) {

                                        $hasUnset = false;

                                        $eta = Carbon::parse($schedule->eta)->diffInDays(now(), false);
                                        if ( $eta >60 ) {
                                            $hasUnset = true;
                                            unset($schedules[$keySecond]);
                                        }
                                        /*
                                        if ( !$hasUnset ) {
                                            if (isset($schedule->sell_rates) && $schedule->sell_rates!=='' && $schedule->sell_rates!==null) {
                                                if (count($schedule->sell_rates)==0) {
                                                    unset($schedules[$keySecond]);
                                                    $hasUnset = true;
                                                }
                                            } else {
                                                unset($schedules[$keySecond]);
                                                $hasUnset = true;
                                            }    
                                        }*/



                                        /*
                                        if ( !$hasUnset ) {
                                            //$hasUnset = true;
                                            $etd = Carbon::parse($schedule->etd)->diffInDays(now(), false);
                                            if ($etd>=-4) {
                                                unset($schedules[$keySecond]);
                                            }
                                        }*/


                                    }

                                    if (count($schedules)==0) {
                                        $shipments[$key]['schedules_group'] = json_encode([]);
                                        $shipments[$key]['schedules_group_bookings'] = json_encode([]);
                                        $shipments[$key]['shipment_status'] = 'Expired';
                                        array_push($newShipments, $shipments[$key]);
                                        /*
                                        if ($diff_days<20){
                                            array_push($newShipments, $shipments[$key]);
                                        }*/
                                    }

                                } else {
                                    $shipments[$key]['schedules_group'] = json_encode([]);
                                    $shipments[$key]['schedules_group_bookings'] = json_encode([]);
                                    $shipments[$key]['shipment_status'] = 'Expired';
                                    array_push($newShipments, $shipments[$key]);
                                }
                            } else {
                                $shipments[$key]['schedules_group'] = json_encode([]);
                                $shipments[$key]['schedules_group_bookings'] = json_encode([]);
                                $shipments[$key]['shipment_status'] = 'Expired';
                                array_push($newShipments, $shipments[$key]);
                            }

                        }

                    }
                    //e

                    }
                }
            }


        if (is_null($request->per_page)) {
            //return StandardResource::collection((new Collection($newShipments)));
            return StandardResource::collection((new Collection($newShipments))->paginate(30));
        }
        if (is_numeric($request->per_page)) {
            // return StandardResource::collection((new Collection($newShipments))->paginate($request->per_page));
            return StandardResource::collection((new Collection($newShipments))->paginate($request->per_page));
        }
        return abort(404);
    }

    /**
     * Completed
     *
     * @authenticated
     *
     * @queryParam per_page int Size per page. Default to 30. Example: 30 
     *
     * @apiResourceCollection App\Http\Resources\Shipment
     * @apiResourceModel App\Shipment with=customer
     *
     * @response status=400 scenario="Unauthenticated" {
     *     "message": "Unauthenticated."
     * }
     *
    */

    public function indexCompleted(Request $request)
    {
        $customers = Auth::user()->customersApi->pluck('id');
        $order_by = $request->has('sort') ? $request->input('sort') : 'eta';
        $order = ($request->has('order')) ? $request->input('order') : 'desc';
        $get_authenticated_user = Auth::user();

        try{
            $customers = ( $get_authenticated_user->default_customer_id!==null ) ? [$get_authenticated_user->default_customer_id] : [$customers[0]];
        }catch (\Exception $exception){
            return response([
                'message'=>'No results found.'
            ], 404);
        }

        $shipments = [];
        $isSpecial = false;

        $newShipments = [];

        if (count($customers) > 0) {

            $shipments = Shipment::whereHas('customer', function($q) use ($customers){
                $q->whereIn('id', $customers);
            })
            ->where('cancelled', 0)
            ->get();
            /*
            if ($request->has('sort') && $request->input('sort')=='eta') {
                $order = ($request->has('order')) ? $request->input('order') : 'desc';
                $valid_orders = ['asc','desc'];

                if (in_array($order, $valid_orders)) {
                    $shipments = $shipments->orderBy('eta', $order)->get();
                } else {
                    $shipments = $shipments->orderBy('eta', 'desc')->get();
                }
            } else {
                $shipments = $shipments->orderBy('eta', 'desc')->get();
            }*/

            if (count($shipments) > 0) {

                foreach ($shipments as $key => $shipment) {

                    $should_push = true;

                    //schedule merge
                    //merge group fields start
                    $findShipment = $shipment;

                    $getCarrier = Carrier::find(intval($findShipment['carrier_id']));
                    $shipments[$key]['carrier'] = (isset($getCarrier->id)) ? $getCarrier : null;
                    $shipments[$key]['schedules_group'] = $this->merge_group($findShipment['schedules_group_bookings'], $findShipment['schedules_group']);
                    $shipments[$key]['schedules_group_bookings'] = $shipments[$key]['schedules_group'];
                    $shipments[$key]['schedule_has_pricing'] = true;

                    if (isset($shipments[$key]['schedules_group']) && $shipments[$key]['schedules_group']!=='') {
                        $getShipmentSchedules = json_decode($shipments[$key]['schedules_group']);
                        $this->processShipmentSchedules($getShipmentSchedules, $shipments, $key, $this);
                    }

                    $shipments[$key]['location_from_name'] = $this->cleanField($shipments[$key]['location_from_name']);
                    $shipments[$key]['location_to_name'] = $this->cleanField($shipments[$key]['location_to_name']);
                    $shipments[$key]['suppliers_group'] = $this->merge_group($findShipment['suppliers_group_bookings'], $findShipment['suppliers_group']);
                    $shipments[$key]['suppliers_group_bookings'] = $shipments[$key]['suppliers_group'];

                    $suppliers_group = json_decode($shipments[$key]['suppliers_group']);
                    $suppliers_name = [];
                    foreach ($suppliers_group ?? [] as $supplier) {
                        // code...
                        array_push($suppliers_name, $this->getSupplierName($supplier));
                    }
                    $shipments[$key]["suppliers_name"] = $suppliers_name;
                    $shipments[$key]['containers_group'] = $this->merge_group($findShipment['containers_group_bookings'], $findShipment['containers_group']);
                    $shipments[$key]['containers_group_bookings'] = $shipments[$key]['containers_group'];
                    // new statuses
                    $shipments[$key]['status_v2'] = $shipment->getStatusV2();
                    // check if tracking shipments
                    $isSpecial = false;
                    $this->processTrackingShipments($findShipment, $shipments, $key, $this, $isSpecial);

                    if ( $shipments[$key]['booking_confirmed']==0 ) {
                        $should_push = false;
                    }
                    //
                    if ($should_push) {

                        // current day minus eta
                        $diff_days = Carbon::parse($shipments[$key]['eta'])->diffInDays(now(), false);
                        $shipments[$key]['diff_days'] = $diff_days;
                        $proceed = false;

                        if ($shipments[$key]['status_v2']==null || $shipments[$key]['status_v2']=='No Status') {

                            //$proceed = true;

                            if ($shipments[$key]['eta']!==null && $shipments[$key]['eta']!=='') {
                                if ($diff_days > 60) {
                                    $proceed = true;
                                }

                            }

                        } else {
                            if ($shipments[$key]['status_v2']==='Full Out' || $shipments[$key]['status_v2']==='Empty In') {

                                if (isset($shipments[$key]['selected_schedule_type']) && $shipments[$key]['selected_schedule_type']=='LCL') {
                                    if ( $diff_days>60 ) {
                                        $proceed = true;
                                    }
                                } else {
                                    $proceed = true;
                                }
                            }
                        }

                        if ( $proceed ){
                            if (isset($shipments[$key]['schedules_group']) && $shipments[$key]['schedules_group']!=='' && $shipments[$key]['schedules_group']!==null && !$isSpecial) {

                                $schedules = json_decode($shipments[$key]['schedules_group']);
                                if (count($schedules)>0) {

                                    foreach($schedules as $keySecond => $schedule) {
                                        $hasUnset = false;
                                        /*
                                        if (isset($schedule->sell_rates) && $schedule->sell_rates!=='' && $schedule->sell_rates!==null) {
                                            if (count($schedule->sell_rates)==0) {
                                                unset($schedules[$keySecond]);
                                                $hasUnset = true;
                                            }
                                        } else {
                                            unset($schedules[$keySecond]);
                                            $hasUnset = true;
                                        }*/
                                    }

                                    if (count($schedules)>0) {
                                        $shipments[$key]['schedules_group'] = json_encode($schedules);
                                        $shipments[$key]['shipment_status'] = 'Completed';
                                        array_push($newShipments, $shipments[$key]);
                                        /*
                                        if ($diff_days<20){
                                            array_push($newShipments, $shipments[$key]);
                                        }*/
                                    }

                                }
                            } else {
                                if ( $isSpecial ) {
                                    $shipments[$key]['shipment_status'] = 'Completed';
                                    array_push($newShipments, $shipments[$key]);
                                }
                            }
                        }

                    }
                    //e

                    }
                }
            }

        if ( count ($newShipments) > 0 ) {
            usort($newShipments, function($a, $b) use ($order, $order_by) {
                if ( $order === 'desc' ) {
                    if ( intval(strtotime($a[$order_by])) > intval(strtotime($b[$order_by])) ) {
                        return -1;
                    } elseif( intval(strtotime($a[$order_by])) < intval(strtotime($b[$order_by])) ) {
                        return 1;
                    } elseif ( intval(strtotime($a[$order_by])) === intval(strtotime($b[$order_by])) ) {
                        return 0;
                    }
                } else {
                    if ( intval(strtotime($a[$order_by])) > intval(strtotime($b[$order_by])) ) {
                        return 1;
                    } elseif( intval(strtotime($a[$order_by])) < intval(strtotime($b[$order_by])) ) {
                        return -1;
                    } elseif ( intval(strtotime($a[$order_by])) === intval(strtotime($b[$order_by])) ) {
                        return 0;
                    }
                }
            });

          
        }

        if (is_null($request->per_page)) {
            //return StandardResource::collection((new Collection($newShipments)));
            return StandardResource::collection((new Collection($newShipments))->paginate(30));
        }
        if (is_numeric($request->per_page)) {
            // return StandardResource::collection((new Collection($newShipments))->paginate($request->per_page));
            return StandardResource::collection((new Collection($newShipments))->paginate($request->per_page));
        }
        return abort(404);
    }

    /**
     * Transit/Completed
     *
     *
     * @queryParam per_page int
     * @queryParam sort string
     * @queryParam order string
     *
     *
     * @authenticated
     *
     * @apiResourceCollection App\Http\Resources\Shipment
     * @apiResourceModel App\Shipment paginate=30 with=customer
     *
     * @response status=400 scenario="Unauthenticated" {
     *     "message": "Unauthenticated."
     * }
     */
    public function indexTransitCompleted(Request $request)
    {
        
        $customers = Auth::user()->customersApi->pluck('id');
        $order_by = $request->has('sort') ? $request->input('sort') : 'eta';
        $order = ($request->has('order')) ? $request->input('order') : 'desc';
        $shipments = [];
        $isSpecial = false;

        $get_authenticated_user = Auth::user();
        try{
          $customers = ( $get_authenticated_user->default_customer_id!==null ) ? [$get_authenticated_user->default_customer_id] : [$customers[0]];
        }catch(\Exception $exception){
            return response([
                'message'=>'No results found.'
            ], 404);
        }
        $newShipments = [];
        if (count($customers) > 0) {

            $shipments = Shipment::whereHas('customer', function($q) use ($customers){
                $q->whereIn('id', $customers);
            })
            //->where('booking_confirmed', 1)
            ->where('cancelled', 0);
            /*
            if ($request->has('sort')) {

                $order = ($request->has('order')) ? $request->input('order') : 'desc';
                $order_by = $request->input('sort');

                $valid_orders = ['asc', 'desc'];
                $valid_sorts = ['eta', 'etd'];

                if (in_array($order, $valid_orders) && in_array($order_by, $valid_sorts)) {
                   // $shipments = $shipments->orderBy($order_by, $order)->get();
                } else {
                    //$shipments = $shipments->orderBy($order_by, 'desc')->get();
                }
            } else {
                //$shipments = $shipments->orderBy('eta', 'desc')->get();
            }*/

            $shipments =$shipments->get();
            $sort_shipments = [];
            if (count($shipments) > 0) {

                foreach ($shipments as $key => $shipment) {

                    $should_push = true;
                    //schedule merge
                    //merge group fields start
                    $findShipment = $shipment;

                    $getCarrier = Carrier::find(intval($findShipment['carrier_id']));
                    $shipments[$key]['carrier'] = (isset($getCarrier->id)) ? $getCarrier : null;
                    $shipments[$key]['schedules_group'] = $this->merge_group($findShipment['schedules_group_bookings'], $findShipment['schedules_group']);
                    $shipments[$key]['schedules_group_bookings'] = $shipments[$key]['schedules_group'];
                    $shipments[$key]['schedule_has_pricing'] = true;

                    if (isset($shipments[$key]['schedules_group']) && $shipments[$key]['schedules_group']!=='') {
                        $getShipmentSchedules = json_decode($shipments[$key]['schedules_group']);
                        $this->processShipmentSchedules($getShipmentSchedules, $shipments, $key, $this);
                    }

                    $shipments[$key]['location_from_name'] = $this->cleanField($shipments[$key]['location_from_name']);

                    $shipments[$key]['location_to_name'] = $this->cleanField($shipments[$key]['location_to_name']);

                    $shipments[$key]['suppliers_group'] = $this->merge_group($findShipment['suppliers_group_bookings'], $findShipment['suppliers_group']);

                    $shipments[$key]['suppliers_group_bookings'] = $shipments[$key]['suppliers_group'];

                    $suppliers_group = json_decode($shipments[$key]['suppliers_group']);
                    $suppliers_name = [];
                    foreach ($suppliers_group ?? [] as $supplier) {
                        array_push($suppliers_name, $this->getSupplierName($supplier));
                    }
                    $shipments[$key]["suppliers_name"] = $suppliers_name;

                    $shipments[$key]['containers_group'] = $this->merge_group($findShipment['containers_group_bookings'], $findShipment['containers_group']);

                    $shipments[$key]['containers_group_bookings'] = $shipments[$key]['containers_group'];
                    
                    // new statuses
                    $shipments[$key]['status_v2'] = $shipment->getStatusV2();

                    // check if tracking shipments
                    $this->processTrackingShipments($findShipment, $shipments, $key, $this, $isSpecial);
                    //

                    $completed = false;

                    if ($shipments[$key]['booking_confirmed']==0) {
                        $should_push = false;
                    }

                    if ($should_push) {

                        // current day minus eta
                        $diff_days = Carbon::parse($shipments[$key]['eta'])->diffInDays(now(), false);
                        $shipments[$key]['diff_days'] = $diff_days;
                        $proceed = false;
                        $shipments[$key]['shipment_status'] = 'Shipments';

                        if ($shipments[$key]['status_v2']==null || $shipments[$key]['status_v2']=='No Status') {

                            if ($shipments[$key]['etd']!==null && $shipments[$key]['etd']!=='') {
                                $proceed = true;
                                if (Carbon::parse($shipments[$key]->etd)->gt(now())) {
                                    $shipments[$key]['shipment_status'] = 'Awaiting Departure';
                                }
                                if (Carbon::parse($shipments[$key]->etd)->lt(now())) {
                                    $shipments[$key]['shipment_status'] = 'In Transit';
                                }
                            }

                            if ($shipments[$key]['eta']!==null && $shipments[$key]['eta']!=='') {
                                /*
                                if ($diff_days<60) {
                                    $proceed = true;
                                } else {
                                    $proceed = false;
                                }*/
                                $proceed = true;
                            } else {
                                //$proceed = true;
                                $proceed = false;
                            }

                            
                            //for completed
                            if ($shipments[$key]['eta']!==null && $shipments[$key]['eta']!=='') {

                                if ($diff_days > 60) {
                                    //$proceed = false;
                                    $proceed = true;
                                    $completed = true;
                                    $shipments[$key]['shipment_status'] = 'Completed';
                                }

                            }

                        } else {

                            if ($shipments[$key]['status_v2']==='Full Out' || $shipments[$key]['status_v2']==='Empty In') {

                                $completed = true;
                                $shipments[$key]['shipment_status'] = 'Completed';
                                $proceed = true;
                                //for completed
                                /*
                                if (isset($shipments[$key]['selected_schedule_type']) && $shipments[$key]['selected_schedule_type']=='LCL') {
                                    if ( $diff_days>60 ) {
                                        //$proceed = false;
                                        $completed = true;
                                        $shipments[$key]['shipment_status'] = 'Completed';
                                        $proceed = true;
                                    } else {
                                        $proceed = true;
                                        //$proceed = false;
                                    }
                                } else {
                                    $completed = true;
                                    $shipments[$key]['shipment_status'] = 'Completed';
                                    $proceed = true;
                                }*/
                                //$proceed = false;

                            } else {

                                $shipments[$key]['original_shipment_status'] = $shipments[$key]['status_v2'];
                                $shipments[$key]['shipment_status'] = $shipments[$key]['status_v2'];

                                if ( $shipments[$key]['status_v2'] === 'In transit - hold' ) {
                                    $shipments[$key]['shipment_status'] = 'In Transit - Pending Release';
                                } elseif ( $shipments[$key]['status_v2'] === 'Past last free day' ) {
                                    $shipments[$key]['shipment_status'] = 'Past last free day';
                                } elseif ( $shipments[$key]['status_v2'] === 'In transit - released' ) {
                                    $shipments[$key]['shipment_status'] = 'In Transit - Released';
                                } elseif($shipments[$key]['status_v2'] === 'Discharged - hold') {
                                    $shipments[$key]['shipment_status'] = 'Discharged - Hold';
                                }

                                
                                if ($diff_days > 60) {
                                    $proceed = true;
                                    $completed = true;
                                    $shipments[$key]['shipment_status'] = 'Completed';
                                } else {

                                    if ( $shipments[$key]['eta']===null || $shipments[$key]['etd']==null ) {
                                        $proceed = true;
                                        //$proceed = false;    
                                    } else {
                                        $proceed = true;
                                    }
                                        
                                }
                                
                            }


                        }

                        if ( $proceed ) {
                            if (isset($shipments[$key]['schedules_group']) && $shipments[$key]['schedules_group']!=='' && $shipments[$key]['schedules_group']!==null && !$isSpecial) {

                                $schedules = json_decode($shipments[$key]['schedules_group']);
                                if (count($schedules)>0) {

                                    foreach($schedules as $keySecond => $schedule) {
                                        $hasUnset = false;

                                        /*
                                        if (isset($schedule->sell_rates) && $schedule->sell_rates!=='' && $schedule->sell_rates!==null) {
                                            if (count($schedule->sell_rates)==0) {
                                                unset($schedules[$keySecond]);
                                                $hasUnset = true;
                                            }
                                        } else {
                                            unset($schedules[$keySecond]);
                                            $hasUnset = true;
                                        }*/

                                        if ( !$hasUnset && !$completed ) {
                                            $hasUnset = true;
                                            $eta = Carbon::parse($schedule->eta)->diffInDays(now(), false);
                                            /*
                                            if ($eta>60) {
                                                unset($schedules[$keySecond]);
                                            }*/
                                        }

                                    }
                                    if (count($schedules)>0) {
                                        $shipments[$key]['schedules_group'] = json_encode($schedules);
                                        array_push($newShipments, $shipments[$key]);
                                    }
                                }


                            } else {
                                if ($isSpecial) {
                                    array_push($newShipments, $shipments[$key]);
                                }
                            }
                        }

                    }

                    //e
                    }


                }
            }
            
        if ( count ($newShipments) > 0 ) {

            usort($newShipments, function($a, $b) use ($order, $order_by) {
                if ( $order === 'desc' ) {
                    if ( intval(strtotime($a[$order_by])) > intval(strtotime($b[$order_by])) ) {
                        return -1;
                    } elseif( intval(strtotime($a[$order_by])) < intval(strtotime($b[$order_by])) ) {
                        return 1;
                    } elseif ( intval(strtotime($a[$order_by])) === intval(strtotime($b[$order_by])) ) {
                        return 0;
                    }
                } else {
                    if ( intval(strtotime($a[$order_by])) > intval(strtotime($b[$order_by])) ) {
                        return 1;
                    } elseif( intval(strtotime($a[$order_by])) < intval(strtotime($b[$order_by])) ) {
                        return -1;
                    } elseif ( intval(strtotime($a[$order_by])) === intval(strtotime($b[$order_by])) ) {
                        return 0;
                    }
                }
            });

          
        }

        if (is_null($request->per_page)) {
            return StandardResource::collection((new Collection($newShipments))->paginate(30));
            //return StandardResource::collection((new Collection($newShipments)))->paginate(30)
        }
        if (is_numeric($request->per_page)) {
            // return StandardResource::collection((new Collection($newShipments))->paginate($request->per_page));
            return StandardResource::collection((new Collection($newShipments))->paginate($request->per_page));
        }



        return abort(404);
    }

    /**
     * Transit
     * 
     * @authenticated
     *
     * @apiResourceCollection App\Http\Resources\Shipment
     * @apiResourceModel App\Shipment paginate=30 with=customer
     *
     * @response status=400 scenario="Unauthenticated" {
     *     "message": "Unauthenticated."
     * }
     */
    public function indexTransit(Request $request) {
        
        $customers = Auth::user()->customersApi->pluck('id');
        $order_by = $request->has('sort') ? $request->input('sort') : 'eta';
        $order = ($request->has('order')) ? $request->input('order') : 'desc';
        $shipments = [];
        $newShipments = [];
        $isSpecial = false;
        $per_page = $request->has('per_page') ? $request->input('per_page') : 30;

        $get_authenticated_user = Auth::user();
        $customers = ( $get_authenticated_user->default_customer_id!==null ) ? [$get_authenticated_user->default_customer_id] : [$customers[0]];

        $shipments = Shipment::with(['shipmentSchedules','carrier'])
                            ->whereIn('customer_id', $customers)
                            ->where('booking_confirmed', 1)
                            ->where('cancelled', 0)
                            ->where(function($q) {
                                $q->where(function($qq) {
                                    /*
                                    $qq->whereHas('shipmentSchedules', function($qqq) {
                                        $qqq->whereDate('eta_date', '>', Carbon::now()->subDays(60));
                                        $qqq->whereNotNull('etd_date');
                                        $qqq->whereNotNull('eta_date');
                                    })*/
                                    //$qq->whereHas('shipmentSchedules');
                                    $qq->whereNotNull('eta')
                                    ->whereNotNull('etd')
                                    ->whereDate('eta', '>', Carbon::now()->subDays(60));
                                    $qq->where(function($qqqq) {
                                        $qqqq->where('status_fallback','No Status');
                                        $qqqq->orWhere('status_fallback', 'In transit');
                                    });
                                });
                                $q->orWhere(function($qq) {
                                    $qq->whereNotNull('status_fallback');
                                    $qq->where('status_fallback', '<>','In transit');
                                    $qq->where('status_fallback','<>','No Status');
                                    $qq->where('status_fallback', '<>','Full Out');
                                    $qq->where('status_fallback', '<>','Empty In');
                                });
                            })
                            ->paginate(30);
        
        if ( count($shipments) > 0 ) {
            foreach( $shipments as $key => $shipment ) {
                $findShipment = $shipment;
                //s
                //$getCarrier = Carrier::find(intval($findShipment['carrier_id']));
                //$shipments[$key]['carrier'] = (isset($getCarrier->id)) ? $getCarrier : null;
                $shipments[$key]['schedules_group'] = $this->merge_group($findShipment['schedules_group_bookings'], $findShipment['schedules_group']);
                $shipments[$key]['schedules_group_bookings'] = $shipments[$key]['schedules_group'];
                $shipments[$key]['schedule_has_pricing'] = true;

                if (isset($shipments[$key]['schedules_group']) && $shipments[$key]['schedules_group']!=='') {
                    $getShipmentSchedules = json_decode($shipments[$key]['schedules_group']);
                    $this->processShipmentSchedules($getShipmentSchedules, $shipments, $key, $this);
                }

                $shipments[$key]['location_from_name'] = $this->cleanField($shipments[$key]['location_from_name']);
                $shipments[$key]['location_to_name'] = $this->cleanField($shipments[$key]['location_to_name']);
                $shipments[$key]['suppliers_group'] = $this->merge_group($findShipment['suppliers_group_bookings'], $findShipment['suppliers_group']);

                $shipments[$key]['suppliers_group_bookings'] = $shipments[$key]['suppliers_group'];
                $suppliers_group = json_decode($shipments[$key]['suppliers_group']);
                $suppliers_name = [];

                foreach ($suppliers_group ?? [] as $supplier) {
                    array_push($suppliers_name, $this->getSupplierName($supplier));
                }

                $shipments[$key]["suppliers_name"] = $suppliers_name;
                $shipments[$key]['containers_group'] = $this->merge_group($findShipment['containers_group_bookings'], $findShipment['containers_group']);
                $shipments[$key]['containers_group_bookings'] = $shipments[$key]['containers_group'];
                
                // new statuses
                //$shipments[$key]['status_v2'] = $shipment->getStatusV2();
                $shipments[$key]['status_v2'] = $shipment->status_fallback;
                // check if tracking shipments
                $this->processTrackingShipments($findShipment, $shipments, $key, $this, $isSpecial);

                //tracking status
                $shipments[$key]['tracking_status'] = $findShipment->getTrackingStatus();
                
                if ($shipments[$key]['status_v2']==null || $shipments[$key]['status_v2']=='No Status') {

                    $schedules = json_decode($shipments[$key]['schedules_group']);

                    if (Carbon::parse($shipments[$key]->etd)->gt(now())) {
                        $shipments[$key]['shipment_status'] = 'Awaiting Departure';
                    }
                    if (Carbon::parse($shipments[$key]->etd)->lt(now())) {
                        $shipments[$key]['shipment_status'] = 'In Transit';
                    }

                    if ( count($schedules)>0 ) {

                        $shipments[$key]['schedules_group'] = json_encode($schedules);
                        $shipments[$key]['snooze_date_at_readable'] = Carbon::parse($shipment->snooze_date_at)->format('F d, Y');
                    }
                } else {
                    if ($shipments[$key]['status_v2']==='Full Out' || $shipments[$key]['status_v2']==='Empty In') {

                    } else {
                        $shipments[$key]['original_shipment_status'] = $shipments[$key]['status_v2'];
                        $shipments[$key]['shipment_status'] = $shipments[$key]['status_v2'];

                        if ( $shipments[$key]['status_v2'] === 'In transit - hold' ) {
                            $shipments[$key]['shipment_status'] = 'In Transit - Pending Release';
                        } elseif ( $shipments[$key]['status_v2'] === 'Past last free day' ) {
                            $shipments[$key]['shipment_status'] = 'Past last free day';
                        } elseif ( $shipments[$key]['status_v2'] === 'In transit - released' ) {
                            $shipments[$key]['shipment_status'] = 'In Transit - Released';
                        } elseif($shipments[$key]['status_v2'] === 'Discharged - hold') {
                            $shipments[$key]['shipment_status'] = 'Discharged - Hold';
                        }
                    }
                }
                //e
            }
        }

        return response()->json([
            'data' => $shipments
        ]);

        return abort(404);
        //return response()->json($shipments);
    }

    /**
     * Pending
     *
     * @authenticated
     *
     * @queryParam per_page int Size per page. Default to 30. Example: 30
     *
     * @apiResourceCollection App\Http\Resources\Shipment
     * @apiResourceModel App\Shipment with=customer
     *
     * @response status=400 scenario="Unauthenticated" {
     *     "message": "Unauthenticated."
     * }
     *
    */
    public function indexPending(Request $request) {
        
        $customers = Auth::user()->customersApi->pluck('id');
        $order_by = $request->has('sort') ? $request->input('sort') : 'eta';
        $order = ($request->has('order')) ? $request->input('order') : 'desc';
        $shipments = [];
        $isSpecial = false;
        $per_page = $request->has('per_page') ? $request->input('per_page') : 30;

        $get_authenticated_user = Auth::user();
        $customers = ( $get_authenticated_user->default_customer_id!==null ) ? [$get_authenticated_user->default_customer_id] : [$customers[0]];

        $shipments = Shipment::with(['shipmentSchedules','carrier'])
                            ->whereIn('customer_id', $customers)
                            ->where('booking_confirmed', 0)
                            ->where('cancelled', 0)
                            ->whereHas('shipmentSchedules', function($q) {
                                $q->whereDate('eta_date', '>', Carbon::now()->subDays(60));
                                $q->whereNotNull('etd_date');
                                $q->whereNotNull('eta_date');
                                $q->whereHas('shipmentScheduleSellRates');
                            })
                            ->where(function ($qq) {
                                $qq->where('snooze_date_at', NULL);
                                $qq->orWhere('snooze_date_at','<=', now());
                            })
                            ->paginate($per_page);           
        
        if ( count($shipments) > 0 ) {
            foreach( $shipments as $key => $shipment ) {
                $findShipment = $shipment;
                //s
                $shipments[$key]['schedules_group'] = $this->merge_group($findShipment['schedules_group_bookings'], $findShipment['schedules_group']);
                $shipments[$key]['schedules_group_bookings'] = $shipments[$key]['schedules_group'];
                $shipments[$key]['schedule_has_pricing'] = true;

                if (isset($shipments[$key]['schedules_group']) && $shipments[$key]['schedules_group']!=='') {
                    $getShipmentSchedules = json_decode($shipments[$key]['schedules_group']);
                    $this->processShipmentSchedules($getShipmentSchedules, $shipments, $key, $this);
                }

                $shipments[$key]['location_from_name'] = $this->cleanField($shipments[$key]['location_from_name']);
                $shipments[$key]['location_to_name'] = $this->cleanField($shipments[$key]['location_to_name']);
                $shipments[$key]['suppliers_group'] = $this->merge_group($findShipment['suppliers_group_bookings'], $findShipment['suppliers_group']);

                $shipments[$key]['suppliers_group_bookings'] = $shipments[$key]['suppliers_group'];
                $suppliers_group = json_decode($shipments[$key]['suppliers_group']);
                $suppliers_name = [];

                foreach ($suppliers_group ?? [] as $supplier) {
                    array_push($suppliers_name, $this->getSupplierName($supplier));
                }

                $shipments[$key]["suppliers_name"] = $suppliers_name;
                $shipments[$key]['containers_group'] = $this->merge_group($findShipment['containers_group_bookings'], $findShipment['containers_group']);
                $shipments[$key]['containers_group_bookings'] = $shipments[$key]['containers_group'];
                
                // new statuses
                $shipments[$key]['status_v2'] = $shipment->getStatusV2();
                
                // check if tracking shipments
                $this->processTrackingShipments($findShipment, $shipments, $key, $this, $isSpecial);

                //tracking status
                $shipments[$key]['tracking_status'] = $findShipment->getTrackingStatus();

                if ($shipments[$key]['status_v2']==null || $shipments[$key]['status_v2']=='No Status') {

                    $schedules = json_decode($shipments[$key]['schedules_group']);
                    if (count($schedules)>0) {
                        $shipments[$key]['schedules_group'] = json_encode($schedules);
                        $shipments[$key]['snooze_date_at_readable'] = Carbon::parse($shipment->snooze_date_at)->format('F d, Y');
                    }
                }
                //e
            }
        }
        return response()->json($shipments);
    }

    /**
     * Display the specified resource.
     *
     * @authenticated
     *
     * @urlParam id int require Shipment ID
     *
     * @apiResourceCollection App\Http\Resources\Shipment
     * @apiResourceModel App\Shipment
     *
     * @response status=400 scenario="Unauthenticated" {
     *     "message": "Unauthenticated."
     * }
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        try {
            $shipment = Shipment::findOrFail($id);
        } catch (\Exception $e) {
            return abort(404);
        }
        $customer_admins = $shipment->customer->customerAdminsApi;
        $scheds = $shipment->shipmentSchedules;

        foreach ($customer_admins as $customer_admin) {
            if ($customer_admin->id == Auth::guard('api')->id() && $customer_admin->hasRole('Customer Admin')) {
                $ship = Shipment::where('id', $id)->with('customer', 'containers', 'suppliers', 'carrier', 'terminalRegions', 'shipmentSuppliers', 'shipmentSchedules')->firstOrFail();
                if (isset($ship['shipmentSchedules'][0])) {
                    $location_from_id = $ship['shipmentSchedules'][0]['location_from'];
                    $getTerminalRegionFrom = TerminalRegion::find($location_from_id);

                    if (isset($getTerminalRegionFrom->id) && $getTerminalRegionFrom->id == $location_from_id) {
                        $location_from_name = $getTerminalRegionFrom->name;
                        $ship['location_from_name'] =  $location_from_name;
                    } else {
                        $ship['location_from_name'] =  '';
                    }

                    $location_to_id = $ship['shipmentSchedules'][0]['location_to'];
                    $getTerminalRegionTo = TerminalRegion::find($location_to_id);

                    if (isset($getTerminalRegionTo->id) && $getTerminalRegionTo->id == $location_to_id) {
                        $location_to_name = $getTerminalRegionTo->name;
                        $ship['location_to_name'] =  $location_to_name;
                    } else {
                        $ship['location_to_name'] =  '';
                    }
                } else {
                    $ship['location_from_name'] =  '';
                    $ship['location_to_name'] =  '';
                }

                $results = new StandardResource($ship);

                return $results;
            }
        }

        return abort(404);
    }
}
