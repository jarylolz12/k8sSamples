<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use PDF;
use App\Shipment;
use App\Supplier;
use App\TerminalRegion;
use App\Container;
use App\ImportNames;

class BolController extends Controller
{
    
    public function index($shipment_id, $supplier_id)
    {
        // return PDF::loadView('pdf.bl', ['content' => $content])->setPaper(array(0,0,630,800))->setOptions(['dpi' => 72, 'isRemoteEnabled' => true]);
        $content = $this->getContent($shipment_id, $supplier_id);
        return PDF::loadView('pdf.bol', ['content' => $content])->setPaper(array(0,0,595,810))->setOptions(['dpi' => 72, 'isRemoteEnabled' => true])->download('BOL_'.$content->hbl_num.'.pdf');
        //return PDF::loadView('pdf.bol', ['content' => $content])->setPaper(array(0,0,595,810))->setOptions(['dpi' => 72, 'isRemoteEnabled' => true])->stream('BOL_'.$content->hbl_num.'.pdf');

    }


    private function mergeWithoutDuplicates($array_first, $array_second, $keyCompare, &$reference) {
        if(count($array_first) > 0) {
            foreach($array_first as $key => $a) {
                $hasSimilarity = false;
                if (count($array_second) > 0) {
                    foreach($array_second as $b) {
                        if (isset($b->{$keyCompare}) && isset($a->{$keyCompare})) {
                            if ($b->{$keyCompare}==$a->{$keyCompare}) {
                                $hasSimilarity = true;
                            }
                        }
                    }
                }

                if (!$hasSimilarity)
                    array_push($reference, $a);
            }

        }
    }


    private function getContent($shipment_id, $supplier_id)
    {
        $shipment = Shipment::findOrFail($shipment_id);
        if ($shipment) {
            $supplier_position= 0;

            $mergeSuppliers = (is_array(json_decode($shipment->suppliers_group))) ? json_decode($shipment->suppliers_group) : [];

            $mergeToSupplierBookings = (is_array(json_decode($shipment->suppliers_group_bookings))) ? json_decode($shipment->suppliers_group_bookings) : [];

            $newSuppliers = [];

            foreach ($mergeSuppliers as $key => $value) {
                array_push($newSuppliers, $value->id);
            }

            foreach ($mergeToSupplierBookings as $key => $value) {
                if (!in_array($value->id, $newSuppliers)) {
                    array_push($newSuppliers, $value->id);
                }
            }
            
            $newFinalSuppliers = [];

            foreach($newSuppliers as $n) {

                $setId = null;
                foreach ($mergeSuppliers as $key => $value) {
                   if ($n==$value->id) {
                        array_push($newFinalSuppliers, $value);
                        $setId = $value->id;
                   }
                }

                foreach ($mergeToSupplierBookings as $key => $value) {
                    if ($n==$value->id && $setId!=$value->id) {
                        array_push($newFinalSuppliers, $value);
                   }
                }    
            }

            if (count($newFinalSuppliers)>0) {
                foreach ($newFinalSuppliers as $key => $value) {
                    if(!isset($value->containers)) {
                        $newFinalSuppliers[$key]->containers = [];
                    }
                }
            }

            $newSuppliers = $newFinalSuppliers;
            foreach ($newSuppliers as $supplier) {

                if ($supplier->id == $supplier_id) {
                    return $this->prepareContent($shipment, $supplier, $supplier_position);
                }
                $supplier_position++;
            }
        }
    }

    private function convertNumberToWord($num = false) {
        $num = str_replace(array(',', ' '), '' , trim($num));
        if(! $num) {
            return false;
        }
        $num = (int) $num;
        $words = array();
        $list1 = array('', 'one', 'two', 'three', 'four', 'five', 'six', 'seven', 'eight', 'nine', 'ten', 'eleven',
            'twelve', 'thirteen', 'fourteen', 'fifteen', 'sixteen', 'seventeen', 'eighteen', 'nineteen'
        );
        $list2 = array('', 'ten', 'twenty', 'thirty', 'forty', 'fifty', 'sixty', 'seventy', 'eighty', 'ninety', 'hundred');
        $list3 = array('', 'thousand', 'million', 'billion', 'trillion', 'quadrillion', 'quintillion', 'sextillion', 'septillion',
            'octillion', 'nonillion', 'decillion', 'undecillion', 'duodecillion', 'tredecillion', 'quattuordecillion',
            'quindecillion', 'sexdecillion', 'septendecillion', 'octodecillion', 'novemdecillion', 'vigintillion'
        );
        $num_length = strlen($num);
        $levels = (int) (($num_length + 2) / 3);
        $max_length = $levels * 3;
        $num = substr('00' . $num, -$max_length);
        $num_levels = str_split($num, 3);
        for ($i = 0; $i < count($num_levels); $i++) {
            $levels--;
            $hundreds = (int) ($num_levels[$i] / 100);
            $hundreds = ($hundreds ? ' ' . $list1[$hundreds] . ' hundred' . ' ' : '');
            $tens = (int) ($num_levels[$i] % 100);
            $singles = '';
            if ( $tens < 20 ) {
                $tens = ($tens ? ' ' . $list1[$tens] . ' ' : '' );
            } else {
                $tens = (int)($tens / 10);
                $tens = ' ' . $list2[$tens] . ' ';
                $singles = (int) ($num_levels[$i] % 10);
                $singles = ' ' . $list1[$singles] . ' ';
            }
            $words[] = $hundreds . $tens . $singles . ( ( $levels && ( int ) ( $num_levels[$i] ) ) ? ' ' . $list3[$levels] . ' ' : '' );
        } //end for loop
        $commas = count($words);
        if ($commas > 1) {
            $commas = $commas - 1;
        }
        return implode(' ', $words);
    }

    private function prepareContent($shipment, $supplier, $supplier_position)
    {
        $alphabet_array = ['A','B','C','D','E','F','G','H','I','J','K','L','M','N','O','P','Q','R','S'];

        //get schedules group bookings
        $mergeSchedules = (is_array(json_decode($shipment->schedules_group))) ? json_decode($shipment->suppliers_group) : [];

        $mergeToScheduleBookings = (is_array(json_decode($shipment->schedules_group_bookings))) ? json_decode($shipment->schedules_group_bookings) : [];

        //ss
        $newSchedules = [];

        foreach ($mergeSchedules as $key => $value) {
            array_push($newSchedules, $value->id);
        }

        foreach ($mergeToScheduleBookings as $key => $value) {
            if (!in_array($value->id, $newSchedules)) {
                array_push($newSchedules, $value->id);
            }
        }
        
        $newFinalSchedules = [];
        
        foreach($newSchedules as $n) {

            $setId = null;
            foreach ($mergeSchedules as $key => $value) {
               if ($n==$value->id) {
                    array_push($newFinalSchedules, $value);
                    $setId = $value->id;
               }
            }

            foreach ($mergeToScheduleBookings as $key => $value) {
                if ($n==$value->id && $setId!=$value->id) {
                    array_push($newFinalSchedules, $value);
               }
            }
        }

        $containerData = [];
        $setContainerData = [];
        if($supplier){
            $supplierContainers = $supplier->containers;
            if (count($supplierContainers) > 0){
                foreach($supplierContainers as $container){
                    //fetch container info
                    $getShipmentContainer = Container::where('unique_id',$container->container_id)->with(['container_size'])->get();
                    //set container data
                    if (count($getShipmentContainer) > 0){
                        $setContainerData = [
                            'container_num' => $getShipmentContainer[0]->container_num,
                            'container_size' => $getShipmentContainer[0]->container_size->name,
                            'cartons' => $container->cartons,
                            'weight' => $container->weight,
                            'cbm' => $container->size,
                            'seal_num' => (isset($getShipmentContainer[0]->seal_num)) ? $getShipmentContainer[0]->seal_num : 'NA'
                        ];
                        array_push($containerData,$setContainerData);
                    }
                }
            }  
        }

        $newSchedules = $newFinalSchedules;
        $newSchedules = json_decode(json_encode($newSchedules), true);
        $selectedSchedule = null;
        if ( count($newSchedules)>0 ) {
            $isNewSchedule = false;
            foreach( $newSchedules as $newSchedule ) {
                if (isset($newSchedule['is_confirmed']) && $newSchedule['is_confirmed']==1) {
                    $selectedSchedule = $newSchedule; 
                }
            }
            if ( $selectedSchedule==null)
                $selectedSchedule = $newSchedules[0];
        }
        $schedule = $selectedSchedule;
        $scheduleBooking = json_decode($shipment->schedules_group_bookings);

        // $mergeContainers = (is_array(json_decode($shipment->containers_group))) ? json_decode($shipment->containers_group) : [];
        // $mergeContainersBookings = (is_array(json_decode($shipment->containers_group_bookings))) ? json_decode($shipment->containers_group_bookings) : [];
        
        // $newContainers = [];
        // foreach ($mergeContainers as $key => $value) {
        //     array_push($newContainers, $value->id);
        // }

        // foreach ($mergeContainersBookings as $key => $value) {
        //     if (!in_array($value->id, $newContainers)) {
        //         array_push($newContainers, $value->id);
        //     }
        // }

        // $newFinalContainers = [];
        // foreach($newContainers as $n) {

        //     $setContainerId = null;
        //     foreach ($mergeContainers as $key => $value) {
        //        if ($n==$value->id) {
        //             array_push($newFinalContainers, $value);
        //             $setContainerId = $value->id;
        //        }
        //     }

        //     foreach ($mergeContainersBookings as $key => $value) {
        //         if ($n==$value->id && $setContainerId!=$value->id) {
        //             array_push($newFinalContainers, $value);
        //        }
        //     }
        // }
        
        
        // $containerData = [];
        // if($newFinalContainers){
        //     foreach($newFinalContainers as $container){
        //         //fetch container info
        //         $getShipmentContainer = Container::where('unique_id',$container->id)->with(['container_size'])->get();
        //         //set container data
        //         $setContainerData = [
        //             'container_num' => $getShipmentContainer[0]->container_num,
        //             'container_size' => $getShipmentContainer[0]->container_size->name,
        //             'cartons' => $container->cartons,
        //             'weight' => $container->kg,
        //             'cbm' => $container->cbm,
        //             'seal_num' => $getShipmentContainer[0]->seal_num
        //         ];
        //         array_push($containerData,$setContainerData);
        //     }
        // }
        return (object)([
            'shifl_ref' => isset($shipment->shifl_ref) && $shipment->shifl_ref !=='' ? $shipment->shifl_ref : '',
            'supplier_name' => isset($supplier->supplier_id) && $supplier->supplier_id !=='' ? Supplier::findOrFail($supplier->supplier_id)->company_name : '',
            'hbl_num' => isset($shipment->shifl_ref) && $shipment->shifl_ref !=='' ? 'SQFN'.$shipment->shifl_ref.$alphabet_array[$supplier_position] : 'SQFN',
            'mbl_num' => isset($shipment->mbl_num) && $shipment->mbl_num !=='' ? $shipment->mbl_num : '',
            'customer_name' => $shipment->getCustomerImportName(),
            // 'customer_name' => isset($shipment->customer->company_name) &&  $shipment->customer->company_name !=='' ?  $shipment->customer->company_name : '',
            'vessel' => isset($shipment->vessel) && $shipment->vessel !=='' ? $shipment->vessel : '',
            'location_from' => isset($scheduleBooking[0]->location_from) && $scheduleBooking[0]->location_from !=='' ? TerminalRegion::findOrFail($scheduleBooking[0]->location_from)->name : '',
            'location_to' => isset($scheduleBooking[0]->location_to) && $scheduleBooking[0]->location_to !=='' ? TerminalRegion::findOrFail($scheduleBooking[0]->location_to)->name : '',
            //'location_from' => isset($schedule['location_from']) ? TerminalRegion::findOrFail($schedule['location_from'])->name : '',
            //'location_to' => isset($schedule['location_to']) ? TerminalRegion::findOrFail($schedule['location_to'])->name : '',
            'total_cartons' => isset($supplier->total_cartons) && $supplier->total_cartons !== '' ? $supplier->total_cartons : '',
            'total_cartons_english' => isset($supplier->total_cartons) && $supplier->total_cartons !== '' ? strtoupper($this->convertNumberToWord(intval($supplier->total_cartons)) ). ' ONLY' : '',
            'kg' => isset($supplier->kg) && $supplier->kg !=='' ? $supplier->kg : '',
            'cbm' => isset($supplier->cbm) && $supplier->cbm !=='' ? $supplier->cbm : '',
            'type' => isset($shipment->type) && $shipment->type !=='' ? $shipment->type : '',
            'etd' => isset($scheduleBooking[0]->etd) && $scheduleBooking[0]->etd !=='' ? $scheduleBooking[0]->etd : '',
            //'etd' => isset($schedule['etd']) ? $schedule['etd'] : '',
            'po_num' =>  isset($supplier->po_num) && $supplier->po_num !=='' ? $supplier->po_num : '',
            'product_description' => isset($supplier->product_description) && $supplier->product_description !=='' ? $supplier->product_description : '',
            'containers' => isset($containerData) && $containerData ? $containerData : [],
            'marks' => isset($supplier->marks) && $supplier->marks !==''  ? $supplier->marks : '',
            'bol_shipper_address' => isset($supplier->bol_shipper_address) && $supplier->bol_shipper_address !=='' ? $supplier->bol_shipper_address : '',
            'bol_shipper_phone_number' => isset($supplier->bol_shipper_phone_number) && $supplier->bol_shipper_phone_number !=='' ? $supplier->bol_shipper_phone_number : '',
            'bol_consignee_address' => isset($supplier->bol_consignee_address) && $supplier->bol_consignee_address !=='' ? $supplier->bol_consignee_address : '',
            'bol_consignee_phone_number' => isset($supplier->bol_consignee_phone_number) && $supplier->bol_consignee_phone_number !=='' ? $supplier->bol_consignee_phone_number : '',
            'bol_notify_party' => isset($supplier->bol_notify_party) && $supplier->bol_notify_party !=='' ? $supplier->bol_notify_party : '',
            'bol_notify_address' => isset($supplier->bol_notify_address) && $supplier->bol_notify_address !=='' ? $supplier->bol_notify_address : '',
            'bol_notify_phone_number' => isset($supplier->bol_notify_phone_number) && $supplier->bol_notify_phone_number !=='' ? $supplier->bol_notify_phone_number : '',
            'bl_status' => isset($supplier->bl_status) && $supplier->bl_status !=='' ? $supplier->bl_status : '',
            'office_from' => (isset($shipment->officeFrom) && isset($shipment->officeFrom->name)) ? $shipment->officeFrom->name : '',
        ]);
    }
}
