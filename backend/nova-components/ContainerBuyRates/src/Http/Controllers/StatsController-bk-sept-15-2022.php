<?php

namespace Juliverdevshifl\ContainerBuyRates\Http\Controllers;

use Illuminate\Routing\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use \Carbon\Carbon;
use \Carbon\CarbonPeriod;

/** 
 * 
 * @group Juliverdevshifl
 * 
 * APIs to manage the Juliverdevshifl
 * 
*/

class StatsController extends Controller{

	/**  
	 * Display the specified graph terminal resource.
     *
     * @authenticated
	 * 
	 * @response 200 {
	 *  	"success": true,
	 *  	"message": ...,
	 *  	"all_time_average": ...,
	 *  	"labels": {
	 * 			...,
	 *  	},
	 * 		"data": {
	 * 			"name": "LOS ANGELES",
	 * 			"color": "#eb9534"
	 * 		}
	 * }  
	 *  
	 * @response status=400 scenario="Unauthenticated" {
     *     "message": "Unauthenticated."
     * }
	 */

	public function graph(Request $request){

		$filter = $request->input('filter','all');
		$container = $request->input('container',"40'HQ");

		$terminals = [	
			[ 'name' => 'NY/NJ', 'color' => "#0171A1" ],
			[ 'name' => 'LOS ANGELES', 'color' => "#eb9534" ]
		];

		if( $request->input('terminals','all') != 'all' ){
			$terminals_req = explode('-',$request->input('terminals'));

			$terminals = collect($terminals)->filter(function($value,$key) use($terminals_req){
				return in_array($value['name'],$terminals_req);
			});

			$terminals = $terminals->all();
		}

		$data = [];

		$labels = $request->input('staging') == 1 ? $this->getGraphLabels2($filter) : $this->getGraphLabels($filter);

		foreach( $terminals as $t ){
			$graphData = $this->getGraphDataByTerminal($t,$container,$labels);
			$data[] = [ 'name'=> $t['name'], 'color' => $t['color'], 'data' => $graphData ];
		}

        return response()->json([ 'success' => true, 'message' => '', 'data' => $data, 'labels' => $labels, 'all_time_average' => $this->getAllTimeAverage($filter,$container,$labels) ]);
	} 

	private function getAllTimeAverage($filter,$container,$labels){

		$terminals = [	
			[ 'name' => 'NY/NJ', 'color' => "#0171A1" ],
			[ 'name' => 'LOS ANGELES', 'color' => "#eb9534" ]
		];

		$data = [];

		foreach( $terminals as $t ){
			$data[] = $this->getGraphDataByTerminal($t,$container,$labels);
		}

		$totals = collect($data)->map(function($item){
			return $item['totals'];
		})->reduce(function ($carry, $item) {
		    return $carry + $item;
		}, 0);

		$total_items = collect($data)->map(function($item){
			return $item['total_items'];
		})->reduce(function ($carry, $item) {
		    return $carry + $item;
		}, 0);

		return $total_items == 0 ? 0 : $totals/$total_items;
	}
	/**
	 * 
     * Display the specified containers resource.
	 * 
	 * @authenticated
	 *  
	 * @urlParam id string required The containers size NAME
	 * 
	 * @apiResource 201 App\Http\Resources\Standard
	 * @apiResourceModel App\ContainerSize  
	 *    
	 * @response status=400 scenario="Unauthenticated" {
     *     "message": "Unauthenticated."
     * }
	 *
	*/
	public function containers(Request $request){


		$container_where_not_in = ["Shipment","Sell","20'GP"];

		$q = DB::table('container_sizes');

		if( $request->id && $request->id != 'all' ){
			$q = $q->where('name',$request->id);
		}else{
			$q = $q->whereNotIn('name',$container_where_not_in);
		}

		return response()->json($q->get()); 
	}

	public function getGraphLabels($filter){

		if( $filter == '18 Months' ){
	        $from = (new Carbon)->subMonths(18)->startOfDay()->toDateString();
		}elseif( $filter == '1 Year' ){
	        $from = (new Carbon)->subMonths(12)->startOfDay()->toDateString();
		}elseif( $filter == '6 Months' ){
	        $from = (new Carbon)->subMonths(6)->startOfDay()->toDateString();
		}elseif( $filter == '3 Months' ){
	        $from = (new Carbon)->subMonths(3)->startOfDay()->toDateString();
		}else{

			$first = DB::table('index_rates')->select('data_date')->orderBy('data_date')->first();

			$from = $first->data_date;
		}

		$to = DB::table('index_rates')->select('data_date')->orderBy('data_date','desc')->first()->data_date;

		$arr = DB::table('index_rates')
			->whereDate('data_date','>=', $from)
			->whereDate('data_date','<=', $to)
            ->select('data_date')
            ->groupBy('data_date')
            ->orderBy('data_date')
            ->get()
            ->pluck('data_date');

        $arr = collect($arr)->map(function($i){
        	return date_format(date_create($i),'Y-m-d');
        });

        $arr = $arr->all();

		$fromd = (new Carbon)->parse(end($arr))->addMonthsNoOverflow(1)->startOfMonth()->toDateString();
        $tod = (new Carbon)->now()->addMonthsNoOverflow(1)->startOfMonth()->toDateString();

		$periodd = CarbonPeriod::create($fromd,'1 month',$tod);

		// Iterate over the period
		foreach ($periodd as $date) {
		    $d = explode('-',$date->format('Y-m-d'));

		    $arr[] = $d[0].'-'.$d[1].'-01';
		}

		return $arr; 
	}

	public function getGraphLabels2($filter){

		$arr = DB::table('index_rates')->select('data_date')->groupBy('data_date')->orderBy('data_date')->get()->pluck('data_date');
		$from = false;

		if( count($arr) == 0 ) return [];

		if( $filter == '18 Months' ){
	        $from = (new Carbon)->subMonths(18)->startOfDay()->toDateString();
		}elseif( $filter == '1 Year' ){
	        $from = (new Carbon)->subMonths(12)->startOfDay()->toDateString();
		}elseif( $filter == '6 Months' ){
	        $from = (new Carbon)->subMonths(6)->startOfDay()->toDateString();
		}elseif( $filter == '3 Months' ){
	        $from = (new Carbon)->subMonths(3)->startOfDay()->toDateString();
		}

		if( $from ){
			$arr =  DB::table('index_rates')->whereBetween('data_date',[$from,$arr[count($arr)-1]] )->select('data_date')->groupBy('data_date')->get()->pluck('data_date');
		}

		return $arr; 
	}

	public function getGraphDataByTerminal($terminal,$container,$labels){
		
        $data = [];
        $total_items = 0;
        $totals = 0;
        $items  = [];

        foreach( $labels as $k => $l ){

        	$q = DB::table('index_rates')
        	->where('container',$container)
			->whereDate('data_date',$l)
			->whereRaw('UPPER(terminal) = "'.strtoupper($terminal['name']).'"')
			->selectRaw('data_date,terminal,location_from,location_to,created_at,is_custom,container,SUM(amount) AS amount');
			

			$items[] = $q;

			$total_items_query = $q->count();

			$total_items = $total_items + $total_items_query;

			$q_res = $q->groupBy('data_date')->get();

			$total_amount = $totals = $q_res->pluck('amount')
			->reduce(function ($carry, $item) {
			    return $carry + $item;
			}, 0);

			$data[] = [ 'date' => $l, 'value' => (float)$total_amount ];
        }

        ////// FIX ZERO VALUE //////
        $data2 = $data;

        foreach( $data2 as $k => $d ){
        	if( empty($d['value']) ){
        		if( $k > 0 ){
        			$data[$k] = [ 'date' => $d['date'], 'value' => $data[$k-1]['value'] ];
        		}else{
        			continue;
        		}
        	}
        }

        $new_data = [];

        foreach( $data as $d ){
        	$new_data[$d['date']] = $d['value'];
        }

        return [ 'data' => $new_data, 'total_items' => $total_items, 'totals' => number_format((float)($totals), 2, '.', ''), 'average' => number_format((float)($total_items == 0 ? 0 : $totals/$total_items), 2, '.', '')];
	}

	public function getPercentage($filter,$container,$terminal){
		if( $filter == '18 Months' ){
	        $from = (new Carbon)->subMonths(36)->startOfDay()->toDateString();
			// $to = (new Carbon)->subMonths(18)->endOfDay()->toDateString();

			$from2 = (new Carbon)->subMonths(18)->startOfDay()->toDateString();
		}elseif( $filter == '1 Year' ){
	        $from = (new Carbon)->subMonths(24)->startOfDay()->toDateString();
			// $to = (new Carbon)->subMonths(12)->endOfDay()->toDateString();

			$from2 = (new Carbon)->subMonths(12)->startOfDay()->toDateString();
		}elseif( $filter == '6 Months' ){
	        $from = (new Carbon)->subMonths(12)->startOfDay()->toDateString();
			// $to = (new Carbon)->subMonths(6)->endOfDay()->toDateString();

			$from2 = (new Carbon)->subMonths(6)->startOfDay()->toDateString();
		}elseif( $filter == '3 Months' ){
	        $from = (new Carbon)->subMonths(6)->startOfDay()->toDateString();
			// $to = (new Carbon)->subMonths(3)->endOfDay()->toDateString();

			$from2 = (new Carbon)->subMonths(3)->startOfDay()->toDateString();
		}else{

			$first = DB::table('index_rates')->select('data_date')->orderBy('data_date')->first();

			$a = new Carbon($first->data_date);

			// $from = $a->greaterThan($b) ? $second->etd_date : $first->data_date;
			$from = $first->data_date;
	        // $to = (new Carbon)->now()->endOfDay()->toDateString();

	        $from2 = $from;
		}

		// $to2 = (new Carbon)->now()->endOfDay()->toDateString();

		$to = DB::table('index_rates')->select('data_date')->orderBy('data_date','desc')->first()->data_date;

		$to2 = $to;

		////// OLD AMOUNT
		$amount_old = DB::table('index_rates')
		->where('container',$container)
		->whereRaw('UPPER(terminal) = "'.strtoupper($terminal['name']).'"')
		->whereDate('data_date','>=', $from)
		->whereDate('data_date','<=', $to)
		->selectRaw('data_date,terminal,location_from,location_to,created_at,is_custom,container,SUM(amount) AS amount')
		->groupBy('data_date')
		->get()
		->map(function($item){
        	return (float)$item->amount;
        })
        ->reduce(function ($carry, $item) {
		    return $carry + $item;
		}, 0);


        ////// NEW AMOUNT
		$amount_new = DB::table('index_rates')
		->where('container',$container)
		->whereRaw('UPPER(terminal) = "'.strtoupper($terminal['name']).'"')
		->whereDate('data_date','>=', $from2)
		->whereDate('data_date','<=', $to2)
		->selectRaw('data_date,terminal,location_from,location_to,created_at,is_custom,container,SUM(amount) AS amount')
		->groupBy('data_date')
		->get()
		->map(function($item){
        	return (float)$item->amount;
        })
        ->reduce(function ($carry, $item) {
		    return $carry + $item;
		}, 0);

		if( $amount_old == 0 ){
			return 100;
		}


		return number_format( ( ( ($amount_new - $amount_old) / $amount_old ) * 100) ,2,'.','');
	}

	private function moneyFormat($m){

        $expo = pow(10,3);

        setlocale(LC_MONETARY,"en_US");
        
        return number_format(intval($m*$expo)/$expo, 2);
	}

	public function getDataByTerminal($terminal,$container,$filter){

        if( $filter == '18 Months' ){
	        $from = (new Carbon)->subMonths(18)->startOfDay()->toDateString();
		}elseif( $filter == '1 Year' ){
	        $from = (new Carbon)->subMonths(12)->startOfDay()->toDateString();
		}elseif( $filter == '6 Months' ){
	        $from = (new Carbon)->subMonths(6)->startOfDay()->toDateString();
		}elseif( $filter == '3 Months' ){
	        $from = (new Carbon)->subMonths(3)->startOfDay()->toDateString();
		}else{

			$first = DB::table('index_rates')->select('data_date')->orderBy('data_date')->first();

			// $a = new Carbon($first->data_date);

			// $from = $a->greaterThan($b) ? $second->etd_date : $first->data_date;
			$from = $first->data_date;
		}

		// $to = (new Carbon)->now()->endOfDay()->toDateString();

		$to = DB::table('index_rates')->select('data_date')->orderBy('data_date','desc')->first()->data_date;

    	$q = DB::table('index_rates')
    	->where('container',$container)
		->whereRaw('UPPER(terminal) = "'.strtoupper($terminal['name']).'"')
		->whereDate('data_date','>=', $from)
		->whereDate('data_date','<=', $to)
		->selectRaw('data_date,terminal,location_from,location_to,created_at,is_custom,container,SUM(amount) AS amount')
		->groupBy('data_date')
		->get();

		$total_items = count($q);
		$totals = $q->map(function($item){
        	return (float)$item->amount;
        })
        ->reduce(function ($carry, $item) {
		    return $carry + $item;
		}, 0);	        
        
        $percentage = $this->getPercentage($filter,$container,$terminal);

        return [ 'total_items' => $total_items, 'totals' => number_format((float)($totals), 2, '.', ''), 'average' => number_format((float)($total_items == 0 ? 0 : $totals/$total_items), 2, '.', ''), 'percentage' => $percentage ];
	}
	/** 
     * Display the specified terminal resource.
	 * 
	 * @authenticated
	 * 
	 * @urlParam id int required The indexes container_sizes ID
	 * 
	 * @response {
	 *    "success": true,
	 *    "message": "",
	 * 	  "data": {
	 * 		 "name": "LOS ANGELES",
	 * 	 	 "color": "#eb9534"
	 * 	  }
	 * }  
	 * 
	 * @response status=400 scenario="Unauthenticated" {
     *     "message": "Unauthenticated."
     * }
	 *  
	*/ 
	public function indexes(Request $request){
		$filter = $request->input('filter','all');
		$container = $request->input('container',"40'HQ");

		$terminals = [	
			[ 'name' => 'NY/NJ', 'color' => "#0171A1" ],
			[ 'name' => 'LOS ANGELES', 'color' => "#eb9534" ]
		];

		$data = [];

		foreach( $terminals as $t ){
			$data[] = [ 'name'=> $t['name'],'data' => $this->getDataByTerminal($t,$container,$filter) ];
		}

        return response()->json([ 'success' => true, 'message' => '', 'data' => $data ]);
	}
}
