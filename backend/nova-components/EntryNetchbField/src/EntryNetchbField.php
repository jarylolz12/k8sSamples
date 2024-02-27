<?php

namespace Cyrus\EntryNetchbField;

use Laravel\Nova\Fields\Field;
use Laravel\Nova\Http\Requests\NovaRequest;
use App\Shipment;
use App\Supplier;


class EntryNetchbField extends Field
{
    /**
     * The field's component.
     *
     * @var string
     */
    public $reload = false;

    public $component = 'entry-netchb-field';

    public function initFields($id)
    {
        $shipment = Shipment::find($id);
        if (!isset($shipment->id)) return $this;

        $shipmentSuppliers = (is_array(json_decode($shipment->suppliers_group))) ? json_decode($shipment->suppliers_group) : [];
        $shipmentSuppliersBookings =  (is_array(json_decode($shipment->suppliers_group_bookings))) ? json_decode($shipment->suppliers_group_bookings) : [];

        $suppliers = [];


        foreach ($shipmentSuppliers as $key => $value) {
            array_push($suppliers, [
                "id" => $value->id,
                "supplier_id" => $value->supplier_id
            ]);
        }

        foreach ($shipmentSuppliersBookings as $key => $supplierBookings) {
            $tempSuppliers = [];
            foreach ($shipmentSuppliers as $key => $supplier) {
                array_push($tempSuppliers, $supplier->id);
            }

            if (!in_array($supplierBookings->id, $tempSuppliers)) {
                array_push($suppliers, [
                    "id" => $supplierBookings->id,
                    "supplier_id" => $supplierBookings->supplier_id
                ]);
            }
        }

        return $this->withMeta(['suppliers' =>  $suppliers]);
    }

    // public function resolve($resource, $attribute = null)
    // {
    //     parent::resolve($resource, $attribute);
    //     $this->withMeta([
    //         "reload" => $this->reload
    //     ]);
    // }

    // public function reload($reload = true)
    // {
    //     $this->reload = $reload;

    //     return $this;
    // }
}
