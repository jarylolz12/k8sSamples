<?php

namespace App\Http\Resources\CLIENT\v1;

use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Collection;
use App\Custom\ProcessSchedulesGroupBookings;

class Shipment extends JsonResource
{
    private $terminal49_shipment = null;
    private $t49_schedule = null;
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */

    protected function suppliers($suppliers)
    {
        $suppliers_array = [];

        foreach ($suppliers ?? [] as $supplier) {
            array_push($suppliers_array, collect([
                'supplier_name' => ($supplier->supplier_id) ? \App\Supplier::find($supplier->supplier_id)->company_name ?? "" : "",
                'cargo_ready_date' => $supplier->cargo_ready_date ?? "",
                'po_num_old' => $supplier->po_num ?? "",
                'selected_po' => $this->selectedPos($supplier->supplier_id) ?? "",
                'weight' => $supplier->kg ?? "",
                'volume' => $supplier->cbm ?? "",
                'total_cartons' => $supplier->total_cartons ?? "",
                'ams' => $supplier->ams_num ?? "",
                'hbl' => $supplier->hbl_num ?? "",
                'marks' => $supplier->marks ?? "",
                'product_description' => $supplier->product_description ?? "",
                'terms' => ($supplier->incoterm_id) ? \App\Incoterm::find($supplier->incoterm_id)->name ?? "" : "",
                'bl_status' => $supplier->bl_status ?? "",
                'product_description' => $supplier->product_description ?? "",
                'hbl_copy' => $supplier->hbl_copy ?? "",
                'packing_list' => $supplier->packing_list ?? "",
                'commercial_documents' => $supplier->commercial_documents ?? "",
                'commercial_invoice' => $supplier->commercial_invoice ?? ""
            ]));
        }
        return $suppliers_array;
    }

    protected function schedules($schedules)
    {
        $selected_schedule = (new ProcessSchedulesGroupBookings($schedules))->getSchedule();

        if($selected_schedule){
            return [
                'etd' => !empty($selected_schedule->etd) ? $selected_schedule->etd : $this->getT49Schedule()['etd'],
                'eta' => !empty($selected_schedule->eta) ? $selected_schedule->eta : $this->getT49Schedule()['eta'],
                'location_from' =>  $this->getTerminalRegionName($selected_schedule->location_from) ?? $this->getT49Schedule()['location_from'],
                'location_to' => $this->getTerminalRegionName($selected_schedule->location_to) ?? $this->getT49Schedule()['location_to'],
                'mode' => $selected_schedule->mode,
                'carrier' => ($selected_schedule->carrier_name ?? false) && ($selected_schedule->carrier_name->id ?? false) ? \App\Carrier::find($selected_schedule->carrier_name->id)->name ?? $this->getT49Schedule()['carrier'] : $this->getT49Schedule()['carrier'] ,
                'type' => $selected_schedule->type,
                'legs' => $this->getScheduleLegs($selected_schedule->legs)
            ];
        }

        return $this->getT49Schedule();
    }

    private function getT49Schedule(){
        if($this->t49_schedule){
            return $this->t49_schedule;
        }
        $this->t49_schedule = [
            'etd' => "",
            'eta' => "",
            'location_from' => "",
            'location_to' => "",
            'mode' => "",
            'carrier' => "",
            'type' => "",
            'legs' => []
        ];

        if($this->terminal49_shipment){
            $attributes = json_decode($this->terminal49_shipment->attributes ?? '[]',true);
            if(is_array($attributes) && count($attributes)){
                if(!empty($attributes['pod_eta_at'])){
                    $this->t49_schedule['eta'] = $attributes['pod_eta_at'];
                }
                if(!empty($attributes['pol_etd_at'])){
                    $this->t49_schedule['etd'] = $attributes['pol_etd_at'];
                }
                if(!empty($attributes['port_of_lading_name'])){
                    $this->t49_schedule['location_from'] = $attributes['port_of_lading_name'];
                }
                if(!empty($attributes['port_of_discharge_name'])){
                    $this->t49_schedule['location_to'] = $attributes['port_of_discharge_name'];
                }
                if(!empty($attributes['shipping_line_name'])){
                    $this->t49_schedule['carrier'] = $attributes['shipping_line_name'];
                }
            }
        }

        return $this->t49_schedule;
    }

    private function getScheduleLegs($legs){
        $filtered_legs = [];
        if($legs && count($legs)){
            foreach ($legs ?? [] as $key => $leg) {
                // code...
                array_push($filtered_legs,[
                    'mode' => $leg->mode,
                    'eta' => $leg->eta,
                    'location_to' => $this->getTerminalRegionName($leg->location_to),
                ]);

            }
        }
        return $filtered_legs;
    }

    private function getTerminalRegionName($id){
        return (!empty($id)) ? \App\TerminalRegion::find($id)->name ?? null : null;
    }

    protected function containers($containers)
    {
        $containers_array = [];

        foreach ($containers ?? [] as $container) {
            array_push($containers_array, collect([
                'container_num' => $container->container_num,
                'size' => ($container->size)? \App\ContainerSize::find($container->size)->name ?? "" : "",
                'seal_num' => $contianer->seal_num ?? "",
                'weight' => $container->kg,
                'volume' => $container->cbm,
                'carton_count' => $container->cartons
            ]));
        }
        if(count($containers_array) == 0 || $this->is_tracking_shipment){
            return $this->getT49Containers();
        }

        return $containers_array;
    }
    private function getT49Containers(){
        $terminal49_containers = [];
        $containers = json_decode($this->terminal49_shipment->containers ?? '[]',true);
        foreach ($containers ?? [] as $key => $container) {
            // code...
            array_push($terminal49_containers,[
                'contianer_num' => $container['attributes']['number'] ?? "",
                'size' => (!empty($container['attributes']['equipment_length'])) ? $container['attributes']['equipment_length'] . $container['attributes']['equipment_height'] ?? "" : "",
                'seal_num' => $container['attributes']['seal_number'] ?? '',
                'weight' => !empty($container['attributes']['weight_in_lbs']) && is_numeric($container['attributes']['weight_in_lbs']) ?  round($container['attributes']['weight_in_lbs']* 0.453592,2) : "",
                'volume' => "",
                'carton_count' => "",
            ]);
        }
        return $terminal49_containers;
    }
    public function toArray($request)
    {
        $this->terminal49_shipment = $this->terminal_fortynine;

        return[
            'id' => $this->id,
            'shifl_ref'=> $this->shifl_ref,
            'customer' => $this->customer->company_name ?? "",
            'mbl_num' => $this->mbl_num,
            'is_tracking_shipment' => $this->is_tracking_shipment ? 'true' : 'false',
            'vessel' => $this->vessel ?? $this->getT49Vessel(),
            'booking_confirmed' => $this->booking_confirmed ? 'true' : 'false',
            'booking_confirmed_at' => $this->booking_confirmed_at,
            'schedules' => $this->schedules($this->schedules_group_bookings),
            'suppliers' => $this->suppliers(json_decode($this->suppliers_group_bookings ?? [])),
            'containers' => $this->containers(json_decode($this->containers_group_bookings)),
            'terminal' => $this->getTerminalDetails(),
            'customs_documents' => $this->getCustomsDocuments(),
        ];
    }

    private function getT49Vessel(){
        if($this->terminal49_shipment){
            $attributes = json_decode($this->terminal49_shipment->attributes ?? [],true);
            if(isset($attributes['pod_vessel_name'])){
               return $attributes['pod_vessel_name']. " ".$attributes['pod_voyage_number'];
            }
        }
        return "";
    }

    private function getTerminalDetails(){

        if($this->terminal){
            return [
                'name' => $this->terminal->name,
                'firms_code' => $this->terminal->firms_code
            ];
        }
        $t49_terminal = $this->terminal49_terminal();
        if($t49_terminal){
          return [
              'name' => $t49_terminal->name,
              'firms_code' => $t49_terminal->firms_code
          ];
        }
        return [
            'name' => "",
            'firms_code' => "",
        ];
    }

    private function getCustomsDocuments(){
        $suppliers_commercial_docs = json_decode($this->suppliers_commercial_docs ?? '[]');
        $docs = [];
        foreach ($suppliers_commercial_docs ?? [] as $key => $doc) {
            // code...
            array_push($docs,[
                'supplier_name' => ($doc->supplier_id) ? \App\Supplier::find($doc->supplier_id)->company_name ?? "" : "",
                'files' => collect($doc->commercial_documents)->map(function($d){
                    return [
                      'name' => $d->name,
                      'path' => $d->file
                    ];
                })

            ]);
        }
        return $docs;
    }
}
