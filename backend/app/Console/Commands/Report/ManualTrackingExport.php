<?php

namespace App\Console\Commands\Report;

use Maatwebsite\Excel\Concerns\WithMultipleSheets;
use Tanvirofficials\UntrackedShipments\Http\Controllers\ShipmentController;
use Illuminate\Http\Request;

class ManualTrackingExport implements WithMultipleSheets
{
    protected $shipmentControllerObject;
    protected $fakeRequest;

    public function __construct()
    {
        $this->shipmentControllerObject = new ShipmentController();
        $this->fakeRequest = new Request();
        $this->fakeRequest->sortby = 'eta';
        $this->fakeRequest->sorttype = 'desc';
    }


    public function sheets(): array
    {
        $shipments = $this->shipmentControllerObject->index($this->fakeRequest);

        $internal_shipments = [];
        $external_shipments = [];
        foreach ($shipments as $key => $shipment) {
            // code...
            if($shipment->is_tracking_shipment){
                $external_shipments[] = $shipment;
            }else{
                $internal_shipments[] = $shipment;
            }
        }

        return [
            "All" => new ManualTrackingSheet($shipments,"All"),
            "Internal" => new ManualTrackingSheet(empty($shipments) ? [] : $internal_shipments,"Internal"),
            "External" => new ManualTrackingSheet(empty($shipments) ? [] : $external_shipments,"External"),
        ];
    }
}
