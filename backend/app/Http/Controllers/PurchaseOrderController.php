<?php

namespace App\Http\Controllers;

use App\PurchaseOrder;
use Illuminate\Http\Request;

class PurchaseOrderController extends Controller
{

    public function whereIn(Request $request) {
        $response = ['status' => 'ok','results' => []];
        $purchaseOrders = [];

        if ($request->has('ids')) {
            $ids = json_decode($request->input('ids'));
            $purchaseOrders = PurchaseOrder::select('id', 'po_num')
                                 ->whereIn('id', $ids)
                                 ->get();
        }
        
        if (count($purchaseOrders)>0) {
            foreach($purchaseOrders as $key => $purchaseOrder) {
                $purchaseOrders[$key]->unique_id = intval($purchaseOrder->unique_id);
            }
            
        }

        $response['results'] = $purchaseOrders;
        return response()->json($response);
    }


    public function getAll(Request $request)
    {

        $response = ['status' => 'ok','results' => []];
        $purchaseOrders = PurchaseOrder::all();

        if (count($purchaseOrders)>0) {
            $response['results'] = $purchaseOrders;
        }

        return response()->json($response);
    }

    public function getById($id){
        $response = ['status' => 'ok','results' => []];
        $purchaseOrder = PurchaseOrder::where('id','=',$id)->with('customer')->get();

        if (count($purchaseOrder)>0) {
            $response['results'] = $purchaseOrder;
        }

        return response()->json($response);
    }

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
     * @param  \App\PurchaseOrder  $purchaseOrder
     * @return \Illuminate\Http\Response
     */
    public function show(PurchaseOrder $purchaseOrder)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\PurchaseOrder  $purchaseOrder
     * @return \Illuminate\Http\Response
     */
    public function edit(PurchaseOrder $purchaseOrder)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\PurchaseOrder  $purchaseOrder
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, PurchaseOrder $purchaseOrder)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\PurchaseOrder  $purchaseOrder
     * @return \Illuminate\Http\Response
     */
    public function destroy(PurchaseOrder $purchaseOrder)
    {
        //
    }
}
