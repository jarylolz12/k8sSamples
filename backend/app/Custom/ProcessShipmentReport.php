<?php

namespace App\Custom;

use Carbon\Carbon;

use App\Shipment;
use App\Terminal49Shipment;

class ProcessShipmentReport 
{

    public function isPassed3Days($date)
    {
        $days = 0;
        if(isset($date)){
            $days = \Carbon\Carbon::parse($date)->diffInDays(now(), false);
        }
        return $days > 2 ? true : false;
    }

    public function getContainersGroupUntracked($shipment, $con_grp)
    {
        $cgu = [];
        $untracked = json_decode($shipment->containers_group_untracked,true) ?? [];        
        if(isset($untracked['containers'])){
            foreach ($untracked['containers'] as $con) {
                    $num = $con['container_num'];   
                    $cgu[$num] = [
                        'full_out' => $con['pod_full_out_at'] ? Carbon::parse($con['pod_full_out_at'])->format("m-d-Y") : '',
                        'empty_terminated_at' => $con['pod_empty_returned_at'] ? Carbon::parse($con['pod_empty_returned_at'])->format("m-d-Y") : '',
                        'empty_out' => $con['empty_out'] ? Carbon::parse($con['empty_out'])->format("m-d-Y") : '',
                        'full_in' => $con['pod_full_in_at'] ? Carbon::parse($con['pod_full_in_at'])->format("m-d-Y") : '',
                        'pickup_lfd' => isset($con['pickup_lfd']) ? Carbon::parse($con['pickup_lfd'])->format("m-d-Y") : " ",
                        'container_num' => $num,
                        'seal_num' => (count($con_grp) > 0 && isset($con_grp[$num]['seal_num'])) ? $con_grp[$num]['seal_num'] :  '',     
                        'kg' => (count($con_grp) > 0 && isset($con_grp[$num]['kg'])) ? $con_grp[$num]['kg'] : '' ,     
                        'cbm' => (count($con_grp) > 0 && isset($con_grp[$num]['cbm'])) ? $con_grp[$num]['cbm'] : '',
                        'cartons' => (count($con_grp) > 0 && isset($con_grp[$num]['cartons'])) ? $con_grp[$num]['cartons'] : '',
                        'size' => (count($con_grp) > 0 && isset($con_grp[$num]['size'])) ? $con_grp[$num]['size'] : '',
                    ];
            }
        }
        return $cgu;
    }

    public function getShipmentContainerAndT49($shipment)
    {
        
        $shipment_t49 = Terminal49Shipment::find($shipment->mbl_num);
        $t49_con = [];
        $aResult['attributes'] = [];
        $untracked = json_decode($shipment->containers_group_untracked,true) ?? [];        
        $container_group = json_decode($shipment->containers_group,true) ?? [];
        $con_grp = [];
        if(is_countable($container_group) && count($container_group) > 0){
            foreach ($container_group as $container){
                if(isset($container['container_num']) && $container['container_num'] != null){
                    $con_grp[$container['container_num']] = $container;
                    $con_grp[$container['container_num']]['cartons'] = $container['cartons'] > 0 ? $container['cartons'] : '';
                    if(isset($container['size']) && !empty($container['size'])){
                        $con_grp[$container['container_num']]['size'] = \App\ContainerSize::find($container['size'])->name ?? '';
                    }
                    
                } 
            }    

            $allContainers = $con_grp;
           
        } 
        
        
        if($shipment_t49) {
            $containers = json_decode($shipment_t49->containers, true);
            $attributes = json_decode($shipment_t49->attributes,true);
            $aResult['attributes'] = $attributes;

            if (!empty($containers)) {
                foreach ($containers as $key => $con) {
                    $num = $con['attributes']['number'];

                    
                    $kg = (count($con_grp) > 0 && isset($con_grp[$num]['kg'])) ? $con_grp[$num]['kg'] : '' ;
                    if(empty($kg)){
                        $kg = $con['attributes']['weight_in_lbs'] ? ($con['attributes']['weight_in_lbs']/2.2046) : '';
                    }
                    if(isset($con['attributes']['available_for_pickup'])){
                        $available_for_pickup =   $con['attributes']['available_for_pickup'] == false ? 'No' : 'Yes';
                    }

                    $t49_con[$num]  = [
                        'available_for_pickup' => $available_for_pickup ?? '',
                        'vessel_discharged' => empty($con['attributes']['pod_discharged_at']) ? '' : Carbon::parse($con['attributes']['pod_discharged_at'])->format("m-d-Y"),
                        'pickup_lfd' => isset($con['attributes']['pickup_lfd']) ? Carbon::parse($con['attributes']['pickup_lfd'])->format("m-d-Y") : '',
                        'full_out' => empty($con['attributes']['pod_full_out_at']) ? '' : Carbon::parse($con['attributes']['pod_full_out_at'])->format("m-d-Y"),
                        'empty_terminated_at' => empty($con['attributes']['empty_terminated_at']) ? '' : Carbon::parse($con['attributes']['empty_terminated_at'])->format("m-d-Y"),
                        'container_num' => $num ?? '',
                        'seal_num' => (count($con_grp) > 0 && isset($con_grp[$num]['seal_num'])) ? $con_grp[$num]['seal_num'] : $con['attributes']['seal_number'] ?? '',     
                        'size' =>  $this->getHeight($con['attributes']['equipment_height'], $con['attributes']['equipment_length']),
                        'kg' => !empty($kg) ? number_format((float)$kg, 2, '.', '') : '',
                        'cbm' => (count($con_grp) > 0 && isset($con_grp[$num]['cbm'])) ? $con_grp[$num]['cbm'] : '',
                        'cartons' => (count($con_grp) > 0 && isset($con_grp[$num]['cartons'])) ? $con_grp[$num]['cartons'] : ''
                    ];

                    $t49_con[$num]['empty_in'] = $t49_con[$num]['empty_terminated_at'] ?? '';
                    
                    //transport_events
                    if(isset($con['id']) && $con['id'] != null){
                        $events = json_decode($shipment_t49->transport_events, true);
                        if (!empty($events) && count($events) > 0) {
                            if (isset($events[$con['id']]['data'])) {
                                foreach ($events[$con['id']]['data'] as $k) {
                                    if (isset($k['attributes']['event'])) {

                                        $event_name = str_replace("container.transport.", "", $k['attributes']['event']);
                                        if($event_name == 'full_in' || $event_name == 'empty_out' ||  $event_name == 'empty_in' || $event_name == 'full_out' ||
                                            $event_name == 'vessel_discharged'){
                                            $date = !empty($k['attributes']['timestamp']) ? Carbon::parse($k['attributes']['timestamp'])->format("m-d-Y") : '';
                                            $t49_con[$num][$event_name] = empty($t49_con[$num][$event_name]) ? $date : $t49_con[$num][$event_name];

                                        }
                                        
                                    }    
                                }    
                            }    
                        }    
                        
                    }

                }//endforeach
               
            }
            $allContainers = $t49_con;

        }elseif(is_countable($untracked) && count($untracked) > 0) {
              //Manual Tracking
            $allContainers = $this->getContainersGroupUntracked($shipment, $con_grp);
       
        }


        $aResult['attributes'] = $attributes ?? [];
        $aResult['all_containers'] = $allContainers ?? [];

        return $aResult;
       
    } 

    public function getShipmentSupplier($shipment)
    {
        $aResult['supplier'] = [];
        $aResult['status'] = [];
        $aResult['cargo_date'] = [];
        $aResult['hbl_num'] = [];
        $aResult['supplier_carton'] = '';
        $supplier_carton = 0;

        $suppliers_groups = [];
        $supplier_group = json_decode($shipment->suppliers_group) ?? [];

        if(is_countable($supplier_group) && count($supplier_group) > 0){
            $suppliers_groups = $supplier_group;
        }else{
            $suppliers_groups = json_decode($shipment->suppliers_group_bookings) ?? [];
        } 

        if(is_countable($suppliers_groups) && count($suppliers_groups) > 0){
            foreach ($suppliers_groups as $supplier){
            
                if (isset($supplier->bl_status) &&  !empty($supplier->bl_status)){
                    array_push($aResult['status'], $supplier->bl_status);
                }
    
                if (isset($supplier->cargo_ready_date) &&  !empty($supplier->cargo_ready_date)){
                    array_push($aResult['cargo_date'], Carbon::parse($supplier->cargo_ready_date)->format("m-d-Y"));
                }
    
                if (isset($supplier->hbl_num) &&  !empty($supplier->hbl_num)){
                    array_push($aResult['hbl_num'], $supplier->hbl_num);
                }
    
                if (isset($supplier->total_cartons) &&  !empty($supplier->total_cartons)){
                    $supplier_carton += filter_var($supplier->total_cartons, FILTER_SANITIZE_NUMBER_INT);
                    $aResult['supplier_carton'] = $supplier_carton;
                }    
    
                if (isset($supplier->supplier_id) &&  !empty($supplier->supplier_id)){
                    $company_name = \App\Supplier::find($supplier->supplier_id)->company_name ?? '';
                    array_push($aResult['supplier'], $company_name);
                }
    
            }

        }
        
        return $aResult;
    }

    public function getHeight($type, $length)
    {
        
        if ($type == 'standard') {
            $size = $length ? $length . "'GP" : '';
        } else {
            $size = $length ? $length . "'HQ" : '';
        }

        return $size;
    }


    public function getTruckingShipmentByMBLAndCon($mbl, $con)
    {

        $shipments = \DB::connection('mysql-trucking')
            ->table('shipments')
            ->where('shipments.cancelled', 0)
            ->where('mbl_num', $mbl)
            ->whereIn('trucker_container', $con)
            ->join('dispatches', 'dispatches.shipment_id', '=', 'shipments.id')
            ->join('dispatch_legs', 'dispatch_legs.dispatch_id', '=', 'dispatches.id')
            ->join('terminals', 'terminals.id', '=', 'dispatches.terminal_id')
            ->join('customers', 'customers.id', '=', 'shipments.customer_id')
            ->select('shipments.*', 'dispatch_legs.*', 'shipments.id as shipments_id', 'dispatch_legs.id as dispatch_legs_id',
                'dispatch_legs.type as dispatch_legs_type', 'dispatch_legs.completed as dispatch_legs_completed', 'terminals.name as terminal')
            ->get();      
            

        $aResult = [];
        if($shipments){
            $is_prepull = null;
            $port_wait_time = null;
            $first_leg_to_customer = null;
            $first_unarrived_leg = null;
            $first_leg_to_empty_return = null;
            $chassis_days = null;
            $first_leg = null;
            $last_arrived_leg = null;
            $storage_days = null;
            $last_leg = null;
            $wait_time_in_seconds = null;
            $wait_time = null;
            $empty_pickup_date = null;
            $empty_pickup_date_check = true;

            foreach($shipments as $shipment){
               
                if($shipment->is_first_leg){
                    $first_leg = $shipment;
                    if($shipment->arrival_at_pickup_location_date_time){
                        $last_arrived_leg = $shipment;
                    }

                    if($shipment->dispatch_legs_completed){
                        $is_prepull = $shipment->dispatch_legs_type != 'Customer';
                    }

                    if($shipment->pickup_scheduled_date_time){
                        if($shipment->departure_from_pickup_location_date_time){
                            $port_wait_time = Carbon::parse($shipment->departure_from_pickup_location_date_time)
                                ->diffForHumans(Carbon::parse($shipment->pickup_scheduled_date_time),[ 'join' => ', ', 'parts' => 7, 
                                'syntax' => \Carbon\CarbonInterface::DIFF_ABSOLUTE]);
                        }else if($shipment->arrival_at_pickup_location_date_time){
                            $port_wait_time = Carbon::parse($shipment->arrival_at_pickup_location_date_time)
                                ->diffForHumans(Carbon::parse($shipment->pickup_scheduled_date_time),[ 'join' => ', ', 'parts' => 7, 
                                'syntax' => \Carbon\CarbonInterface::DIFF_ABSOLUTE]);
                        }
                    }
                }else if($shipment->arrival_at_pickup_location_date_time){
                    $last_arrived_leg = $shipment;
                }

                if($empty_pickup_date_check && $first_leg_to_customer){
                    $empty_pickup_date_check = false;
                    $empty_pickup_date = $shipment->arrival_at_pickup_location_date_time ?? $shipment->arrival_at_delivery_location_date_time;
                }

                if($shipment->dispatch_legs_type == 'Customer'){
                    if(empty($first_leg_to_customer)){
                        $first_leg_to_customer = $shipment;
                    }

                    if($shipment->arrival_at_delivery_location_date_time){
                        if($shipment->departure_from_delivery_location_date_time){
                            $wait_time_in_seconds += Carbon::parse($shipment->departure_from_delivery_location_date_time)
                                ->diffInSeconds(Carbon::parse($shipment->arrival_at_delivery_location_date_time));
                        }
                    }
                }

                if(empty($first_leg_to_empty_return) && $shipment->dispatch_legs_type == 'Empty Return'){
                    $first_leg_to_empty_return = $shipment;
                }

                if(empty($first_unarrived_leg) && !$shipment->completed){
                    $first_unarrived_leg = $shipment;
                }

                if($last_leg && $last_leg->dispatch_legs_type == 'Yard'){
                    if($last_leg->arrival_at_delivery_location_date_time && $shipment->arrival_at_pickup_location_date_time){
                        $sd = Carbon::parse($shipment->arrival_at_pickup_location_date_time)->startOfDay()->subDay()
                            ->diffInDays(Carbon::parse($last_leg->arrival_at_delivery_location_date_time)->startOfDay());
                        if($storage_days){
                            $storage_days += $sd;
                        }else{
                            $storage_days = $sd;
                        }
                    }
                }

                $last_leg = $shipment;
               
                
                if($first_leg && $first_leg->arrival_at_pickup_location_date_time){
                    if($last_arrived_leg && $last_arrived_leg->arrival_at_pickup_location_date_time){
                        $chassis_days = Carbon::parse($last_arrived_leg->arrival_at_pickup_location_date_time)
                            ->startOfDay()->subDay()->diffInDays(Carbon::parse($first_leg->arrival_at_delivery_location_date_time)->startOfDay());
                    }
                }

                if($wait_time_in_seconds){
                    $wait_time = \Carbon\CarbonInterval::seconds($wait_time_in_seconds)->cascade()->forHumans();
                }


                $mbl = $shipment->mbl_num ?? '';    
                $num = $shipment->trucker_container ?? '';

                $aResult[$mbl][$num] = [
                    'shipment_id' => $shipment->shipments_id,
                    'customer_ref_num' => $shipment->customer_po,
                    'mbl_num' => $mbl,
                    'container_num' => $num,
                    'location_at' => $shipment->terminal ?? '',
                    'deliver_to' => $shipment->deliver_to ?? '',
                    'pre_gate_fees' => $shipment->pier_pass_paid ? 'Paid' : 'Not Yet Paid',
                    'pickup_scheduled' => empty($first_unarrived_leg) ? '' : $first_unarrived_leg->pickup_scheduled_date_time ?? '',
                    'pickup_date' => empty($first_unarrived_leg) ? '' : $first_unarrived_leg->arrival_at_pickup_location_date_time ?? '',
                    'prepull' => is_null($is_prepull) ? '' : ($is_prepull ? 'Yes' : 'No'),
                    'port_wait_time' => $port_wait_time ?? '',
                    'scheduled_delivery' => empty($first_leg_to_customer) ? '' : $first_leg_to_customer->delivery_scheduled_date_time ?? '',
                    'mode' => empty($first_leg_to_customer) ? '' : ($first_leg_to_customer->mode == 'Dropped' ? 'Dropped' : $first_leg_to_customer->mode  ?? '' ),
                    'delivered' => empty($first_leg_to_customer) ? '' : $first_leg_to_customer->arrival_at_delivery_location_date_time ?? 'Not Yet',
                    'container_empty' => $shipment->container_empty ? $shipment->container_empty_date ?? '' : 'Not yet Notified',
                    'container_empty_date' => $shipment->container_empty_date ?? '',
                    'wait_time' => $wait_time ?? '',
                    'empty_pickup_date' => $empty_pickup_date ?? '',
                    'return_empty' => empty($first_leg_to_empty_return) ? '' : $first_leg_to_empty_return->arrival_at_delivery_location_date_time ?? '',
                    'per_diem_date' => $shipment->per_diem_date ?? '',
                    'chassis_days' => $chassis_days ?? '',
                    'storage_days' =>  $storage_days ?? ''
    
                ];

            }
        }    

        return $aResult;
           
    }

    public function getContainersGroupBookings($shipment)
    {
        $containers = json_decode($shipment->containers_group_bookings) ?? [];
        $containerData = [];
        if(is_countable($containers) && count($containers) > 0){
            foreach($containers as $con){
                if(isset($con->size)){
                    $size = \App\ContainerSize::select('name')->where('id', $con->size)->first();
                }
                $containerData[$con->container_num] = [
                    'container_num' => $con->container_num ?? '',
                    'seal_num' => $con->seal_num ?? '',
                    'cbm' => $con->cbm ?? '',
                    'kg' => $con->kg ?? '',
                    'size' => $size->name ?? '',
                    'cartons' => $con->cartons ?? ''
                ];
            }
        }
        return $containerData;    
    }
}