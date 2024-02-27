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

class ProfitMonitorController extends Controller
{
    // public function getCalculatedShipment(Request $request)
    // {
    //     $response = ['status' => 'ok','results' => []];

    //     $per_page = $request->query('per-page');

    //     $ref_num_q_string = $request->query('filterbyrefnum');
    //     $customer_q_string = $request->query('filterbycustomer');

    //     $sort_by = $request->query('sortby') !=='' ? ($request->query('sortby')==='customer' ? 'eta' : $request->query('sortby')) : 'eta';
    //     $sort_type = $request->query('sorttype') !=='' ? $request->query('sorttype') : 'desc';

    //     $customers = [];
    //     if (!is_null($customer_q_string) && $customer_q_string !=='') {
    //         $customers = Customer::where('company_name', 'like', '%'.$customer_q_string.'%')->select('id')->get();
    //     }
    //     $customer_ids = [];
    //     if (count($customers) > 0) {
    //         $customer_ids = collect($customers)->pluck('id');
    //     }

    //     $shipments = [];
    //     $shipments = Shipment::select('id', 'shifl_ref', 'eta', 'customer_id', 'shifl_office_origin_from_id')
    //     ->when(count($customer_ids) > 0, function ($q) use ($customer_ids) {
    //         return $q->whereIn('customer_id', $customer_ids);
    //     })
    //     ->when(!is_null($ref_num_q_string) && $ref_num_q_string !=='', function ($q) use ($ref_num_q_string) {
    //         return $q->where('shifl_ref', 'like', '%'.$ref_num_q_string.'%');
    //     })
    //     ->with(['customer' => function ($q) {
    //         $q->select('id', 'company_name');
    //     }])
    //     ->with(['bill' => function ($q) {
    //         $q->with(['bill_items']);
    //         $q->select('id', 'shipment_id', 'total_amount');
    //     }])
    //     ->with(['invoice' => function ($q) {
    //         $q->with(['invoiceServices']);
    //         $q->select('id', 'shipment_id', 'total_amount');
    //     }])
    //     ->where(function ($q) {
    //         $q->whereExists(function ($q2) {
    //             $q2->select('id', 'shipment_id', 'total_amount')
    //              ->from('bills')
    //              ->whereColumn('bills.shipment_id', 'shipments.id');
    //         })
    //         ->orWhereExists(function ($q3) {
    //             $q3->select('id', 'shipment_id', 'total_amount')
    //              ->from('invoices')
    //              ->whereColumn('invoices.shipment_id', 'shipments.id');
    //         });
    //     })
    //     ->orderBy($sort_by, $sort_type)
    //     ->paginate(isset($per_page) ? $per_page : 50);

    //     if (count($shipments->items())>0) {
    //         $response['results'] = $shipments;
    //     }
    //     return response()->json($response);
    // }

    public function getCalculatedShipmentV2(Request $request)
    {
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
            
            ->orderBy($sort_by, $sort_type)
            ->paginate(isset($per_page) ? $per_page : 50);

            if (count($shipments->items())>0) {
                $response['results'] = $shipments;
            }
            return response()->json($response);
        }
        return 'not Authorized';

    }

    // public function paginate($items, $perPage = 50, $page = null){
    //     $page = $page ? : (Paginator::resolveCurrentPage() ?: 1);
    //     $items = $items instanceof Collection ? $items : Collection::make($items);
    //     return new LengthAwarePaginator($items->forPage($page, $perPage), $items->count(), $perPage, $page, $options);
    // }

    // public function getCalculatedShipment(Request $request){
    //     $response = ['status' => 'ok','results' => []];

    //     $shipments = Shipment::with(['bill','invoice','customer'])->get();
    //     $shipmentProfits = [];
    //     foreach($shipments as $shpmnt){
    //         $totalInvoiceAmount = collect($shpmnt->invoice)->sum('total_amount');
    //         $totalBillAmount = collect($shpmnt->bill)->sum('total_amount');
    //         $filteredData = [
    //             'shifl_ref' => $shpmnt->shifl_ref,
    //             'customer' => $shpmnt->customer->company_name,
    //             'eta' => $shpmnt->eta,
    //             'customer_id' => $shpmnt->customer->id,
    //             'total_invoice' => $totalInvoiceAmount,
    //             'total_bill' => $totalBillAmount,
    //             'shipment_id' => $shpmnt->id,
    //         ];
    //         array_push($shipmentProfits,$filteredData);
    //     }
    //     //return $shipmentProfits;

    //     if(count($shipmentProfits)>0){
    //         $response['results'] = $shipmentProfits;
    //     }
    //     return response()->json($response);
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
        return 'not Authorized';
            
    }
}
