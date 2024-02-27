<?php

namespace App\Observers;

use App\Shipment;
use Illuminate\Support\Facades\DB;
use App\Custom\AddContainerToCrux;
use App\Custom\SendEtaChangeAlert;
use App\Custom\AddShipmentToTerminal49;
use App;
use Exception;
use App\Terminal49Shipment;
use App\Custom\ProcessShipmentReport;
use Illuminate\Support\Arr;

class ShipmentObserver
{

    /**
     * Handle the shipment "creating" event.
     *
     * @param  \App\Shipment  $shipment
     * @return void
     */

    public function creating(Shipment $shipment)
    {
        // \Log::info($shipment);
        // \Log::info("from creating");
    }



    /**
     * Handle the shipment "created" event.
     *
     * @param  \App\Shipment  $shipment
     * @return void
     */
    public function created(Shipment $shipment)
    {
        $shipment->shifl_ref = 700000 + $shipment->id;
	       $shipment->shipment_status = 'Pending Approval';
        // DB::table('shipments')->where('id', '=', $shipment->id)->update(['shifl_ref' => (700000+$shipment->id)]);
        if ($shipment->cloned_from) {
            $suppliers = json_decode($shipment->suppliers_group_bookings);
            foreach ($suppliers as $supplier) {
                $supplier->hbl_copy = null;
                $supplier->packing_list = null;
                $supplier->commercial_documents = null;
                $supplier->commercial_invoice = null;
            }
            $shipment->suppliers_group_bookings = json_encode($suppliers);

            $schedules = json_decode($shipment->schedules_group_bookings ?? '[]');
            foreach ($schedules ?? [] as $key => $schedule) {
                // code...
                $schedule->is_confirmed = false;
            }
            $shipment->schedules_group_bookings = json_encode($schedules);
        }
        if($shipment->is_tracking_shipment && $shipment->is_shipment_tracking_create !== 1){
            $shipment->booking_confirmed = 1;
            $shipment->schedules_group_bookings = $this->getDummySchedule();
        }
        $shipment->save();

        //start saving container_data
        $processShipment = new ProcessShipmentReport();
        $containerData = [];
        $containerData = $processShipment->getShipmentContainerAndT49($shipment);
        $containerData = $containerData['all_containers'] ?? [];
        if(is_countable($containerData) && count($containerData) == 0){
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
                $shipment->containers_data = json_encode($containerData);
            }
        }

        \DB::table('shipments')->where('id', $shipment->id)
            ->update([
                'containers_data' => json_encode($containerData)
            ]);
        //end saving containers_data

        ////// UPDATE INDEX RATE //////
//          $amount_sells = DB::table('shipment_schedules_sellrates')->where('shipment_id',$shipment->id)->get()->map(function($item){
//             return (float)$item->amount * (int)$item->qty;
//         })->reduce(function($a,$b){
//             return $a+$b;
//         },0);

//         $amount_buys = DB::table('shipment_schedules_buyrates')->where('shipment_id',$shipment->id)->get()->map(function($item){
//             return (float)$item->amount * (int)$item->qty;
//         })->reduce(function($a,$b){
//             return $a+$b;
//         },0);

//         $location_from  = DB::table('shifl_offices')->select('name')->where('id',$shipment->shifl_office_origin_from_id)->first();
//         $location_to = DB::table('shifl_offices')->select('name')->where('id',$shipment->shifl_office_origin_to_id)->first();
//         $terminal  = DB::table('terminal_regions')->select('name')->where('id',$shipment->terminal_id)->first();

//         $location_from  = $location_from ? $location_from->name : '';
//         $location_to  = $location_to ? $location_to->name : '';
//         $terminal  = $terminal ? $terminal->name : '';

//         // INSERT TO INDEX RATE TABLE
//         DB::table('index_rates')->insert([
//             'data_date' => $shipment->etd,
//             'location_from' => $location_from,
//             'location_to' => $location_to,
//             'terminal' => $terminal,
//             'amount' => (float)$amount_sells + (float)$amount_buys
//         ]);
    }

    /**
     * Handle the shipment "updated" event.
     *
     * @param  \App\Shipment  $shipment
     * @return void
     */
    public function updated(Shipment $shipment)
    {
        $status =  $shipment->getStatusV2();
        $containers_information = $shipment->getContainersInformation();
        $tracking_eta = null;
        $tracking_etd = null;

        if(!empty($shipment->mbl_num) || isset($shipment->terminal_fortynine) ){
            $terminal49_shipment = $shipment->terminal_fortynine;
            if ( isset($terminal49_shipment) && $terminal49_shipment!=null && isset ($terminal49_shipment->attributes) ) {
                $attributes = json_decode($terminal49_shipment->attributes, true);
                $tracking_eta = $this->ifNull($attributes['pod_eta_at'],$attributes['pod_ata_at']);
                $tracking_etd = $this->ifNull($attributes['pol_etd_at'],$attributes['pol_atd_at']);
            }
        }

        //start update containers_data field
        $processShipment = new ProcessShipmentReport();
        $container_grp = $processShipment->getShipmentContainerAndT49($shipment);
        $containers = $container_grp['all_containers'] ?? [];
        $container_num = Arr::pluck($containers, 'container_num') ?? [];
        $tracking_info =  $processShipment->getTruckingShipmentByMBLAndCon($shipment->mbl_num, $container_num);




        $containers_count = (isset($containers_information['containers_count'])) ? $containers_information['containers_count'] : 0;

        $containers_return_count = (isset($containers_information['containers_return_count'])) ? $containers_information['containers_return_count'] : 0;

        $containers_array =  (isset($containers_information['containers_array'])) ? $containers_information['containers_array'] : [];

        $full_out_array = [];
        if ( is_countable($containers_array) ) {
            foreach ($containers_array as $container ) {
                if ( isset($container['pod_full_out_at'])) {
                    array_push($full_out_array, $container['pod_full_out_at']);
                }
            }
        }

        //endstart update containers_data field
        \DB::table('shipments')->where('id', $shipment->id)
        ->update([
            'status_fallback' => $status,
            'is_tracking_processing' => 1,
            'is_processing' => 1,
            'has_check_docs' => 0,
            'tracking_eta' => $tracking_eta,
            'tracking_etd' => $tracking_etd,
            'containers_data' => json_encode($containers) ?? [],

            'tracking_info' => json_encode($tracking_info) ?? [],
            'containers_count' => $containers_count,
            'containers_return_count' => $containers_return_count,
            'containers_array' => json_encode($containers_array),
            'full_out_at_array' => json_encode($full_out_array)
        ]);
    }

    private function ifNull($firstValue, $secondValue, $nullValue = null){
        return (empty($firstValue) ? (empty($secondValue) ? $nullValue : $secondValue) : $firstValue);
    }


    /**
     * Handle the shipment "Updating" event.
     *
     * @param  \App\Shipment  $shipment
     * @return void
     */
    public function updating(Shipment $shipment)
    {

        // show a error if shifl_ref field null
        throw_if(
            empty(trim($shipment->shifl_ref)),
            Exception::class,
            'Shifl Reference field is required'
        );

        throw_if(
            empty(trim($shipment->customer_id)),
            Exception::class,
            'Customer field is required'
        );

        // if ($shipment->isDirty("schedules_group")) {
        //     if (!empty(trim($shipment->mbl_num))) {
        //         SendEtaChangeAlert::build($shipment);
        //     }
        // }

        if ($shipment->isDirty("schedules_group_bookings")) {
            if (!empty(trim($shipment->mbl_num))) {
                SendEtaChangeAlert::build($shipment);
            }
        }


        // \Log::info("from updating");
        //Mail::to(["anthony@shifl.com"])->cc(['shabsie@shifl.com'])->send(new Test("Updating", "mails.test_crux_webhook"));
        //if (!App::environment(['staging'])) {
        // \Log::info("App on Production");
        // The environment is neither local OR staging...
        //if (env("ADD_CONTAINER_TO_CRUX", false)) {
        // \Log::info("Add container to Crux Feature is On");
        // if ($shipment->isDirty("containers_group")) {
        //     // run crux functionality
        //     AddContainerToCrux::build($shipment);
        //     //Mail::to(["anthony@shifl.com"])->cc(['shabsie@shifl.com'])->send(new Test("Updating", "mails.test_crux_webhook"));
        // }
        //}
        //}

        // only proccess if the value is true. so that we can turn of the feature on localhost or any other required platform.
        //if (env("ADD_CONTAINER_TO_TERMINAL49", false)) {
        $key = config('terminal49.terminal49key');
        /* \Log::info($key); */
        $on = config('terminal49.addtoterminal49');
        if ($on != 'no') {
            // send tracking request to terminal49
            if ($shipment->isDirty("mbl_num") && (is_null($shipment->type) || $shipment->type != 'Air')) {//&& $shipment->type != "Air"
                AddShipmentToTerminal49::build($shipment);
            }
        } else {
            \Log::info("ADD_CONTAINER_TO_TERMINAL49 env variable value is either false or not found");
        }

        if($shipment->isDirty('tracked_up_to')){
            $shipment->mt_expected_to_be_addressed = $shipment->getOriginal('tracked_up_to');
        }

        if($shipment->isDirty("mbl_num")){
            if($shipment->mbl_num){
                if(Terminal49Shipment::where("mbl_num", $shipment->mbl_num)->exists()){
                    $shipment->should_manually_track = 0;
                }else {
                    $shipment->should_manually_track = 1;
                }
            }else {
                $shipment->should_manually_track = 1;
            }
        }

        if($shipment->isDirty("containers_group_untracked")){
            $containers_group_untracked = json_decode($shipment->containers_group_untracked ?? '[]');
            $is_mt_past_lfd_not_released = 0;
            foreach ($containers_group_untracked->containers ?? [] as $key => $container) {
                // code...
                $lfd = $container->pickup_lfd;
                if (empty($lfd)) {
                    continue;
                }
                $lfd = \Carbon\Carbon::parse($lfd);
                if (now()->gt($lfd)) {
                    $passed_lfd = true;
                }

                if(isset($passed_lfd) && empty($container->pod_empty_returned_at ?? $container->pod_full_out_at)){
                    $is_mt_past_lfd_not_released = 1;
                    break;
                }
            }
            $shipment->is_mt_past_lfd_not_released = $is_mt_past_lfd_not_released;
        }
    }

    /**
     * Handle the shipment "deleted" event.
     *
     * @param  \App\Shipment  $shipment
     * @return void
     */
    public function deleted(Shipment $shipment)
    {
        //
    }

    /**
     * Handle the shipment "restored" event.
     *
     * @param  \App\Shipment  $shipment
     * @return void
     */

    public function restored(Shipment $shipment)
    {
        //
    }

    /**
     * Handle the shipment "force deleted" event.
     *
     * @param  \App\Shipment  $shipment
     * @return void
     */

    public function forceDeleted(Shipment $shipment)
    {
        //
    }

    // private functions
    private function getDummySchedule(){
        return json_encode([
          [
            "id" => hexdec(uniqid()),
            "eta" => null,
            "etd" => null,
            "location_from" => null,
            "location_to" => null,
            "mode" => null,
            "legs" => [],
            "type" => null,
            "carrier_name" => null,
            "size_id" => null,
            "price" => null,
            "selling_size_id" => null,
            "selling_price" => null,
            "sell_rates" => [],
            "buy_rates" => [],
            "is_confirmed" => 1,
            "etaError" => [],
            "etdError" => [],
            "size_name" => "",
            "selling_size_name" => "",
            "carrier_name_label" => null,
            "location_to_name" => null,
            "location_from_name" => null
          ]
        ]);
    }
}
