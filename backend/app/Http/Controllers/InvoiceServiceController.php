<?php

namespace App\Http\Controllers;

use App\InvoiceService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class InvoiceServiceController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
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
     * @param  \App\InvoiceService  $invoiceService
     * @return \Illuminate\Http\Response
     */
    public function show(InvoiceService $invoiceService)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\InvoiceService  $invoiceService
     * @return \Illuminate\Http\Response
     */
    public function edit(InvoiceService $invoiceService)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\InvoiceService  $invoiceService
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, InvoiceService $invoiceService)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\InvoiceService  $invoiceService
     * @return \Illuminate\Http\Response
     */
    public function destroy(InvoiceService $invoiceService)
    {
        //
    }

    public function getByInvoice($id)
    {

        $response = ['status' => 'ok','results' => []];

        $invoiceServices = DB::select('select * from invoice_services where invoice_id = ?', [$id]);

       /*  echo "<pre>";
        dd($invoiceServices);
        echo "</pre>"; */

        if (count($invoiceServices)>0) {
            $response['results'] = $invoiceServices;
        }

        return response()->json($response);
    }
}
