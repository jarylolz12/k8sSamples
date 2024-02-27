<?php

namespace Kenetashi\ShipmentSupplierLimitedGroup;


use Laravel\Nova\Fields\Field;
use Laravel\Nova\Http\Requests\NovaRequest;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\File;
use App\Shipment;

class ShipmentSupplierLimitedGroup extends Field
{
    /**
     * The field's component.
     *
     * @var string
     */
    public $component = 'shipment-supplier-limited-group';

    

    public function initFields($id) {

        $findShipment = ($id!=null) ? Shipment::find($id) : [];

        if (isset($findShipment->id)) {

            //supplier merge
            $mergeSuppliers = (is_array(json_decode($findShipment->suppliers_group))) ? json_decode($findShipment->suppliers_group) : [];

            $mergeToSupplierBookings = (is_array(json_decode($findShipment->suppliers_group_bookings))) ? json_decode($findShipment->suppliers_group_bookings) : [];


            //ss
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
            
            $findShipment->suppliers_group_bookings = json_encode($newFinalSuppliers);

            /*
            $mergeSupplier = (is_array(json_decode($findShipment->suppliers_group))) ? json_decode($findShipment->suppliers_group) : [];

            $mergeToSupplierBookings = (is_array(json_decode($findShipment->suppliers_group_bookings))) ? json_decode($findShipment->suppliers_group_bookings) : [];
            
            $findShipment->suppliers_group_bookings = json_encode(array_merge($mergeSupplier, $mergeToSupplierBookings));*/
            
        }
        

        return $this->withMeta([
            'shipment' => $findShipment,
            'baseUrl' => url('/')
        ]);
    }

    protected function fillAttributeFromRequest(
        NovaRequest $request,
        $requestAttribute,
        $model,
        $attribute
    ) {

        $model->custom_content = ($request->has('custom_content')) ? $request->input('custom_content') : '';

        if ($request->has('suppliers_group_bookings')) {
            if ($request->input('suppliers_group_bookings')!=null) {
                $suppliers_group = json_decode($request->input('suppliers_group_bookings'));
                //$model->suppliers_group = $request->input('suppliers_group');

                if (count($suppliers_group)>0) {
                    foreach ($suppliers_group as $key => $supplier) {
                        $copyKey = intval($key + 1);

                        if ($request->has('packing_list_'.$copyKey) && $request->file('packing_list_'.$copyKey)!=null) {
                            $originalName = 'shipment/packing_list/'. basename($request->file('packing_list_'.$copyKey)->getClientOriginalName(), '.'. $request->file('packing_list_'.$copyKey)->guessExtension()) . '_'.$model->id.'_'.$copyKey.'_packing.' .$request->file('packing_list_'.$copyKey)->guessExtension();

                            // $originalName = 'shipment/packing_list/'. $request->file('packing_list_'.$copyKey)->getClientOriginalName();

                            Storage::disk('local')->putFileAs('/public', $request->file('packing_list_'.$copyKey), $originalName);
                            //  $get_path = $request->file('hbl_copy_'.$copyKey)->store('/', 'public');
                            $suppliers_group[$key]->packing_list = $originalName;
                        } else {
                            // $suppliers_group[$key]->packing_list = null;
                        }

                        if ($request->has('commercial_documents_'.$copyKey) && $request->file('commercial_documents_'.$copyKey)!=null) {
                            $originalName = 'shipment/commercial_documents/'. basename($request->file('commercial_documents_'.$copyKey)->getClientOriginalName(), '.'. $request->file('commercial_documents_'.$copyKey)->guessExtension()) . '_'.$model->id.'_'.$copyKey.'_cdocuments.' .$request->file('commercial_documents_'.$copyKey)->guessExtension();


                            // $originalName = 'shipment/commercial_documents/'. $request->file('commercial_documents_'.$copyKey)->getClientOriginalName();

                            Storage::disk('local')->putFileAs('/public', $request->file('commercial_documents_'.$copyKey), $originalName);
                            $suppliers_group[$key]->commercial_documents = $originalName;
                        } else {
                            //$suppliers_group[$key]->commercial_documents = null;
                        }

                        if ($request->has('commercial_invoice_'.$copyKey) && $request->file('commercial_invoice_'.$copyKey)!=null) {
                            $originalName = 'shipment/commercial_invoice/'. basename($request->file('commercial_invoice_'.$copyKey)->getClientOriginalName(), '.'. $request->file('commercial_invoice_'.$copyKey)->guessExtension()) . '_'.$model->id.'_'.$copyKey.'_cinvoice.' .$request->file('commercial_invoice_'.$copyKey)->guessExtension();

                            // $originalName = 'shipment/commercial_invoice/'. $request->file('commercial_invoice_'.$copyKey)->getClientOriginalName();

                            Storage::disk('local')->putFileAs('/public', $request->file('commercial_invoice_'.$copyKey), $originalName);
                            $suppliers_group[$key]->commercial_invoice = $originalName;
                        } else {
                            //$suppliers_group[$key]->commercial_invoice = null;
                        }

                        $suppliers_group[$key]->supplier_id = (isset($supplier->supplier_id)) ? $supplier->supplier_id : null;
                    }

                    $model->suppliers_group_bookings = json_encode($suppliers_group);
                } else {
                    $model->suppliers_group_bookings = json_encode([]);
                }
            } else {
                $model->suppliers_group_bookings = json_encode([]);
            }
        } else {
            $model->suppliers_group_bookings = json_encode([]);
        }
    }
}
