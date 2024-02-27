<?php

/**
  * @author Kenneth
*/
namespace App\Http\Controllers\API\Shipments\Traits;

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

trait ShipmentSearchTrait {


    protected function pendingQuoteSearch($parameters)
    {
        $customers = Auth::user()->customersApi->pluck('id');
        $shipments = [];
        $get_authenticated_user = Auth::user();

        $customers = ( $get_authenticated_user->default_customer_id!==null ) ? [$get_authenticated_user->default_customer_id] : [$customers[0]];

        $newShipments = [];
        if (count($customers) > 0) {

            $shipments = Shipment::whereHas('customer', function($q) use ($customers){
                $q->whereIn('id', $customers);
            })
            ->where('booking_confirmed', 0)
            ->where('cancelled', 0)
            ->where(function ($qq) {
                $qq->where('snooze_date_at', NULL);
                $qq->orWhere('snooze_date_at','<=', now());
            })
            ->orderBy($parameters['sort'],$parameters['order'])
            ->get();

            $query = strval(strtolower($parameters['q']));

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


                    //containers merge

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

                        if ($shipments[$key]['status_v2']==null || $shipments[$key]['status_v2'] === 'No Status') {
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
                                        if (isset($schedule->sell_rates) && $schedule->sell_rates!=='' && $schedule->sell_rates!==null) {
                                            if (count($schedule->sell_rates)>0) {
                                                unset($schedules[$keySecond]);
                                                $hasUnset = true;
                                            }
                                        }

                                            
                                        /*
                                        if ( !$hasUnset ) {
                                            $hasUnset = true;
                                            $etd = Carbon::parse($schedule->etd)->diffInDays(now(), false);
                                            if ($etd>=-4) {
                                                unset($schedules[$keySecond]);
                                            }
                                        }*/

                                        if ( !$hasUnset ) {
                                            $hasUnset = true;
                                            $eta = Carbon::parse($schedule->eta)->diffInDays(now(), false);
                                            if ($eta>60) {
                                                unset($schedules[$keySecond]);
                                            }
                                        }
                                    }

                                    if (count($schedules)>0) {
                                        $shipments[$key]['schedules_group'] = json_encode($schedules);

                                        //s
                                        $final_push = false;
                                        /*
                                        if (strpos(strval(strtolower($shipments[$key]->shifl_ref)),strval($query))!==false) {
                                            $final_push = true;
                                        }

                                        if ( isset($shipments[$key]['po_num']) && strpos(strval(strtolower($shipments[$key]['po_num'])), strval($query))!==false) {
                                            $final_push = true;
                                        }

                                        if (isset($shipments[$key]['suppliers_group_bookings']) && $shipments[$key]['suppliers_group_bookings']!=='' && $shipments[$key]['suppliers_group_bookings']!==null) {
                                            $suppliers = json_decode($shipments[$key]['suppliers_group_bookings']);

                                            if (count($suppliers)>0) {
                                                foreach($suppliers as $supplier) {
                                                    if (strpos(strval(strtolower($supplier->ams_num)),strval(strtolower($query)))!==false || strpos(strval(strtolower($supplier->hbl_num)),strval(strtolower($query)))!==false || strpos(strval(strtolower($supplier->po_num)),strval(strtolower($query)))!==false || strpos(strval(strtolower($supplier->bl_status)),strval(strtolower($query)))!==false) {
                                                        $final_push = true;
                                                    }
                                                }
                                            }
                                        }
                                        if (isset($shipments[$key]['suppliers_name']) && count($shipments[$key]['suppliers_name'])>0) {
                                            
                                            foreach($shipments[$key]['suppliers_name'] as $name) {
                                                if ( strpos(strval(strtolower($name)), strval($query))!==false) {
                                                    $final_push = true;
                                                }
                                            }
                                        }*/
                                        $queries = explode(' ', $query);
                                        
                                        if ( count ($queries) > 0 ) {
                                            foreach($queries as $q) {
                                                if (strpos(strval(strtolower($shipments[$key]['mbl_num'])),strval(strtolower($q)))!==false || strpos(strval(strtolower($shipments[$key]['type'])),strval(strtolower($q)))!==false || strpos(strval(strtolower($shipments[$key]['shifl_ref'])),strval(strtolower($q)))!==false) {
                                                    $final_push = true;
                                                }
                                            
                                                if (strpos(strval(strtolower($shipments[$key]['shipment_status'])),strval(strtolower($q)))!==false) {
                                                    $final_push = true;
                                                }
                                            
                                                if (isset($shipments[$key]['containers_group_bookings']) && $shipments[$key]['containers_group_bookings']!=='' && $shipments[$key]['containers_group_bookings']!==null) {
                                                    $containers = json_decode($shipments[$key]['containers_group_bookings']);
                                            
                                                    if (count($containers)>0) {
                                                        foreach($containers as $container) {
                                                            if (strpos(strval(strtolower($container->container_num)),strval(strtolower($q)))!==false){
                                                                $final_push = true;
                                                            }
                                                        }
                                                    }
                                                }
                                            
                                                if (isset($shipments[$key]['suppliers_group_bookings']) && $shipments[$key]['suppliers_group_bookings']!=='' && $shipments[$key]['suppliers_group_bookings']!==null) {
                                                    $suppliers = json_decode($shipments[$key]['suppliers_group_bookings']);
                                            
                                            
                                                    if (count($suppliers)>0) {
                                                        foreach($suppliers as $supplier) {
                                                            if (strpos(strval(strtolower($supplier->ams_num)),strval(strtolower($q)))!==false || strpos(strval(strtolower($supplier->hbl_num)),strval(strtolower($q)))!==false || strpos(strval(strtolower($supplier->po_num)),strval(strtolower($q)))!==false) {
                                                                $final_push = true;
                                                            }
                                                        }
                                                    }
                                                }
                                            
                                                if (isset($shipments[$key]['suppliers_name']) && count($shipments[$key]['suppliers_name'])>0) {
                                                    
                                                    foreach($shipments[$key]['suppliers_name'] as $name) {
                                                        if ( strpos(strval(strtolower($name)), strval($q))!==false) {
                                                            $final_push = true;
                                                        }
                                                    }
                                                }
                                            
                                                if ( isset($shipments[$key]['po_num']) && strpos(strval(strtolower($shipments[$key]['po_num'])), strval($q))!==false) {
                                                    $final_push = true;
                                                }
                                            
                                            }
                                        }

                                        if ( $final_push ) {
                                            array_push($newShipments, $shipments[$key]);    
                                        }
                                        //e
                                        
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


        if (is_null($parameters['per_page'])) {
            //return StandardResource::collection((new Collection($newShipments)));
            return StandardResource::collection((new Collection($newShipments))->paginate(30));
        }
        if (is_numeric($parameters['per_page'])) {
            // return StandardResource::collection((new Collection($newShipments))->paginate($request->per_page));
            return StandardResource::collection((new Collection($newShipments))->paginate($parameters['per_page']));
        }
        return abort(404);
    }

    protected function snoozeSearch($parameters)
    {
        $customers = Auth::user()->customersApi->pluck('id');
        $shipments = [];

        $get_authenticated_user = Auth::user();

        $customers = ( $get_authenticated_user->default_customer_id!==null ) ? [$get_authenticated_user->default_customer_id] : [$customers[0]];

        $newShipments = [];

        if (count($customers) > 0) {

            $shipments = Shipment::whereHas('customer', function($q) use ($customers){
                $q->whereIn('id', $customers);
            })
            ->where('booking_confirmed', 0)
            ->where('cancelled', 0)
            ->where(function ($qq) {
                $qq->whereNotNull('snooze_date_at');
                $qq->where('snooze_date_at','>', now());
            })
            ->orderBy($parameters['sort'],$parameters['order'])
            ->get();

            $query = strval(strtolower($parameters['q']));

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

                    if ($shipments[$key]['booking_confirmed']==1) {
                        $should_push = false;
                    }
                    //
                    if ($should_push) {
                        
                        // current day minus eta
                        //$diff_days = Carbon::parse($shipments[$key]['eta'])->diffInDays(now(), false);
                        $shipments[$key]['diff_days'] = 100000;
                        $proceed = false;

                        if ($shipments[$key]['status_v2']==null || $shipments[$key]['status_v2'] === 'No Status') {
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
                                        } else {
                                            /*
                                            unset($schedules[$keySecond]);
                                            $hasUnset = true;
                                            */
                                        //}

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

                                        //s
                                        $final_push = false;
                                        $queries = explode(' ', $query);
                                        
                                        if ( count ($queries) > 0 ) {
                                            foreach($queries as $q) {
                                                if (strpos(strval(strtolower($shipments[$key]['mbl_num'])),strval(strtolower($q)))!==false || strpos(strval(strtolower($shipments[$key]['type'])),strval(strtolower($q)))!==false || strpos(strval(strtolower($shipments[$key]['shifl_ref'])),strval(strtolower($q)))!==false) {
                                                    $final_push = true;
                                                }
                                            
                                                if (strpos(strval(strtolower($shipments[$key]['shipment_status'])),strval(strtolower($q)))!==false) {
                                                    $final_push = true;
                                                }
                                            
                                                if (isset($shipments[$key]['containers_group_bookings']) && $shipments[$key]['containers_group_bookings']!=='' && $shipments[$key]['containers_group_bookings']!==null) {
                                                    $containers = json_decode($shipments[$key]['containers_group_bookings']);
                                            
                                                    if (count($containers)>0) {
                                                        foreach($containers as $container) {
                                                            if (strpos(strval(strtolower($container->container_num)),strval(strtolower($q)))!==false){
                                                                $final_push = true;
                                                            }
                                                        }
                                                    }
                                                }
                                            
                                                if (isset($shipments[$key]['suppliers_group_bookings']) && $shipments[$key]['suppliers_group_bookings']!=='' && $shipments[$key]['suppliers_group_bookings']!==null) {
                                                    $suppliers = json_decode($shipments[$key]['suppliers_group_bookings']);
                                            
                                            
                                                    if (count($suppliers)>0) {
                                                        foreach($suppliers as $supplier) {
                                                            if (strpos(strval(strtolower($supplier->ams_num)),strval(strtolower($q)))!==false || strpos(strval(strtolower($supplier->hbl_num)),strval(strtolower($q)))!==false || strpos(strval(strtolower($supplier->po_num)),strval(strtolower($q)))!==false) {
                                                                $final_push = true;
                                                            }
                                                        }
                                                    }
                                                }
                                            
                                                if (isset($shipments[$key]['suppliers_name']) && count($shipments[$key]['suppliers_name'])>0) {
                                                    
                                                    foreach($shipments[$key]['suppliers_name'] as $name) {
                                                        if ( strpos(strval(strtolower($name)), strval($q))!==false) {
                                                            $final_push = true;
                                                        }
                                                    }
                                                }
                                            
                                                if ( isset($shipments[$key]['po_num']) && strpos(strval(strtolower($shipments[$key]['po_num'])), strval($q))!==false) {
                                                    $final_push = true;
                                                }
                                            
                                            }
                                        }
                                        /*
                                        if (strpos(strval(strtolower($shipments[$key]->shifl_ref)),strval($query))!==false) {
                                            $final_push = true;
                                        }

                                        if ( isset($shipments[$key]['po_num']) && strpos(strval(strtolower($shipments[$key]['po_num'])), strval($query))!==false) {
                                            $final_push = true;
                                        }

                                        if (isset($shipments[$key]['suppliers_group_bookings']) && $shipments[$key]['suppliers_group_bookings']!=='' && $shipments[$key]['suppliers_group_bookings']!==null) {
                                            $suppliers = json_decode($shipments[$key]['suppliers_group_bookings']);

                                            if (count($suppliers)>0) {
                                                foreach($suppliers as $supplier) {
                                                    if (strpos(strval(strtolower($supplier->ams_num)),strval(strtolower($query)))!==false || strpos(strval(strtolower($supplier->hbl_num)),strval(strtolower($query)))!==false || strpos(strval(strtolower($supplier->po_num)),strval(strtolower($query)))!==false || strpos(strval(strtolower($supplier->bl_status)),strval(strtolower($query)))!==false) {
                                                        $final_push = true;
                                                    }
                                                }
                                            }
                                        }
                                        if (isset($shipments[$key]['suppliers_name']) && count($shipments[$key]['suppliers_name'])>0) {
                                            
                                            foreach($shipments[$key]['suppliers_name'] as $name) {
                                                if ( strpos(strval(strtolower($name)), strval($query))!==false) {
                                                    $final_push = true;
                                                }
                                            }
                                        }*/

                                        if ( $final_push ) {
                                            array_push($newShipments, $shipments[$key]);    
                                        }
                                        //e
                                        
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

        if (is_null($parameters['per_page'])) {
            //return StandardResource::collection((new Collection($newShipments)));
            return StandardResource::collection((new Collection($newShipments))->paginate(30));
        }
        if (is_numeric($parameters['per_page'])) {
            // return StandardResource::collection((new Collection($newShipments))->paginate($request->per_page));
            return StandardResource::collection((new Collection($newShipments))->paginate($parameters['per_page']));
        }
        return abort(404);
    }

    protected function completedSearchTest($parameters)
    {
        $customers = Auth::user()->customersApi->pluck('id');
        $order = $parameters['order'];
        $order_by = $parameters['sort'];
        $sort = $order_by;
        
        $shipments = [];
        $isSpecial = false;

        $get_authenticated_user = Auth::user();

        try {

          $customers = ( $get_authenticated_user->default_customer_id!==null ) ? [$get_authenticated_user->default_customer_id] : [$customers[0]];
        } catch (\Exception $exception){
            return response([
                'message'=>'No results found.'
            ], 404);
        }

        
        $newShipments = [];
        if ( count($customers) > 0) {
            
            $per_page = 20;

            $shipments = Shipment::whereIn('customer_id', $customers)
            ->where('booking_confirmed', 1)
            ->where('cancelled', 0)
            ->where(function($q) use ($parameters){
                $q->where(function($qq) use ($parameters){

                    $query = strval(strtolower($parameters['q']));
                    $queries = explode(' ', $query);

                    $qq->whereNotNull('eta')
                    ->whereNotNull('etd')
                    ->where('eta','<>','0000-00-00')
                    ->where('etd','<>','0000-00-00')
                    ->whereDate('eta', '<', Carbon::now()->subDays(60));
                    $qq->where(function($qqqq) {
                        $qqqq->where('status_fallback','No Status');
                        $qqqq->orWhere('status_fallback','<>','In transit');
                    });

                    foreach( $queries as $qry ) {
                        
                        $qq->where('mbl_num','LIKE','%'.$qry.'%')
                        ->orWhere('type','LIKE','%'.$qry.'%')
                        ->orWhere('shifl_ref','LIKE','%'.$qry.'%')
                        ->orWhere('shipment_status',$qry)
                        ->orWhereHas('containers', function($qqq) use ($qry){
                            $qqq->where('container_num','LIKE','%'.$qry.'%');
                        })
                        ->orWhereHas('suppliers', function ($qqq) use ($qry) {
                            $qqq->where('ams_num','LIKE','%'.$qry.'%')
                                ->orWhere('hbl_num','LIKE','%'.$qry.'%')
                                ->orWhere('po_num','LIKE','%'.$qry.'%')
                                ->orWhere('company_name','LIKE','%'.$qry.'%');
                        });
                    }
                });
                $q->orWhere(function($qq) use ($parameters) {

                    $query = strval(strtolower($parameters['q']));
                    $queries = explode(' ', $query);

                    $qq->whereNotNull('tracking_eta')
                    ->whereNotNull('tracking_etd')
                    ->whereDate('tracking_eta', '<', Carbon::now()->subDays(60));
                    $qq->whereNotNull('status_fallback');
                    $qq->where(function($qqqq) {
                        $qqqq->where('status_fallback','Full Out');
                        $qqqq->orWhere('status_fallback','Empty In');
                    });

                    foreach( $queries as $qry ) {
                        
                        $qq->where('mbl_num','LIKE','%'.$qry.'%')
                        ->orWhere('type','LIKE','%'.$qry.'%')
                        ->orWhere('shifl_ref','LIKE','%'.$qry.'%')
                        ->orWhere('shipment_status',$qry)
                        ->orWhereHas('containers', function($qqq) use ($qry){
                            $qqq->where('container_num','LIKE','%'.$qry.'%');
                        })
                        ->orWhereHas('suppliers', function ($qqq) use ($qry) {
                            $qqq->where('ams_num','LIKE','%'.$qry.'%')
                                ->orWhere('hbl_num','LIKE','%'.$qry.'%')
                                ->orWhere('po_num','LIKE','%'.$qry.'%')
                                ->orWhere('company_name','LIKE','%'.$qry.'%');
                        });
                    }


                });
            })
            ->orderBy($sort, $order)
            ->paginate($per_page);

            if (count($shipments) > 0) {

                foreach ($shipments as $key => $shipment) {

                    $should_push = true;
                    //schedule merge
                    //merge group fields start
                    $findShipment = $shipment;

                    //$getCarrier = Carrier::find(intval($findShipment['carrier_id']));
                    //$shipments[$key]['carrier'] = (isset($getCarrier->id)) ? $getCarrier : null;
                    $shipments[$key]['carrier'] = $findShipment->carrier;
                    $shipments[$key]['shipment_metas'] = $findShipment->shipmentMetas;
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
                    //

                    //tracking status
                    $shipments[$key]['tracking_status'] = $findShipment->getTrackingStatus();
                    //$shipments[$key]['tracking_status'] = '';


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
                                if ($diff_days<60) {
                                    $proceed = true;
                                } else {
                                    $proceed = false;
                                }
                            } else {
                                $proceed = false;
                            }

                            //for completed
                            if ($shipments[$key]['eta']!==null && $shipments[$key]['eta']!=='') {

                                if ($diff_days > 60) {
                                    $proceed = false;
                                    //$proceed = true;
                                    //$completed = true;
                                    //$shipments[$key]['shipment_status'] = 'Completed';
                                }
                            }

                            if ( $shipments[$key]['mbl_num'] !=='' && $shipments[$key]['mbl_num']!==null && ($shipments[$key]['eta']==null || $shipments[$key]['eta']=='' || $shipments[$key]['etd']==null || $shipments[$key]['etd']=='')) {
                                $proceed = true;
                            }


                        } else {

                            if ($shipments[$key]['status_v2']==='Full Out' || $shipments[$key]['status_v2']==='Empty In') {
                                $proceed = false;

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
                                    $proceed = false;
                                    //$completed = true;
                                    //$shipments[$key]['shipment_status'] = 'Completed';
                                } else {
                                    $proceed = true;
                                }

                            }


                        }
                        

                    }
                    //e
                }
            }
        }

        return response()->json([
            'data' => $shipments->items(),
            'links' => [
                'prev' => $shipments->previousPageUrl(),
                'next' => $shipments->nextPageUrl()
            ],
            'meta' => [
                'current_page' => $shipments->currentPage(),
                'from' => $shipments->firstItem(),
                'last_page' => $shipments->lastPage(),
                'path' => $shipments->path(),
                'per_page' => $shipments->perPage(),
                'to' => $shipments->lastItem(),
                'total' =>$shipments->total(),
            ]
        ]);

        return abort(404);
    }

    protected function completedSearch($parameters)
    {
        $customers = Auth::user()->customersApi->pluck('id');
        $order = $parameters['order'];
        $order_by = $parameters['sort'];
        $get_authenticated_user = Auth::user();
        $customers = ( $get_authenticated_user->default_customer_id!==null ) ? [$get_authenticated_user->default_customer_id] : [$customers[0]];
        $shipments = [];

        $isSpecial = false;

        $newShipments = [];
        if (count($customers) > 0) {

            $shipments = Shipment::whereHas('customer', function($q) use ($customers){
                $q->whereIn('id', $customers);
            })
            ->where('cancelled', 0)
            //->orderBy($parameters['sort'],$parameters['order'])
            ->get();

            $query = strval(strtolower($parameters['q']));
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
                    $shipments[$key]['suppliers_group_bookings'] = $shipments[$key]['suppliers_group_bookings'];
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

                        if ($shipments[$key]['status_v2']==null || $shipments[$key]['status_v2'] === 'No Status') {

                            //$proceed = true;
                            
                            if ($shipments[$key]['eta']!==null && $shipments[$key]['eta']!=='') {
                                if ($diff_days > 60) {
                                    $proceed = true;
                                }
                                
                            }

                        } else {
                            if ($shipments[$key]['status_v2']==='Full Out' || $shipments[$key]['status_v2']==='Empty In') {
                                /*
                                if (isset($shipments[$key]['selected_schedule_type']) && $shipments[$key]['selected_schedule_type']=='LCL') {
                                    if ( $diff_days>30 ) {
                                        $proceed = true;
                                    }
                                } else {
                                    $proceed = true;
                                }*/
                                $proceed = true;
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
                                        $final_push = false;
                                        $queries = explode(' ', $query);

                                        if ( count ($queries) > 0 ) {
                                            foreach($queries as $q) {
                                                if (strpos(strval(strtolower($shipments[$key]['mbl_num'])),strval(strtolower($q)))!==false || strpos(strval(strtolower($shipments[$key]['type'])),strval(strtolower($q)))!==false || strpos(strval(strtolower($shipments[$key]['shifl_ref'])),strval(strtolower($q)))!==false) {
                                                    $final_push = true;
                                                }
                                            
                                                if (strpos(strval(strtolower($shipments[$key]['shipment_status'])),strval(strtolower($q)))!==false) {
                                                    $final_push = true;
                                                }
                                            
                                                if (isset($shipments[$key]['containers_group_bookings']) && $shipments[$key]['containers_group_bookings']!=='' && $shipments[$key]['containers_group_bookings']!==null) {
                                                    $containers = json_decode($shipments[$key]['containers_group_bookings']);
                                            
                                                    if (count($containers)>0) {
                                                        foreach($containers as $container) {
                                                            if (strpos(strval(strtolower($container->container_num)),strval(strtolower($q)))!==false){
                                                                $final_push = true;
                                                            }
                                                        }
                                                    }
                                                }
                                            
                                                if (isset($shipments[$key]['suppliers_group_bookings']) && $shipments[$key]['suppliers_group_bookings']!=='' && $shipments[$key]['suppliers_group_bookings']!==null) {
                                                    $suppliers = json_decode($shipments[$key]['suppliers_group_bookings']);
                                            
                                            
                                                    if (count($suppliers)>0) {
                                                        foreach($suppliers as $supplier) {
                                                            if (strpos(strval(strtolower($supplier->ams_num)),strval(strtolower($q)))!==false || strpos(strval(strtolower($supplier->hbl_num)),strval(strtolower($q)))!==false || strpos(strval(strtolower($supplier->po_num)),strval(strtolower($q)))!==false) {
                                                                $final_push = true;
                                                            }
                                                        }
                                                    }
                                                }
                                            
                                                if (isset($shipments[$key]['suppliers_name']) && count($shipments[$key]['suppliers_name'])>0) {
                                                    
                                                    foreach($shipments[$key]['suppliers_name'] as $name) {
                                                        if ( strpos(strval(strtolower($name)), strval($q))!==false) {
                                                            $final_push = true;
                                                        }
                                                    }
                                                }
                                            
                                                if ( isset($shipments[$key]['po_num']) && strpos(strval(strtolower($shipments[$key]['po_num'])), strval($q))!==false) {
                                                    $final_push = true;
                                                }
                                            
                                            }
                                        }
                                        //s
                                        /*
                                        if (strpos(strval(strtolower($shipments[$key]['mbl_num'])),strval(strtolower($query)))!==false || strpos(strval(strtolower($shipments[$key]['type'])),strval(strtolower($query)))!==false || strpos(strval(strtolower($shipments[$key]['shifl_ref'])),strval(strtolower($query)))!==false || strpos(strval(strtolower($shipments[$key]['shipment_status'])),strval(strtolower($query)))!==false) {
                                                $final_push = true;
                                            }

                                        if (isset($shipments[$key]['status_v2'])) {
                                            if (strpos(strval(strtolower($shipments[$key]['status_v2'])),strval(strtolower($query)))!==false) {
                                                $final_push = true;
                                            }
                                        }
                                        

                                        if (isset($shipments[$key]['containers_group_bookings']) && $shipments[$key]['containers_group_bookings']!=='' && $shipments[$key]['containers_group_bookings']!==null) {
                                            $containers = json_decode($shipments[$key]['containers_group_bookings']);

                                            if (count($containers)>0) {
                                                foreach($containers as $container) {
                                                    if (strpos(strval(strtolower($container->container_num)),strval(strtolower($query)))!==false){
                                                        $final_push = true;
                                                    }
                                                }
                                            }
                                        }

                                        if (isset($shipments[$key]['suppliers_group_bookings']) && $shipments[$key]['suppliers_group_bookings']!=='' && $shipments[$key]['suppliers_group_bookings']!==null) {
                                            $suppliers = json_decode($shipments[$key]['suppliers_group_bookings']);

                                            if (count($suppliers)>0) {
                                                foreach($suppliers as $supplier) {
                                                    if (strpos(strval(strtolower($supplier->ams_num)),strval(strtolower($query)))!==false || strpos(strval(strtolower($supplier->hbl_num)),strval(strtolower($query)))!==false || strpos(strval(strtolower($supplier->po_num)),strval(strtolower($query)))!==false || strpos(strval(strtolower($supplier->bl_status)),strval(strtolower($query)))!==false) {
                                                        $final_push = true;
                                                    }
                                                }
                                            }
                                        }

                                        if (isset($shipments[$key]['suppliers_name']) && count($shipments[$key]['suppliers_name'])>0) {
                                            
                                            foreach($shipments[$key]['suppliers_name'] as $name) {
                                                if ( strpos(strval(strtolower($name)), strval($query))!==false) {
                                                    $final_push = true;
                                                }
                                            }
                                        }

                                        if ( isset($shipments[$key]['po_num']) && strpos(strval(strtolower($shipments[$key]['po_num'])), strval($query))!==false) {
                                            $final_push = true;
                                        }*/

                                        if ( $final_push ) {
                                            array_push($newShipments, $shipments[$key]);    
                                        }
                                        //e
                                        
                                        /*
                                        if ($diff_days<20){
                                            array_push($newShipments, $shipments[$key]);
                                        }*/
                                    } 

                                }
                            } else {
                                if ( $isSpecial ) {
                                    $final_push = false;
                                    $shipments[$key]['shipment_status'] = 'Completed';
                                    if (strpos(strval(strtolower($shipments[$key]['mbl_num'])),strval(strtolower($query)))!==false || strpos(strval(strtolower($shipments[$key]['type'])),strval(strtolower($query)))!==false || strpos(strval(strtolower($shipments[$key]['shifl_ref'])),strval(strtolower($query)))!==false || strpos(strval(strtolower($shipments[$key]['shipment_status'])),strval(strtolower($query)))!==false) {
                                        $final_push = true;
                                    }


                                    if (isset($shipments[$key]['status_v2'])) {
                                        if (strpos(strval(strtolower($shipments[$key]['status_v2'])),strval(strtolower($query)))!==false) {
                                            $final_push = true;
                                        }
                                    }
                                        
                                    if (isset($shipments[$key]['containers_group_bookings']) && $shipments[$key]['containers_group_bookings']!=='' && $shipments[$key]['containers_group_bookings']!==null) {
                                        $containers = json_decode($shipments[$key]['containers_group_bookings']);

                                        if (count($containers)>0) {
                                            foreach($containers as $container) {
                                                if (strpos(strval(strtolower($container->container_num)),strval(strtolower($query)))!==false) {
                                                    $final_push = true;
                                                }
                                            }
                                        }
                                    }

                                    if (isset($shipments[$key]['suppliers_group_bookings']) && $shipments[$key]['suppliers_group_bookings']!=='' && $shipments[$key]['suppliers_group_bookings']!==null) {
                                        $suppliers = json_decode($shipments[$key]['suppliers_group_bookings']);
                                        if (count($suppliers)>0) {
                                            foreach($suppliers as $supplier) {
                                                if (strpos(strval(strtolower($supplier->ams_num)),strval(strtolower($query)))!==false || strpos(strval(strtolower($supplier->hbl_num)),strval(strtolower($query)))!==false || strpos(strval(strtolower($supplier->po_num)),strval(strtolower($query)))!==false || strpos(strval(strtolower($supplier->bl_status)),strval(strtolower($query)))!==false) {
                                                    $final_push = true;
                                                }
                                            }
                                        }
                                    }

                                    if (isset($shipments[$key]['suppliers_name']) && count($shipments[$key]['suppliers_name'])>0) {
                                            
                                        foreach($shipments[$key]['suppliers_name'] as $name) {
                                            if ( strpos(strval(strtolower($name)), strval($query))!==false) {
                                                $final_push = true;
                                            }
                                        }
                                    }

                                    if ( isset($shipments[$key]['po_num']) && strpos(strval(strtolower($shipments[$key]['po_num'])), strval($query))!==false) {
                                        $final_push = true;
                                    }

                                    if ( $final_push )
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

        if (is_null($parameters['per_page'])) {
            //return StandardResource::collection((new Collection($newShipments)));
            return StandardResource::collection((new Collection($newShipments))->paginate(30));
        }
        if (is_numeric($parameters['per_page'])) {
            return StandardResource::collection((new Collection($newShipments))->paginate($parameters['per_page']));
        }
        return abort(404);
    }

    protected function expiredSearch($parameters)
    {

        $customers = Auth::user()->customersApi->pluck('id');
        $get_authenticated_user = Auth::user();
        $customers = ( $get_authenticated_user->default_customer_id!==null ) ? [$get_authenticated_user->default_customer_id] : [$customers[0]];
        
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
            ->orderBy($parameters['sort'],$parameters['order'])
            ->get();

            $query = strval(strtolower($parameters['q']));

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
                    //$shipments[$key]['status_v2'] = Shipment::find($shipment['id'])->getStatusV2() ?? null;
                    // check if tracking shipments
                    $isSpecial = false;
                    $this->processTrackingShipments($findShipment, $shipments, $key, $this, $isSpecial);
                    if ($shipments[$key]['booking_confirmed']==1) {
                        $should_push = false;
                    }

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

                        if ($shipments[$key]['status_v2']==null || $shipments[$key]['status_v2'] === 'No Status') {
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
                                        if (isset($schedule->sell_rates) && $schedule->sell_rates!=='' && $schedule->sell_rates!==null) {
                                            if (count($schedule->sell_rates)==0) {
                                                unset($schedules[$keySecond]);
                                                $hasUnset = true;
                                            }
                                        }
                                        
                                        /*
                                        if ( !$hasUnset ) {
                                            $hasUnset = true;
                                            $etd = Carbon::parse($schedule->etd)->diffInDays(now(), false);
                                            if ($etd>=-4) {
                                                unset($schedules[$keySecond]);
                                            }
                                        }*/


                                        if ( !$hasUnset ) {
                                            $eta = Carbon::parse($schedule->eta)->diffInDays(now(), false);
                                            if ( $eta >60 ) {
                                                unset($schedules[$keySecond]);
                                            }
                                        }
                                    }

                                    if (count($schedules)==0) {
                                        $shipments[$key]['schedules_group'] = json_encode([]);
                                        $shipments[$key]['schedules_group_bookings'] = json_encode([]);
                                        $shipments[$key]['shipment_status'] = 'Expired';

                                        $final_push = false;
                                        //s
                                        if ( isset($shipments[$key]['po_num']) && strpos(strval(strtolower($shipments[$key]['po_num'])), strval($query))!==false) {
                                            $final_push = true;
                                        }

                                        if (strpos(strval(strtolower($shipments[$key]->shifl_ref)),strval(strtolower($query)))!==false) {
                                            $final_push = true;
                                        }
                                        if (isset($shipments[$key]['suppliers_group_bookings']) && $shipments[$key]['suppliers_group_bookings']!=='' && $shipments[$key]['suppliers_group_bookings']!==null) {
                                            $suppliers = json_decode($shipments[$key]['suppliers_group_bookings']);

                                            if (count($suppliers)>0) {
                                                foreach($suppliers as $supplier) {
                                                    if (strpos(strval(strtolower($supplier->ams_num)),strval(strtolower($query)))!==false || strpos(strval(strtolower($supplier->hbl_num)),strval(strtolower($query)))!==false || strpos(strval(strtolower($supplier->po_num)),strval(strtolower($query)))!==false || strpos(strval(strtolower($supplier->bl_status)),strval(strtolower($query)))!==false) {
                                                        $final_push = true;
                                                    }
                                                }
                                            }
                                        }

                                        if (isset($shipments[$key]['suppliers_name']) && count($shipments[$key]['suppliers_name'])>0) {
                                            
                                            foreach($shipments[$key]['suppliers_name'] as $name) {
                                                if ( strpos(strval(strtolower($name)), strval($query))!==false) {
                                                    $final_push = true;
                                                }
                                            }
                                        }
                                        //e

                                        if ($final_push) {
                                            array_push($newShipments, $shipments[$key]);    
                                        }
                                    }

                                } else {
                                    $shipments[$key]['schedules_group'] = json_encode([]);
                                    $shipments[$key]['schedules_group_bookings'] = json_encode([]);
                                    $shipments[$key]['shipment_status'] = 'Expired';

                                    $final_push = false;
                                    //s
                                    if ( isset($shipments[$key]['po_num']) && strpos(strval(strtolower($shipments[$key]['po_num'])), strval($query))!==false) {
                                        $final_push = true;
                                    }

                                    if (strpos(strval(strtolower($shipments[$key]->shifl_ref)),strval(strtolower($query)))!==false) {
                                        $final_push = true;
                                    }
                                    if (isset($shipments[$key]['suppliers_group_bookings']) && $shipments[$key]['suppliers_group_bookings']!=='' && $shipments[$key]['suppliers_group_bookings']!==null) {
                                        $suppliers = json_decode($shipments[$key]['suppliers_group_bookings']);

                                        if (count($suppliers)>0) {
                                            foreach($suppliers as $supplier) {
                                                if (strpos(strval(strtolower($supplier->ams_num)),strval(strtolower($query)))!==false || strpos(strval(strtolower($supplier->hbl_num)),strval(strtolower($query)))!==false || strpos(strval(strtolower($supplier->po_num)),strval(strtolower($query)))!==false || strpos(strval(strtolower($supplier->bl_status)),strval(strtolower($query)))!==false) {
                                                    $final_push = true;
                                                }
                                            }
                                        }
                                    }

                                    if (isset($shipments[$key]['suppliers_name']) && count($shipments[$key]['suppliers_name'])>0) {
                                        
                                        foreach($shipments[$key]['suppliers_name'] as $name) {
                                            if ( strpos(strval(strtolower($name)), strval($query))!==false) {
                                                $final_push = true;
                                            }
                                        }
                                    }
                                    if ($final_push) {
                                        array_push($newShipments, $shipments[$key]);    
                                    }
                                }
                            } else {

                                $shipments[$key]['schedules_group'] = json_encode([]);
                                $shipments[$key]['schedules_group_bookings'] = json_encode([]);
                                $shipments[$key]['shipment_status'] = 'Expired';

                                $final_push = false;
                                //s
                                if ( isset($shipments[$key]['po_num']) && strpos(strval(strtolower($shipments[$key]['po_num'])), strval($query))!==false) {
                                    $final_push = true;
                                }

                                if (strpos(strval(strtolower($shipments[$key]->shifl_ref)),strval(strtolower($query)))!==false) {
                                    $final_push = true;
                                }
                                if (isset($shipments[$key]['suppliers_group_bookings']) && $shipments[$key]['suppliers_group_bookings']!=='' && $shipments[$key]['suppliers_group_bookings']!==null) {
                                    $suppliers = json_decode($shipments[$key]['suppliers_group_bookings']);

                                    if (count($suppliers)>0) {
                                        foreach($suppliers as $supplier) {
                                            if (strpos(strval(strtolower($supplier->ams_num)),strval(strtolower($query)))!==false || strpos(strval(strtolower($supplier->hbl_num)),strval(strtolower($query)))!==false || strpos(strval(strtolower($supplier->po_num)),strval(strtolower($query)))!==false || strpos(strval(strtolower($supplier->bl_status)),strval(strtolower($query)))!==false) {
                                                $final_push = true;
                                            }
                                        }
                                    }
                                }

                                if (isset($shipments[$key]['suppliers_name']) && count($shipments[$key]['suppliers_name'])>0) {
                                    
                                    foreach($shipments[$key]['suppliers_name'] as $name) {
                                        if ( strpos(strval(strtolower($name)), strval($query))!==false) {
                                            $final_push = true;
                                        }
                                    }
                                }
                                if ($final_push) {
                                    array_push($newShipments, $shipments[$key]);    
                                }
                            }
                        }
                        
                    }
                    //e

                    }
                }
            }


        if (is_null($parameters['per_page'])) {
            return StandardResource::collection((new Collection($newShipments))->paginate(30));
        }
        if (is_numeric($parameters['per_page'])) {
            return StandardResource::collection((new Collection($newShipments))->paginate($parameters['per_page']));
        }
        return abort(404);
    }


    protected function pendingSearch($parameters)
    {
        $customers = Auth::user()->customersApi->pluck('id');
        $get_authenticated_user = Auth::user();
        $customers = ( $get_authenticated_user->default_customer_id!==null ) ? [$get_authenticated_user->default_customer_id] : [$customers[0]];

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
            ->orderBy($parameters['sort'],$parameters['order'])
            ->get();

            $query = strval(strtolower($parameters['q']));

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
                    //
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

                        if ($shipments[$key]['status_v2']==null || $shipments[$key]['status_v2'] === 'No Status') {
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
                                        if (isset($schedule->sell_rates) && $schedule->sell_rates!=='' && $schedule->sell_rates!==null) {
                                            if (count($schedule->sell_rates)==0) {
                                                unset($schedules[$keySecond]);
                                                $hasUnset = true;
                                            }
                                        } else {
                                            unset($schedules[$keySecond]);
                                            $hasUnset = true;
                                        }
                                        /*
                                    
                                        if ( !$hasUnset ) {
                                            $hasUnset = true;
                                            $etd = Carbon::parse($schedule->etd)->diffInDays(now(), false);
                                            if ($etd>=-4) {
                                                unset($schedules[$keySecond]);
                                            }
                                        }*/

                                        if ( !$hasUnset ) {
                                            $hasUnset = true;
                                            $eta = Carbon::parse($schedule->eta)->diffInDays(now(), false);
                                            if ($eta>60) {
                                                unset($schedules[$keySecond]);
                                            }
                                        }
                                    }

                                    if (count($schedules)>0) {
                                        $shipments[$key]['schedules_group'] = json_encode($schedules);

                                        //s
                                        $final_push = false;
                                        if ( count ($queries) > 0 ) {
                                            foreach($queries as $q) {
                                                if (strpos(strval(strtolower($shipments[$key]['mbl_num'])),strval(strtolower($q)))!==false || strpos(strval(strtolower($shipments[$key]['type'])),strval(strtolower($q)))!==false || strpos(strval(strtolower($shipments[$key]['shifl_ref'])),strval(strtolower($q)))!==false) {
                                                    $final_push = true;
                                                }
                                            
                                                if (strpos(strval(strtolower($shipments[$key]['shipment_status'])),strval(strtolower($q)))!==false) {
                                                    $final_push = true;
                                                }
                                            
                                                if (isset($shipments[$key]['containers_group_bookings']) && $shipments[$key]['containers_group_bookings']!=='' && $shipments[$key]['containers_group_bookings']!==null) {
                                                    $containers = json_decode($shipments[$key]['containers_group_bookings']);
                                            
                                                    if (count($containers)>0) {
                                                        foreach($containers as $container) {
                                                            if (strpos(strval(strtolower($container->container_num)),strval(strtolower($q)))!==false){
                                                                $final_push = true;
                                                            }
                                                        }
                                                    }
                                                }
                                            
                                                if (isset($shipments[$key]['suppliers_group_bookings']) && $shipments[$key]['suppliers_group_bookings']!=='' && $shipments[$key]['suppliers_group_bookings']!==null) {
                                                    $suppliers = json_decode($shipments[$key]['suppliers_group_bookings']);
                                            
                                            
                                                    if (count($suppliers)>0) {
                                                        foreach($suppliers as $supplier) {
                                                            if (strpos(strval(strtolower($supplier->ams_num)),strval(strtolower($q)))!==false || strpos(strval(strtolower($supplier->hbl_num)),strval(strtolower($q)))!==false || strpos(strval(strtolower($supplier->po_num)),strval(strtolower($q)))!==false) {
                                                                $final_push = true;
                                                            }
                                                        }
                                                    }
                                                }
                                            
                                                if (isset($shipments[$key]['suppliers_name']) && count($shipments[$key]['suppliers_name'])>0) {
                                                    
                                                    foreach($shipments[$key]['suppliers_name'] as $name) {
                                                        if ( strpos(strval(strtolower($name)), strval($q))!==false) {
                                                            $final_push = true;
                                                        }
                                                    }
                                                }
                                            
                                                if ( isset($shipments[$key]['po_num']) && strpos(strval(strtolower($shipments[$key]['po_num'])), strval($q))!==false) {
                                                    $final_push = true;
                                                }
                                            
                                            }
                                        }

                                        if ( $final_push ) {
                                            array_push($newShipments, $shipments[$key]);    
                                        }
                                        //e
                                        
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


        if (is_null($parameters['per_page'])) {
            return StandardResource::collection((new Collection($newShipments))->paginate(30));
        }
        if (is_numeric($parameters['per_page'])) {
            return StandardResource::collection((new Collection($newShipments))->paginate($parameters['per_page']));
        }
        return abort(404);
    }


    protected function transitSearchTest($parameters, $is_app_test = 0)
    {
        $customers = Auth::user()->customersApi->pluck('id');
        $get_authenticated_user = Auth::user();
        $customers = ( $get_authenticated_user->default_customer_id!==null ) ? [$get_authenticated_user->default_customer_id] : [$customers[0]];

        $shipments = [];
        $isSpecial = false;
        $newShipments = [];
        $order = $parameters['order'];
        $order_by = $parameters['sort'];
        $completed = false;


        $per_page = 20;
        $sort = $order_by;
        
        $shipments = Shipment::whereIn('customer_id', $customers)
        ->where('booking_confirmed', 1)
        ->where('cancelled', 0)
        ->where(function($q) use ($parameters){

            $q->where(function($qq) use ($parameters){

                $query = strval(strtolower($parameters['q']));
                $queries = explode(' ', $query);

                $qq->whereNotNull('eta')
                ->whereNotNull('etd')
                ->where('eta','<>','0000-00-00')
                ->where('etd','<>','0000-00-00')
                ->whereDate('eta', '>', Carbon::now()->subDays(60));
                $qq->where(function($qqqq) {
                    $qqqq->where('status_fallback','No Status');
                    $qqqq->orWhere('status_fallback', 'In transit');
                });

                foreach( $queries as $qry ) {
                    
                    $qq->where('mbl_num','LIKE','%'.$qry.'%')
                    ->orWhere('type','LIKE','%'.$qry.'%')
                    ->orWhere('shifl_ref','LIKE','%'.$qry.'%')
                    ->orWhere('shipment_status',$qry)
                    ->orWhereHas('containers', function($qqq) use ($qry){
                        $qqq->where('container_num','LIKE','%'.$qry.'%');
                    })
                    ->orWhereHas('suppliers', function ($qqq) use ($qry) {
                        $qqq->where('ams_num','LIKE','%'.$qry.'%')
                            ->orWhere('hbl_num','LIKE','%'.$qry.'%')
                            ->orWhere('po_num','LIKE','%'.$qry.'%')
                            ->orWhere('company_name','LIKE','%'.$qry.'%');
                    });
                }
            });
            $q->orWhere(function($qq) use ($parameters) {

                $query = strval(strtolower($parameters['q']));
                $queries = explode(' ', $query);

                $qq->whereNotNull('tracking_eta')
                ->whereNotNull('tracking_etd')
                ->whereDate('tracking_eta', '>', Carbon::now()->subDays(60));
                $qq->whereNotNull('status_fallback');
                $qq->where('status_fallback','<>','No Status');
                $qq->where('status_fallback', '<>','Full Out');
                $qq->where('status_fallback', '<>','Empty In');

                foreach( $queries as $qry ) {
                    
                    $qq->where('mbl_num','LIKE','%'.$qry.'%')
                    ->orWhere('type','LIKE','%'.$qry.'%')
                    ->orWhere('shifl_ref','LIKE','%'.$qry.'%')
                    ->orWhere('shipment_status',$qry)
                    ->orWhereHas('containers', function($qqq) use ($qry){
                        $qqq->where('container_num','LIKE','%'.$qry.'%');
                    })
                    ->orWhereHas('suppliers', function ($qqq) use ($qry) {
                        $qqq->where('ams_num','LIKE','%'.$qry.'%')
                            ->orWhere('hbl_num','LIKE','%'.$qry.'%')
                            ->orWhere('po_num','LIKE','%'.$qry.'%')
                            ->orWhere('company_name','LIKE','%'.$qry.'%');
                    });
                }



            });
        })
        ->orderBy($sort, $order)
        ->paginate($per_page);
        if (count($shipments) > 0) {

            foreach ($shipments as $key => $shipment) {

                $should_push = true;
                //schedule merge
                //merge group fields start
                $findShipment = $shipment;

                //$getCarrier = Carrier::find(intval($findShipment['carrier_id']));
                //$shipments[$key]['carrier'] = (isset($getCarrier->id)) ? $getCarrier : null;
                $shipments[$key]['carrier'] = $findShipment->carrier;
                $shipments[$key]['shipment_metas'] = $findShipment->shipmentMetas;
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
                //

                //tracking status
                $shipments[$key]['tracking_status'] = $findShipment->getTrackingStatus();
                //$shipments[$key]['tracking_status'] = '';

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
                            if ($diff_days<60) {
                                $proceed = true;
                            } else {
                                $proceed = false;
                            }
                        } else {
                            $proceed = false;
                        }

                        //for completed
                        if ($shipments[$key]['eta']!==null && $shipments[$key]['eta']!=='') {

                            if ($diff_days > 60) {
                                $proceed = false;
                                //$proceed = true;
                                //$completed = true;
                                //$shipments[$key]['shipment_status'] = 'Completed';
                            }
                        }

                        if ( $shipments[$key]['mbl_num'] !=='' && $shipments[$key]['mbl_num']!==null && ($shipments[$key]['eta']==null || $shipments[$key]['eta']=='' || $shipments[$key]['etd']==null || $shipments[$key]['etd']=='')) {
                            $proceed = true;
                        }


                    } else {

                        if ($shipments[$key]['status_v2']==='Full Out' || $shipments[$key]['status_v2']==='Empty In') {
                            $proceed = false;

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
                                $proceed = false;
                                //$completed = true;
                                //$shipments[$key]['shipment_status'] = 'Completed';
                            } else {
                                $proceed = true;
                            }

                        }


                    }
                    

                }
                //e
            }
        }

        return response()->json([
            'data' => $shipments->items(),
            'links' => [
                'prev' => $shipments->previousPageUrl(),
                'next' => $shipments->nextPageUrl()
            ],
            'meta' => [
                'current_page' => $shipments->currentPage(),
                'from' => $shipments->firstItem(),
                'last_page' => $shipments->lastPage(),
                'path' => $shipments->path(),
                'per_page' => $shipments->perPage(),
                'to' => $shipments->lastItem(),
                'total' =>$shipments->total(),
            ]
        ]);
        return abort(404);
    }

    protected function transitSearch($parameters, $is_app_test = 0)
    {
        $customers = Auth::user()->customersApi->pluck('id');
        $get_authenticated_user = Auth::user();
        $customers = ( $get_authenticated_user->default_customer_id!==null ) ? [$get_authenticated_user->default_customer_id] : [$customers[0]];

        $shipments = [];
        $isSpecial = false;
        $newShipments = [];
        $order = $parameters['order'];
        $order_by = $parameters['sort'];
        $completed = false;

        if (count($customers) > 0) {

            $shipments = Shipment::whereHas('customer', function($q) use ($customers){
                $q->whereIn('id', $customers);
            })
            ->where('cancelled', 0)
            //->orderBy($parameters['sort'],$parameters['order'])
            ->get();

            $order_by = $parameters['sort'];
            $order = $parameters['order'];


            $query = strval(strtolower($parameters['q']));

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
                    //$shipments[$key]['status_v2'] = null;

                    //
                    // check if tracking shipments
                    $isSpecial = false;
                    $this->processTrackingShipments($findShipment, $shipments, $key, $this, $isSpecial);
                    //
                    
                    if ($shipments[$key]['booking_confirmed']==0) {
                        $should_push = false;
                    }
                    
                    if ($should_push) {
                        
                        // current day minus eta
                        $diff_days = Carbon::parse($shipments[$key]['eta'])->diffInDays(now(), false);
                        $shipments[$key]['diff_days'] = $diff_days;
                        $proceed = false;
                        $shipments[$key]['shipment_status'] = 'Shipments';
                        
                        if ($shipments[$key]['status_v2']==null || $shipments[$key]['status_v2'] === 'No Status' || $shipments[$key]['status_v2'] === 'In transit') {

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

                                if ($diff_days<60) {
                                    $proceed = true;
                                } else {
                                    if ( $is_app_test === 1 ) {
                                        $proceed = true;
                                        $completed = true;
                                         $shipments[$key]['shipment_status'] = 'Completed';
                                    } else {
                                        $proceed = false;    
                                    }   
                                }
                                
                            } else {
                                //$proceed = true;
                                $proceed = false;
                            }
                            
                            //if tracking, process schedule with different logic
                            $tracking_status = $shipments[$key]->getTrackingStatus();

                            //set valid tracking labels
                            $validTrackings = ['Auto Tracking', 'Auto Tracked', 'Manual Tracking', 'Manual Tracked'];

                            if ( in_array($tracking_status, $validTrackings) ) {
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

                                if ( $is_app_test === 1 && $diff_days > 60) {
                                    $proceed = true;
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
                            
                            if ( $shipments[$key]['mbl_num'] !=='' && $shipments[$key]['mbl_num']!==null && ($shipments[$key]['eta']==null || $shipments[$key]['eta']=='' || $shipments[$key]['etd']==null || $shipments[$key]['etd']=='')) {
                                $proceed = true;
                            }
                            
                        } else {

                            if ($shipments[$key]['status_v2']==='Full Out' || $shipments[$key]['status_v2']==='Empty In') {
                                
                                $proceed = ( $is_app_test === 0) ? false : true;
                                $completed = true;
                                $shipments[$key]['shipment_status'] = 'Completed';
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

                                if ( $is_app_test === 1 && $diff_days > 60) {
                                    $proceed = true;
                                    $shipments[$key]['shipment_status'] = 'Completed';
                                } else {
                                    if ( $shipments[$key]['eta']===null || $shipments[$key]['etd']==null ) {
                                        $proceed = true;
                                        //$proceed = false;    
                                    } else {
                                        $proceed = true;
                                    }
                                }

                                //$proceed = true;

                                //if (strpos($shipments[$key]['status_v2'], 'In transit')) {
                                 //   $shipments[$key]['shipment_status'] = 'In Transit';
                               // }                                
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
                                        
                                        if ( !$hasUnset ) {
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
                                        $final_push = false;
                                        //s
                                        //allow whitespace searching
                                        $queries = explode(' ', $query);

                                        if ( count ($queries) > 0 ) {
                                            foreach($queries as $q) {
                                                if (strpos(strval(strtolower($shipments[$key]['mbl_num'])),strval(strtolower($q)))!==false || strpos(strval(strtolower($shipments[$key]['type'])),strval(strtolower($q)))!==false || strpos(strval(strtolower($shipments[$key]['shifl_ref'])),strval(strtolower($q)))!==false) {
                                                    $final_push = true;
                                                }
                                            
                                                if (strpos(strval(strtolower($shipments[$key]['shipment_status'])),strval(strtolower($q)))!==false) {
                                                    $final_push = true;
                                                }
                                            
                                                if (isset($shipments[$key]['containers_group_bookings']) && $shipments[$key]['containers_group_bookings']!=='' && $shipments[$key]['containers_group_bookings']!==null) {
                                                    $containers = json_decode($shipments[$key]['containers_group_bookings']);
                                            
                                                    if (count($containers)>0) {
                                                        foreach($containers as $container) {
                                                            if (strpos(strval(strtolower($container->container_num)),strval(strtolower($q)))!==false){
                                                                $final_push = true;
                                                            }
                                                        }
                                                    }
                                                }
                                            
                                                if (isset($shipments[$key]['suppliers_group_bookings']) && $shipments[$key]['suppliers_group_bookings']!=='' && $shipments[$key]['suppliers_group_bookings']!==null) {
                                                    $suppliers = json_decode($shipments[$key]['suppliers_group_bookings']);
                                            
                                            
                                                    if (count($suppliers)>0) {
                                                        foreach($suppliers as $supplier) {
                                                            if (strpos(strval(strtolower($supplier->ams_num)),strval(strtolower($q)))!==false || strpos(strval(strtolower($supplier->hbl_num)),strval(strtolower($q)))!==false || strpos(strval(strtolower($supplier->po_num)),strval(strtolower($q)))!==false) {
                                                                $final_push = true;
                                                            }
                                                        }
                                                    }
                                                }
                                            
                                                if (isset($shipments[$key]['suppliers_name']) && count($shipments[$key]['suppliers_name'])>0) {
                                                    
                                                    foreach($shipments[$key]['suppliers_name'] as $name) {
                                                        if ( strpos(strval(strtolower($name)), strval($q))!==false) {
                                                            $final_push = true;
                                                        }
                                                    }
                                                }
                                            
                                                if ( isset($shipments[$key]['po_num']) && strpos(strval(strtolower($shipments[$key]['po_num'])), strval($q))!==false) {
                                                    $final_push = true;
                                                }
                                            
                                            }
                                        }

                                        
                                        //e
                                        if ( $final_push )
                                            array_push($newShipments, $shipments[$key]);
                                    }
                                }


                            } else {
                                if ($isSpecial) {

                                    //s
                                    $final_push = false;
                                    $check_external_containers = (isset($shipments[$key]->terminal_fortynine) && isset($shipments[$key]->terminal_fortynine->containers)) ? json_decode($shipments[$key]->terminal_fortynine->containers, true) : [];

                                    if ( count($check_external_containers) > 0) {
                                        if (count($check_external_containers)>0) {
                                            foreach($check_external_containers as $container) {
                                                if (strpos(strval(strtolower($container['attributes']['number'])),strval(strtolower($query)))!==false){
                                                    $final_push = true;
                                                }
                                            }
                                        }
                                    }

                                    $shipments[$key]['external_containers'] = $check_external_containers;
                                    
                                    if (strpos(strval(strtolower($shipments[$key]['mbl_num'])),strval(strtolower($query)))!==false || strpos(strval(strtolower($shipments[$key]['type'])),strval(strtolower($query)))!==false || strpos(strval(strtolower($shipments[$key]['shifl_ref'])),strval(strtolower($query)))!==false) {
                                        $final_push = true;
                                    }


                                    if (strpos(strval(strtolower($shipments[$key]['shipment_status'])),strval(strtolower($query)))!==false) {
                                        $final_push = true;
                                    }
                                    

                                    if (isset($shipments[$key]['containers_group_bookings']) && $shipments[$key]['containers_group_bookings']!=='' && $shipments[$key]['containers_group_bookings']!==null) {
                                        $containers = json_decode($shipments[$key]['containers_group_bookings']);

                                        if (count($containers)>0) {
                                            foreach($containers as $container) {
                                                if (strpos(strval(strtolower($container->container_num)),strval(strtolower($query)))!==false) {
                                                    $final_push = true;
                                                }
                                            }
                                        }
                                    }

                                    if (isset($shipments[$key]['suppliers_group_bookings']) && $shipments[$key]['suppliers_group_bookings']!=='' && $shipments[$key]['suppliers_group_bookings']!==null) {
                                        $suppliers = json_decode($shipments[$key]['suppliers_group_bookings']);

                                        if (count($suppliers)>0) {
                                            foreach($suppliers as $supplier) {
                                                if (strpos(strval(strtolower($supplier->ams_num)),strval(strtolower($query)))!==false || strpos(strval(strtolower($supplier->hbl_num)),strval(strtolower($query)))!==false || strpos(strval(strtolower($supplier->po_num)),strval(strtolower($query)))!==false) {
                                                    $final_push = true;
                                                }
                                            }
                                        }
                                    }

                                    if (isset($shipments[$key]['suppliers_name']) && count($shipments[$key]['suppliers_name'])>0) {
                                            
                                        foreach($shipments[$key]['suppliers_name'] as $name) {
                                            if ( strpos(strval(strtolower($name)), strval($query))!==false) {
                                                $final_push = true;
                                            }
                                        }
                                    }

                                    if ( isset($shipments[$key]['po_num']) && strpos(strval(strtolower($shipments[$key]['po_num'])), strval($query))!==false) {
                                        $final_push = true;
                                    }
                                    //e
                                    if ($final_push) {
                                        array_push($newShipments, $shipments[$key]);    
                                    }
                                    
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

        if (is_null($parameters['per_page'])) {
            return StandardResource::collection((new Collection($newShipments))->paginate(30));
        }
        if (is_numeric($parameters['per_page'])) {
            return StandardResource::collection((new Collection($newShipments))->paginate($parameters['per_page']));
        }
        return abort(404);
    }
}