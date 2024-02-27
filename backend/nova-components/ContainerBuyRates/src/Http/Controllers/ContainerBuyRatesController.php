<?php

namespace Juliverdevshifl\ContainerBuyRates\Http\Controllers;

use Illuminate\Routing\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

//// MODELS ////
use \Juliverdevshifl\ContainerBuyRates\Models\IndexRates;

class ContainerBuyRatesController extends Controller
{
    public function index(Request $request){

        // $authRole = \Auth::user()->roles()->first();

        $per_page = $request->query('per-page') && $request->query('per-page') != '' ? $request->query('per-page') : 50;
        $carrier= $request->query('filterbycarrier') && $request->query('filterbycarrier') != '' ? $request->query('filterbycarrier') : false;
        $service = $request->query('filterbyservice') && $request->query('filterbyservice') != '' ? $request->query('filterbyservice')  : false;
        // $sort_by =  $request->query('sortby') && $request->query('sortby') != '' ? $request->query('sortby') : 'id';
        // $sort_type = $request->query('sorttype') && $request->query('sorttype') != '' ? $request->query('sorttype') : 'desc';

        $container_where_not_in = ["Shipment","Sell","20'GP"];
        $terminal = $request->input('terminal',false);

        if( !$terminal ){
            return response()->json([ 'success' => false, 'message' => 'Terminal is required' ]);
        }

        $terminal = strtoupper($terminal);

        $sells = DB::table('shipment_schedules_sellrates')
        ->leftJoin('container_sizes', 'container_sizes.id', '=', 'shipment_schedules_sellrates.container_size_id')
        ->leftJoin('shipments', 'shipments.id', '=', 'shipment_schedules_sellrates.shipment_id')
        ->leftJoin('shifl_offices as shifl_office_from', 'shifl_office_from.id', '=', 'shipments.shifl_office_origin_from_id')
        ->leftJoin('shifl_offices as shifl_office_to', 'shifl_office_to.id', '=', 'shipments.shifl_office_origin_to_id')
        ->leftJoin('terminals', 'terminals.id', '=', 'shipments.terminal_id')
        ->leftJoin('carriers', 'carriers.id', '=', 'shipments.carrier_id')
        ->leftJoin('services', 'services.id', '=', 'shipment_schedules_sellrates.service_id')
        ->whereNotNull('shipments.etd')
        ->whereRaw('UPPER(terminals.name) = "'.$terminal.'"')
        ->whereNotIn('container_sizes.name',$container_where_not_in)
        ->select('shipment_schedules_sellrates.amount','shipment_schedules_sellrates.qty','shipments.etd AS etd_date','shipments.eta AS eta_date','carriers.name AS carrier','services.name as service','shipments.vessel','container_sizes.name as container','shifl_office_from.name AS location_from','shifl_office_to.name AS location_to','terminals.name AS terminal');
        
        $buys =  DB::table('shipment_schedules_buyrates')
        ->leftJoin('container_sizes', 'container_sizes.id', '=', 'shipment_schedules_buyrates.container_size_id')
        ->leftJoin('shipments', 'shipments.id', '=', 'shipment_schedules_buyrates.shipment_id')
        ->leftJoin('shifl_offices as shifl_office_from', 'shifl_office_from.id', '=', 'shipments.shifl_office_origin_from_id')
        ->leftJoin('shifl_offices as shifl_office_to', 'shifl_office_to.id', '=', 'shipments.shifl_office_origin_to_id')
        ->leftJoin('terminals', 'terminals.id', '=', 'shipments.terminal_id')
        ->leftJoin('carriers', 'carriers.id', '=', 'shipments.carrier_id')
        ->leftJoin('services', 'services.id', '=', 'shipment_schedules_buyrates.service_id')
        ->whereNotNull('shipments.etd')
        ->whereRaw('UPPER(terminals.name) = "'.$terminal.'"')
        ->whereNotIn('container_sizes.name',$container_where_not_in)
        ->select('shipment_schedules_buyrates.amount','shipment_schedules_buyrates.qty','shipments.etd AS etd_date','shipments.eta AS eta_date','carriers.name AS carrier','services.name as service','shipments.vessel','container_sizes.name as container','shifl_office_from.name AS location_from','shifl_office_to.name AS location_to','terminals.name AS terminal')
        ->union($sells)
        ->paginate(isset($per_page) ? $per_page : 50);

        return response()->json([ 'success' => true, 'message' => $buys, 't' => $terminal ]);
    }

    public function terminals(Request $request){

        $selects = $request->input('tr') ? explode('-',$request->input('tr')) : false;

        if( $selects && count($selects) > 0 ){
            $trs = \App\TerminalRegion::whereIn('name',$selects)->get();
        }else{
            $trs = \App\TerminalRegion::all();
        }

        return response()->json([ 'success' => true, 'message' => $trs ]);
    }

    public function generate(Request $request){
        $container_where_not_in = ["Shipment","Sell","20'GP"];

        if( $request->input('empty') == 1 ){
            DB::table('index_rates')->where('is_custom',0)->delete();
        }

        $sells = DB::table('shipment_schedules_sellrates')
        ->leftJoin('container_sizes', 'container_sizes.id', '=', 'shipment_schedules_sellrates.container_size_id')
        ->leftJoin('shipments', 'shipments.id', '=', 'shipment_schedules_sellrates.shipment_id')
        ->leftJoin('shifl_offices as shifl_office_from', 'shifl_office_from.id', '=', 'shipments.shifl_office_origin_from_id')
        ->leftJoin('shifl_offices as shifl_office_to', 'shifl_office_to.id', '=', 'shipments.shifl_office_origin_to_id')
        ->leftJoin('terminals', 'terminals.id', '=', 'shipments.terminal_id')
        ->whereNotNull('shipments.etd')        
        ->whereNotIn('container_sizes.name',$container_where_not_in)
        ->select('shipment_schedules_sellrates.amount','shipment_schedules_sellrates.qty','shipments.etd AS etd_date','container_sizes.name as container','shifl_office_from.name AS location_from','shifl_office_to.name AS location_to','terminals.name AS terminal');

        $buys =  DB::table('shipment_schedules_buyrates')
        ->leftJoin('container_sizes', 'container_sizes.id', '=', 'shipment_schedules_buyrates.container_size_id')
        ->leftJoin('shipments', 'shipments.id', '=', 'shipment_schedules_buyrates.shipment_id')
        ->leftJoin('shifl_offices as shifl_office_from', 'shifl_office_from.id', '=', 'shipments.shifl_office_origin_from_id')
        ->leftJoin('shifl_offices as shifl_office_to', 'shifl_office_to.id', '=', 'shipments.shifl_office_origin_to_id')
        ->leftJoin('terminals', 'terminals.id', '=', 'shipments.terminal_id')
        ->whereNotNull('shipments.etd')
        ->whereNotIn('container_sizes.name',$container_where_not_in)
        ->select('shipment_schedules_buyrates.amount','shipment_schedules_buyrates.qty','shipments.etd AS etd_date','container_sizes.name as container','shifl_office_from.name AS location_from','shifl_office_to.name AS location_to','terminals.name AS terminal')
        ->union($sells)
        ->get();

        foreach( $buys as $b ){
            if( DB::table('index_rates')->where([
                'data_date' => $b->etd_date,
                'location_from' => $b->location_from,
                'location_to' => $b->location_to,
                'terminal' => strtoupper($b->terminal),
                'amount' => ( (float)$b->amount*(int)$b->qty ),
                'container' => $b->container
            ])->exists() ) continue;

            DB::table('index_rates')->insert([
                'data_date' => $b->etd_date,
                'location_from' => $b->location_from,
                'location_to' => $b->location_to,
                'terminal' => strtoupper($b->terminal),
                'amount' => ( (float)$b->amount*(int)$b->qty ),
                'container' => $b->container
            ]);
        }

        return response()->json([ 'success' => true, 'message' => 'Successfully generated']);
    }

    public function indexRates(Request $request){
        $per_page = $request->query('per-page') && $request->query('per-page') != '' ? $request->query('per-page') : 50;

        $data =  DB::table('index_rates')->orderBy('data_date','desc')->paginate(isset($per_page) ? $per_page : 50);

        return response()->json([ 'success' => true, 'message' => $data ]);
    }

    public function manualImport(Request $request){
        $request->validate([
            'data_date' => 'required|date',
            'location_to' => 'required',
            'location_from' => 'required',
            'amount' => 'required|numeric',
            'container' => 'required'
        ]);

        if( $request->input('id') ){
            $q = IndexRates::findOrFail($request->input('id'));

            if( $q ){
                $q->data_date = $request->input('data_date');
                $q->location_from = $request->input('location_from');
                $q->location_to = $request->input('location_to');
                $q->amount = $request->input('amount');
                $q->terminal = strtoupper($request->input('terminal'));
                $q->container = $request->input('container');
                $q->save();
            }

            return response()->json([ 'success' => $q, 'message' => $q ? 'Successfully updated' : 'Something went wrong, unable to process request' ]);
        }

        $q = new IndexRates;
        $q->data_date = $request->input('data_date');
        $q->location_from = $request->input('location_from');
        $q->location_to = $request->input('location_to');
        $q->amount = $request->input('amount');
        $q->terminal = strtoupper($request->input('terminal'));
        $q->container = $request->input('container');
        $q->is_custom = 1;
        $q->save();

        return response()->json([ 'success' => $q, 'message' => $q ? 'Successfully created new data' : 'Something went wrong, unable to process request' ]);
    }

    public function deleteManualImport(Request $request){
        $request->validate([
            'id' => 'required|numeric'
        ]);

        $q = IndexRates::findOrFail($request->input('id'));

        if( $q ){
            $q->delete();
        }

        return response()->json([ 'success' => $q, 'message' => $q ? 'Successfully deleted' : 'Something went wrong, unable to process request' ]);
    }

    public function getContainers(){
        return response()->json(\DB::table('container_sizes')->get()->pluck('name'));
    }

    public function getOffices(){
        return response()->json(\DB::table('shifl_offices')->get()->pluck('name'));
    }

    public function getTerminals(){
        return response()->json(\DB::table('terminals')->get()->pluck('name'));
    }
}
