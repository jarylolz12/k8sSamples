<?php

namespace App\Http\Controllers;

use App\Buyer;
use App\Customer;
use App\CustomerBuyer;
use App\CustomerSupplier;
use Illuminate\Http\Request;
use App\Supplier;

class SupplierController extends Controller
{
    //

    public function whereIn(Request $request)
    {
        $response = ['status' => 'ok', 'results' => []];
        $suppliers = [];


        if ($request->has('ids')) {
            $ids = json_decode($request->input('ids'));
            $suppliers = Supplier::select('id', 'company_name')
                ->whereIn('id', $ids)
                ->get();


            if ( count ($suppliers) > 0 ) {
                foreach ( $suppliers as $key => $supplier ) {
                    if ( isset($supplier->documents)) {
                        $suppliers[$key]->documents = $supplier->documents;
                    } else {
                        $suppliers[$key]->documents = [];
                    }
                }
            }

        }

        if (count($suppliers) > 0) {
            $response['results'] = $suppliers;
        }

        return response()->json($response);
    }

    public function whereInCustom(Request $request)
    {
        $response = ['status' => 'ok', 'results' => []];
        $suppliers = [];

        $shipment_id = $request->has('shipment_id') ? $request->input('shipment_id') : 0;
        $shipment_id = ($shipment_id == 0 || $shipment_id == null ) ? 0 : $shipment_id;

        if ($request->has('ids')) {
            $ids = json_decode($request->input('ids'));

            $suppliers = Supplier::select('id', 'company_name')
                ->whereIn('id', $ids)
                ->get();
            if ( count ($suppliers) > 0 ) {
                $newSuppliers = [];
                foreach ( $suppliers as $key => $supplier ) {
                    $get_documents = $supplier->documents;
                    $new_documents = [];
                    $check_document_names = [];
                    $check_document_ids = [];
                    if ( isset($supplier->documents)) {
                        if ( is_countable($get_documents)) {
                            foreach($get_documents as $get_document) {
                                if ( $shipment_id !== 0 ) {
                                    if ( $shipment_id == $get_document->shipment_id){

                                        if ( !in_array($get_document->id, $check_document_ids)) {
                                            array_push($new_documents, $get_document);
                                            array_push($check_document_ids, $get_document->id);
                                        }

                                        /*
                                        if ( !in_array($get_document->name, $check_document_names)) {
                                            array_push($new_documents, $get_document);
                                            array_push($check_document_names, $get_document->name);
                                        }*/
                                    }
                                } else {
                                    array_push($new_documents, $get_document);
                                }
                            }
                        }
                    }
                    $suppliers[$key]->new_documents = $new_documents;
                    $suppliers[$key]->documents = $get_documents;
                }
            }

        }

        if (count($suppliers) > 0) {
            $response['results'] = $suppliers;
            $response['shipment_id'] = $shipment_id;
        }

        return response()->json($response);
    }

    public function getAll(Request $request)
    {

        $response = ['status' => 'ok', 'results' => []];
        $suppliers = Supplier::all();

        if (count($suppliers) > 0) {
            $response['results'] = $suppliers;
        }

        return response()->json($response);
    }

    public function findById(Request $request)
    {

        $response = ['status' => 'not ok', 'result' => null];

        $findSupplier = Supplier::find($request->input('id'));

        if (isset($findSupplier->id)) {
            $response['status'] = 'ok';
            $response['result'] = $findSupplier;
        }

        return response()->json($response);
    }

    public function getSupplier(Supplier $supplier_id)
    {
        $supplier = $supplier_id;
        return response()->json($supplier);
    }

    public function connect(Request $request)
    {
        $supplierId = $request->supplierId;
        $customerId = $request->customerId;
        $selectedCustomerId = $request->selectedCustomerId;

        $customer = Customer::find($customerId);

        $buyer = new Buyer();
        $buyer->company_name = $customer->company_name;
        $buyer->address = $customer->address;
        $buyer->phone = $customer->phone;
        $buyer->emails = json_encode($customer->emails);
        $buyer->admin_user_id = null;
        $buyer->connected_customer = $customerId;
        $buyer->connection_generated = 1;
        if ($buyer->save()) {
            Supplier::where('id', $supplierId)->update(['connected_customer' => $selectedCustomerId]);

            CustomerBuyer::updateOrCreate(
                [
                    'customer_id' => $selectedCustomerId,
                    'buyer_id' => $buyer->id
                ],
                [
                    'customer_id' => $selectedCustomerId,
                    'buyer_id' => $buyer->id
                ]
            );

            return $selectedCustomerId;
        }
    }

    public function disconnect(Request $request)
    {
        $id = $request->id;
        $supplier = Supplier::where('id', $id)->where('connection_generated', 0)->first();
        $buyerId = $request->buyerId;
        $customerId = $request->customerId;
        $currentCustomerId = $request->currentCustomerId;
        if (!empty($supplier)) {
            Buyer::where('id', $buyerId)->where('connection_generated', 1)->delete();
            CustomerBuyer::where('customer_id', $customerId)->where('buyer_id', $buyerId)->delete();
            Supplier::where('id', $id)->update(['connected_customer' => 0]);
        } else {
            Supplier::where('id', $id)->delete();
            CustomerSupplier::where('customer_id', $currentCustomerId)->where('supplier_id', $id)->delete();
            Buyer::where('id', $buyerId)->update(['connected_customer' => 0]);
        }
    }

    public function getConnectedCustomer($id, $type, $connectedId)
    {
        if ($type == 'buyer') {
            $returnResponse = Buyer::where('connected_customer', $id)->pluck('id');
            $connectedCustomer = Supplier::where('id', $connectedId)->pluck('connected_customer');
            $returnValue = ['ids' => $returnResponse, 'connected_customer' => $connectedCustomer];
        } else {
            $returnResponse = Supplier::where('connected_customer', $id)->pluck('id');
            $connectedCustomer = Buyer::where('id', $connectedId)->pluck('connected_customer');
            $returnValue = ['ids' => $returnResponse, 'connected_customer' => $connectedCustomer];

        }
        return response()->json(["results" => $returnValue]);
    }
}
