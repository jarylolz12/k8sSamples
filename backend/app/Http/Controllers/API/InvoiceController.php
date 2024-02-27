<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Resources\Standard as StandardResource;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;
use App\Invoice;
use App\Card;
use App\TerminalRegion;
use App\PurchaseOrder;
use Carbon\Carbon;
use App\Supplier;
use App\Customer;
use App\Rules\IsArrayRule;
use App\Rules\CheckIfOwnInvoice;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use PDF;
use App\InvoiceService;
use App\Events\InvoiceUpdateEvent;
/**
 * @authenticated
 *
 * @group Invoice
 *
 * APIs to manage the Invoice resource
 */
class InvoiceController extends Controller
{

    /**
     * Display a total due of invoice.
     *
     * @authenticated
     *
     * @apiResource 201 App\Http\Resources\ScribeResources\InvoiceResource
     * @apiResourceModel App\Invoice
     *
     * @response status=404 {
     *    "message": "No data found"
     * }
     *
     * @response status=400 scenario="Unauthenticated" {
     *     "message": "Unauthenticated."
     * }
     *
    */

    public function totalDue(Request $request) {

        $get_authenticated_user = Auth::user();
        $customers = Auth::user()->customersApi->pluck('id');
        try {
            $customer = ($get_authenticated_user->default_customer_id !== null) ? $get_authenticated_user->default_customer_id : $customers[0];
        }catch (\Exception $exception){
            return response([
                'message'=>'No results found'
            ], 404);
        }
        $check_customer = Customer::findOrFail($customer);
        $check_qbo_customers = [$check_customer->qbo_customer];
        $final_qbo_customers = [];

        if (count($check_qbo_customers)>0) {
            foreach($check_qbo_customers as $check_qbo_customer) {

                if ($check_qbo_customer!==null && $check_qbo_customer!=='') {
                    $obj = json_decode($check_qbo_customer);
                    if (isset($obj->customer)) {
                        if (isset($obj->customer->Id)) {
                            array_push($final_qbo_customers, intval($obj->customer->Id));
                        }
                    }
                }
            }
        }

        $invoices = Invoice::whereIn('qbo_customer_id', $final_qbo_customers)
                           ->where('balance','<>','0.00')
                           ->where('due_date','<=', now())
                           ->get();
        $total_due = 0;

        if ( count($invoices) > 0) {
            foreach($invoices as $invoice) {
                $total_due += floatval($invoice->total_amount);
            }
        }

        return response()->json([
            'total_due' => $total_due
        ]);
    }

    /**
     * Downloadable File
     *
     * @authenticated
     *
     * @queryParam id int required Downloadable File
     *
     * @response 200 {
     *      "message": "ok"
     * }
     *
     * @response status=422 scenario="Validation error" {
     *    "message": "",
     *    "errors": {
     *        "message": [
     *            "Failed in retrieving data."
     *        ],
     *        "status": [
     *            "failed."
     *        ],
     *        "id": [
     *            "The id is required."
     *        ]
     *    }
     * }
     *
     * @response status=400 scenario="Unauthenticated" {
     *     "message": "Unauthenticated."
     * }
     *
     */
    public function download(Request $request) {

        $validator = Validator::make($request->all(), [
            'id' => ['required', new CheckIfOwnInvoice],
        ]);

        if ($validator->fails()) {
            return response()->json(
            [
                'errors' => $validator->errors()
            ]
            ,401);
        }

        $check_qbo_customers = Auth::user()->customersApi->pluck('qbo_customer');
        $final_qbo_customers = [];

        if (count($check_qbo_customers)>0) {

            foreach($check_qbo_customers as $check_qbo_customer) {

                if ($check_qbo_customer!==null && $check_qbo_customer!=='') {
                    $obj = json_decode($check_qbo_customer);
                    if (isset($obj->customer)) {
                        if (isset($obj->customer->Id)) {
                            array_push($final_qbo_customers, intval($obj->customer->Id));
                        }
                    }
                }
            }
        }

        $invoice_id = intval($request->input('id'));

        $check_invoice = Invoice::whereIn('qbo_customer_id', $final_qbo_customers)
                            ->where('id', $invoice_id)
                            ->first();

        $check_invoice_items = InvoiceService::where('invoice_id', $invoice_id)->get();

        $final_invoice = null;
        $check_shipment = $check_invoice->shipment;
        $selected_schedule = null;
        $suppliers = [];
        $containers = [];
        $schedules = [];
        $po_numbers = [];
        $container_numbers = [];
        if (isset($check_shipment->schedules_group_bookings) && $check_shipment->schedules_group_bookings!==null && $check_shipment->schedules_group_bookings!=='') {
            $schedules = json_decode($check_shipment->schedules_group_bookings);

            if ( count($schedules)>0 ) {
                foreach ($schedules as $key => $schedule) {
                    if (isset($schedule->is_confirmed) && $schedule->is_confirmed==1) {
                        $selected_schedule = $schedule;
                    }
                }

                if ($selected_schedule!==null) {
                    $selected_schedule->location_from_name = TerminalRegion::findOrFail($selected_schedule->location_from)->name;
                    $selected_schedule->location_to_name = TerminalRegion::findOrFail($selected_schedule->location_to)->name;
                }
            }

        }

        if (isset($check_shipment->suppliers_group_bookings) && $check_shipment->suppliers_group_bookings!==null && $check_shipment->suppliers_group_bookings!=='') {
            $suppliers = json_decode($check_shipment->suppliers_group_bookings);

            if (count($suppliers)>0) {

                foreach($suppliers as $key => $supplier) {
                    $po = PurchaseOrder::find(intval($supplier->po_id));
                    if (isset($po->name)) {
                        array_push($po_numbers,$po->name);
                    }

                    $suppliers[$key]->po_name = (isset($po->name)) ? $po->name : '';
                }
            }
        }

        if (isset($check_shipment->containers_group_bookings) && $check_shipment->containers_group_bookings!==null && $check_shipment->containers_group_bookings!=='') {
            $containers = json_decode($check_shipment->containers_group_bookings);

            if (count($containers)>0) {
                foreach($containers as $key => $container) {
                    array_push($container_numbers,$container->container_num);
                }
            }
        }

        if ( isset($check_invoice->id) ) {
            $balance = floatval($check_invoice->balance);
            $check_balance = floatval($check_invoice->balance);
            $total_amount = floatval($check_invoice->total_amount);
            $payment_status = '';
            if ($check_balance==0) {
                $payment_status = 'Paid';
            } else {
                if ($check_balance!==$total_amount) {
                    $payment_status = 'Partially Paid';
                } else {
                    if ($check_balance==$total_amount) {
                        $payment_status = 'Not Paid';
                    }
                }
            }
            $suppliers = (isset($check_shipment->suppliers_group_bookings)) ? json_decode($check_shipment->suppliers_group_bookings) : [];

            $suppliers_name = [];
            if (isset($suppliers) && count($suppliers)>0) {
                foreach($suppliers as $key => $supplier) {
                    $checkSupplier = Supplier::find($supplier->supplier_id);
                    $suppliers[$key]->name = '';
                    $suppliers[$key]->address = '';
                    if (isset($checkSupplier->id)) {
                        $suppliers[$key]->name = $checkSupplier->company_name;
                        $suppliers[$key]->address = $checkSupplier->address;
                        array_push($suppliers_name, $checkSupplier->company_name);
                    }
                }
            }

            $suppliers_name_string = 'N/A';
            $containers_numbers_string = 'N/A';
            $purchase_numbers_string = 'N/A';

            if ( count($suppliers_name)>0 ) {
                $suppliers_name_string = implode(',', $suppliers_name);
            }

            if ( count($container_numbers)>0 ) {
                $containers_numbers_string = implode(',', $container_numbers);
            }

            if ( count($po_numbers)>0 ) {
                $purchase_numbers_string = implode(',', $po_numbers);
            }


            $check_shipment->suppliers_group_bookings = $suppliers;

            $final_invoice = [
                'shipment_reference_number' => (isset($check_invoice->shipment->shifl_ref)) ? $check_invoice->shipment->shifl_ref : '',
                'invoice_number' => $check_invoice->invoice_num,
                'balance' => $check_invoice->balance,
                'total_amount' => $check_invoice->total_amount,
                'payment_method' => $check_invoice->paymentMethod(),
                'paid_on' => $check_invoice->paid_on? Carbon::parse($check_invoice->paid_on)->format('F d, Y') : null,
                'suppliers_name_string' => $suppliers_name_string,
                'bill_to' => $check_invoice->qbo_customer_name,
                'bill_to_address' => $check_invoice->qbo_bill_to_address,
                'date' => Carbon::parse($check_invoice->invoice_date)->format('F d, Y'),
                'due_date' => Carbon::parse($check_invoice->due_date)->format('F d, Y'),
                'mbl_number' => $check_invoice->shipment->mbl_num? $check_invoice->shipment->mbl_num : "N/A",
                'from' => (isset($selected_schedule)) ? $selected_schedule->location_from_name : 'N/A',
                'to' => (isset($selected_schedule)) ? $selected_schedule->location_to_name : 'N/A',
                'etd' => (isset($selected_schedule)) ? Carbon::parse($selected_schedule->etd)->format('m/d/Y') : '',
                'eta' => (isset($selected_schedule)) ? Carbon::parse($selected_schedule->eta)->format('m/d/Y') : '',
                'suppliers' => (isset($check_shipment->suppliers_group_bookings)) ? $check_shipment->suppliers_group_bookings : [],
                'purchase_orders' => $po_numbers,
                'purchase_numbers_string' => $purchase_numbers_string,
                'containers' => $container_numbers,
                'containers_name_string' => $containers_numbers_string,
                'schedule' => (isset($selected_schedule)) ? $selected_schedule : [],
                'payment_status' => $payment_status,
                'invoice_items' => $check_invoice_items,
            ];
        }
        //e

        $pdf = PDF::loadView('invoices.invoice', [
            'data' => $final_invoice]);
        return $pdf->download('invoice-'.$check_invoice->invoice_num.'.pdf');
        //return $pdf->stream();
    }


    /**
     * Display a listing of the resource.
     *
     * @authenticated
     *
     *
     * @apiResourceCollection App\Http\Resources\ScribeResources\InvoiceResource
     * @apiResourceModel App\Invoice paginate=5
     *
     * @response status=400 scenario="Unauthenticated" {
     *     "message": "Unauthenticated."
     * }
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request, $shipmentId = 0) {

        $get_authenticated_user = Auth::user();

        $customers = Auth::user()->customersApi->pluck('id');
        try{
            $customer = ( $get_authenticated_user->default_customer_id!==null ) ? $get_authenticated_user->default_customer_id : $customers[0];
        }catch (\Exception $exception){
            return response([
                'message'=>'No results found'
            ], 404);
        }
        $check_customer = Customer::findOrFail($customer);
        $check_qbo_customers = [$check_customer->qbo_customer];

        //$check_qbo_customers = [$customer[0]];
        //$check_qbo_customers = Auth::user()->customersApi->pluck('qbo_customer');
        $final_qbo_customers = $qbo_company_realmid = [];

        if (count($check_qbo_customers)>0) {
            foreach($check_qbo_customers as $check_qbo_customer) {

                if ($check_qbo_customer!==null && $check_qbo_customer!=='') {
                    $obj = json_decode($check_qbo_customer);
                    if (isset($obj->customer)) {
                        if (isset($obj->customer->Id)) {
                            array_push($final_qbo_customers, intval($obj->customer->Id));
                        }
                        if (isset($obj->realm_id)) {
                            array_push($qbo_company_realmid, $obj->realm_id);
                        }
                    }
                }
            }
        }

        $new_invoices = [];
        $invoices = Invoice::whereIn('qbo_customer_id', $final_qbo_customers)
            ->whereIn('qbo_company_realmid', $qbo_company_realmid)
            ->where('is_email_sent', 1);
        if ($shipmentId && $shipmentId > 0) {
            $invoices = $invoices->where('shipment_id', $shipmentId);
        }
        $invoices = $invoices->get();
        /*
        $invoices = Invoice::whereHas('shipment',function($q) use ($checkCustomersId){
            $q->whereHas('customer', function($qq) use ($checkCustomersId){
                $qq->whereIn('id', $checkCustomersId);
            });
        })->get();
        */
        if (count($invoices)>0) {

            foreach($invoices as $key => $invoice) {
                $payment_status = '';
                if ($invoice->balance==null) {
                    $payment_status = 'Not Paid';
                } else {
                    $balance = floatval($invoice->balance);
                    $check_balance = floatval($invoice->balance);
                    $total_amount = floatval($invoice->total_amount);
                    $payment_status = '';
                    if ($check_balance==0) {
                        $payment_status = 'Paid';
                    } else {
                        if ($check_balance!==$total_amount) {
                            $payment_status = 'Partially Paid';
                        } else {
                            if ($check_balance==$total_amount) {
                                $payment_status = 'Not Paid';
                            }
                        }
                    }
                }

                array_push($new_invoices, [
                    'shipment_reference_number' => (isset($invoice->shipment->shifl_ref)) ? $invoice->shipment->shifl_ref : '',
                    'id' => $invoice->id,
                    'invoice_number' => $invoice->invoice_num,
                    'invoice_date' => $invoice->invoice_date,
                    'payment_method' => $invoice->payment_method,
                    'paid_on' =>  $invoice->paid_on ? Carbon::parse($invoice->paid_on)->format('F d,Y') : null,
                    'due_date' => $invoice->due_date,
                    'total_amount' => $invoice->total_amount,
                    'company' => $invoice->shipment->customer->company_name,
                    'balance' => ($invoice->balance==null) ? $invoice->total_amount : $invoice->balance,
                    'payment_status' => $payment_status,
                    'is_duty_invoice' => $invoice->is_duty_invoice,
                    'total_duty_amount' => $invoice->total_duty_amount,
                    'processing_fee' => floatval(((3.2 / 100) * $invoice->total_duty_amount))
                ]);
            }
        }


        if (is_null($request->per_page)) {
            return StandardResource::collection((new Collection($new_invoices)));
        }
        if (is_numeric($request->per_page)) {
            return StandardResource::collection((new Collection($new_invoices))->paginate($request->per_page));
        }

        return abort(404);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @authenticated
     *
     * @urlParam id int required Invoice ID
     *
     * @apiResource 201 App\Http\Resources\ScribeResources\InvoiceResource
     * @apiResourceModel App\Invoice
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
        try{
            //
            $check_invoice = Invoice::find($id);
            $final_invoice = null;
            $check_shipment = $check_invoice->shipment;
            $selected_schedule = null;
            $po_numbers = [];
            $container_numbers = [];
            if (isset($check_shipment->schedules_group_bookings) && $check_shipment->schedules_group_bookings!==null && $check_shipment->schedules_group_bookings!=='') {
                $schedules = json_decode($check_shipment->schedules_group_bookings);

                if ( count($schedules)>0 ) {
                    foreach ($schedules as $key => $schedule) {
                        if (isset($schedule->is_confirmed) && $schedule->is_confirmed==1) {
                            $selected_schedule = $schedule;
                        }
                    }

                    if ($selected_schedule!==null) {
                        $selected_schedule->location_from_name = TerminalRegion::findOrFail($selected_schedule->location_from)->name;
                        $selected_schedule->location_to_name = TerminalRegion::findOrFail($selected_schedule->location_to)->name;
                    }
                }
            }

            if (isset($check_shipment->suppliers_group_bookings) && $check_shipment->suppliers_group_bookings!==null && $check_shipment->suppliers_group_bookings!=='') {
                $suppliers = json_decode($check_shipment->suppliers_group_bookings);

                if (count($suppliers)>0) {

                    foreach($suppliers as $key => $supplier) {
                        $has_po = false;
                        $suppliers[$key]->po_name = '';
                        if ( isset($supplier->po_id) ) {
                            $po = PurchaseOrder::find(intval($supplier->po_id));
                            if (isset($po->name)) {
                                array_push($po_numbers,$po->name);
                                $suppliers[$key]->po_name = $po->name;
                            }
                        }
                    }
                }
            }

            if (isset($check_shipment->containers_group_bookings) && $check_shipment->containers_group_bookings!==null && $check_shipment->containers_group_bookings!=='') {
                $containers = json_decode($check_shipment->containers_group_bookings);

                if (count($containers)>0) {
                    foreach($containers as $key => $container) {
                        array_push($container_numbers,$container->container_num);
                    }
                }
            }

            if ( isset($check_invoice->id) ) {

                $balance = floatval($check_invoice->balance);
                $check_balance = floatval($check_invoice->balance);
                $total_amount = floatval($check_invoice->total_amount);
                $payment_status = '';
                if ($check_balance==0) {
                    $payment_status = 'Paid';
                } else {
                    if ($check_balance!==$total_amount) {
                        $payment_status = 'Partially Paid';
                    } else {
                        if ($check_balance==$total_amount) {
                            $payment_status = 'Not Paid';
                        }
                    }
                }
                $suppliers = (isset($check_shipment->suppliers_group_bookings)) ? json_decode($check_shipment->suppliers_group_bookings) : [];


                if (isset($suppliers) && count($suppliers)>0) {
                    foreach($suppliers as $key => $supplier) {
                        $checkSupplier = Supplier::find($supplier->supplier_id);
                        $suppliers[$key]->name = '';
                        $suppliers[$key]->address = '';
                        if (isset($checkSupplier->id)) {
                            $suppliers[$key]->name = $checkSupplier->company_name;
                            $suppliers[$key]->address = $checkSupplier->address;
                        }
                    }
                }
                $check_shipment->suppliers_group_bookings = $suppliers;
                $check_invoice_items = InvoiceService::where('invoice_id', $id)->get();
                $final_invoice = [
                    'shipment_reference_number' => (isset($check_invoice->shipment->shifl_ref)) ? $check_invoice->shipment->shifl_ref : '',
                    'invoice_items' => $check_invoice_items,
                    'invoice_number' => $check_invoice->invoice_num,
                    'balance' => $check_invoice->balance,
                    'total_amount' => $check_invoice->total_amount,
                    'bill_to' => $check_invoice->qbo_customer_name,
                    'bill_to_address' => $check_invoice->qbo_bill_to_address,
                    'date' => Carbon::parse($check_invoice->invoice_date)->format('F d, Y'),
                    'due_date' => Carbon::parse($check_invoice->due_date)->format('F d, Y'),
                    'payment_method' => $check_invoice->paymentMethod(),
                    'paid_on' => $check_invoice->paid_on ? Carbon::parse($check_invoice->paid_on)->format('F d,Y') : null,
                    'mbl_number' => $check_invoice->shipment->mbl_num,
                    'from' => (isset($selected_schedule)) ? $selected_schedule->location_from_name : '',
                    'to' => (isset($selected_schedule)) ? $selected_schedule->location_to_name : '',
                    'etd' => (isset($selected_schedule)) ? $selected_schedule->etd : '',
                    'eta' => (isset($selected_schedule)) ? $selected_schedule->eta : '',
                    'suppliers' => (isset($check_shipment->suppliers_group_bookings)) ? $check_shipment->suppliers_group_bookings : [],
                    'purchase_orders' => $po_numbers,
                    'containers' => $container_numbers,
                    'schedule' => (isset($selected_schedule)) ? $selected_schedule : [],
                    'payment_status' => $payment_status,
                    'is_duty_invoice' => $check_invoice->is_duty_invoice,
                    'total_duty_amount' => $check_invoice->total_duty_amount,
                    'processing_fee' => floatval(((3.2 / 100) * $check_invoice->total_duty_amount))
                ];
            }

            return response()->json($final_invoice);
        }catch (\Exception $exception){
            return response ([
                'message'=>'No results found'
            ], 500);
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
