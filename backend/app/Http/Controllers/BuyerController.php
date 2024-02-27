<?php

namespace App\Http\Controllers;

use App\Buyer;
use App\Customer;
use App\CustomerBuyer;
use App\Supplier;
use Illuminate\Http\Request;
use App\CustomerSupplier;

class BuyerController extends Controller
{
    public function connect(Request $request)
    {
        $buyerId = $request->buyerId;
        $customerId = $request->customerId;
        $selectedCustomerId = $request->selectedCustomerId;

        $customer = Customer::find($customerId);

        $supplier = new Supplier();
        $supplier->company_name = $customer->company_name;
        $supplier->address = $customer->address;
        $supplier->phone = $customer->phone;
        $supplier->emails = json_encode($customer->emails);
        $supplier->admin_user_id = null;
        $supplier->connected_customer = $customerId;
        $supplier->connection_generated = 1;
        if ($supplier->save()) {
            Buyer::where('id', $buyerId)->update(['connected_customer' => $selectedCustomerId]);

            CustomerSupplier::updateOrCreate(
                [
                    'customer_id' => $selectedCustomerId,
                    'supplier_id' => $supplier->id
                ],
                [
                    'customer_id' => $selectedCustomerId,
                    'supplier_id' => $supplier->id
                ]
            );

            return $selectedCustomerId;
        }
    }

    public function disconnect(Request $request)
    {
        $id = $request->id;
        $supplier = Buyer::where('id', $id)->where('connection_generated', 0)->first();
        $supplierId = $request->supplierId;
        $customerId = $request->customerId;
        $currentCustomerId = $request->currentCustomerId;

        if (!empty($supplier)) {
            Supplier::where('id', $supplierId)->where('connection_generated', 1)->delete();
            CustomerSupplier::where('customer_id', $customerId)->where('supplier_id', $supplierId)->delete();
            Buyer::where('id', $id)->update(['connected_customer' => 0]);
        } else {
            Buyer::where('id', $id)->delete();
            CustomerBuyer::where('customer_id', $currentCustomerId)->where('buyer_id', $id)->delete();
            Supplier::where('id', $supplierId)->update(['connected_customer' => 0]);
        }

    }

    public function getBuyer(Customer $customer_id)
    {
        $suppliers = $customer_id->buyer;

        $customer_id["buyer"] = $suppliers;

        return response()->json($customer_id);
    }

    public function whereIn(Request $request)
    {
        $response = ['status' => 'ok', 'results' => []];
        $buyers = [];

        if ($request->has('ids')) {
            $ids = json_decode($request->input('ids'));
            $buyers = Buyer::select('id', 'company_name')
                ->whereIn('id', $ids)
                ->get();


//            if (count($buyers) > 0) {
//                foreach ($buyers as $key => $buyer) {
//                    if ( isset($supplier->documents)) {
//                        $buyers[$key]->documents = $buyer->documents;
//                    }else {
//                        $buyers[$key]->documents = [];
//                    }
//                }
//            }

        }

        if (count($buyers) > 0) {
            $response['results'] = $buyers;
        }

        return response()->json($response);
    }

    public function getCustomerBuyer(Buyer $customer_id)
    {
        $suppliers = $customer_id->buyer;

        $customer_id["buyer"] = $suppliers;

        return response()->json($customer_id);
    }
}
