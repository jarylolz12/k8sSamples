<?php

namespace Ezea\ProfitMonitor\Http\Controllers;

use Illuminate\Routing\Controller;
use Illuminate\Http\Request;
use App\Invoice;
use App\Bill;
use App\Shipment;
use App\Customer;
use App\ShiflOffice;
use App\SaleAgent;
use App\QuickbooksCompany;
use Illuminate\Support\Facades\Auth;
use App\Http\Resources\Standard as StandardResource;
use Illuminate\Support\Collection;

class ProfitMonitorController extends Controller
{

    public function getCalculatedShipmentV2(Request $request)
    {
        $authRole = Auth::user()->roles()->first();

        if(strtoupper($authRole->name) === "SUPER ADMIN" || strtoupper($authRole->name) === "SALES REPRESENTATIVE" ){
            $response = ['status' => 'ok','results' => []];

            $per_page = $request->input('per-page',50);
            $ref_num_q_string = $request->input('filterbyrefnum');
            $customer_q_string = $request->input('filterbycustomer');
            $realmId = $request->input('realmId');
            $sort_by = $request->input('sortby','shifl_ref');
            $sort_type = $request->input('sorttype','desc');

            $sort_by = $sort_by == 'profit' ? 'profitt' : $sort_by;
            $sort_by = $sort_by == 'customer' ? 'customer_name' : $sort_by;
            $sort_custom_arr = [ 'containers_qty','profitt','projected_profit' ];


            $shipments = Shipment::select('id', 'shifl_ref', 'eta', 'customer_id','schedules_group_bookings','containers_group',\DB::raw('(SELECT company_name FROM customers WHERE id = shipments.customer_id LIMIT 1) AS customer_name'))
            ->with([
                'customer',
                'notes',
                'bill' => function ($q) use ($realmId){
                    $q->when($realmId != 0, function($q2) use ($realmId){
                        return $q2->where('bills.qbo_company_realmid',$realmId);
                    });
                    $q->with(['bill_items']);
                    $q->select('id', 'shipment_id', 'total_amount','qbo_company_realmid');
                },
                'invoice' => function ($q) use ($realmId) {
                    $q->when($realmId != 0, function($q2) use ($realmId){
                        return $q2->where('invoices.qbo_company_realmid',$realmId);
                    });
                    $q->with(['invoiceServices']);
                    $q->select('id', 'shipment_id', 'total_amount','qbo_company_realmid');
                }
            ]);

            if( !empty($customer_q_string) ){
                $shipments = $shipments->whereHas('customer',function($q) use($customer_q_string){
                    $q->where('company_name', 'like', '%'.$customer_q_string.'%');
                });
            }

            if( !empty($ref_num_q_string) ){
                $shipments = $shipments->where('shifl_ref', 'like', '%'.$ref_num_q_string.'%');
            }

            if( !empty($realmId) ){
                $shipments = $shipments->where(function ($q) use ($realmId){
                    $q->whereExists(function ($q2) use ($realmId){
                        $q2->select('id', 'shipment_id', 'total_amount','qbo_company_realmid')
                        ->from('bills')
                        ->whereColumn('bills.shipment_id', 'shipments.id')
                        ->when($realmId != 0, function($q3) use ($realmId){
                            return $q3->where('bills.qbo_company_realmid',$realmId);
                        });
                    })
                    ->orWhereExists(function ($q4) use ($realmId){
                        $q4->select('id', 'shipment_id', 'total_amount','qbo_company_realmid')
                        ->from('invoices')
                        ->whereColumn('invoices.shipment_id', 'shipments.id')
                        ->when($realmId != 0, function($q5) use ($realmId){
                            return $q5->where('invoices.qbo_company_realmid',$realmId);
                        });;
                    });
                });
            }

            if( strtoupper($authRole->name) === "SUPER ADMIN" ){
                $shipments = $shipments->where(function ($q1) use ($request) {
                    $month = $request->query("month");
                    if (!is_null($month) && !empty($month) && $month != "null") {
                        $month = \Carbon\Carbon::parse($month);
                        if (!empty($month)) {
                            $q1->whereDate("eta", ">=", $month->startOfMonth())
                            ->whereDate("eta", "<=", $month->endOfMonth());
                        }
                    }
                })
                ->whereHas('customer', function ($q2) use ($request) {
                    $sale_persons = $request->query("sale_persons");
                    if (!is_null($sale_persons) && !empty($sale_persons) && $sale_persons != "null") {
                        $q2->where("sale_persons", $sale_persons);
                    }
                });
            }

            if( strtoupper($authRole->name) === "SALES REPRESENTATIVE" ){
                $shipments = $shipments->where(function ($q1) use ($request) {
                    $month = $request->query("month");
                    if (!is_null($month) && !empty($month) && $month != "null") {
                        $month = \Carbon\Carbon::parse($month);
                        if (!empty($month)) {
                            $q1->whereDate("eta", ">=", $month->startOfMonth())
                            ->whereDate("eta", "<=", $month->endOfMonth());
                        }
                    }
                })
                ->whereHas('customer', function ($q2) use ($request) {
                    $q2->where("sale_persons", strval(Auth::id()));
                });
            }

            
            if( !in_array($sort_by,$sort_custom_arr) ){
                $shipments = $shipments->orderBy($sort_by,$sort_type);
            }

            $shipments = $shipments->paginate($per_page);

            $totalInvoices = 0;
            $totalBills = 0;
            
            $shipments_json = json_decode(json_encode($shipments));
            
            if( in_array($sort_by,$sort_custom_arr) ){
                if( $sort_type == 'desc' ){
                    $shipments_json->data = collect($shipments_json->data)->sortByDesc($sort_by)->values()->all();
                }else{
                    $shipments_json->data = collect($shipments_json->data)->sortBy($sort_by)->values()->all();
                }

                $shipments = $shipments_json;
            }

            if( !empty($customer_q_string) ){
                foreach($shipments_json->data as $shpmnt){
                    foreach($shpmnt->invoice as $inv){
                        $totalInvoices += $inv->total_amount;
                    }
                    foreach($shpmnt->bill as $bill){
                        $totalBills += $bill->total_amount;
                    }
                }
            }

            return response()->json([ 'success' => true, 'message' => $shipments, 'data' => [ 'bills' => $totalBills, 'invoices' => $totalInvoices ] ]);
            
        }
    }

    // public function getProfit(Request $request){
    //     $ref_num_q_string = $request->input('filterbyrefnum');
    //     $customer_q_string = $request->input('filterbycustomer');
    //     $realmId = $request->input('realmId');
    //     $authRole = Auth::user()->roles()->first();
    //     $customers = false;
    //     $shipments = false;

    //     if( !empty($customer_q_string) ){
    //         $customers = $customers ? $customers : \DB::table('customers')->select('id');
    //         $customers = $customers->where('company_name', 'like', '%'.$customer_q_string.'%');
    //     }

    //     if( strtoupper($authRole->name) === "SUPER ADMIN" ){
    //         $customers = $customers ? $customers : \DB::table('customers')->select('id');

    //         $sale_persons = $request->query("sale_persons");
    //         if (!is_null($sale_persons) && !empty($sale_persons) && $sale_persons != "null") {
    //             $customers = $customers->where("sale_persons", $sale_persons);
    //         }
    //     }

    //     if( strtoupper($authRole->name) === "SALES REPRESENTATIVE" ){
    //         $customers = $customers ? $customers : \DB::table('customers')->select('id');
    //         $customers = $customers->where("sale_persons", strval(Auth::id()));
    //     }

    //     if( $customers ){
    //         $customers = $customers->pluck('id');
    //     }

    //     $shipments = $shipments ? $shipments : \DB::table('shipments')->select('id');
    //     $shipments = $shipments->whereIn('customer_id',$customers);

    //     dd($shipments);

    //     if( !empty($ref_num_q_string) ){
    //         $shipments = $shipments ? $shipments : \DB::table('shipments')->select('id');
    //         $shipments = $shipments->where('shifl_ref', 'like', '%'.$ref_num_q_string.'%');
    //     }

    //     if( !empty($realmId) ){
    //         $shipments = $shipments ? $shipments : \DB::table('shipments')->select('id');
    //         $shipments = $shipments->where(function ($q) use ($realmId){
    //             $q->whereExists(function ($q2) use ($realmId){
    //                 $q2->select('id', 'shipment_id', 'total_amount','qbo_company_realmid')
    //                 ->from('bills')
    //                 ->whereColumn('bills.shipment_id', 'shipments.id')
    //                 ->when($realmId != 0, function($q3) use ($realmId){
    //                     return $q3->where('bills.qbo_company_realmid',$realmId);
    //                 });
    //             })
    //             ->orWhereExists(function ($q4) use ($realmId){
    //                 $q4->select('id', 'shipment_id', 'total_amount','qbo_company_realmid')
    //                 ->from('invoices')
    //                 ->whereColumn('invoices.shipment_id', 'shipments.id')
    //                 ->when($realmId != 0, function($q5) use ($realmId){
    //                     return $q5->where('invoices.qbo_company_realmid',$realmId);
    //                 });;
    //             });
    //         });
    //     }

    //     if( strtoupper($authRole->name) === "SUPER ADMIN" ){
    //         $shipments = $shipments ? $shipments : \DB::table('shipments')->select('id');
    //         $shipments = $shipments->where(function ($q1) use ($request) {
    //             $month = $request->query("month");
    //             if (!is_null($month) && !empty($month) && $month != "null") {
    //                 $month = \Carbon\Carbon::parse($month);
    //                 if (!empty($month)) {
    //                     $q1->whereDate("eta", ">=", $month->startOfMonth())
    //                     ->whereDate("eta", "<=", $month->endOfMonth());
    //                 }
    //             }
    //         });
    //     }

    //     if( strtoupper($authRole->name) === "SALES REPRESENTATIVE" ){
    //         $shipments = $shipments ? $shipments : \DB::table('shipments')->select('id');
    //         $shipments = $shipments->where(function ($q1) use ($request) {
    //             $month = $request->query("month");
    //             if (!is_null($month) && !empty($month) && $month != "null") {
    //                 $month = \Carbon\Carbon::parse($month);
    //                 if (!empty($month)) {
    //                     $q1->whereDate("eta", ">=", $month->startOfMonth())
    //                     ->whereDate("eta", "<=", $month->endOfMonth());
    //                 }
    //             }
    //         });
    //     }

    //     if( $shipments ){
    //         if( $customers ){
    //             $shipments = $shipments->whereIn('customer_id',$customers);
    //         }
    //         $shipments = $shipments->pluck('id');
    //     }


    //     $totalInvoices = 0;
    //     $totalBills = 0;

    //     if( $shipments && !empty($customer_q_string) ){
    //         $totalInvoices = \DB::table('invoices')
    //         ->whereNotNull('total_amount')
    //         ->whereIn('shipment_id',$shipments)
    //         ->select(\DB::raw('SUM(total_amount) AS total_invoices'))
    //         ->first()
    //         ->total_invoices;

    //         $totalBills = \DB::table('bills')
    //         ->whereNotNull('total_amount')
    //         ->whereIn('shipment_id',$shipments)
    //         ->select(\DB::raw('SUM(total_amount) AS total_bills'))
    //         ->first()
    //         ->total_bills;
    //     }

    //     dd($totalInvoices.' / '.$totalBills);
        
    // }

    public function getShiflOffices()
    {
        $offices = ShiflOffice::all();
        return response()->json($offices);
    }

    public function getQBOCompanies(){
        $companies = [];
        $getCompanies = QuickbooksCompany::select('id','company_realm_id','company_name')->get();
        foreach($getCompanies as $comp){
            $c = [
                'id'=> $comp->id,
                'company_realm_id' => strval($comp->company_realm_id),
                'company_name' => $comp->company_name
            ];
            array_push($companies,$c);
        }
        return response()->json($companies);
    }

    public function getSalesRepresentatives()
    {
        return response()->json(SaleAgent::select('id', 'name')->whereHas('roles', function ($role) {
            $role->where('name', 'Sales Representative');
        })->get());
    }

    public function getTotalProfitByRequest(Request $request){
        try{
            $authRole = Auth::user()->roles()->first();

            if(strtoupper($authRole->name) === "SUPER ADMIN" || strtoupper($authRole->name) === "SALES REPRESENTATIVE" ){
                $response = ['status' => 'ok','results' => []];

                $per_page = $request->query('per-page');

                $ref_num_q_string = $request->query('filterbyrefnum');
                $customer_q_string = $request->query('filterbycustomer');
                $realmId = $request->query('realmId');

                $sort_by = $request->query('sortby') !=='' ? ($request->query('sortby')==='customer' ? 'eta' : $request->query('sortby')) : 'eta';
                $sort_type = $request->query('sorttype') !=='' ? $request->query('sorttype') : 'desc';

                $customers = [];
                if (!is_null($customer_q_string) && $customer_q_string !=='') {
                    $customers = Customer::where('company_name', 'like', '%'.$customer_q_string.'%')->select('id')->get();
                }
                $customer_ids = [];
                if (count($customers) > 0) {
                    $customer_ids = collect($customers)->pluck('id');
                }

                $shipments = [];
                $shipments = Shipment::select('id', 'shifl_ref', 'eta', 'customer_id','schedules_group_bookings')
                ->when(count($customer_ids) > 0, function ($q) use ($customer_ids) {
                    return $q->whereIn('customer_id', $customer_ids);
                })
                ->when(!is_null($ref_num_q_string) && $ref_num_q_string !=='', function ($q) use ($ref_num_q_string) {
                    return $q->where('shifl_ref', 'like', '%'.$ref_num_q_string.'%');
                })
                // ->when($office_id > 0, function ($q) use ($office_id) {
                //     return $q->where('shifl_office_origin_from_id', $office_id);
                // })
                ->with(['customer' => function ($q) {
                    $q->select('id', 'company_name', 'sale_persons');
                }])
                ->with(['bill' => function ($q) use ($realmId){
                    $q->when($realmId != 0, function($q2) use ($realmId){
                        return $q2->where('bills.qbo_company_realmid',$realmId);
                    });
                    $q->with(['bill_items']);
                    $q->select('id', 'shipment_id', 'total_amount','qbo_company_realmid');
                }])
                ->with(['invoice' => function ($q) use ($realmId) {
                    $q->when($realmId != 0, function($q2) use ($realmId){
                        return $q2->where('invoices.qbo_company_realmid',$realmId);
                    });
                    $q->with(['invoiceServices']);
                    $q->select('id', 'shipment_id', 'total_amount','qbo_company_realmid');
                }])
                ->where(function ($q) use ($realmId){
                    $q->whereExists(function ($q2) use ($realmId){
                        $q2->select('id', 'shipment_id', 'total_amount','qbo_company_realmid')
                        ->from('bills')
                        ->whereColumn('bills.shipment_id', 'shipments.id')
                        ->when($realmId != 0, function($q3) use ($realmId){
                            return $q3->where('bills.qbo_company_realmid',$realmId);
                        });
                    })
                    ->orWhereExists(function ($q4) use ($realmId){
                        $q4->select('id', 'shipment_id', 'total_amount','qbo_company_realmid')
                        ->from('invoices')
                        ->whereColumn('invoices.shipment_id', 'shipments.id')
                        ->when($realmId != 0, function($q5) use ($realmId){
                            return $q5->where('invoices.qbo_company_realmid',$realmId);
                        });;
                    });
                })
                ->when(strtoupper($authRole->name) === "SUPER ADMIN", function($q) use ($request){
                    return $q->where(function ($q1) use ($request) {
                        $month = $request->query("month");
                        //\Log::info($month);
                        if (!is_null($month) && !empty($month) && $month != "null") {
                            $month = \Carbon\Carbon::parse($month);
                            if (!empty($month)) {
                                $q1->whereDate("eta", ">=", $month->startOfMonth())
                                ->whereDate("eta", "<=", $month->endOfMonth());
                            }
                        }
                    })
                    ->whereHas('customer', function ($q2) use ($request) {
                        $sale_persons = $request->query("sale_persons");
                        if (!is_null($sale_persons) && !empty($sale_persons) && $sale_persons != "null") {
                            $q2->where("sale_persons", $sale_persons);
                        }
                    });
                })
                ->when(strtoupper($authRole->name) === "SALES REPRESENTATIVE", function($q) use ($request){
                    return $q->where(function ($q1) use ($request) {
                        $month = $request->query("month");
                        if (!is_null($month) && !empty($month) && $month != "null") {
                            $month = \Carbon\Carbon::parse($month);
                            if (!empty($month)) {
                                $q1->whereDate("eta", ">=", $month->startOfMonth())
                                ->whereDate("eta", "<=", $month->endOfMonth());
                            }
                        }
                    })
                    ->whereHas('customer', function ($q2) use ($request) {
                        $q2->where("sale_persons", strval(Auth::id()));
                    });
                })
                ->get();

                $totalInvoices = 0;
                $totalBills = 0;

                if(count($shipments)>0) {
                    foreach($shipments as $shpmnt){
                        foreach($shpmnt->invoice as $inv){
                            $totalInvoices += $inv->total_amount;
                        }
                        foreach($shpmnt->bill as $bill){
                            $totalBills += $bill->total_amount;
                        }
                    }
                }

                if (count($shipments)>0) {
                    $response['results'] = [
                        'bills' => $totalBills,
                        'invoices' => $totalInvoices,
                        'count' => count($shipments),
                    ];
                }
                
                return response()->json($response);
            }
        }catch (\Throwable $throwable){
            return response([
                'message' => 'No results found'
            ], 404);
        }
        return 'not Authorized';       
    }

    public function saveNotes(Request $request){
        $request->validate([
            'id' => 'required|exists:shipments',
            'notes' => 'required'
        ],[
            'id.required' => 'Shipment ID is required',
            'id.exists' => 'Shipment not found',
        ]);

        $q = Shipment::find($request->input('id'))->notes()->create([
            'name' => 'profit notes',
            'type' => 'text',
            'content' => $request->input('notes')
        ]);

        return response()->json([ 'success' => ( $q ? true : false), 'message' => $q ? 'Successfully created' : 'Saving failed. Try again.' ]);
    }
}
