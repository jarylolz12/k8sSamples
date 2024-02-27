<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Laravel\Scout\Searchable;
use Whitecube\NovaFlexibleContent\Concerns\HasFlexible;
use Carbon\Carbon;

// use App\Customer;

class Shipment extends Model
{
    use HasFlexible;

    protected $guarded= ['id','created_at'];
    protected $appends = ['projected_profit'];
    protected $casts = ['etd' => 'date', 'eta' => 'date'];

    private static $noSync;


    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    public function carrier()
    {
        return $this->belongsTo(Carrier::class);
    }

    public function terminalRegions()
    {
        return $this->belongsToMany(TerminalRegion::class, 'shipments_terminal_regions', 'shipment_id', 'terminal_region_id');
    }

    public function getProjectedProfitAttribute(){
        $total_sell_rate = 0;
        $total_buy_rate = 0;

        $schedules_group_bookings = [];

        if( $this->schedules_group_bookings && !is_null($this->schedules_group_bookings)){
            $schedules_group_bookings = json_decode($this->schedules_group_bookings);
        }else{
            return 0;
        }

        foreach( $schedules_group_bookings as $a ){
            if( $a->sell_rates ){
                foreach($a->sell_rates as $b ){
                    $total_sell_rate = $total_sell_rate+(!$b->amount || $b->amount == '' ? 0 : (float)$b->amount );
                }    
            }else{
                $total_sell_rate = false;   
            }
            
            if( $a->buy_rates ){
                foreach($a->buy_rates as $b ){
                    $total_buy_rate = $total_buy_rate+(!$b->amount || $b->amount == '' ? 0 : (float)$b->amount );
                }
            }else{
                $total_buy_rate = false;
            }
        }

        if( !$total_sell_rate && !$total_buy_rate ){
            return ['No sell rate and buy rate'];
        }elseif( !$total_sell_rate && $total_buy_rate ){
            return ['No sell rate'];
        }elseif( $total_sell_rate && !$total_buy_rate ){
            return ['No buy rate'];
        }else{
            $projected_profit = (float)($total_sell_rate - $total_buy_rate);

            $expo = pow(10,3);

            setlocale(LC_MONETARY,"en_US");

            return number_format(intval($projected_profit*$expo)/$expo, 2);
        }
    }

    private static function synchronise($item)
    {

        /* suppliers group */
        if (isset($item->suppliers_group) && $item->suppliers_group!='') {
            $getSuppliers = json_decode($item->suppliers_group);
            $setSuppliers = [];

            if ($getSuppliers!='') {
                if (count($getSuppliers)>0) {
                    $selectedSuppliers = [];
                    //map all current supplier id
                    foreach ($getSuppliers as $getSupplier) {
                        // array_push($selectedSuppliers, $getSupplier->supplier_id);
                        array_push($selectedSuppliers, $getSupplier->id);
                    }

                    //delete shipment_suppliers data that not are not listed
                    \DB::table('shipment_suppliers')
                   //->whereNotIn('supplier_id',$selectedSuppliers)
                    ->whereNotIn('unique_id', $selectedSuppliers)
                    ->where('shipment_id', $item->id)
                    ->delete();

                    $push_suppliers_containers = [];

                    foreach ($getSuppliers as $key => $getSupplier) {
                        $checkSupplier = \DB::table('shipment_suppliers')
                                ->where('unique_id', $getSupplier->id)
                                ->where('shipment_id', $item->id)
                                //->where('shipment_id',$item->id)
                                //->where('supplier_id',$getSupplier->supplier_id)
                                ->first();

                        if (isset($getSupplier->containers)) {
                            if (is_array($getSupplier->containers)) {
                                $check_containers = $getSupplier->containers;
                            }

                            if (isset($check_containers) && isset($check_containers[0])) {

                                //get all selected supplier containers
                                $selectedContainers = [];

                                foreach ($check_containers as $check_container) {
                                    $insert_suppliers_containers = [
                                        'supplier_unique_id' => $getSupplier->id,
                                        'unique_id' => $check_container->id
                                    ];

                                    array_push($selectedContainers, $check_container->id);

                                    if (isset($item->id)) {
                                        $insert_suppliers_containers['shipment_id'] = $item->id;
                                    }

                                    if (isset($check_container->container_id)) {
                                        $insert_suppliers_containers['container_id'] = intval($check_container->container_id);
                                    }

                                    if (isset($check_container->weight)) {
                                        $insert_suppliers_containers['weight'] = $check_container->weight;
                                    }

                                    if (isset($check_container->size)) {
                                        $insert_suppliers_containers['size'] = $check_container->size;
                                    }

                                    if (isset($check_container->cartons)) {
                                        $insert_suppliers_containers['cartons'] = $check_container->cartons;
                                    }

                                    $checkSupplierContainer = \DB::table('shipment_suppliers_containers')
                                                                //->where('schedule_id', $getSchedule->id)
                                                                ->where('supplier_unique_id', $getSupplier->id)
                                                                ->where('unique_id', $check_container->id);

                                    if (isset($checkSupplierContainer->supplier_unique_id)) {
                                        \DB::table('shipment_suppliers_containers')
                                            ->where('unique_id', $check_container->id)
                                            ->update($insert_suppliers_containers);
                                    } else {
                                        array_push($push_suppliers_containers, $insert_suppliers_containers);
                                    }
                                }

                                //delete all not selected containers
                                \DB::table('shipment_suppliers_containers')
                                ->whereNotIn('unique_id', $selectedContainers)
                                ->where('shipment_id', $item->id)
                                ->delete();
                                // \DB::table('shipment_schedules_legs')->insert($push_legs);
                            }
                        }

                        $getSupplier->cargo_ready_date = (isset($getSupplier->cargo_ready_date) && $getSupplier->cargo_ready_date!==null && $getSupplier->cargo_ready_date!=='') ? $getSupplier->cargo_ready_date : null;

                        $getSupplier->hbl_num =  (isset($getSupplier->hbl_num) && $getSupplier->hbl_num!='') ? $getSupplier->hbl_num : null;
                        $getSupplier->kg =  (isset($getSupplier->kg) && $getSupplier->kg!='') ? $getSupplier->kg : null;
                        $getSupplier->cbm =  (isset($getSupplier->cbm) && $getSupplier->cbm!='') ? $getSupplier->cbm : null;
                        $getSupplier->incoterm_id =  (isset($getSupplier->incoterm_id) && $getSupplier->incoterm_id!='') ? $getSupplier->incoterm_id : 0;
                        $getSupplier->product_description =  (isset($getSupplier->product_description) && $getSupplier->product_description!='') ? $getSupplier->product_description : null;
                        $getSupplier->product_description =  (isset($getSupplier->product_description) && $getSupplier->product_description!='') ? $getSupplier->product_description : null;
                        $getSupplier->total_cartons =  (isset($getSupplier->total_cartons) && $getSupplier->total_cartons!='') ? $getSupplier->total_cartons : null;
                        /* $getSupplier->po_id =  (isset($getSupplier->po_id) && $getSupplier->po_id!='') ? $getSupplier->po_id : null; */
                        $getSupplier->marks = (isset($getSupplier->marks) && $getSupplier->marks!='') ? $getSupplier->marks : null;
                        $getSupplier->bol_shipper_address = (isset($getSupplier->bol_shipper_address) && $getSupplier->bol_shipper_address!='') ? $getSupplier->bol_shipper_address : null;
                        $getSupplier->bol_shipper_phone_number = (isset($getSupplier->bol_shipper_phone_number) && $getSupplier->bol_shipper_phone_number!='') ? $getSupplier->bol_shipper_phone_number : null;
                        $getSupplier->bol_consignee_address = (isset($getSupplier->bol_consignee_address) && $getSupplier->bol_consignee_address!='') ? $getSupplier->bol_consignee_address : null;
                        $getSupplier->bol_consignee_phone_number = (isset($getSupplier->bol_consignee_phone_number) && $getSupplier->bol_consignee_phone_number!='') ? $getSupplier->bol_consignee_phone_number : null;
                        $getSupplier->bol_notify_party = (isset($getSupplier->bol_notify_party) && $getSupplier->bol_notify_party!='') ? $getSupplier->bol_notify_party : null;
                        $getSupplier->bol_notify_address = (isset($getSupplier->bol_notify_address) && $getSupplier->bol_notify_address!='') ? $getSupplier->bol_notify_address : null;
                        $getSupplier->bol_notify_phone_number = (isset($getSupplier->bol_notify_phone_number) && $getSupplier->bol_notify_phone_number!='') ? $getSupplier->bol_notify_phone_number : null;
                        if (!isset($checkSupplier->id)) {
                            array_push(
                                $setSuppliers,
                                [
                                    'shipment_id' => $item->id,
                                    'supplier_id' => $getSupplier->supplier_id,
                                    'kg' => $getSupplier->kg,
                                    'cbm' => $getSupplier->cbm,
                                    'incoterm_id' => $getSupplier->incoterm_id,
                                    'hbl_num' => $getSupplier->hbl_num,
                                    'product_description' => $getSupplier->product_description,
                                    'total_cartons' => $getSupplier->total_cartons,
                                    'ams_num' => $getSupplier->ams_num,
                                    'bl_status' => $getSupplier->bl_status,
                                    'po_num' => $getSupplier->po_num,
                                    'unique_id' => $getSupplier->id,
                                    'hbl_copy' => !is_object($getSupplier->hbl_copy) ? $getSupplier->hbl_copy : '',
                                    'packing_list' => !is_object($getSupplier->packing_list) ? $getSupplier->packing_list : '',
                                    'commercial_documents' => !is_object($getSupplier->commercial_documents) ? $getSupplier->commercial_documents : '',
                                    'commercial_invoice' => !is_object($getSupplier->commercial_invoice) ? $getSupplier->commercial_invoice: '',
                                    /*'po_id' => $getSupplier->po_id, */
                                    'marks' => $getSupplier->marks,
                                    'bol_shipper_address' => $getSupplier->bol_shipper_address,
                                    'bol_shipper_phone_number' => $getSupplier->bol_shipper_phone_number,
                                    'bol_consignee_address' => $getSupplier->bol_consignee_address,
                                    'bol_consignee_phone_number' => $getSupplier->bol_consignee_phone_number,
                                    'bol_notify_party' => $getSupplier->bol_notify_party,
                                    'bol_notify_address' => $getSupplier->bol_notify_address,
                                    'bol_notify_phone_number' => $getSupplier->bol_notify_phone_number,
                                    'cargo_ready_date' => $getSupplier->cargo_ready_date
                                    ]
                            );
                        } else {
                            \DB::table('shipment_suppliers')
                            ->where('id', $checkSupplier->id)
                            //->where('shipment_id',$item->id)
                            //->where('supplier_id',$getSupplier->supplier_id)
                            ->update([
                                'kg' => $getSupplier->kg,
                                'cbm' => $getSupplier->cbm,
                                'incoterm_id' => $getSupplier->incoterm_id,
                                'hbl_num' => $getSupplier->hbl_num,
                                'product_description' => $getSupplier->product_description,
                                'total_cartons' => $getSupplier->total_cartons,
                                'ams_num' => $getSupplier->ams_num,
                                'bl_status' => $getSupplier->bl_status,
                                'po_num' => $getSupplier->po_num,'supplier_id' => $getSupplier->supplier_id,
                                'hbl_copy' => !is_object($getSupplier->hbl_copy) ? $getSupplier->hbl_copy : '',
                                'packing_list' => !is_object($getSupplier->packing_list) ? $getSupplier->packing_list : '',
                                'commercial_documents' => !is_object($getSupplier->commercial_documents) ? $getSupplier->commercial_documents : '',
                                'commercial_invoice' => !is_object($getSupplier->commercial_invoice) ? $getSupplier->commercial_invoice : '',
                                /*'po_id' => intval($getSupplier->po_id), */
                                'marks' => $getSupplier->marks,
                                'bol_shipper_address' => $getSupplier->bol_shipper_address,
                                'bol_shipper_phone_number' => $getSupplier->bol_shipper_phone_number,
                                'bol_consignee_address' => $getSupplier->bol_consignee_address,
                                'bol_consignee_phone_number' => $getSupplier->bol_consignee_phone_number,
                                'bol_notify_party' => $getSupplier->bol_notify_party,
                                'bol_notify_address' => $getSupplier->bol_notify_address,
                                'bol_notify_phone_number' => $getSupplier->bol_notify_phone_number,
                                'cargo_ready_date' => $getSupplier->cargo_ready_date
                                ]);
                        }
                    }
                } else {

                    //delete also related suppliers containers
                    \DB::table('shipment_suppliers_containers')
                        ->where('shipment_id', $item->id)
                        ->delete();

                    \DB::table('shipment_suppliers')
                     ->where('shipment_id', $item->id)
                     ->delete();
                }
            }
        }

        if (count($setSuppliers)>0) {
            if (count($push_suppliers_containers) > 0) {
                \DB::table('shipment_suppliers_containers')->insert($push_suppliers_containers);
            }

            \DB::table('shipment_suppliers')->insert($setSuppliers);
        }

        /* suppliers bookings group */
        if (isset($item->suppliers_group_bookings) && $item->suppliers_group_bookings!='') {
            $getSuppliers = json_decode($item->suppliers_group_bookings);
            $setSuppliers = [];

            if ($getSuppliers!='') {
                if (count($getSuppliers)>0) {
                    $selectedSuppliers = [];
                    //map all current supplier id
                    foreach ($getSuppliers as $getSupplier) {
                        // array_push($selectedSuppliers, $getSupplier->supplier_id);
                        array_push($selectedSuppliers, $getSupplier->id);
                    }

                    //delete shipment_suppliers data that not are not listed
                    \DB::table('shipment_suppliers')
                   //->whereNotIn('supplier_id',$selectedSuppliers)
                    ->whereNotIn('unique_id', $selectedSuppliers)
                    ->where('shipment_id', $item->id)
                    ->delete();


                    foreach ($getSuppliers as $key => $getSupplier) {
                        $checkSupplier = \DB::table('shipment_suppliers')
                                ->where('unique_id', $getSupplier->id)
                                ->where('shipment_id', $item->id)
                                //->where('shipment_id',$item->id)
                                //->where('supplier_id',$getSupplier->supplier_id)
                                ->first();

                        $getSupplier->cargo_ready_date = (isset($getSupplier->cargo_ready_date) && $getSupplier->cargo_ready_date!==null && $getSupplier->cargo_ready_date!=='') ? $getSupplier->cargo_ready_date : null;
                        $getSupplier->hbl_num =  (isset($getSupplier->hbl_num) && $getSupplier->hbl_num!='') ? $getSupplier->hbl_num : null;
                        $getSupplier->kg =  (isset($getSupplier->kg) && $getSupplier->kg!='') ? $getSupplier->kg : null;
                        $getSupplier->cbm =  (isset($getSupplier->cbm) && $getSupplier->cbm!='') ? $getSupplier->cbm : null;
                        $getSupplier->incoterm_id =  (isset($getSupplier->incoterm_id) && $getSupplier->incoterm_id!='') ? $getSupplier->incoterm_id : 0;
                        $getSupplier->product_description =  (isset($getSupplier->product_description) && $getSupplier->product_description!='') ? $getSupplier->product_description : null;
                        $getSupplier->product_description =  (isset($getSupplier->product_description) && $getSupplier->product_description!='') ? $getSupplier->product_description : null;
                        $getSupplier->total_cartons =  (isset($getSupplier->total_cartons) && $getSupplier->total_cartons!='') ? $getSupplier->total_cartons : null;
                        /* $getSupplier->po_id =  (isset($getSupplier->po_id) && $getSupplier->po_id!='') ? $getSupplier->po_id : null; */

                        if (!isset($checkSupplier->id)) {
                            array_push($setSuppliers, ['cargo_ready_date' => $getSupplier->cargo_ready_date,'shipment_id' => $item->id,'supplier_id' => $getSupplier->supplier_id,'kg' => $getSupplier->kg,'cbm' => $getSupplier->cbm,'incoterm_id' => $getSupplier->incoterm_id,'hbl_num' => $getSupplier->hbl_num,'product_description' => $getSupplier->product_description,'total_cartons' => $getSupplier->total_cartons,'ams_num' => $getSupplier->ams_num,'bl_status' => $getSupplier->bl_status,'po_num' => $getSupplier->po_num,'unique_id' => $getSupplier->id,'hbl_copy' => !is_object($getSupplier->hbl_copy) ? $getSupplier->hbl_copy : '','packing_list' => !is_object($getSupplier->packing_list) ? $getSupplier->packing_list : '','commercial_documents' => !is_object($getSupplier->commercial_documents) ? $getSupplier->commercial_documents : '','commercial_invoice' => !is_object($getSupplier->commercial_invoice) ? $getSupplier->commercial_invoice: ''/* ,'po_id' => $getSupplier->po_id */]);
                        } else {
                            \DB::table('shipment_suppliers')
                            ->where('id', $checkSupplier->id)
                //->where('shipment_id',$item->id)
                //->where('supplier_id',$getSupplier->supplier_id)
                            ->update(['cargo_ready_date' => $getSupplier->cargo_ready_date,'kg' => $getSupplier->kg,'cbm' => $getSupplier->cbm,'incoterm_id' => $getSupplier->incoterm_id,'hbl_num' => $getSupplier->hbl_num,'product_description' => $getSupplier->product_description,'total_cartons' => $getSupplier->total_cartons,'ams_num' => $getSupplier->ams_num,'bl_status' => $getSupplier->bl_status,'po_num' => $getSupplier->po_num,'supplier_id' => $getSupplier->supplier_id,'hbl_copy' => !is_object($getSupplier->hbl_copy) ? $getSupplier->hbl_copy : '','packing_list' => !is_object($getSupplier->packing_list) ? $getSupplier->packing_list : '','commercial_documents' => !is_object($getSupplier->commercial_documents) ? $getSupplier->commercial_documents : '','commercial_invoice' => !is_object($getSupplier->commercial_invoice) ? $getSupplier->commercial_invoice: ''/* ,'po_id' => intval($getSupplier->po_id) */]);
                        }
                    }
                } else {
                    \DB::table('shipment_suppliers')
                     ->where('shipment_id', $item->id)
                     ->delete();
                }
            }

            //unset($model->suppliers_group_bookings);
        } else {
            $item->suppliers_group_bookings = [];
        }

        if (count($setSuppliers)>0) {
            \DB::table('shipment_suppliers')->insert($setSuppliers);
        }

        /* container group */
        if (isset($item->containers_group) && $item->containers_group!='') {
            $getContainers = json_decode($item->containers_group);
            $setContainers = [];

            if ($getContainers!='') {
                if (count($getContainers)>0) {
                    $selectedContainers = [];

                    foreach ($getContainers as $key => $getContainer) {
                        array_push($selectedContainers, $getContainer->id);

                        $checkContainer = \DB::table('containers')
                                ->where('unique_id', $getContainer->id)
                                ->where('shipment_id', $item->id)
                                ->first();

                        if (!isset($checkContainer->id)) {
                            array_push($setContainers, ['shipment_id' => $item->id,'unique_id' => $getContainer->id,'size' => intval($getContainer->size),'cbm' => intval($getContainer->cbm),'kg' => intval($getContainer->kg),'cartons' => intval($getContainer->cartons),'container_num' => $getContainer->container_num,'seal_num'=>isset($getContainer->seal_num) ? $getContainer->seal_num : null]);
                        } else {
                            \DB::table('containers')
                            ->where('id', $checkContainer->id)
                            ->update(['shipment_id' => $item->id,'size' => intval($getContainer->size),'cbm' => $getContainer->cbm != "" ? intval($getContainer->cbm) : 0,'kg' => intval($getContainer->kg),'cartons' => intval($getContainer->cartons),'container_num' => $getContainer->container_num,'seal_num'=>isset($getContainer->seal_num) ? $getContainer->seal_num : null]);
                        }
                    }

                    if (count($selectedContainers) > 0) {
                        \DB::table('containers')
                        //->whereNotIn('supplier_id',$selectedSuppliers)
                        ->whereNotIn('unique_id', $selectedContainers)
                        ->where('shipment_id', $item->id)
                        ->delete();
                    }
                } else {
                    \DB::table('containers')
                        ->where('shipment_id', $item->id)
                        ->delete();
                }
            }
        }

        if (count($setContainers)>0) {
            \DB::table('containers')->insert($setContainers);
        }

        if (isset($item->containers_group_bookings) && $item->containers_group_bookings!='') {
            $getContainers = json_decode($item->containers_group_bookings);
            $setContainers = [];

            if ($getContainers!='') {
                if (count($getContainers)>0) {
                    $selectedContainers = [];
                    //map all current container id
                    foreach ($getContainers as $getContainer) {
                        // array_push($selectedSuppliers, $getSupplier->supplier_id);
                        array_push($selectedContainers, $getContainer->id);
                    }

                    if (count($selectedContainers)>0) {

                        //delete containers data that not are not listed
                        \DB::table('containers')
                        //->whereNotIn('supplier_id',$selectedSuppliers)
                        ->whereNotIn('unique_id', $selectedContainers)
                        ->where('shipment_id', $item->id)
                        ->delete();
                    }

                    foreach ($getContainers as $key => $getContainer) {
                        $checkContainer = \DB::table('containers')
                                ->where('unique_id', $getContainer->id)
                                ->where('shipment_id', $item->id)
                                //->where('shipment_id',$item->id)
                                //->where('supplier_id',$getContainer->supplier_id)
                                ->first();

                        if (!isset($checkContainer->id)) {
                            array_push($setContainers, ['shipment_id' => $item->id,'unique_id' => $getContainer->id,'size' => intval($getContainer->size),'cbm' => intval($getContainer->cbm),'kg' => intval($getContainer->kg),'cartons' => intval($getContainer->cartons),'container_num' => $getContainer->container_num,'seal_num'=>isset($getContainer->seal_num) ? $getContainer->seal_num : null]);
                        } else {
                            \DB::table('containers')
                            ->where('id', $checkContainer->id)
                            ->update(['shipment_id' => $item->id,'size' => intval($getContainer->size),'cbm' => intval($getContainer->cbm),'kg' => intval($getContainer->kg),'cartons' => intval($getContainer->cartons),'container_num' => $getContainer->container_num,'seal_num'=>isset($getContainer->seal_num) ? $getContainer->seal_num : null]);
                        }
                    }
                } else {
                    //delete containers data that not are not listed
                    \DB::table('containers')
                    ->where('shipment_id', $item->id)
                    ->delete();
                }
            }
        }

        if (count($setContainers)>0) {
            \DB::table('containers')->insert($setContainers);
        }

        /* schedules group */
        if (isset($item->schedules_group) && $item->schedules_group!='') {
            $getSchedules = json_decode($item->schedules_group);
            $setSchedules = [];

            if ($getSchedules!='') {
                if (count($getSchedules)>0) {
                    $selectedSchedules = [];

                    foreach ($getSchedules as $key => $getSchedule) {
                        array_push($selectedSchedules, $getSchedule->id);
                        $checkSchedule = \DB::table('shipment_schedules')
                            ->where('unique_id', $getSchedule->id)
                            ->where('shipment_id', $item->id)
                            //->where('shipment_id',$item->id)
                            //->where('supplier_id',$getSupplier->supplier_id)
                            ->first();

                        if (!isset($checkSchedule->id)) {
                            if ($key==0) {
                                \DB::table('shipments')
                                ->where('id', $item->id)
                                ->update([
                                    'etd' => $getSchedule->etd,
                                    'eta' => $getSchedule->eta,
                                    'updated_at' => Carbon::now()
                                ]);

                                $item->etd = $getSchedule->etd;
                                $item->eta = $getSchedule->eta;

                                /*
                                $item->etd = $getSchedule->eta;
                                $item->eta = $getSchedule->etd;
                                $item->save();*/
                                $push_schedules = ['shipment_id' => $item->id,'location_from' => (isset($getSchedule->location_from)) ? $getSchedule->location_from : '','location_to' => (isset($getSchedule->location_to)) ? $getSchedule->location_to : '','mode' => $getSchedule->mode,'unique_id' => $getSchedule->id];

                                if (isset($getSchedule->eta)) {
                                    if ($getSchedule->eta!='' && $getSchedule->eta!=null) {
                                        $push_schedules['eta_date'] = $getSchedule->eta;
                                    }
                                }

                                if (isset($getSchedule->etd)) {
                                    if ($getSchedule->etd!='' && $getSchedule->etd!=null) {
                                        $push_schedules['etd_date'] = $getSchedule->etd;
                                    }
                                }
                                /*
                                $updateShipmentNow = \App\Shipment::find($item->id);
                                if(isset($updateShipmentNow->id)) {
                                  $updateShipmentNow->eta = $getSchedule->eta;
                                  $updateShipmentNow->etd = $getSchedule->etd;
                                  $updateShipmentNow->save();
                                  /*
                                  \DB::table('shipments')->where('id',$item->id)
                                                          ->update([
                                                            'eta' => $getSchedule->eta,
                                                            'etd' => $getSchedule->etd
                                                          ]);*/
                                //}

                                array_push($setSchedules, $push_schedules);
                            } else {
                                $push_schedules = ['shipment_id' => $item->id,'location_from' => '','location_to' => (isset($getSchedule->location_to)) ? $getSchedule->location_to : '','mode' => $getSchedule->mode,'unique_id' => $getSchedule->id];
                                if (isset($getSchedule->eta)) {
                                    if ($getSchedule->eta!='' && $getSchedule->eta!=null) {
                                        $push_schedules['eta_date'] = $getSchedule->eta;
                                    } else {
                                        $push_schedules['eta_date'] = null;
                                    }
                                } else {
                                    $push_schedules['eta_date'] = null;
                                }

                                if (isset($getSchedule->etd)) {
                                    if ($getSchedule->etd!='' && $getSchedule->etd!=null) {
                                        $push_schedules['etd_date'] = $getSchedule->etd;
                                    } else {
                                        $push_schedules['etd_date'] = null;
                                    }
                                } else {
                                    $push_schedules['etd_date'] = null;
                                }

                                array_push($setSchedules, $push_schedules);
                            }
                        } else {


                            //if this is new shipment
                            if (isset($getSchedule->is_confirmed) && $getSchedule->is_confirmed!==null && $getSchedule->is_confirmed==1) {
                                \DB::table('shipments')
                                   ->where('id', $item->id)
                                   ->update([
                                        'etd' => $getSchedule->etd,
                                        'eta' => $getSchedule->eta
                                ]);

                                $item->etd = $getSchedule->etd;
                                $item->eta = $getSchedule->eta;
                            }

                            $buildUpdate['shipment_id'] = $item->id;

                            if ($getSchedule->eta!='' && $getSchedule->eta!=null) {
                                $buildUpdate['eta_date'] = $getSchedule->eta;
                            }

                            if ($getSchedule->etd!='' && $getSchedule->etd!=null) {
                                $buildUpdate['etd_date'] = $getSchedule->etd;
                            }

                            if ($getSchedule->location_from!='' && $getSchedule->location_from!=null) {
                                $buildUpdate['location_from'] = $getSchedule->location_from;
                            }

                            if ($getSchedule->location_to!='' && $getSchedule->location_to!=null) {
                                $buildUpdate['location_to'] = $getSchedule->location_to;
                            }

                            $buildUpdate['updated_at'] = Carbon::now();

                            \DB::table('shipment_schedules')
                            ->where('id', $checkSchedule->id)
                        //->where('shipment_id',$item->id)
                        //->where('supplier_id',$getSupplier->supplier_id)
                            ->update($buildUpdate);
                        }
                    }

                    if (count($selectedSchedules) > 0) {

                        //delete shipment_schedules data that not are not listed
                        \DB::table('shipment_schedules')
                        //->whereNotIn('supplier_id',$selectedSuppliers)
                        ->whereNotIn('unique_id', $selectedSchedules)
                        ->where('shipment_id', $item->id)
                        ->delete();
                    }
                }
            }
        }

        if (isset($setSchedules)) {
            if (count($setSchedules)>0) {
                $updateShipmentSchedule = [];
                \DB::table('shipment_schedules')->insert($setSchedules[0]);

                //  \DB::table('shipments')->where('id',$item->id)
                //                       ->update($updateShipmentSchedule);

                if (isset($setSchedules[1])) {
                    \DB::table('shipment_schedules')->insert($setSchedules[1]);
                }


                if (isset($getSchedules[0])) {
                    $pass = false;
                    $updateShipment = [];
                    if (isset($getSchedules[0]->etd)) {
                        $pass = true;
                        if ($getSchedules[0]->etd!='' && $getSchedules[0]->etd!=null) {
                            $updateShipment['etd'] = $getSchedules[0]->etd;
                        }
                    }


                    if (isset($getSchedules[0]->eta)) {
                        $pass = true;
                        if ($getSchedules[0]->eta!='' && $getSchedules[0]->eta!=null) {
                            $updateShipment['eta'] = $getSchedules[0]->eta;
                        }
                    }

                    if (isset($updateShipment['etd']) || isset($updateShipment['eta'])) {
                        //  \DB::table('shipments')->where('id', $item->id)
                        //            ->update($updateShipment);
                    }

                    //$getSchedules[0]->etd =  Carbon::parse($item->etd)->format('Y-m-d');
                    //$getSchedules[0]->eta = Carbon::parse($item->eta)->format('Y-m-d');

            //\DB::table('shipments')->where('id',$item->id)
            //                    ->update(['schedules_group' => json_encode($getSchedules)]);
                }
            }
        }

        if (isset($item->schedules_group_bookings) && $item->schedules_group_bookings!='') {
            $getSchedules = (isset($item->schedules_group_bookings) && !is_array($item->schedules_group_bookings)) ? json_decode($item->schedules_group_bookings) : $item->schedules_group_bookings;
            $setSchedulesBookings = [];
            $push_legs = [];
            $push_buy_rates = [];
            $push_sell_rates = [];

            if ($getSchedules!='') {
                if (count($getSchedules)>0) {
                    $selectedSchedules = [];
                    //map all current supplier id
                    foreach ($getSchedules as $getSchedule) {
                        array_push($selectedSchedules, $getSchedule->id);
                    }

                    //delete shipment_schedules data that not are not listed
                    \DB::table('shipment_schedules')
                    //->whereNotIn('supplier_id',$selectedSuppliers)
                    ->whereNotIn('unique_id', $selectedSchedules)
                    ->where('shipment_id', $item->id)
                    ->where('is_new', 1)
                    ->delete();

                    foreach ($getSchedules as $key => $getSchedule) {
                        $checkSchedule = \DB::table('shipment_schedules')
                            ->where('unique_id', $getSchedule->id)
                            ->where('shipment_id', $item->id)
                            ->where('is_new', 1)
                            //->where('shipment_id',$item->id)
                            //->where('supplier_id',$getSupplier->supplier_id)
                            ->first();



                        if (!isset($checkSchedule->id)) {

                            //save old shipment data
                            if ($key==0) {
                                \DB::table('shipments')
                                   ->where('id', $item->id)
                                   ->update([
                                        'etd' => $getSchedule->etd,
                                        'eta' => $getSchedule->eta
                                ]);

                                $item->etd = $getSchedule->etd;
                                $item->eta = $getSchedule->eta;
                                //$item->save();
                            }

                            $push_schedules = ['shipment_id' => $item->id,'location_from' => (isset($getSchedule->location_from)) ? $getSchedule->location_from : '','location_to' => (isset($getSchedule->location_to)) ? $getSchedule->location_to : '','mode' => $getSchedule->mode,'unique_id' => $getSchedule->id, 'is_new' => 1,'carrier_name' => (isset($getSchedule->carrier_name->name)) ? $getSchedule->carrier_name->name : '', 'size_id' => $getSchedule->size_id, 'price' => $getSchedule->price,'is_confirmed' => 0];

                            if (isset($getSchedule->selling_size_id)) {
                                $push_schedules['selling_size_id'] = $getSchedule->selling_size_id;
                            }

                            if (isset($getSchedule->selling_price)) {
                                $push_schedules['selling_price'] = $getSchedule->selling_price;
                            }


                            if (isset($getSchedule->eta)) {
                                if ($getSchedule->eta!='' && $getSchedule->eta!=null) {
                                    $push_schedules['eta_date'] = $getSchedule->eta;
                                }
                            }

                            if (isset($getSchedule->etd)) {
                                if ($getSchedule->etd!='' && $getSchedule->etd!=null) {
                                    $push_schedules['etd_date'] = $getSchedule->etd;
                                }
                            }

                            array_push($setSchedulesBookings, $push_schedules);

                            //insert now the shipment schedule

                            /*
                            $get_schedule_id = \DB::table('shipment_schedules')->insertGetId(
                                $push_schedules
                            );*/

                            //get all added schedule legs if there are any
                            if (isset($getSchedule->legs)) {
                                if (is_array($getSchedule->legs)) {
                                    $check_legs = $getSchedule->legs;
                                }

                                if (isset($check_legs) && isset($check_legs[0])) {
                                    foreach ($check_legs as $check_leg) {
                                        /*
                                        $insert_leg = [
                                            'schedule_unique_id' => $getSchedule->id,
                                            //'aschedule_id'
                                            //'schedule_id' => $get_schedule_id,
                                            'unique_id' => $check_leg->id
                                        ];*/


                                        $insert_leg = [
                                            'schedule_unique_id' => $getSchedule->id,
                                            'unique_id' => $check_leg->id,
                                            'shipment_id' => (isset($item->id)) ? $item->id : '',
                                            'eta' => (isset($check_leg->eta) && $check_leg->eta!==null && $check_leg->eta!=='') ? $check_leg->eta : '',
                                            'mode' => (isset($check_leg->mode) && $check_leg->mode!==null && $check_leg->mode!=='') ? $check_leg->mode : '',
                                            'location_to' => (isset($check_leg->location_to) && $check_leg->location_to!==null && $check_leg->location_to!=='') ? $check_leg->location_to : ''
                                        ];
                                        /*

                                        if (isset($item->id)) {
                                            $insert_leg['shipment_id'] = $item->id;
                                        }

                                        if (isset($check_leg->eta)) {
                                            if ($check_leg->eta!='' && $check_leg->eta!=null) {
                                                $insert_leg['eta'] = $check_leg->eta;
                                            }
                                        }

                                        if (isset($check_leg->mode)) {
                                            if ($check_leg->mode!='' && $check_leg->mode!=null) {
                                                $insert_leg['mode'] = $check_leg->mode;
                                            }
                                        }

                                        if (isset($check_leg->location_to)) {
                                            if ($check_leg->location_to!='' && $check_leg->location_to!=null) {
                                                $insert_leg['location_to'] = $check_leg->location_to;
                                            }
                                        }*/

                                        array_push($push_legs, $insert_leg);
                                    }

                                    // \DB::table('shipment_schedules_legs')->insert($push_legs);
                                }
                            }

                            //get all added buy rates if there are any
                            if (isset($getSchedule->buy_rates)) {
                                if (is_array($getSchedule->buy_rates)) {
                                    $check_buy_rates = $getSchedule->buy_rates;
                                }

                                if (isset($check_buy_rates) && isset($check_buy_rates[0])) {
                                    foreach ($check_buy_rates as $check_buy_rate) {
                                        /*
                                        $insert_buy_rate = [
                                            'schedule_unique_id' => $getSchedule->id,
                                            //'aschedule_id'
                                            //'schedule_id' => $get_schedule_id,
                                            'unique_id' => $check_buy_rate->id
                                        ];*/

                                        $insert_buy_rate = [
                                            'unique_id' => $check_buy_rate->id,
                                            'schedule_unique_id' => $getSchedule->id,
                                            'shipment_id' => (isset($item->id) && $item->id!==null && $item->id!=='') ? $item->id : '',
                                            'service_id' => (isset($check_buy_rate->service_id) && $check_buy_rate->service_id!==null && $check_buy_rate->service_id!=='') ? $check_buy_rate->service_id : '',
                                            'container_size_id' => (isset($check_buy_rate->container_size_id) && $check_buy_rate->container_size_id!==null && $check_buy_rate->container_size_id!=='') ? $check_buy_rate->container_size_id : '',
                                            'amount' => (isset($check_buy_rate->amount) && $check_buy_rate->amount!==null && $check_buy_rate->amount!=='') ? $check_buy_rate->amount : '',
                                            'qty' => (isset($check_buy_rate->qty) && $check_buy_rate->qty!==null && $check_buy_rate->qty!=='') ? $check_buy_rate->qty : ''
                                        ];

                                        /*
                                        if (isset($item->id)) {
                                            $insert_buy_rate['shipment_id'] = $item->id;
                                        }

                                        if (isset($check_buy_rate->service_id)) {
                                            $insert_buy_rate['service_id'] = $check_buy_rate->service_id;
                                        }

                                        if (isset($check_buy_rate->container_size_id)) {
                                            $insert_buy_rate['container_size_id'] = $check_buy_rate->service_id;
                                        }

                                        if (isset($check_buy_rate->amount)) {
                                            $insert_buy_rate['amount'] = $check_buy_rate->amount;
                                        }

                                        if (isset($check_buy_rate->qty)) {
                                            $insert_buy_rate['qty'] = $check_buy_rate->qty;
                                        }*/

                                        array_push($push_buy_rates, $insert_buy_rate);
                                    }

                                    // \DB::table('shipment_schedules_legs')->insert($push_legs);
                                }
                            }

                            //get all added sell rates if there are any
                            if (isset($getSchedule->sell_rates)) {
                                if (is_array($getSchedule->sell_rates)) {
                                    $check_sell_rates = $getSchedule->sell_rates;
                                }

                                if (isset($check_sell_rates) && isset($check_sell_rates[0])) {
                                    foreach ($check_sell_rates as $check_sell_rate) {
                                        $insert_sell_rate = [
                                            'schedule_unique_id' => $getSchedule->id,
                                            'shipment_id' => (isset($item->id)) ? $item->id : '',
                                            'service_id' => (isset($check_sell_rate->service_id) && $check_sell_rate->service_id!==null && $check_sell_rate->service_id!=='') ? $check_sell_rate->service_id : '',
                                            'container_size_id' => (isset($check_sell_rate->container_size_id) && $check_sell_rate->container_size_id!==null && $check_sell_rate->container_size_id!=='') ? $check_sell_rate->container_size_id : '',
                                            'amount' => (isset($check_sell_rate->amount) && $check_sell_rate->amount!==null && $check_sell_rate->amount!=='') ? $check_sell_rate->service_id : '',
                                            'qty' => (isset($check_sell_rate->qty) && $check_sell_rate->qty!==null && $check_sell_rate->qty!=='') ? $check_sell_rate->qty : '',
                                            'unique_id' => $check_sell_rate->id
                                        ];

                                        /*
                                        $insert_sell_rate = [
                                            'schedule_unique_id' => $getSchedule->id,
                                            //'aschedule_id'
                                            //'schedule_id' => $get_schedule_id,
                                            'unique_id' => $check_sell_rate->id
                                        ];

                                        if (isset($item->id)) {
                                            $insert_sell_rate['shipment_id'] = $item->id;
                                        }

                                        if (isset($check_sell_rate->service_id)) {
                                            $insert_sell_rate['service_id'] = $check_sell_rate->service_id;
                                        }

                                        if (isset($check_sell_rate->container_size_id)) {
                                            $insert_sell_rate['container_size_id'] = $check_sell_rate->service_id;
                                        }

                                        if (isset($check_sell_rate->amount)) {
                                            $insert_sell_rate['amount'] = $check_sell_rate->amount;
                                        }

                                        if (isset($check_sell_rate->qty)) {
                                            $insert_sell_rate['qty'] = $check_sell_rate->qty;
                                        }*/

                                        array_push($push_sell_rates, $insert_sell_rate);
                                    }

                                    // \DB::table('shipment_schedules_legs')->insert($push_legs);
                                }
                            }
                        } else {
                            if (isset($getSchedule->is_confirmed) && $getSchedule->is_confirmed!==null && $getSchedule->is_confirmed==1) {
                                \DB::table('shipments')
                                   ->where('id', $item->id)
                                   ->update([
                                        'etd' => $getSchedule->etd,
                                        'eta' => $getSchedule->eta
                                ]);

                                $item->etd = $getSchedule->etd;
                                $item->eta = $getSchedule->eta;
                            }

                            $buildUpdate['shipment_id'] = $item->id;

                            if ($getSchedule->eta!='' && $getSchedule->eta!=null) {
                                $buildUpdate['eta_date'] = $getSchedule->eta;
                            }

                            if ($getSchedule->etd!='' && $getSchedule->etd!=null) {
                                $buildUpdate['etd_date'] = $getSchedule->etd;
                            }

                            if ($getSchedule->location_from!='' && $getSchedule->location_from!=null) {
                                $buildUpdate['location_from'] = $getSchedule->location_from;
                            }

                            if ($getSchedule->location_to!='' && $getSchedule->location_to!=null) {
                                $buildUpdate['location_to'] = $getSchedule->location_to;
                            }

                            if ($getSchedule->carrier_name!='' && $getSchedule->carrier_name!=null && isset($getSchedule->carrier_name->name)) {
                                $buildUpdate['carrier_name'] = $getSchedule->carrier_name->name;
                            }

                            if ($getSchedule->mode!='' && $getSchedule->mode!=null) {
                                $buildUpdate['mode'] = $getSchedule->mode;
                            }

                            if ($getSchedule->size_id!='' && $getSchedule->size_id!=null) {
                                $buildUpdate['size_id'] = $getSchedule->size_id;
                            }

                            if ($getSchedule->is_confirmed!='' && $getSchedule->is_confirmed!=null) {
                                $buildUpdate['is_confirmed'] = intval($getSchedule->is_confirmed);
                            }

                            if ($getSchedule->price!='' && $getSchedule->price!=null) {
                                $buildUpdate['price'] = $getSchedule->price;
                            }

                            if (isset($getSchedule->selling_size_id) && $getSchedule->selling_size_id!='' && $getSchedule->selling_size_id!=null) {
                                $buildUpdate['selling_size_id'] = $getSchedule->selling_size_id;
                            }

                            if (isset($getSchedule->selling_price) && $getSchedule->selling_price!='' && $getSchedule->selling_price!=null) {
                                $buildUpdate['selling_price'] = $getSchedule->selling_price;
                            }

                            //get all added schedule legs if there are any
                            if (isset($getSchedule->legs)) {
                                $check_legs = $getSchedule->legs;
                                // $push_legs = [];
                                if (isset($check_legs) && isset($check_legs[0])) {
                                    $selectedLegs = [];

                                    foreach ($check_legs as $check_leg) {
                                        array_push($selectedLegs, $check_leg->id);
                                    }

                                    //delete legs data that not are not listed
                                    \DB::table('shipment_schedules_legs')
                                    //->whereNotIn('supplier_id',$selectedSuppliers)
                                    ->whereNotIn('unique_id', $selectedLegs)
                                    ->where('shipment_id', $item->id)
                                    ->where('schedule_unique_id', $getSchedule->id)
                                    ->delete();


                                    if (count($check_legs) > 0) {
                                        foreach ($check_legs as $check_leg) {
                                            $insert_leg = [
                                                'schedule_unique_id' => $getSchedule->id,
                                                'unique_id' => $check_leg->id,
                                                'shipment_id' => (isset($item->id)) ? $item->id : '',
                                                'eta' => (isset($check_leg->eta) && $check_leg->eta!==null && $check_leg->eta!=='') ? $check_leg->eta : '',
                                                'mode' => (isset($check_leg->mode) && $check_leg->mode!==null && $check_leg->mode!=='') ? $check_leg->mode : '',
                                                'location_to' => (isset($check_leg->location_to) && $check_leg->location_to!==null && $check_leg->location_to!=='') ? $check_leg->location_to : ''
                                            ];

                                            //$insert_leg = [];
                                            //start backup
                                            /*
                                            $insert_leg['schedule_unique_id'] = $getSchedule->id;
                                            /*
                                            $insert_leg = [
                                                'schedule_id' => $get_schedule_id
                                            ];*/
                                            /*

                                            if (isset($insert_leg['shipment_id'])) {
                                                $insert_leg['shipment_id'] = $item->id;
                                            }

                                            if (isset($check_leg->eta)) {
                                                if ($check_leg->eta!='' && $check_leg->eta!=null) {
                                                    $insert_leg['eta'] = $check_leg->eta;
                                                }
                                            }

                                            if (isset($check_leg->mode)) {
                                                if ($check_leg->mode!='' && $check_leg->mode!=null) {
                                                    $insert_leg['mode'] = $check_leg->mode;
                                                }
                                            }

                                            if (isset($check_leg->location_to)) {
                                                if ($check_leg->location_to!='' && $check_leg->location_to!=null) {
                                                    $insert_leg['location_to'] = $check_leg->location_to;
                                                }
                                            }*/

                                            //end edit

                                            //check if it is already in the schedule leg table
                                            $checkScheduleLeg = \DB::table('shipment_schedules_legs')
                                                                //->where('schedule_id', $getSchedule->id)
                                                                ->where('schedule_unique_id', $getSchedule->id)
                                                                ->where('unique_id', $check_leg->id);


                                            if (isset($checkScheduleLeg->schedule_id)) {
                                                \DB::table('shipment_schedules_legs')
                                                //->where('schedule_id', $getSchedule->id)
                                                ->where('unique_id', $check_leg->id)
                                                ->update($insert_leg);
                                            } else {
                                                array_push($push_legs, $insert_leg);
                                                //\DB::table('shipment_schedules_legs')->insert($insert_leg);
                                            }

                                            //array_push($push_legs, $insert_leg);
                                        }
                                    } else {
                                        \DB::table('shipment_schedules_legs')
                                        ->where('shipment_id', $item->id)
                                        ->delete();
                                    }


                                    //\DB::table('shipment_schedules_legs')->insert($push_legs);
                                }
                            }

                            //get all added buy rates if there are any
                            if (isset($getSchedule->buy_rates)) {
                                $check_buy_rates = $getSchedule->buy_rates;
                                // $push_legs = [];
                                if (isset($check_buy_rates) && isset($check_buy_rates[0])) {
                                    $selectedBuyRates = [];

                                    foreach ($check_buy_rates as $check_buy_rate) {
                                        array_push($selectedBuyRates, $check_buy_rate->id);
                                    }

                                    //delete buy rates data that not are not listed
                                    \DB::table('shipment_schedules_buyrates')
                                    //->whereNotIn('supplier_id',$selectedSuppliers)
                                    ->whereNotIn('unique_id', $selectedBuyRates)
                                    ->where('shipment_id', $item->id)
                                    ->where('schedule_unique_id', $getSchedule->id)
                                    ->delete();

                                    if (count($check_buy_rates) > 0) {
                                        foreach ($check_buy_rates as $check_buy_rate) {
                                            $insert_buy_rate = [
                                                'unique_id' => $check_buy_rate->id,
                                                'schedule_unique_id' => $getSchedule->id,
                                                'shipment_id' => (isset($item->id) && $item->id!==null && $item->id!=='') ? $item->id : '',
                                                'service_id' => (isset($check_buy_rate->service_id) && $check_buy_rate->service_id!==null && $check_buy_rate->service_id!=='') ? $check_buy_rate->service_id : '',
                                                'container_size_id' => (isset($check_buy_rate->container_size_id) && $check_buy_rate->container_size_id!==null && $check_buy_rate->container_size_id!=='') ? $check_buy_rate->container_size_id : '',
                                                'amount' => (isset($check_buy_rate->amount) && $check_buy_rate->amount!==null && $check_buy_rate->amount!=='') ? $check_buy_rate->amount : '',
                                                'qty' => (isset($check_buy_rate->qty) && $check_buy_rate->qty!==null && $check_buy_rate->qty!=='') ? $check_buy_rate->qty : ''
                                            ];

                                            /*
                                            //$insert_leg = [];
                                            $insert_buy_rate['schedule_unique_id'] = $getSchedule->id;

                                            if (isset($item->id)) {
                                                $insert_buy_rate['shipment_id'] = $item->id;
                                            }

                                            if (isset($check_buy_rate->service_id)) {
                                                $insert_buy_rate['service_id'] = $check_buy_rate->service_id;
                                            }

                                            if (isset($check_buy_rate->container_size_id)) {
                                                $insert_buy_rate['container_size_id'] = $check_buy_rate->service_id;
                                            }

                                            if (isset($check_buy_rate->amount)) {
                                                $insert_buy_rate['amount'] = $check_buy_rate->amount;
                                            }

                                            if (isset($check_buy_rate->qty)) {
                                                $insert_buy_rate['qty'] = $check_buy_rate->amount;
                                            }*/


                                            //check if it is already in the schedule leg table
                                            $checkBuyRate = \DB::table('shipment_schedules_buyrates')
                                                                    //->where('schedule_id', $getSchedule->id)
                                                                    ->where('schedule_unique_id', $getSchedule->id)
                                                                    ->where('unique_id', $check_buy_rate->id);

                                            if (isset($checkBuyRate->schedule_id)) {
                                                \DB::table('shipment_schedules_buyrates')
                                                    //->where('schedule_id', $getSchedule->id)
                                                    ->where('unique_id', $check_buy_rate->id)
                                                    ->update($insert_buy_rate);
                                            } else {
                                                array_push($push_buy_rates, $insert_buy_rate);
                                                //\DB::table('shipment_schedules_legs')->insert($insert_leg);
                                            }

                                            //array_push($push_legs, $insert_leg);
                                        }
                                    }


                                    //\DB::table('shipment_schedules_legs')->insert($push_legs);
                                } else {
                                    \DB::table('shipment_schedules_buyrates')
                                    ->where('shipment_id', $item->id)
                                    ->delete();
                                }
                            } else {
                                \DB::table('shipment_schedules_buyrates')
                                ->where('shipment_id', $item->id)
                                ->delete();
                            }


                            //get all added sell rates if there are any
                            if (isset($getSchedule->sell_rates)) {
                                $check_sell_rates = $getSchedule->sell_rates;
                                // $push_legs = [];
                                if (isset($check_sell_rates) && isset($check_sell_rates[0])) {
                                    $selectedSellRates = [];
                                    foreach ($check_sell_rates as $check_sell_rate) {
                                        array_push($selectedSellRates, $check_sell_rate->id);
                                    }

                                    //delete sell rates data that not are not listed
                                    \DB::table('shipment_schedules_sellrates')
                                    //->whereNotIn('supplier_id',$selectedSuppliers)
                                    ->whereNotIn('unique_id', $selectedSellRates)
                                    ->where('shipment_id', $item->id)
                                    ->where('schedule_unique_id', $getSchedule->id)
                                    ->delete();

                                    if (count($check_sell_rates) > 0) {
                                        foreach ($check_sell_rates as $check_sell_rate) {
                                            $insert_sell_rate = [
                                                'schedule_unique_id' => $getSchedule->id,
                                                'shipment_id' => (isset($item->id)) ? $item->id : '',
                                                'service_id' => (isset($check_sell_rate->service_id) && $check_sell_rate->service_id!==null && $check_sell_rate->service_id!=='') ? $check_sell_rate->service_id : '',
                                                'container_size_id' => (isset($check_sell_rate->container_size_id) && $check_sell_rate->container_size_id!==null && $check_sell_rate->container_size_id!=='') ? $check_sell_rate->container_size_id : '',
                                                'amount' => (isset($check_sell_rate->amount) && $check_sell_rate->amount!==null && $check_sell_rate->amount!=='') ? $check_sell_rate->service_id : '',
                                                'qty' => (isset($check_sell_rate->qty) && $check_sell_rate->qty!==null && $check_sell_rate->qty!=='') ? $check_sell_rate->qty : '',
                                                'unique_id' => $check_sell_rate->id
                                            ];


                                            //$insert_leg = [];
                                            /*
                                            $insert_sell_rate['schedule_unique_id'] = $getSchedule->id;

                                            if (isset($item->id)) {
                                                $insert_sell_rate['shipment_id'] = $item->id;
                                            }

                                            if (isset($check_sell_rate->service_id)) {
                                                $insert_sell_rate['service_id'] = $check_sell_rate->service_id;
                                            }

                                            if (isset($check_sell_rate->container_size_id)) {
                                                $insert_sell_rate['container_size_id'] = $check_sell_rate->service_id;
                                            }

                                            if (isset($check_sell_rate->amount)) {
                                                $insert_sell_rate['amount'] = $check_sell_rate->amount;
                                            }

                                            if (isset($check_sell_rate->qty)) {
                                                $insert_sell_rate['qty'] = $check_sell_rate->qty;
                                            }*/

                                            //check if it is already in the schedule leg table
                                            $checkSellRate = \DB::table('shipment_schedules_sellrates')
                                                                    //->where('schedule_id', $getSchedule->id)
                                                                    ->where('schedule_unique_id', $getSchedule->id)
                                                                    ->where('unique_id', $check_sell_rate->id);

                                            if (isset($checkSellRate->schedule_id)) {
                                                \DB::table('shipment_schedules_sellrates')
                                                    //->where('schedule_id', $getSchedule->id)
                                                    ->where('unique_id', $check_sell_rate->id)
                                                    ->update($insert_sell_rate);
                                            } else {
                                                array_push($push_sell_rates, $insert_sell_rate);
                                                //\DB::table('shipment_schedules_legs')->insert($insert_leg);
                                            }

                                            //array_push($push_legs, $insert_leg);
                                        }
                                    }


                                    //\DB::table('shipment_schedules_legs')->insert($push_legs);
                                } else {
                                    \DB::table('shipment_schedules_sellrates')
                                    ->where('shipment_id', $item->id)
                                    ->delete();
                                }
                            } else {
                                \DB::table('shipment_schedules_sellrates')
                                    ->where('shipment_id', $item->id)
                                    ->delete();
                            }


                            $buildUpdate['updated_at'] = Carbon::now();

                            \DB::table('shipment_schedules')
                            ->where('id', $checkSchedule->id)
                            ->update($buildUpdate);
                        }
                    }
                }
            }
        } else {
            $item->schedules_group_bookings = [];
        }


        if (isset($setSchedules)) {
            if (count($setSchedules)>0) {
                $updateShipmentSchedule = [];
                \DB::table('shipment_schedules')->insert($setSchedules[0]);

                //  \DB::table('shipments')->where('id',$item->id)
                //                       ->update($updateShipmentSchedule);

                if (isset($setSchedules[1])) {
                    \DB::table('shipment_schedules')->insert($setSchedules[1]);
                }



                if (isset($getSchedules[0])) {
                    $pass = false;
                    $updateShipment = [];
                    if (isset($getSchedules[0]->etd)) {
                        $pass = true;
                        if ($getSchedules[0]->etd!='' && $getSchedules[0]->etd!=null) {
                            $updateShipment['etd'] = $getSchedules[0]->etd;
                        }
                    }


                    if (isset($getSchedules[0]->eta)) {
                        $pass = true;
                        if ($getSchedules[0]->eta!='' && $getSchedules[0]->eta!=null) {
                            $updateShipment['eta'] = $getSchedules[0]->eta;
                        }
                    }

                    if (isset($updateShipment['etd']) || isset($updateShipment['eta'])) {
                        //  \DB::table('shipments')->where('id', $item->id)
                        //            ->update($updateShipment);
                    }

                    //$getSchedules[0]->etd =  Carbon::parse($item->etd)->format('Y-m-d');
                    //$getSchedules[0]->eta = Carbon::parse($item->eta)->format('Y-m-d');

            //\DB::table('shipments')->where('id',$item->id)
            //                    ->update(['schedules_group' => json_encode($getSchedules)]);
                }
            }
        }

        //schedules for bookings
        if (isset($setSchedulesBookings)) {


            //if there are inserted schedules
            if (count($setSchedulesBookings)>0) {
                $updateShipmentSchedule = [];

                /*
                \DB::table('shipment_schedules')->insert($setSchedulesBookings[0]);

                //  \DB::table('shipments')->where('id',$item->id)
                //                       ->update($updateShipmentSchedule);

                if (isset($setSchedulesBookings[1])) {
                    \DB::table('shipment_schedules')->insert($setSchedulesBookings[1]);
                }*/

                if (count($push_legs) > 0) {
                    \DB::table('shipment_schedules_legs')->insert($push_legs);
                }

                if (count($push_buy_rates) > 0) {
                    \DB::table('shipment_schedules_buyrates')->insert($push_buy_rates);
                }



                if (count($push_sell_rates) > 0) {
                    \DB::table('shipment_schedules_sellrates')->insert($push_sell_rates);
                }

                \DB::table('shipment_schedules')->insert($setSchedulesBookings);


                /*
                if (isset($getSchedules[0])) {
                    $pass = false;
                    $updateShipment = [];
                    if (isset($getSchedules[0]->etd)) {
                        $pass = true;
                        if ($getSchedules[0]->etd!='' && $getSchedules[0]->etd!=null) {
                            $updateShipment['etd'] = $getSchedules[0]->etd;
                        }
                    }


                    if (isset($getSchedules[0]->eta)) {
                        $pass = true;
                        if ($getSchedules[0]->eta!='' && $getSchedules[0]->eta!=null) {
                            $updateShipment['eta'] = $getSchedules[0]->eta;
                        }
                    }

                    if (isset($updateShipment['etd']) || isset($updateShipment['eta'])) {
                        //  \DB::table('shipments')->where('id', $item->id)
                        //            ->update($updateShipment);
                    }
                }*/

                    //$getSchedules[0]->etd =  Carbon::parse($item->etd)->format('Y-m-d');
                    //$getSchedules[0]->eta = Carbon::parse($item->eta)->format('Y-m-d');

            //\DB::table('shipments')->where('id',$item->id)
            //                    ->update(['schedules_group' => json_encode($getSchedules)]);
              //  }
            }
        }
    }

    public static function boot()
    {
        parent::boot();
        //$thisHere = $this;
        static::saving(function (Shipment $item) {
            if (!isset($item->noSync)) {
                self::$noSync = false;
            } else {
                self::$noSync = true;
            }

            unset($item->noSync);
        });

        static::updated(function (Shipment $item) {
            if (!self::$noSync) {
                static::synchronise($item);
            }
        });


        static::created(function (Shipment $item) {
            static::synchronise($item);
        });
    }


    public function containers()
    {
        return $this->hasMany(Container::class);
    }

    public function suppliers()
    {
        return $this->belongsToMany(Supplier::class, 'shipment_suppliers', 'shipment_id', 'supplier_id');
    }

    public function shipmentSuppliers()
    {
        return $this->hasMany(ShipmentSupplier::class);
    }

    public function shipmentCharges()
    {
        return $this->hasMany(ShipmentCharge::class);
    }

    public function shipmentDeliveryInfos()
    {
        return $this->hasMany(ShipmentDeliveryInfo::class);
    }

    public function shipmentSchedules()
    {
        return $this->hasMany(ShipmentSchedule::class);
    }

    public function shipmentSellCharges()
    {
        return $this->hasMany(ShipmentSellCharge::class);
    }

    public function terminal()
    {
        return $this->belongsTo('App\Terminal');
    }
    public function trucker()
    {
        return $this->belongsTo('App\Trucker');
    }

    public function invoice(){
        return $this->hasMany(Invoice::class);
    }

    public function bill(){
        return $this->hasMany(Bill::class);
    }


    // custom functions

    public function isValidForReport()
    {
        return (!($this->cancelled ?? true)) && \Carbon\Carbon::parse($this->created_at)->gt(\Carbon\Carbon::parse("2020-05-01"));
    }

    public function getStatus()
    {
        $shipment_status = '';

        if ($this->shipment_status != "Completed") {
            if ($this->booking_confirmed === 0) {
                $shipment_status = "Pending Approval";
            }
            if ($this->booking_confirmed === 1) {
                //
                if (Carbon::parse($this->etd)->gt(Carbon::now())) {
                    $shipment_status = "Awaiting Departure";
                } else {
                    $shipment_status = "In Transit";
                }
            }
            if (Carbon::now()->gt(Carbon::parse($this->eta)) && Carbon::parse($this->eta)->diffInDays(Carbon::now()) >= 10) {
                $shipment_status = "Completed";
            }
        }
        return $shipment_status;
    }

    public function isCompleted()
    {
        return $this->getStatus() === "Completed";
    }

    public function isInCompleted()
    {
        return !($this->isCompleted());
    }

    public function shipmentInvoices()
    {
        return $this->hasMany(Invoice::class, 'shipment_id', 'id');
    }

    public function originCountry()
    {
        return $this->belongsTo(Country::class);
    }

    public function destinationCountry()
    {
        return $this->belongsTo(Country::class);
    }

    public functon getSellRatesAttribute($item){
        if( $item && !is_null($item) ){
            return false;
        }

        try {
            return json_decode($item);    
        }catch(Exception $err){
            return false;
        }
    }

    public functon getBuyRatesAttribute($item){
        if( $item && !is_null($item) ){
            return false;
        }

        try {
            return json_decode($item);    
        }catch(Exception $err){
            return false;
        }
    }
}
