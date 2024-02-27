<?php

namespace App\Http\Controllers;

use App\Customer;
use Illuminate\Http\Request;

class AddCustomersSuppliersRelationship extends Controller
{
    //
    public function setCustomerSuppliers()
    {
        $customers = Customer::all();
        if (count($customers) > 0) {
            foreach ($customers as $customer) {
                $newSuppliersIds = [];
                $customerSuppliers = $customer->suppliers;
                $customerSuppliersIds = [];
                if (count($customerSuppliers) > 0) {
                    foreach ($customerSuppliers as $customerSupplier) {
                        array_push($customerSuppliersIds, $customerSupplier->id);
                    }
                }
                if (count($customer->shipments) > 0) {
                    foreach ($customer->shipments as $shipment) {
                        $supplierGroupBookings = $shipment->suppliers_group_bookings;
                        if ($supplierGroupBookings) {
                            $supplierGroupBookings = json_decode($supplierGroupBookings);
                            foreach ($supplierGroupBookings as $supplierGroupBooking) {
                                if (isset($supplierGroupBooking->supplier_id)) {
                                    if (!in_array($supplierGroupBooking->supplier_id, $customerSuppliersIds) && !in_array($supplierGroupBooking->supplier_id, $newSuppliersIds)) {
                                        array_push($newSuppliersIds, $supplierGroupBooking->supplier_id);
                                    }
                                }
                            }
                        }
                    }
                }
                $customer->suppliers()->attach(
                    $newSuppliersIds
                );
            }
        }

        return view('scripts_page.customer_supplier', ["message" => "Success"]);
    }

    public function getCustomerSuppliersPage()
    {
        return view('scripts_page.customer_supplier');
    }
}
