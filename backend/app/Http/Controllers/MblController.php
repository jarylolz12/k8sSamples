<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use PDF;
use App\Shipment;
use App\Container;
use App\TerminalRegion;
use App\ContainerSize;

class MblController extends Controller
{
    public function index($shipment_id){
        $content = $this->getContent($shipment_id);
        //return $content;
        return PDF::loadView('pdf.mbl',['content'=>$content])->setPaper(array(0,0,595,810))->setOptions(['dpi' => 72, 'isRemoteEnabled' => true])->download('MBL_'.$content->mbl_num.'.pdf');
    }

    private function getContent($shipment_id){ 
        $shipment = Shipment::findOrFail($shipment_id);
        
        $total_cartons = 0;    
        $total_kg = 0;    
        $total_cbm = 0;
        $containersData = [];
        //$shipmentContainers = Container::where('shipment_id',$shipment_id)->with(['container_size'])->get();
        $shipmentContainers2 = json_decode($shipment->containers_group_bookings);
        if($shipmentContainers2){
            foreach($shipmentContainers2 as $container){
                $container_size = '';
                if(isset($container->size) && $container->size !== ''){
                    $container_size = ContainerSize::where('id',$container->size)->select('name')->get();
                }
                if(isset($container->cartons)){
                    $total_cartons = $total_cartons + floatval(str_replace(',','', $container->cartons));
                }
                if(isset($container->kg)){
                    $total_kg = $total_kg + floatval(str_replace(',','', $container->kg));
                }
                if(isset($container->cbm)){
                    $total_cbm = $total_cbm + floatval(str_replace(',','', $container->cbm));
                }
                $setContainerData = [
                    'container_num' => isset($container->container_num) ? $container->container_num : '',
                    'size' => isset($container_size[0]->name) ? $container_size[0]->name : '',
                    'cartons' => isset($container->cartons) ? $container->cartons : '',
                    'kg' => isset($container->kg) ? $container->kg : '',
                    'cbm' => isset($container->cbm) ? $container->cbm : '',
                    'seal_num' => isset($container->seal_num) ? $container->seal_num : ''
                ];
                array_push($containersData,$setContainerData);
            }
        }
        // $total_cartons = collect($shipmentContainers2)->sum('cartons');    
        // $total_kg = collect($shipmentContainers2)->sum('kg');    
        // $total_cbm = collect($shipmentContainers2)->sum('cbm');        

        $scheduleBooking = json_decode($shipment->schedules_group_bookings);
        $supplierBooking = json_decode($shipment->suppliers_group_bookings);
        
        $po_nums = [];
        if($supplierBooking){
            $po_nums = collect($supplierBooking)->pluck('po_num');
        }

        //return $po_nums;
        if ($shipment) {
            return (object) [
                'shifl_ref' => isset($shipment->shifl_ref) ? $shipment->shifl_ref : '',
                'mbl_num' => isset($shipment->mbl_num) ? $shipment->mbl_num : '',
                'mbl_shipper' => isset($shipment->mbl_shipper) ? $shipment->mbl_shipper : '',
                'mbl_shipper_address' => $shipment->mbl_shipper_address,
                'mbl_shipper_phone_number' => $shipment->mbl_shipper_phone_number,
                'mbl_consignee' => $shipment->mbl_consignee,
                'mbl_consignee_address' => $shipment->mbl_consignee_address,
                'mbl_consignee_phone_number' => $shipment->mbl_consignee_phone_number,
                'mbl_notify' => $shipment->mbl_notify,
                'mbl_notify_address' => $shipment->mbl_notify_address,
                'mbl_notify_phone_number' => $shipment->mbl_notify_phone_number,
                'mbl_description' => $shipment->mbl_description,
                'mbl_marks' => $shipment->mbl_marks,
                'po_numbers' => $po_nums,
                'vessel' => isset($shipment->vessel) && $shipment->vessel !=='' ? $shipment->vessel : '',
                'location_from' => isset($scheduleBooking[0]->location_from) && $scheduleBooking[0]->location_from !=='' ? TerminalRegion::findOrFail($scheduleBooking[0]->location_from)->name : '',
                'location_to' => isset($scheduleBooking[0]->location_to) && $scheduleBooking[0]->location_to !=='' ? TerminalRegion::findOrFail($scheduleBooking[0]->location_to)->name : '',
                'total_cartons' => $total_cartons,
                'total_kg' => $total_kg,
                'total_cbm' => $total_cbm,
                'type' => isset($shipment->type) && $shipment->type !=='' ? $shipment->type : '',
                'etd' => isset($scheduleBooking[0]->etd) ? $scheduleBooking[0]->etd : '',
                'containers' => $containersData,
            ];
        }
    }
}
