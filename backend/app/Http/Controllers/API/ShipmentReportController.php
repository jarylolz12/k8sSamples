<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\CustomerAdmin;
use App\Customer;
use App\Shipment;
use App\EmailReportSchedule;
use Illuminate\Support\Facades\Auth;

class ShipmentReportController extends Controller
{
    
    public function getShipmentReport($id)
    {

        //COMMMENT OUT THIS IS ONLY FOR TESTING
        
        // $emailSchedules = EmailReportSchedule::with('CustomerAdmin')->where('active', true)
        //         ->where('customer_admin_id', $id)->first();
        
        // $customerAdmin = $emailSchedules->CustomerAdmin;
        
        // $customers = $customerAdmin->customersApi;
        
        // $fileName = 'shipment_report_' . str_replace(" ", "_", $customerAdmin->name) . '.xlsx';

        // $shipments = $this->shipment($customers)->sortBy('eta', SORT_REGULAR, false);

        // \Excel::store(new \App\Custom\ReportExport($shipments), 'reports/excel/'.$fileName, 'public');

        // return response()->json([
        //     "message" => "Shipment Report",
        //     "shipments" => $shipments
        // ]);
       
    }


    private function shipment($customers)
    {

        $shipments = collect();

        foreach($customers as $customer){
            $shipments = $shipments->merge($customer->shipments);
        }

        return $shipments->filter->isValidForReport()->values();
        
    }
}
