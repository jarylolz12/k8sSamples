<?php

namespace App;

use App\Custom\CustomJWTGenerator;
use DateTime;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Laravel\Nova\Nova;
use Laravel\Scout\Searchable;
use Whitecube\NovaFlexibleContent\Concerns\HasFlexible;
use Carbon\Carbon;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\File;

use App\Bill;
use App\Invoice;
use App\InvoiceService;
use App\BillItem;
// use App\Customer;
use Exception;
use Laravel\Nova\Actions\Actionable;
use App\ImportNames;

class Shipment extends Model
{
    use HasFlexible,Actionable;

    protected $guarded = ['id', 'created_at'];


    protected $casts = ['etd' => 'date', 'eta' => 'date','carrier_arrival_notice_eta' => 'date', 'containers_data' => 'array', 'tracking_info' => 'array', 'containers_array' => 'array', 'full_out_at_array' => 'array'];

    private static $noSync;
    private static $relatedCustomer = null;
    private static $multiPurchaseOrders = null;
    private static $personalToken = null;

    protected $appends = ['projected_profit', 'profitt','sort_score','containers_qty'];

    //protected $fillable = ['services_section'];

    public function getSortScoreAttribute(){

        /* check if contains number
        preg_match('~[0-9]+~', $this->mbl_num)
        */

        if( empty($this->mbl_num) ){ /* check if blank */
            return 4;
        }elseif( strpos(((string)$this->mbl_num),'0') === 0){
            return 3;
        }elseif( is_numeric($this->mbl_num) ){ /* check if number */
            return 2;
        }else{
            $str = $this->mbl_num;
            $a = false;

            foreach( str_split($str) as $k => $v ){
                if( is_numeric($v) && $k == 0 ){
                  $a = true;
                  break;
                }
            }

            // if 1 for string with numbers on front, otherwise 0
            return $a ? 1 : 0;
        }
    }

    public function shipmentMetas()
    {
        return $this->hasOne(ShipmentMeta::class, 'shipment_id');
    }

    public function customerDocuments()
    {
        return $this->hasMany(Document::class);
    }

    public function terminal_fortynine()
    {
        return $this->belongsTo(Terminal49Shipment::class, 'mbl_num');
    }

    public function terminal_fortynineMany(){
        return $this->hasMany(Terminal49Shipment::class, 'mbl_num', 'mbl_num');
    }

    function etalogs(){
        return $this->hasMany('App\EtaLog', 'mbl_num', 'mbl_num');
    }

    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    public function officeFrom()
    {
        return $this->belongsTo(ShiflOffice::class, 'shifl_office_origin_from_id');
    }

    public function officeTo()
    {
        return $this->belongsTo(ShiflOffice::class, 'shifl_office_origin_to_id');
    }


    public function carrier()
    {
        return $this->belongsTo(Carrier::class);
    }

    public function terminalRegions()
    {
        return $this->belongsToMany(TerminalRegion::class, 'shipments_terminal_regions', 'shipment_id', 'terminal_region_id');
    }


    public function getProfittAttribute()
    {

        $invoices = $this->invoice;
        $bills = $this->bill;
        $total_invoice_amount = 0;
        $total_bill_amount = 0;
        $service_amount = 0;

        if (count($invoices) > 0) {
            foreach($invoices as $invoice) {
                $i = json_decode($invoice,true);
                //$invoice_services = InvoiceService::where('invoice_id', $invoice->id)->get();
                $invoice_services = isset($i['invoice_services']) ? $i['invoice_services'] : [];

                $invoice_sub_total = 0;
                if (isset($invoice_services)) {
                    foreach( $invoice_services as $service) {
                        if (isset($service['quantity']) && isset($service['rate'])) {
                            $service_amount = floatval($service['quantity']) * floatval($service['rate']);
                        } else {
                            $service_amount = 0;
                        }
                        $invoice_sub_total += $service_amount;
                    }
                }
                $total_invoice_amount += $invoice_sub_total;
            }
        }

        if (count($bills) > 0) {
            foreach($bills as $bill) {
                //$bill_items = BillItem::where('bill_id', $bill->id)->get();
                $bill_items = $bill['bill_items'];
                $bill_sub_total = 0;
                if (isset($bill_items)) {
                    foreach( $bill_items as $bill_item) {
                        $bill_sub_total += floatval($bill_item['qbo_amount']);
                    }
                }
                $total_bill_amount += $bill_sub_total;
            }
        }
        return floatval($total_invoice_amount - $total_bill_amount);
    }

    public function getProjectedProfitAttribute(){
        $total_sell_rate = 0;
        $total_buy_rate = 0;
        $sell_rates_exist = false;
        $buy_rates_exist = false;

        $schedules_group_bookings = [];

        if( !empty($this->schedules_group_bookings) ){
            $schedules_group_bookings = json_decode($this->schedules_group_bookings);
        }else{
            return 'Missing sell rates and buy
            rates';
        }

        $selected_schedule = collect($schedules_group_bookings)->firstWhere('is_confirmed',1);

        if( empty($selected_schedule) ){
            return 'No selected schedule';
        }


        if( isset($selected_schedule->sell_rates) ){
            $total_sell_rate = collect($selected_schedule->sell_rates)->map(function($item){
                return ( !isset($item->amount) || !isset($item->qty) || !is_numeric($item->qty) || !is_numeric($item->amount) ) ? 0 : (float)$item->amount * $item->qty;
            })
            ->reduce(function($a,$b){
                return $a + $b;
            },0);

            $sell_rates_exist = true;
        }


        if( isset($selected_schedule->buy_rates) ){
            $total_buy_rate = collect($selected_schedule->buy_rates)->map(function($item){
                return ( !isset($item->amount) || !isset($item->qty) || !is_numeric($item->qty) || !is_numeric($item->amount) ) ? 0 : (float)$item->amount * $item->qty;
            })
            ->reduce(function($a,$b){
                return $a + $b;
            },0);

            $buy_rates_exist = true;
        }


        if( !$sell_rates_exist  && !$buy_rates_exist ){
            return 'No sell rate and buy rate';
        }

        if( !$sell_rates_exist && $buy_rates_exist ){
            return 'No sell rate';
        }

        if( $sell_rates_exist && !$buy_rates_exist ){
            return 'No buy rate';
        }

        $projected_profit = (float)($total_sell_rate - $total_buy_rate);

        $expo = pow(10,3);

        setlocale(LC_MONETARY,"en_US");

        return number_format(intval($projected_profit*$expo)/$expo, 2);

    }

    private static function synchronise($item)
    {

        /* suppliers group */
        if (isset($item->suppliers_group) && $item->suppliers_group != '') {
            $getSuppliers = json_decode($item->suppliers_group);
            $setSuppliers = [];

            if ($getSuppliers != '') {
                if (count($getSuppliers) > 0) {
                    $selectedSuppliers = [];
                    //map all current supplier id
                    foreach ($getSuppliers as $getSupplier) {
                      if(!empty($getSupplier->supplier_id)) {
                          array_push($selectedSuppliers, $getSupplier->id);
                      }
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

                        $getSupplier->cargo_ready_date = (isset($getSupplier->cargo_ready_date) && $getSupplier->cargo_ready_date !== null && $getSupplier->cargo_ready_date !== '') ? $getSupplier->cargo_ready_date : null;

                        $getSupplier->hbl_num =  (isset($getSupplier->hbl_num) && $getSupplier->hbl_num != '') ? $getSupplier->hbl_num : null;
                        $getSupplier->kg =  (isset($getSupplier->kg) && $getSupplier->kg != '') ? $getSupplier->kg : null;
                        $getSupplier->cbm =  (isset($getSupplier->cbm) && $getSupplier->cbm != '') ? $getSupplier->cbm : null;
                        $getSupplier->incoterm_id =  (isset($getSupplier->incoterm_id) && $getSupplier->incoterm_id != '') ? $getSupplier->incoterm_id : 0;
                        $getSupplier->product_description =  (isset($getSupplier->product_description) && $getSupplier->product_description != '') ? $getSupplier->product_description : null;
                        $getSupplier->product_description =  (isset($getSupplier->product_description) && $getSupplier->product_description != '') ? $getSupplier->product_description : null;
                        $getSupplier->total_cartons =  (isset($getSupplier->total_cartons) && $getSupplier->total_cartons != '') ? $getSupplier->total_cartons : null;
                        /* $getSupplier->po_id =  (isset($getSupplier->po_id) && $getSupplier->po_id!='') ? $getSupplier->po_id : null; */
                        $getSupplier->marks = (isset($getSupplier->marks) && $getSupplier->marks != '') ? $getSupplier->marks : null;
                        $getSupplier->bol_shipper_address = (isset($getSupplier->bol_shipper_address) && $getSupplier->bol_shipper_address != '') ? $getSupplier->bol_shipper_address : null;
                        $getSupplier->bol_shipper_phone_number = (isset($getSupplier->bol_shipper_phone_number) && $getSupplier->bol_shipper_phone_number != '') ? $getSupplier->bol_shipper_phone_number : null;
                        $getSupplier->bol_consignee_address = (isset($getSupplier->bol_consignee_address) && $getSupplier->bol_consignee_address != '') ? $getSupplier->bol_consignee_address : null;
                        $getSupplier->bol_consignee_phone_number = (isset($getSupplier->bol_consignee_phone_number) && $getSupplier->bol_consignee_phone_number != '') ? $getSupplier->bol_consignee_phone_number : null;
                        $getSupplier->bol_notify_party = (isset($getSupplier->bol_notify_party) && $getSupplier->bol_notify_party != '') ? $getSupplier->bol_notify_party : null;
                        $getSupplier->bol_notify_address = (isset($getSupplier->bol_notify_address) && $getSupplier->bol_notify_address != '') ? $getSupplier->bol_notify_address : null;
                        $getSupplier->bol_notify_phone_number = (isset($getSupplier->bol_notify_phone_number) && $getSupplier->bol_notify_phone_number != '') ? $getSupplier->bol_notify_phone_number : null;
                        if (!isset($checkSupplier->id)) {
                            array_push(
                                $setSuppliers,
                                [
                                    'shipment_id' => $item->id,
                                    'supplier_id' => $getSupplier->supplier_id,
                                    'buyer_id' => $getSupplier->buyer_id,
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
                                    'commercial_invoice' => !is_object($getSupplier->commercial_invoice) ? $getSupplier->commercial_invoice : '',
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
                                    'po_num' => $getSupplier->po_num,
                                    'supplier_id' => $getSupplier->supplier_id,
                                    'buyer_id' => $getSupplier->buyer_id,
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

        if (isset($setSuppliers) && count($setSuppliers) > 0) {
            if (count($push_suppliers_containers) > 0) {
                \DB::table('shipment_suppliers_containers')->insert($push_suppliers_containers);
            }

            \DB::table('shipment_suppliers')->insert($setSuppliers);
        }

        /* suppliers bookings group */
        if (isset($item->suppliers_group_bookings) && $item->suppliers_group_bookings != '' && !empty($item->suppliers_group_bookings)) {
            $getSuppliers = json_decode($item->suppliers_group_bookings);
            $setSuppliers = [];

            if ($getSuppliers != '') {
                if (count($getSuppliers) > 0) {
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

                        $getSupplier->cargo_ready_date = (isset($getSupplier->cargo_ready_date) && $getSupplier->cargo_ready_date !== null && $getSupplier->cargo_ready_date !== '') ? $getSupplier->cargo_ready_date : null;
                        $getSupplier->hbl_num =  (isset($getSupplier->hbl_num) && $getSupplier->hbl_num != '') ? $getSupplier->hbl_num : null;
                        $getSupplier->kg =  (isset($getSupplier->kg) && $getSupplier->kg != '') ? $getSupplier->kg : null;
                        $getSupplier->cbm =  (isset($getSupplier->cbm) && $getSupplier->cbm != '') ? $getSupplier->cbm : null;
                        $getSupplier->incoterm_id =  (isset($getSupplier->incoterm_id) && $getSupplier->incoterm_id != '') ? $getSupplier->incoterm_id : 0;
                        $getSupplier->product_description =  (isset($getSupplier->product_description) && $getSupplier->product_description != '') ? $getSupplier->product_description : null;
                        $getSupplier->product_description =  (isset($getSupplier->product_description) && $getSupplier->product_description != '') ? $getSupplier->product_description : null;
                        $getSupplier->total_cartons =  (isset($getSupplier->total_cartons) && $getSupplier->total_cartons != '') ? $getSupplier->total_cartons : null;
                        /* $getSupplier->po_id =  (isset($getSupplier->po_id) && $getSupplier->po_id!='') ? $getSupplier->po_id : null; */

                        if (!isset($checkSupplier->id)) {
                        array_push($setSuppliers, ['cargo_ready_date' => $getSupplier->cargo_ready_date, 'shipment_id' => $item->id, 'supplier_id' => $getSupplier->supplier_id, 'buyer_id' => $getSupplier->buyer_id ?? null, 'kg' => $getSupplier->kg, 'cbm' => $getSupplier->cbm, 'incoterm_id' => $getSupplier->incoterm_id, 'hbl_num' => $getSupplier->hbl_num, 'product_description' => $getSupplier->product_description, 'total_cartons' => $getSupplier->total_cartons, 'ams_num' => $getSupplier->ams_num, 'bl_status' => $getSupplier->bl_status, 'po_num' => $getSupplier->po_num, 'unique_id' => $getSupplier->id, 'hbl_copy' => !is_object($getSupplier->hbl_copy) ? $getSupplier->hbl_copy : '', 'packing_list' => !is_object($getSupplier->packing_list) ? $getSupplier->packing_list : '', 'commercial_documents' => !is_object($getSupplier->commercial_documents) ? $getSupplier->commercial_documents : '', 'commercial_invoice' => !is_object($getSupplier->commercial_invoice) ? $getSupplier->commercial_invoice : ''/* ,'po_id' => $getSupplier->po_id */]);
                        } else {
                            \DB::table('shipment_suppliers')
                                ->where('id', $checkSupplier->id)
                                //->where('shipment_id',$item->id)
                                //->where('supplier_id',$getSupplier->supplier_id)
                                ->update(['cargo_ready_date' => $getSupplier->cargo_ready_date, 'kg' => $getSupplier->kg, 'cbm' => $getSupplier->cbm, 'incoterm_id' => $getSupplier->incoterm_id, 'hbl_num' => $getSupplier->hbl_num, 'product_description' => $getSupplier->product_description, 'total_cartons' => $getSupplier->total_cartons, 'ams_num' => $getSupplier->ams_num, 'bl_status' => $getSupplier->bl_status, 'po_num' => $getSupplier->po_num, 'supplier_id' => $getSupplier->supplier_id, 'buyer_id' => $getSupplier->buyer_id, 'hbl_copy' => !is_object($getSupplier->hbl_copy) ? $getSupplier->hbl_copy : '', 'packing_list' => !is_object($getSupplier->packing_list) ? $getSupplier->packing_list : '', 'commercial_documents' => !is_object($getSupplier->commercial_documents) ? $getSupplier->commercial_documents : '', 'commercial_invoice' => !is_object($getSupplier->commercial_invoice) ? $getSupplier->commercial_invoice : ''/* ,'po_id' => intval($getSupplier->po_id) */]);
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

        if (isset($setSuppliers) && count($setSuppliers) > 0) {
            \DB::table('shipment_suppliers')->insert($setSuppliers);
        }

        /* container group */
        if (isset($item->containers_group) && $item->containers_group != '') {
            $getContainers = json_decode($item->containers_group);
            $setContainers = [];

            if ($getContainers != '') {
                if (count($getContainers) > 0) {
                    $selectedContainers = [];

                    foreach ($getContainers as $key => $getContainer) {
                        array_push($selectedContainers, $getContainer->id);

                        $checkContainer = \DB::table('containers')
                            ->where('unique_id', $getContainer->id)
                            ->where('shipment_id', $item->id)
                            ->first();

                        if (!isset($checkContainer->id)) {
                            array_push($setContainers, ['shipment_id' => $item->id, 'unique_id' => $getContainer->id, 'size' => intval($getContainer->size), 'cbm' => intval($getContainer->cbm), 'kg' => intval($getContainer->kg), 'cartons' => intval($getContainer->cartons), 'container_num' => $getContainer->container_num, 'seal_num' => isset($getContainer->seal_num) ? $getContainer->seal_num : null]);
                        } else {
                            \DB::table('containers')
                                ->where('id', $checkContainer->id)
                                ->update(['shipment_id' => $item->id, 'size' => intval($getContainer->size), 'cbm' => $getContainer->cbm != "" ? intval($getContainer->cbm) : 0, 'kg' => intval($getContainer->kg), 'cartons' => intval($getContainer->cartons), 'container_num' => $getContainer->container_num, 'seal_num' => isset($getContainer->seal_num) ? $getContainer->seal_num : null]);
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

        if (isset($setContainers) && count($setContainers) > 0) {
            \DB::table('containers')->insert($setContainers);
        }

        if (isset($item->containers_group_bookings) && $item->containers_group_bookings != '') {
            $getContainers = json_decode($item->containers_group_bookings);
            $setContainers = [];

            if ($getContainers != '') {
                if (count($getContainers) > 0) {
                    $selectedContainers = [];
                    //map all current container id
                    foreach ($getContainers as $getContainer) {
                        // array_push($selectedSuppliers, $getSupplier->supplier_id);
                        array_push($selectedContainers, $getContainer->id);
                    }

                    if (count($selectedContainers) > 0) {

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
                            array_push($setContainers, ['shipment_id' => $item->id, 'unique_id' => $getContainer->id, 'size' => intval($getContainer->size), 'cbm' => intval($getContainer->cbm), 'kg' => intval($getContainer->kg), 'cartons' => intval($getContainer->cartons), 'container_num' => $getContainer->container_num, 'seal_num' => isset($getContainer->seal_num) ? $getContainer->seal_num : null]);
                        } else {
                            \DB::table('containers')
                                ->where('id', $checkContainer->id)
                                ->update(['shipment_id' => $item->id, 'size' => intval($getContainer->size), 'cbm' => intval($getContainer->cbm), 'kg' => intval($getContainer->kg), 'cartons' => intval($getContainer->cartons), 'container_num' => $getContainer->container_num, 'seal_num' => isset($getContainer->seal_num) ? $getContainer->seal_num : null]);
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

        if (isset($setContainers) && count($setContainers) > 0) {
            \DB::table('containers')->insert($setContainers);
        }

        /* schedules group */
        if (isset($item->schedules_group) && $item->schedules_group != '') {
            $getSchedules = json_decode($item->schedules_group);
            $setSchedules = [];

            if ($getSchedules != '') {
                if (count($getSchedules) > 0) {
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
                            if ($key == 0) {
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
                                $push_schedules = ['shipment_id' => $item->id, 'location_from' => (isset($getSchedule->location_from)) ? $getSchedule->location_from : '', 'location_to' => (isset($getSchedule->location_to)) ? $getSchedule->location_to : '', 'mode' => $getSchedule->mode, 'unique_id' => $getSchedule->id];

                                if (isset($getSchedule->eta)) {
                                    if ($getSchedule->eta != '' && $getSchedule->eta != null) {
                                        $push_schedules['eta_date'] = $getSchedule->eta;
                                    }
                                }

                                if (isset($getSchedule->etd)) {
                                    if ($getSchedule->etd != '' && $getSchedule->etd != null) {
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
                                $push_schedules = ['shipment_id' => $item->id, 'location_from' => '', 'location_to' => (isset($getSchedule->location_to)) ? $getSchedule->location_to : '', 'mode' => $getSchedule->mode, 'unique_id' => $getSchedule->id];
                                if (isset($getSchedule->eta)) {
                                    if ($getSchedule->eta != '' && $getSchedule->eta != null) {
                                        $push_schedules['eta_date'] = $getSchedule->eta;
                                    } else {
                                        $push_schedules['eta_date'] = null;
                                    }
                                } else {
                                    $push_schedules['eta_date'] = null;
                                }

                                if (isset($getSchedule->etd)) {
                                    if ($getSchedule->etd != '' && $getSchedule->etd != null) {
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
                            if (isset($getSchedule->is_confirmed) && $getSchedule->is_confirmed !== null && $getSchedule->is_confirmed == 1) {
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

                            if ($getSchedule->eta != '' && $getSchedule->eta != null) {
                                $buildUpdate['eta_date'] = $getSchedule->eta;
                            }

                            if ($getSchedule->etd != '' && $getSchedule->etd != null) {
                                $buildUpdate['etd_date'] = $getSchedule->etd;
                            }

                            if ($getSchedule->location_from != '' && $getSchedule->location_from != null) {
                                $buildUpdate['location_from'] = $getSchedule->location_from;
                            }

                            if ($getSchedule->location_to != '' && $getSchedule->location_to != null) {
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
            if (count($setSchedules) > 0) {
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
                        if ($getSchedules[0]->etd != '' && $getSchedules[0]->etd != null) {
                            $updateShipment['etd'] = $getSchedules[0]->etd;
                        }
                    }


                    if (isset($getSchedules[0]->eta)) {
                        $pass = true;
                        if ($getSchedules[0]->eta != '' && $getSchedules[0]->eta != null) {
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

        if (isset($item->schedules_group_bookings) && $item->schedules_group_bookings != '') {
            $getSchedules = (isset($item->schedules_group_bookings) && !is_array($item->schedules_group_bookings)) ? json_decode($item->schedules_group_bookings) : $item->schedules_group_bookings;
            $setSchedulesBookings = [];
            $push_legs = [];
            $push_buy_rates = [];
            $push_sell_rates = [];

            if ($getSchedules != '') {
                if (count($getSchedules) > 0) {
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
                            if ($key == 0) {
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

                            $push_schedules = ['shipment_id' => $item->id, 'location_from' => (isset($getSchedule->location_from)) ? $getSchedule->location_from : '', 'location_to' => (isset($getSchedule->location_to)) ? $getSchedule->location_to : '', 'mode' => $getSchedule->mode, 'unique_id' => $getSchedule->id, 'is_new' => 1, 'carrier_name' => (isset($getSchedule->carrier_name->name)) ? $getSchedule->carrier_name->name : '', 'size_id' => $getSchedule->size_id, 'price' => $getSchedule->price, 'is_confirmed' => 0];

                            if (isset($getSchedule->selling_size_id)) {
                                $push_schedules['selling_size_id'] = $getSchedule->selling_size_id;
                            }

                            if (isset($getSchedule->selling_price)) {
                                $push_schedules['selling_price'] = $getSchedule->selling_price;
                            }


                            if (isset($getSchedule->eta)) {
                                if ($getSchedule->eta != '' && $getSchedule->eta != null) {
                                    $push_schedules['eta_date'] = $getSchedule->eta;
                                }
                            }

                            if (isset($getSchedule->etd)) {
                                if ($getSchedule->etd != '' && $getSchedule->etd != null) {
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
                                            'eta' => (isset($check_leg->eta) && $check_leg->eta !== null && $check_leg->eta !== '') ? $check_leg->eta : '',
                                            'mode' => (isset($check_leg->mode) && $check_leg->mode !== null && $check_leg->mode !== '') ? $check_leg->mode : '',
                                            'location_to' => (isset($check_leg->location_to) && $check_leg->location_to !== null && $check_leg->location_to !== '') ? $check_leg->location_to : ''
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
                                            'shipment_id' => (isset($item->id) && $item->id !== null && $item->id !== '') ? $item->id : '',
                                            'service_id' => (isset($check_buy_rate->service_id) && $check_buy_rate->service_id !== null && $check_buy_rate->service_id !== '') ? $check_buy_rate->service_id : '',
                                            'container_size_id' => (isset($check_buy_rate->container_size_id) && $check_buy_rate->container_size_id !== null && $check_buy_rate->container_size_id !== '') ? $check_buy_rate->container_size_id : '',
                                            'amount' => (isset($check_buy_rate->amount) && $check_buy_rate->amount !== null && $check_buy_rate->amount !== '') ? $check_buy_rate->amount : '',
                                            'qty' => (isset($check_buy_rate->qty) && $check_buy_rate->qty !== null && $check_buy_rate->qty !== '') ? $check_buy_rate->qty : '',
                                            'valid_to' => (isset($check_buy_rate->valid_to) && $check_buy_rate->valid_to !== null && $check_buy_rate->valid_to !== '') ? $check_buy_rate->valid_to : ''
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
                                            'service_id' => (isset($check_sell_rate->service_id) && $check_sell_rate->service_id !== null && $check_sell_rate->service_id !== '') ? $check_sell_rate->service_id : '',
                                            'container_size_id' => (isset($check_sell_rate->container_size_id) && $check_sell_rate->container_size_id !== null && $check_sell_rate->container_size_id !== '') ? $check_sell_rate->container_size_id : '',
                                            'amount' => (isset($check_sell_rate->amount) && $check_sell_rate->amount !== null && $check_sell_rate->amount !== '') ? $check_sell_rate->service_id : '',
                                            'qty' => (isset($check_sell_rate->qty) && $check_sell_rate->qty !== null && $check_sell_rate->qty !== '') ? $check_sell_rate->qty : '',

                                            'valid_to' => (isset($check_sell_rate->valid_to) && $check_sell_rate->valid_to !== null && $check_sell_rate->valid_to !== '') ? $check_sell_rate->valid_to : null,
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
                            if (isset($getSchedule->is_confirmed) && $getSchedule->is_confirmed !== null && $getSchedule->is_confirmed == 1) {
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

                            if ($getSchedule->eta != '' && $getSchedule->eta != null) {
                                $buildUpdate['eta_date'] = $getSchedule->eta;
                            }

                            if ($getSchedule->etd != '' && $getSchedule->etd != null) {
                                $buildUpdate['etd_date'] = $getSchedule->etd;
                            }

                            if ($getSchedule->location_from != '' && $getSchedule->location_from != null) {
                                $buildUpdate['location_from'] = $getSchedule->location_from;
                            }

                            if ($getSchedule->location_to != '' && $getSchedule->location_to != null) {
                                $buildUpdate['location_to'] = $getSchedule->location_to;
                            }

                            if ($getSchedule->carrier_name != '' && $getSchedule->carrier_name != null && isset($getSchedule->carrier_name->name)) {
                                $buildUpdate['carrier_name'] = $getSchedule->carrier_name->name;
                            }

                            if ($getSchedule->mode != '' && $getSchedule->mode != null) {
                                $buildUpdate['mode'] = $getSchedule->mode;
                            }

                            if ($getSchedule->size_id != '' && $getSchedule->size_id != null) {
                                $buildUpdate['size_id'] = $getSchedule->size_id;
                            }

                            if ($getSchedule->is_confirmed != '' && $getSchedule->is_confirmed != null) {
                                $buildUpdate['is_confirmed'] = intval($getSchedule->is_confirmed);
                            }

                            if ($getSchedule->price != '' && $getSchedule->price != null) {
                                $buildUpdate['price'] = $getSchedule->price;
                            }

                            if (isset($getSchedule->selling_size_id) && $getSchedule->selling_size_id != '' && $getSchedule->selling_size_id != null) {
                                $buildUpdate['selling_size_id'] = $getSchedule->selling_size_id;
                            }

                            if (isset($getSchedule->selling_price) && $getSchedule->selling_price != '' && $getSchedule->selling_price != null) {
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
                                                'eta' => (isset($check_leg->eta) && $check_leg->eta !== null && $check_leg->eta !== '') ? $check_leg->eta : '',
                                                'mode' => (isset($check_leg->mode) && $check_leg->mode !== null && $check_leg->mode !== '') ? $check_leg->mode : '',
                                                'location_to' => (isset($check_leg->location_to) && $check_leg->location_to !== null && $check_leg->location_to !== '') ? $check_leg->location_to : ''
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
                                                'shipment_id' => (isset($item->id) && $item->id !== null && $item->id !== '') ? $item->id : '',
                                                'service_id' => (isset($check_buy_rate->service_id) && $check_buy_rate->service_id !== null && $check_buy_rate->service_id !== '') ? $check_buy_rate->service_id : '',
                                                'container_size_id' => (isset($check_buy_rate->container_size_id) && $check_buy_rate->container_size_id !== null && $check_buy_rate->container_size_id !== '') ? $check_buy_rate->container_size_id : '',
                                                'amount' => (isset($check_buy_rate->amount) && $check_buy_rate->amount !== null && $check_buy_rate->amount !== '') ? $check_buy_rate->amount : '',
                                                'qty' => (isset($check_buy_rate->qty) && $check_buy_rate->qty !== null && $check_buy_rate->qty !== '') ? $check_buy_rate->qty : '',
                                                'valid_to' => (isset($check_buy_rate->valid_to) && $check_buy_rate->valid_to !== null && $check_buy_rate->valid_to !== '') ? $check_buy_rate->valid_to : null
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
                                                'service_id' => (isset($check_sell_rate->service_id) && $check_sell_rate->service_id !== null && $check_sell_rate->service_id !== '') ? $check_sell_rate->service_id : '',
                                                'container_size_id' => (isset($check_sell_rate->container_size_id) && $check_sell_rate->container_size_id !== null && $check_sell_rate->container_size_id !== '') ? $check_sell_rate->container_size_id : '',
                                                'amount' => (isset($check_sell_rate->amount) && $check_sell_rate->amount !== null && $check_sell_rate->amount !== '') ? $check_sell_rate->service_id : '',
                                                'qty' => (isset($check_sell_rate->qty) && $check_sell_rate->qty !== null && $check_sell_rate->qty !== '') ? $check_sell_rate->qty : '',
                                                'valid_to' => (isset($check_sell_rate->valid_to) && $check_sell_rate->valid_to !== null && $check_sell_rate->valid_to !== '') ? $check_sell_rate->valid_to : '',
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
            if (count($setSchedules) > 0) {
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
                        if ($getSchedules[0]->etd != '' && $getSchedules[0]->etd != null) {
                            $updateShipment['etd'] = $getSchedules[0]->etd;
                        }
                    }


                    if (isset($getSchedules[0]->eta)) {
                        $pass = true;
                        if ($getSchedules[0]->eta != '' && $getSchedules[0]->eta != null) {
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
            if (count($setSchedulesBookings) > 0) {
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

            //Pass request customer to static $relatedCustomer variable
            if (isset($item->custom_customer)) {
                self::$relatedCustomer = $item->custom_customer;
                unset($item->custom_customer);
            }

            if (isset($item->multi_purchase_orders)) {
                self::$multiPurchaseOrders = $item->multi_purchase_orders;
                if (isset($item->id)) {
                    $jwtToken = CustomJWTGenerator::generateToken();

                    $poInstanceClient = new \GuzzleHttp\Client([
                        'base_uri' => getenv('PO_URL'),
                        'verify' => false,
                        'headers' => [
                            'Content-Type' => 'application/json',
                            'Accept'     => 'application/json',
                            'Authorization'  => 'Bearer ' . $jwtToken,
                        ]
                    ]);

                    $res = $poInstanceClient->put('/api/po/purchase-orders/products', [
                        "json" => [
                            'multi_purchase_orders' => self::$multiPurchaseOrders,
                            'shipment_id' => $item->id,
                        ]
                    ]);
                }
            }

            if (isset($item->personal_token)) {
                self::$personalToken = $item->personal_token;
            }
            unset($item->personal_token);
            unset($item->multi_purchase_orders);
            unset($item->noSync);
        });

        static::updated(function (Shipment $item) {
            if (!self::$noSync) {
                static::synchronise($item);
            }
            if (self::$relatedCustomer != null) {
                $item->customer()->associate(self::$relatedCustomer);
                self::$relatedCustomer = null;
            }
        });
        static::updating(function (Shipment $item) {
            if(array_key_exists('cancelled', $item->getDirty())){
                if($item->getDirty()['cancelled'] == 1){
                    $actionLogData = Nova::actionEvent()->forResourceUpdate(Auth::user(), $item)->toArray();
                    $actionLogData['original'] = json_encode($actionLogData['original']);
                    $actionLogData['changes'] = json_encode($actionLogData['changes']);
                    $actionLogData['name'] = 'Cancel Shipment';
                    $actionLogData['created_at'] = new DateTime;
                    $actionLogData['updated_at'] = new DateTime;
                    DB::connection()->table('action_events')->insert($actionLogData);
                }
            } else if(!array_key_exists('shipment_status', $item->getDirty())){
                $actionLogData = Nova::actionEvent()->forResourceUpdate(Auth::user(), $item)->toArray();
                $actionLogData['original'] = json_encode($actionLogData['original']);
                $actionLogData['changes'] = json_encode($actionLogData['changes']);
                $actionLogData['name'] = 'General Edits';
                $actionLogData['created_at'] = new DateTime;
                $actionLogData['updated_at'] = new DateTime;
                DB::connection()->table('action_events')->insert($actionLogData);
            }

        });
        static::created(function (Shipment $item) {

            //Associate customer to shipment
            if (self::$relatedCustomer != null) {
                //Required import name if custom_customer has an import name
                $importName = (new self)->getShipmentImportNamesByCustomerId(self::$relatedCustomer);
                if(count($importName) > 0 && !isset($item->import_name_id)){
                    throw new Exception("Import name is required.");
                }
                $item->customer()->associate(self::$relatedCustomer);
                self::$relatedCustomer = null;
            }
            if (self::$multiPurchaseOrders) {
                // $jwtToken = CustomJWTGenerator::generateToken();

                // $poInstanceClient = new \GuzzleHttp\Client([
                //     'base_uri' => getenv('PO_URL'),
                //     'headers' => [
                //         'Content-Type' => 'application/json',
                //         'Accept'     => 'application/json',
                //         'Authorization'  => 'Bearer ' . $jwtToken,
                //     ]
                // ]);
                // $res = $poInstanceClient->put('/api/po/purchase-orders/products', [
                //     "json" => [
                //         'multi_purchase_orders' => self::$multiPurchaseOrders,
                //         'shipment_id' => $item->id,
                //     ]
                // ]);
            }

            static::synchronise($item);
        });
    }

    public function updatePurchaseOrderProducts()
    {
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
    public function canfTerminal()
    {
        return $this->belongsTo('App\Terminal', 'carrier_arrival_notice_firms');
    }
    public function customsBroker()
    {
        return $this->belongsTo('App\CustomsBroker', 'customs_broker_id');
    }
    public function trucker()
    {
        return $this->belongsTo('App\Trucker');
    }

    public function invoice()
    {
        return $this->hasMany(Invoice::class);
    }

    public function bill()
    {
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

        // if ($this->shipment_status != "Completed") {

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
        if (!empty($this->eta) && Carbon::now()->gt(Carbon::parse($this->eta)) && Carbon::parse($this->eta)->diffInDays(Carbon::now()) >= 10) {
            $shipment_status = "Completed";
        }

        // }
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

    public function getContainersInformation()
    {
        $shipment_status = "No Status";
        $check_untracked_tool = false;
        $containers_return_count = 0;
        $containers_array = [];
        $container_count = 0;

        $now = now()->startOfDay();
        if (!empty($this->mbl_num)) {
            //$shipment_t49 = \App\Terminal49Shipment::find($this->mbl_num);
            $shipment_t49 = $this->terminal_fortynine;

            if ($shipment_t49) {
                $containers = json_decode($shipment_t49->containers, true);
                if (!empty($containers)) {
                    $is_in_hold = false;
                    $is_released = false;
                    $is_discharged = 0;
                    $is_full_out = 0;
                    $is_empty_in = 0;
                    $is_loaded = 0;
                    $is_past_last_free_day = false;
                    $container_count = count($containers);

                    foreach ($containers as $key => $container) {
                        $released_at_terminal = $container['attributes']['available_for_pickup'];
                        $holds = $container['attributes']['holds_at_pod_terminal'];

                        if (!$is_in_hold && !empty($holds)) {
                            $is_in_hold = true;
                        }
                        if (!$is_released && $released_at_terminal) {
                            $is_released = true;
                        }

                        $pod_discharged_at = $container['attributes']['pod_discharged_at'];
                        $event_discharged_at = $this->getTransportEvent($container['id'], $shipment_t49->transport_events, "container.transport.vessel_discharged");

                        if (!empty($pod_discharged_at) || !empty($event_discharged_at)) {
                            $is_discharged++;
                        }

                        // check last free day
                        $last_free_day = $container['attributes']['pickup_lfd'];
                        $pod_full_out_at = $container['attributes']['pod_full_out_at'];
                        $empty_terminated_at = $container['attributes']['empty_terminated_at'];

                        $event_empty_in = $this->getTransportEvent($container['id'], $shipment_t49->transport_events, "container.transport.empty_in");
                        $event_full_out = $this->getTransportEvent($container['id'], $shipment_t49->transport_events, "container.transport.full_out");
                        $event_loaded = $this->getTransportEvent($container['id'], $shipment_t49->transport_events, "container.transport.vessel_loaded");

                        if (!empty($last_free_day)) {
                            if ($now->gt(\Carbon\Carbon::parse($last_free_day)->startOfDay())) {
                                if (empty($pod_full_out_at) && empty($empty_terminated_at) && empty($event_full_out) && empty($event_empty_in)) {
                                    $is_past_last_free_day = true;
                                }
                            }
                        }

                        // full out
                        if (!empty($pod_full_out_at) || !empty($event_full_out)) {
                            $is_full_out++;
                        }

                        // empty in
                        if (!empty($empty_terminated_at) || !empty($event_empty_in)) {
                            $is_empty_in++;
                        }

                        // vessel loaded
                        if(!empty($event_loaded)){
                            $is_loaded++;
                        }
                    }

                    // check for hold and released
                    if ($is_in_hold || $is_released) {
                        $shipment_status = 'In transit - ' . ($is_in_hold ? 'hold' : 'released');
                    } else {
                        if((empty($this->etd) || Carbon::parse($this->etd)->gt(now())) && $is_loaded > 0){
                            $shipment_status = ($is_loaded == $container_count ? "Loaded" : "Partially Loaded");
                        }else{
                            $shipment_status = 'In transit';
                        }
                    }
                    if ($is_discharged) {
                        $shipment_status = (($is_discharged == $container_count ? 'Discharged': 'Partially discharged') . ($is_in_hold ? ' - hold' : ($is_released ? ' - released' : '')));
                    }

                    // full out
                    if ($is_full_out) {
                        $shipment_status = ($is_full_out == $container_count ? "Full Out" : "Full Out Partial");
                    }

                    // empty in
                    if ($is_empty_in) {
                        $shipment_status = ($is_empty_in == $container_count ? "Empty In" : "Empty In Partial");
                    }

                    if ($is_past_last_free_day) {
                        $shipment_status = 'Past last free day';
                    }
                }
            } else {
                $check_untracked_tool = true;
            }
        } else {
            $check_untracked_tool = true;
        }

        if ($check_untracked_tool) {
            $containers_group_untracked = $this->containers_group_untracked;
            if ($containers_group_untracked) {
                $containers_group_untracked = json_decode($containers_group_untracked, true);
                if (isset($containers_group_untracked['containers']) && count($containers_group_untracked['containers'])) {
                    $is_in_hold = false;
                    $is_released = false;
                    $is_discharged = 0;
                    $is_full_out = 0;
                    $is_empty_in = 0;
                    $is_loaded = 0;
                    $is_past_last_free_day = false;
                    $container_count = count($containers_group_untracked['containers']);
                    $containers_array = $containers_group_untracked['containers'];

                    foreach ($containers_group_untracked['containers'] as $container) {

                        if (!$is_in_hold && ($container['holds_at_pod_terminal'] ?? false) && !empty($container['holds_at_pod_terminal']) && count($container['holds_at_pod_terminal'])) {
                            $is_in_hold = true;
                        } elseif (!$is_released && ($container['available_for_pickup'] ?? false) && !empty($container['available_for_pickup'])) {
                            $is_released = true;
                        }

                        $pod_discharged_at = $container['pod_discharged_at'] ?? null;

                        if (!empty($pod_discharged_at)) {
                            $is_discharged++;
                        }
                        //
                        // check last free day
                        $last_free_day = $container['pickup_lfd'] ?? null;
                        $pod_full_out_at = $container['pod_full_out_at'] ?? null;
                        $empty_terminated_at = $container['pod_empty_returned_at'] ?? null;
                        $vessel_loaded = $container['vessel_loaded'] ?? null;


                        if (!empty($last_free_day)) {
                            if ($now->gt(\Carbon\Carbon::parse($last_free_day)->startOfDay())) {
                                if (empty($pod_full_out_at) && empty($empty_terminated_at)) {
                                    $is_past_last_free_day = true;
                                }
                            }
                        }

                        // full out
                        if (!empty($pod_full_out_at)) {
                            $is_full_out++;
                        }

                        // empty in
                        if (!empty($empty_terminated_at)) {
                            $is_empty_in++;
                            $containers_return_count++;
                        }

                        // vessel loaded

                        if(!empty($vessel_loaded)){
                            $is_loaded++;
                        }
                    }


                    // check for hold and released
                    if ($is_in_hold || $is_released) {
                        $shipment_status = 'In transit - ' . ($is_in_hold ? 'hold' : 'released');
                    }else{
                        if((empty($this->etd) || Carbon::parse($this->etd)->gt(now())) && $is_loaded > 0){
                            $shipment_status = ($is_loaded == $container_count ? "Loaded" : "Partially Loaded");
                        }else{
                            $shipment_status = 'In transit';
                        }
                    }

                    if ($is_discharged) {
                        $shipment_status = ($is_discharged == $container_count ? 'Discharged': 'Partially discharged') . ($is_in_hold ? ' - hold' : ($is_released ? ' - released' : ''));
                    }

                    // full out
                    if ($is_full_out) {
                        $shipment_status = ($is_full_out == $container_count ? "Full Out" : "Full Out Partial");
                    }

                    // empty in
                    if ($is_empty_in) {
                        $shipment_status = ($is_empty_in == $container_count ? "Empty In" : "Empty In Partial");
                    }

                    if ($is_past_last_free_day) {
                        $shipment_status = 'Past last free day';
                    }
                }
            }
        }
        return [
            'containers_count' => $container_count,
            'containers_return_count' => $containers_return_count,
            'containers_array' => $containers_array
        ];

    }


    // In transit (if no status in hold)
    // In transit - released (released at terminal)
    // In transit - hold (any holds)
    // Partially discharged (one of the containers discharged
    // Discharged (all containers discharged)
    // Past last free day (one or more containers not gated out after  last free day)
    public function getStatusV2()
    {
        $shipment_status = "No Status";
        $check_untracked_tool = false;
        $now = now()->startOfDay();
        if (!empty($this->mbl_num)) {
            //$shipment_t49 = \App\Terminal49Shipment::find($this->mbl_num);
            $shipment_t49 = $this->terminal_fortynine;

            if ($shipment_t49) {
                $containers = json_decode($shipment_t49->containers, true);
                if (!empty($containers)) {
                    $is_in_hold = false;
                    $is_released = false;
                    $is_discharged = 0;
                    $is_full_out = 0;
                    $is_empty_in = 0;
                    $is_loaded = 0;
                    $is_past_last_free_day = false;
                    $container_count = count($containers);

                    foreach ($containers as $key => $container) {
                        $released_at_terminal = $container['attributes']['available_for_pickup'];
                        $holds = $container['attributes']['holds_at_pod_terminal'];

                        if (!$is_in_hold && !empty($holds)) {
                            $is_in_hold = true;
                        }
                        if (!$is_released && $released_at_terminal) {
                            $is_released = true;
                        }

                        $pod_discharged_at = $container['attributes']['pod_discharged_at'];
                        $event_discharged_at = $this->getTransportEvent($container['id'], $shipment_t49->transport_events, "container.transport.vessel_discharged");

                        if (!empty($pod_discharged_at) || !empty($event_discharged_at)) {
                            $is_discharged++;
                        }

                        // check last free day
                        $last_free_day = $container['attributes']['pickup_lfd'];
                        $pod_full_out_at = $container['attributes']['pod_full_out_at'];
                        $empty_terminated_at = $container['attributes']['empty_terminated_at'];

                        $event_empty_in = $this->getTransportEvent($container['id'], $shipment_t49->transport_events, "container.transport.empty_in");
                        $event_full_out = $this->getTransportEvent($container['id'], $shipment_t49->transport_events, "container.transport.full_out");
                        $event_loaded = $this->getTransportEvent($container['id'], $shipment_t49->transport_events, "container.transport.vessel_loaded");

                        if (!empty($last_free_day)) {
                            if ($now->gt(\Carbon\Carbon::parse($last_free_day)->startOfDay())) {
                                if (empty($pod_full_out_at) && empty($empty_terminated_at) && empty($event_full_out) && empty($event_empty_in)) {
                                    $is_past_last_free_day = true;
                                }
                            }
                        }

                        // full out

                        if (!empty($pod_full_out_at) || !empty($event_full_out)) {
                            $is_full_out++;
                        }

                        // empty in

                        if (!empty($empty_terminated_at) || !empty($event_empty_in)) {
                            $is_empty_in++;
                        }

                        // vessel loaded
                        if(!empty($event_loaded)){
                            $is_loaded++;
                        }
                    }
                    // check for hold and released
                    if ($is_in_hold || $is_released) {
                        $shipment_status = 'In transit - ' . ($is_in_hold ? 'hold' : 'released');
                    } else {
                        if((empty($this->etd) || Carbon::parse($this->etd)->gt(now())) && $is_loaded > 0){
                            $shipment_status = ($is_loaded == $container_count ? "Loaded" : "Partially Loaded");
                        }else{
                            $shipment_status = 'In transit';
                        }
                    }
                    if ($is_discharged) {
                        $shipment_status = (($is_discharged == $container_count ? 'Discharged': 'Partially discharged') . ($is_in_hold ? ' - hold' : ($is_released ? ' - released' : '')));
                    }

                    // full out
                    if ($is_full_out) {
                        $shipment_status = ($is_full_out == $container_count ? "Full Out" : "Full Out Partial");
                    }

                    // empty in
                    if ($is_empty_in) {
                        $shipment_status = ($is_empty_in == $container_count ? "Empty In" : "Empty In Partial");
                    }

                    if ($is_past_last_free_day) {
                        $shipment_status = 'Past last free day';
                    }
                }
            } else {
                $check_untracked_tool = true;
            }
        } else {
            $check_untracked_tool = true;
        }

        if ($check_untracked_tool) {
            $containers_group_untracked = $this->containers_group_untracked;
            if ($containers_group_untracked) {
                $containers_group_untracked = json_decode($containers_group_untracked, true);
                if (isset($containers_group_untracked['containers']) && count($containers_group_untracked['containers'])) {
                    $is_in_hold = false;
                    $is_released = false;
                    $is_discharged = 0;
                    $is_full_out = 0;
                    $is_empty_in = 0;
                    $is_loaded = 0;
                    $is_past_last_free_day = false;
                    $container_count = count($containers_group_untracked['containers']);

                    foreach ($containers_group_untracked['containers'] as $container) {

                        if (!$is_in_hold && ($container['holds_at_pod_terminal'] ?? false) && !empty($container['holds_at_pod_terminal']) && count($container['holds_at_pod_terminal'])) {
                            $is_in_hold = true;
                        } elseif (!$is_released && ($container['available_for_pickup'] ?? false) && !empty($container['available_for_pickup'])) {
                            $is_released = true;
                        }

                        $pod_discharged_at = $container['pod_discharged_at'] ?? null;

                        if (!empty($pod_discharged_at)) {
                            $is_discharged++;
                        }
                        //
                        // check last free day
                        $last_free_day = $container['pickup_lfd'] ?? null;
                        $pod_full_out_at = $container['pod_full_out_at'] ?? null;
                        $empty_terminated_at = $container['pod_empty_returned_at'] ?? null;
                        $vessel_loaded = $container['vessel_loaded'] ?? null;

                        if (!empty($last_free_day)) {
                            if ($now->gt(\Carbon\Carbon::parse($last_free_day)->startOfDay())) {
                                if (empty($pod_full_out_at) && empty($empty_terminated_at)) {
                                    $is_past_last_free_day = true;
                                }
                            }
                        }

                        // full out
                        if (!empty($pod_full_out_at)) {
                            $is_full_out++;
                        }

                        // empty in
                        if (!empty($empty_terminated_at)) {
                            $is_empty_in++;
                        }

                        // vessel loaded

                        if(!empty($vessel_loaded)){
                            $is_loaded++;
                        }
                    }


                    // check for hold and released
                    if ($is_in_hold || $is_released) {
                        $shipment_status = 'In transit - ' . ($is_in_hold ? 'hold' : 'released');
                    }else{
                        if((empty($this->etd) || Carbon::parse($this->etd)->gt(now())) && $is_loaded > 0){
                            $shipment_status = ($is_loaded == $container_count ? "Loaded" : "Partially Loaded");
                        }else{
                            $shipment_status = 'In transit';
                        }
                    }

                    if ($is_discharged) {
                        $shipment_status = ($is_discharged == $container_count ? 'Discharged': 'Partially discharged') . ($is_in_hold ? ' - hold' : ($is_released ? ' - released' : ''));
                    }

                    // full out
                    if ($is_full_out) {
                        $shipment_status = ($is_full_out == $container_count ? "Full Out" : "Full Out Partial");
                    }

                    // empty in
                    if ($is_empty_in) {
                        $shipment_status = ($is_empty_in == $container_count ? "Empty In" : "Empty In Partial");
                    }

                    if ($is_past_last_free_day) {
                        $shipment_status = 'Past last free day';
                    }
                }else{
                    if($this->is_tracking_shipment){
                        return "No Status";
                    }
                }
            }else{
                if($this->is_tracking_shipment){
                    return "No Status";
                }
            }
        }

        return $shipment_status;
    }

    // utility functions
    private function getTransportEvent($container_id, $transport_events, $event_name)
    {
        //
        if (!empty(trim($transport_events))) {
            $transport_events = json_decode($transport_events, true);
            if (!empty($transport_events) && count($transport_events) > 0) {
                if (isset($transport_events[$container_id]['data'])) {
                    foreach ($transport_events[$container_id]['data'] ?? [] as $transport_event) {
                        // code...
                        if (isset($transport_event['attributes']['event']) && $transport_event['attributes']['event'] == $event_name) {
                            return $transport_event;
                        }
                    }
                }
            }
        }
        return false;
    }

    public function compareByTimeStamp($time1, $time2)
    {
        if (strtotime($time1) < strtotime($time2))
            return 1;
        else if (strtotime($time1) > strtotime($time2))
            return -1;
        else
            return 0;
    }

    public function isFullOutPassed($days = 40)
    {
        $check_untracked_tool = false;
        $now = Carbon::now();
        if (!empty($this->mbl_num)) {
            $shipment_t49 = $this->terminal_fortynine;

            if ($shipment_t49) {
                $containers = json_decode($shipment_t49->containers, true);
                if (!empty($containers)) {
                    $fullOutDate = collect();
                    foreach ($containers as $key => $container) {
                        $pod_full_out_at = $container['attributes']['pod_full_out_at'];
                        if(isset($pod_full_out_at))
                            $fullOutDate->add($pod_full_out_at);
                    }
                    $fullOutDateArray = $fullOutDate->toArray();
                    usort($fullOutDateArray, ["App\Shipment","compareByTimeStamp"]);

                    if(is_array($fullOutDateArray) && isset($fullOutDateArray[0])){
                        $date = Carbon::parse($fullOutDateArray[0]);

                        $diff = $date->diffInDays($now);

                        if($diff > $days)
                            return true;
                        else
                            return false;
                    }
                    else{
                        return false;
                    }
                }
            } else {
                $check_untracked_tool = true;
            }
        } else {
            $check_untracked_tool = true;
        }

        if ($check_untracked_tool) {
            $containers_group_untracked = $this->containers_group_untracked;
            if ($containers_group_untracked) {
                $containers_group_untracked = json_decode($containers_group_untracked, true);
                if (isset($containers_group_untracked['containers']) && count($containers_group_untracked['containers'])) {
                    foreach ($containers_group_untracked['containers'] as $container) {
                        $pod_full_out_at = $container['pod_full_out_at'] ?? null;

                        $fullOutDate = collect();
                        if($pod_full_out_at)
                            $fullOutDate->add($pod_full_out_at);
                    }

                    $fullOutDateArray = $fullOutDate->toArray();
                    usort($fullOutDateArray, ["App\Shipment","compareByTimeStamp"]);

                    if(is_array($fullOutDateArray) && isset($fullOutDateArray[0])){
                        $date = Carbon::parse($fullOutDateArray[0]);
                        $diff = $date->diffInDays($now);

                        if($diff > $days)
                            return true;
                        else
                            return false;
                    }
                    else{
                        return false;
                    }
                }else{
                    if($this->is_tracking_shipment){
                        return false;
                    }
                }
            }else{
                if($this->is_tracking_shipment){
                    return false;
                }
            }
        }

        return false;
    }

    //
    public function officeToAccountManager()
    {
        $offices_managers = $this->customer->offices_managers;
        if (!empty($offices_managers)) {
            $offices_managers = json_decode($offices_managers);
            if (!empty($offices_managers)) {
                $office_to_id = $this->shifl_office_origin_to_id;
                if (!empty($office_to_id)) {
                    foreach ($offices_managers ?? [] as $office_manager) {
                        // code...
                        if ($office_manager->office_id == $office_to_id) {
                            $manager = $office_manager->manager_id;
                            if (!empty($manager)) {
                                $manager = \App\User::find($manager);
                                if (!empty($manager)) {
                                    return $manager;
                                }
                            }
                        }
                    }
                }
            }
        }
        return [];
    }

    // check if a shipment is a rail shipment or not
    public function isRailShipment()
    {
        if (!empty($this->schedules_group_bookings)) {
            $schedules = json_decode($this->schedules_group_bookings);
            foreach ($schedules ?? [] as $key => $schedule) {
                // code...
                if (isset($schedule->is_confirmed) && $schedule->is_confirmed) {
                    if (!empty($schedule->legs)) {
                        foreach ($schedule->legs ?? [] as $key => $leg) {
                            // code...
                            if ($leg->mode == 'Rail') {
                                return true;
                            }
                        }
                    }
                }
            }
        }
        return false;
    }

    // get shipment type
    public function getType(){
        if (!empty($this->schedules_group_bookings)) {
            $schedules = json_decode($this->schedules_group_bookings);
            foreach ($schedules ?? [] as $key => $schedule) {
                // code...
                if (isset($schedule->is_confirmed) && $schedule->is_confirmed && isset($schedule->type) ) {
                    return $schedule->type;
                }
            }
        }
        return '';
    }

    // get terminal49_terminal
    public function terminal49_terminal(){
        if(!empty($this->mbl_num)){
            $terminals = $this->terminal_fortynine->terminals ?? null;
            if(!empty($terminals)){
                $terminals = json_decode($terminals);
                if(!empty($terminals)){
                    foreach ($terminals as $key => $terminal) {
                        // code...
                        if(isset($terminal->attributes->firms_code) && !empty($terminal->attributes->firms_code)){
                            return $terminal->attributes;
                        }
                    }
                }
            }
        }
        return null;
    }

    public function selectedPos($suppliers_id){
        $selected_po = \DB::connection('mysql-po')->table('product_shipment_distributions')
                                  ->select("products.sku","po_number","products.name","purchase_order_products.quantity","product_shipment_distributions.ship_cartons","products.units_per_carton")
                                  ->join('purchase_order_products','product_shipment_distributions.purchase_order_product_id','=','purchase_order_products.id')
                                  ->join('purchase_orders','purchase_orders.id', '=','purchase_order_products.purchase_order_id')
                                  ->join('products','products.id','=','purchase_order_products.product_id')
                                  ->where('product_shipment_distributions.shipment_id',$this->id)
                                  ->where('product_shipment_distributions.is_shipment', true)
                                  ->where('product_shipment_distributions.supplier_id',$suppliers_id)
                                  ->get();
         $po_list = [];

         if($selected_po->first()){
              $unique_po_list = $selected_po->pluck(['po_number'])->unique();
              foreach ($unique_po_list as $key => $po) {
                  // code...
                  $details = [
                    'po_number' => $po,
                    'products' => [],
                  ];
                  foreach ($selected_po as $key => $p) {
                    // code...
                    if($p->po_number === $po){
                        array_push($details['products'],[
                            'sku' => $p->sku,
                            'name' => $p->name,
                            'total_cartons' => $p->quantity,
                            'to_ship_cartons' => $p->ship_cartons,
                            'units_per_carton' => $p->units_per_carton
                        ]);
                    }
                  }
                  array_push($po_list,$details);
              }
         }
         return $po_list;
    }

    public function getShipmentImportNamesByCustomerId($id)
    {
        return ImportNames::where('customer_id', $id)
            ->where('status', 1)
            ->pluck('import_name', 'id')->toArray();
    }

    public function getCustomerImportName()
    {
        $importName = $this->customer->company_name ?? '';
        if(isset($this->import_name_id)){
            $name = (new ImportNames)->getImportNameByShipmentImportNameId($this)->first();
            $importName = $name->import_name ?? $this->customer->company_name;
        }
        return $importName;
    }

    public function ImportNames()
    {
        return $this->belongsTo(ImportNames::class, 'import_name_id');
    }

    public function importNamesfiltered()
    {
        return $this->ImportNames()->where('status', 1);

    }

    public function getTrackingStatus(){
        $labels = [
            "tracking" => "Auto Tracking",
            "tracked" => "Auto Tracked",
            "mtracking" => "Manual Tracking",
            "mtracked" => "Manually Tracked",
            "ntracking" => "Not Tracking",
        ];
        // checking on basis of t49
        if($this->terminal_fortynine) {
            $shipment_t49 = $this->terminal_fortynine;
            $containers = json_decode($shipment_t49->containers, true);
            if(!empty($containers)){
                $container_count = count($containers);
                $is_full_out = 0;
                $is_empty_in = 0;
                $full_outs = [];
                foreach ($containers as $key => $container) {
                    $pod_full_out_at = $container['attributes']['pod_full_out_at'];
                    $empty_terminated_at = $container['attributes']['empty_terminated_at'];
                    $event_empty_in = $this->getTransportEvent($container['id'], $shipment_t49->transport_events, "container.transport.empty_in");
                    $event_full_out = $this->getTransportEvent($container['id'], $shipment_t49->transport_events, "container.transport.full_out");

                    if (!empty($pod_full_out_at) || !empty($event_full_out)) {
                        $full_out_at = $pod_full_out_at;
                        if(empty($full_out_at)){
                            $full_out_at = $event_full_out;
                            if($full_out_at){
                                $full_out_at = $full_out_at['attributes']['timestamp'] ?? null;
                            }
                        }
                        if($full_out_at){
                            $is_full_out++;
                            array_push($full_outs,$full_out_at);
                        }
                    }
                    if (!empty($empty_terminated_at) || !empty($event_empty_in)) {
                        $is_empty_in++;
                    }
                }
                if($container_count == $is_empty_in){
                    return $labels['tracked'];
                }
                if($container_count == $is_full_out){
                    if(count($full_outs) > 0){

                        foreach ($full_outs as $ft) {
                            // code...
                            if($ft){
                                $ft = Carbon::parse($ft);
                                if(now()->gt($ft) && $ft->diffInDays(now()) < 20) return $labels['tracking'];
                            }
                        }
                        return $labels['tracked'];
                    }
                }
            }
            return $labels['tracking'];
        }
        else if($this->not_tracking_manually) {
            return $labels['ntracking'];
        }
        // Checking on basis of manual tracking tool
        else {
            $containers_group_untracked = $this->containers_group_untracked;
            if ($containers_group_untracked) {
                $containers_group_untracked = json_decode($containers_group_untracked, true);
                if (isset($containers_group_untracked['containers']) && count($containers_group_untracked['containers'])) {
                    $container_count = count($containers_group_untracked['containers']);
                    $type = $this->gettype();
                    if ($type != 'FCL' && isset($containers_group_untracked['picked_up']) && !empty($containers_group_untracked['picked_up'])) {
                        return $labels['mtracked'];
                    }

                    $full_outs = [];
                    $count = 0;
                    foreach ($containers_group_untracked['containers'] as $container) {
                        // code...
                        if ($type == 'FCL' && isset($container['pod_full_out_at']) && !empty($container['pod_full_out_at'])) {
                            array_push($full_outs,$container['pod_full_out_at']);
                            $count++;
                        }
                        // else if ($shipment->gettype() == 'LCL' && )
                    }
                    if ($count && $count == count($containers_group_untracked['containers'])) {
                        foreach ($full_outs as $ft) {
                            // code...
                            if($ft){
                                if(!now()->gt(Carbon::parse($ft)->addWeeks(2))){
                                    return $labels['mtracking'];
                                }
                            }

                        }
                        return $labels['mtracked'];
                    }

                }
            }
            return $labels['mtracking'];
        }
    }

    function notes(){
        return $this->morphOne('App\Notes','noteable');
    }

    public function getTrackingAndSupplierPo(Shipment $shipment)
    {
        $arPOList = [];
        $supplier_po = json_decode($shipment->suppliers_group,true) ?? [];
        $arPOList = array_map(fn ($po) => $po['po_num'], $supplier_po);

        $po_nums = json_decode($shipment->po_num);
        if(is_countable($po_nums)){
            foreach ($po_nums as $po){
               if(!in_array($po, $arPOList)){
                array_push($arPOList, $po);
               }
            }
        }

        return $arPOList;
    }

    public function isShipmentActive()
    {
        $status = $this->getGeneralStatus();
        // $eta = $this->getFinalETA();
        // $diff_days = Carbon::parse($eta)->diffInDays(now(), false);
        $diff_days = Carbon::parse($this->eta)->diffInDays(now(), false);
        if ($diff_days > 60) {
            $status = 'Completed';

        }
        if($this->is_draft == 1){
            $status = 'Draft';
        }
        return $status !== 'Full Out' && $status !== 'Empty In' && $status !== 'Completed' && $status !== 'Expired' && $status !== 'Draft';

    }


    private function bJson($jsonString) {
        $data = json_decode($jsonString);
        return ( json_last_error() === JSON_ERROR_NONE ) ? true : false;
    }

    public function getGeneralStatus()
    {

        //default status and status v2
        $status = 'In Transit';
        $status_v2 = $this->status_fallback;
        // $status_v2 = $this->getStatusV2();

        //if the booking is confirmed
        if ( $this->cancelled === 0 && $this->booking_confirmed === 1) {

            // current day minus eta
            $diff_days = Carbon::parse($this->eta)->diffInDays(now(), false);

            //if the shipment is not tracking proceed with default status
            if ( $status_v2==null || $status_v2=='No Status' ) {

                if ( $this->etd!==null && $this->etd!=='' ) {
                    if ( Carbon::parse($this->etd)->gt(now()) ) {
                        $status = 'Awaiting Departure';
                    }
                    if ( Carbon::parse($this->etd)->lt(now()) ) {
                        $status = 'In Transit';
                    }
                }
                //if eta is not null and it is more than 60 days then mark it as completed
                if ( $this->eta!==null && $this->eta!=='' ) {
                    if ($diff_days > 60) {
                        $status = 'Completed';
                    }
                }

            } else {
                //if tracking check status v2 value
                if ($status_v2=='Full Out' || $status_v2==='Empty In') {
                    $status = 'Completed';
                } else {

                    if ( $status_v2 === 'In transit - hold' ) {
                        $status = 'In Transit - Pending Release';
                    } elseif ( $status_v2 === 'Past last free day' ) {
                        $status = 'Past last free day';
                    } elseif ( $status_v2 === 'In transit - released' ) {
                        $status = 'In Transit - Released';
                    } elseif($status_v2 === 'Discharged - hold') {
                        $status = 'Discharged - Hold';
                    } else {
                        $status = $status_v2;
                    }

                    if ($diff_days > 60) {
                        $status = 'Completed';
                    }
                }
            }

        } else {
            if ( $this->cancelled === 1 ) {
                $status = 'Cancelled';
            } else {
                //if it's not completed or transit shipments then do other logic
                //first check schedules
                if ( $this->booking_confirmed === 0 ) {

                    if ( $this->bJson($this->schedules_group_bookings)) {
                        $schedules = json_decode($this->schedules_group_bookings);
                        $status = 'Pending Approval';
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
                                        }
                                    } else {
                                        $is_pending_quote_counter++;
                                    }
                                }
                            }
                        }

                        if (count($schedules)==0) {
                            $status = 'Expired';
                        } else {

                            if ($this->snooze_date_at === null || $this->snooze_date_at <= now()) {
                                if ($is_pending_quote_counter > 0) {
                                    $status = 'Pending Quote';
                                }
                            } else {
                                $status = 'Snoozed';
                            }
                        }
                    }
                }
            }
        }

        if ($status_v2=='Full Out') {
            $status = 'Full Out';
        }elseif($status_v2==='Empty In'){
            $status = 'Empty In';
        }

        return $status;
    }

    public function getGeneralStatusV2()
    {

        //default status and status v2
        $status = 'In Transits';
        $status_v2 = $this->getStatusV2();

        //if the booking is confirmed
        if ( $this->cancelled === 0 && $this->booking_confirmed === 1) {

            // current day minus eta
            $diff_days = Carbon::parse($this->eta)->diffInDays(now(), false);

            //if the shipment is not tracking proceed with default status
            if ( $status_v2==null || $status_v2=='No Status' ) {

                if ( $this->etd!==null && $this->etd!=='' ) {
                    if ( Carbon::parse($this->etd)->gt(now()) ) {
                        $status = 'Awaiting Departure';
                    }
                    if ( Carbon::parse($this->etd)->lt(now()) ) {
                        $status = 'In Transit';
                    }
                }
                //if eta is not null and it is more than 60 days then mark it as completed
                if ( $this->eta!==null && $this->eta!=='' ) {
                    if ($diff_days > 60) {
                        $status = 'Completed';
                    }
                }

            } else {
                //if tracking check status v2 value
                if ($status_v2=='Full Out' || $status_v2==='Empty In') {
                    $status = 'Completed';
                } else {

                    if ( $status_v2 === 'In transit - hold' ) {
                        $status = 'In Transit - Pending Release';
                    } elseif ( $status_v2 === 'Past last free day' ) {
                        $status = 'Past last free day';
                    } elseif ( $status_v2 === 'In transit - released' ) {
                        $status = 'In Transit - Released';
                    } elseif($status_v2 === 'Discharged - hold') {
                        $status = 'Discharged - Hold';
                    }

                    if ($diff_days > 60) {
                        $status = 'Completed';
                    }
                }
            }

        } else {
            if ( $this->cancelled === 1 ) {
                $status = 'Cancelled';
            } else {
                //if it's not completed or transit shipments then do other logic
                //first check schedules
                if ( $this->booking_confirmed === 0 ) {

                    if ( $this->bJson($this->schedules_group_bookings)) {
                        $schedules = json_decode($this->schedules_group_bookings);
                        $status = 'Pending Approval';
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
                                        }
                                    } else {
                                        $is_pending_quote_counter++;
                                    }
                                }
                            }
                        }

                        if (count($schedules)==0) {
                            $status = 'Expired';
                        } else {

                            if ($this->snooze_date_at === null || $this->snooze_date_at <= now()) {
                                if ($is_pending_quote_counter > 0) {
                                    $status = 'Pending Quote';
                                }
                            } else {
                                $status = 'Snoozed';
                            }
                        }
                    }
                }
            }
        }

        return $status;
    }

    public function getShipmentStatus()
    {
        $shipment_status = $this->getGeneralStatus();
        $status_v2 = $this->getStatusV2();
        if ($status_v2=='Full Out') {
            $shipment_status = 'Full Out';
        }
        if($status_v2==='Empty In'){
            $shipment_status = 'Empty In';
        }
        return $shipment_status;

    }

    public function isShipmentComplete()
    {
        $status = $this->getGeneralStatus();
        return $status === 'Full Out' || $status === 'Empty In' || $status === 'Completed';
    }

    public function isReportWithinSixMonths()
    {
        $schedule = (new \App\Custom\ProcessSchedulesGroupBookings($this->schedules_group_bookings))->getSchedule();
        if(!empty($schedule->eta)){
            $diff_months = Carbon::parse($this->getFinalETA())->diffInMonths(now(), false);
            return $diff_months >= 0 && $diff_months <= 6;
        }

    }

    public function getFinalETA($t49 = null)
    {
        $eta_latest = '';
        if($this->is_tracking_shipment == 1){
            $shipment_t49 =  $t49 ?? Terminal49Shipment::find($this->mbl_num);
            if (!empty($shipment_t49)) {
                $attributes = json_decode($shipment_t49['attributes'], true);
                $eta = $attributes['pod_eta_at'] ?? '';
                $ata = $attributes['pod_ata_at'] ?? '';
                $eta_latest =  !empty($eta) ? $eta : $ata;
            }
        }else{
            $schedule = (new \App\Custom\ProcessSchedulesGroupBookings($this->schedules_group_bookings))->getSchedule();
            $eta_latest = $schedule->eta ?? '';
        }
        return $eta_latest;
    }


    public function getSelectedSchedule(){
        $schedules_group_bookings = json_decode($this->schedules_group_bookings ?? '[]');
        foreach ($schedules_group_bookings ?? [] as $key => $schedule) {
            // code...
            if($schedule->is_confirmed ?? false){
                return $schedule;
            }
        }
        return null;
    }

    public function getManualTrackingComments(){
        $now = now();
        $status_v2 = $this->getStatusV2();
        $selected_schedule = $this->getSelectedSchedule();
        $etd = null;
        $is_past_etd = false;

        $eta = null;
        $is_past_eta = false;
        $is_before_eta = false;

        $is_in_transit = false;

        $diff_from_eta = null;

        if($selected_schedule){
            if($selected_schedule->etd ?? false){
                $etd = Carbon::parse($selected_schedule->etd);
                $is_past_etd = $now->gt($etd);
            }
            if($selected_schedule->eta ?? false){
                $eta = Carbon::parse($selected_schedule->eta);
                $is_past_eta = $now->gt($eta);
                $is_before_eta = $eta->gt($now);
                $diff_from_eta = $now->diffInDays($eta);
            }
            $is_in_transit = $eta && $etd && $is_past_etd && $is_before_eta;
        }
        $missing_empty_out = [];
        $missing_full_in = [];
        $missing_vessel_loaded = [];

        $containers_group_untracked = $this->containers_group_untracked;
        if ($containers_group_untracked) {
            $containers_group_untracked = json_decode($containers_group_untracked ?? '[]');

            foreach ($containers_group_untracked->containers ?? [] as $key => $container) {
                // code...
                if(empty($container->empty_out ?? '')){
                    $missing_empty_out[] = $container;
                }
                if(empty($container->pod_full_in_at ?? '')){
                    $missing_full_in[] = $container;
                }
                if(empty($container->vessel_loaded ?? '')){
                    $missing_vessel_loaded[] = $container;
                }
            }

        }
        if($status_v2 === 'No Status' && empty($selected_schedule)){
            return "A Schedule has yet to be accepted for this Shipment";
        }else if($status_v2 === 'No Status' && $selected_schedule && $etd && $is_past_etd){
            return "it is ". $now->diffInDays($etd) ." days to the ETD";
        }else if($selected_schedule && $now->gt($etd) && empty(json_decode($this->containers_group_bookings ?? '[]'))){
            return "Missing Containers, should have em' by now";
        }else if($selected_schedule && $etd && $is_past_etd && $eta && $is_before_eta && count($missing_empty_out)){
            $comments = '';
            foreach ($missing_empty_out as $key => $container) {
                // code...
                if($key){
                    $comments .= '<br>';
                }
                $comments .= "Missing Empty Out for Container, ". $container->container_num . ". It is past the ETD so we should have it.";
            }
            return $comments;
        }else if($selected_schedule && $etd && $is_past_etd && $eta && $is_before_eta && count($missing_full_in)){
            $comments = '';
            foreach ($missing_full_in as $key => $container) {
                // code...
                if($key){
                    $comments .= '<br>';
                }
                $comments .= "Missing Full In for Container, ". $container->container_num . ". It is past the ETD so we should have it.";
            }
            return $comments;
        }else if($selected_schedule && $etd && $is_past_etd && $eta && $is_before_eta && count($missing_vessel_loaded)){
            // $comments = '';
            // foreach ($missing_vessel_loaded as $key => $container) {
            //     // code...
            //     if($key){
            //         $comments .= '<br>';
            //     }
            //     $comments .= "Missing Empty Out for Container, ". $container->container_num . ". It is past the ETD so we should have it.";
            // }
            return "Missing Vessel Loaded Date it is past the ETD so we should have it.";
        }else if($is_in_transit && $diff_from_eta > 7){
            return "It is day ". $now->diffInDays($etd) ." of ". $etd->diffInDays($eta) ." days of travel";
        }else if($is_in_transit && $diff_from_eta == 7){
            return "it is day ". $now->diffInDays($etd) . " of " . $etd->diffInDays($eta) . " days of travel (ETD - ETA) & a week to the ETA.";
        }else if($is_in_transit && $diff_from_eta < 7){
            return "It is day ". $now->diffInDays($etd) . " of " . $etd->diffInDays($eta) . " days of travel (ETD - ETA) & less than a week to the ETA.";
        }else{
            return "No Comments";
        }
    }

    public function getManualTrackingReportStatus(){
        $now = now();
        $status_v2 = $this->getStatusV2();
        $selected_schedule = $this->getSelectedSchedule();
        $has_full_in = [];

        $etd = null;
        $is_past_etd = false;

        $eta = null;
        $is_past_eta = false;
        $is_before_eta = false;

        $is_before_etd = false;

        $is_in_transit = false;

        $diff_from_eta = null;
        $diff_from_etd = null;

        if($selected_schedule){
            if($selected_schedule->etd ?? false){
                $etd = Carbon::parse($selected_schedule->etd);
                $is_past_etd = $now->gt($etd);
                $is_before_etd = $etd->gt($now);
                $diff_from_etd = $now->diffInDays($etd);
            }
            if($selected_schedule->eta ?? false){
                $eta = Carbon::parse($selected_schedule->eta);
                $is_past_eta = $now->gt($eta);
                $is_before_eta = $eta->gt($now);
                $diff_from_eta = $now->diffInDays($eta);
            }
            $is_in_transit = $eta && $etd && $is_past_etd && $is_before_eta;
        }


        $containers_group_untracked = $this->containers_group_untracked;
        $container_count = 0;
        if ($containers_group_untracked) {
            $containers_group_untracked = json_decode($containers_group_untracked ?? '[]');

            foreach ($containers_group_untracked->containers ?? [] as $key => $container) {
                // code...
                $container_count++;
                if(!empty($container->pod_full_in_at)){
                    $has_full_in[] = $container;
                }
            }

        }

        if($status_v2 == 'No Status'){
            if(empty($this->schedules_group_bookings)){
                return "Not Yet Scheduled";
            }else if(empty($selected_schedule)){
                return "Schedule in, X Selected";
            }else if($selected_schedule && $is_before_etd && $etd && empty($has_full_in) && !is_null($diff_from_etd) && $diff_from_etd >= 7){
                return "Booked, " . $diff_from_etd . " days to departure";
            }else if($selected_schedule && $is_before_etd && $etd && empty($has_full_in) && !is_null($diff_from_etd) && $diff_from_etd < 7){
                return "Booked, Empty not yet picked up";
            }else if(!empty($has_full_in)){
                if($container_count == count($has_full_in)) return "Gated In";
                else return "Partially Gated In";
            }else if($is_past_eta) {
                return "Arrived";
            }
        }else if($status_v2 == 'In transit' && $is_before_eta && $diff_from_eta){
            // code...
            return 'In transit, '. $diff_from_eta .' days to Arrival';
        }

        return $status_v2;
    }

    public function getContainersQtyAttribute(){
        return !empty($this->containers_group) ? count(json_decode($this->containers_group)) : 0;
    }
}
