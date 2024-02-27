<?php

namespace Vishalmarakana\SufficientCommercialDocs;

use App\Shipment;
use Illuminate\Support\Facades\Storage;
use Laravel\Nova\Fields\Field;
use Laravel\Nova\Http\Requests\NovaRequest;

class SufficientCommercialDocs extends Field
{
    /**
     * The field's component.
     *
     * @var string
     */
    public $component = 'sufficient-commercial-docs';


    public function initFields($id) {

        $r = new NovaRequest();

        $fetchSuppliers = [];

        if (!$r->isResourceIndexRequest()) {

            $findShipment = ($id!=null) ? Shipment::find($id) : [];

            if (isset($findShipment->id)) {

                //suppliers merge
                //supplier merge
                $mergeSuppliers = (is_array(json_decode($findShipment->suppliers_group_bookings))) ? json_decode($findShipment->suppliers_group_bookings) : [];

                $mergeToSupplierBookings = (is_array(json_decode($findShipment->suppliers_group))) ? json_decode($findShipment->suppliers_group) : [];

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

                        if  (!isset($value->cargo_ready_date)) {
                            $newFinalSuppliers[$key]->cargo_ready_date = '';
                        }

                    }
                }

                $finalSuppliers = $newFinalSuppliers;
                $finalSuppliersIds = [];
                $finalSupplierIncoTermIds = [];

                if (is_array($finalSuppliers) && count($finalSuppliers) > 0) {
                    foreach($finalSuppliers as $key => $finalSupplier) {

                        if (!isset($finalSupplier->containers)) {
                            $finalSuppliers[$key]->containers = [];
                        } else {

                        }
                    }
                }

                $finalSuppliers = json_encode($finalSuppliers);

                $findShipment->suppliers_group_bookings = $finalSuppliers;
                $findShipment->suppliers_group = $finalSuppliers;

            }

            return $this->withMeta([
                'shipment' => $findShipment,
                'baseUrl' => url('/')
            ]);
        } else {
            return null;
        }


    }

    protected function fillAttributeFromRequest(
        NovaRequest $request,
        $requestAttribute,
        $model,
        $attribute
    ) {

        $model->custom_content = ($request->has('custom_content')) ? $request->input('custom_content') : '';

        if ($request->has('suppliers_group')) {
            if ($request->input('suppliers_group')!=null) {
                $suppliers_group = json_decode($request->input('suppliers_group'));

                if (count($suppliers_group)>0) {
                    foreach ($suppliers_group as $key => $supplier) {
                        $copyKey = intval($key + 1);

                        if ($request->has('hbl_copy_'.$copyKey) && $request->file('hbl_copy_'.$copyKey)!=null) {
                            $originalName = 'shipment/hbl_copy/'. basename($request->file('hbl_copy_'.$copyKey)->getClientOriginalName(), '.'. $request->file('hbl_copy_'.$copyKey)->guessExtension()) . '_'.$model->id.'_'.$copyKey.'_hbl.' .$request->file('hbl_copy_'.$copyKey)->guessExtension();

                            Storage::disk('local')->putFileAs('/public', $request->file('hbl_copy_'.$copyKey), $originalName);

                            $suppliers_group[$key]->hbl_copy = $originalName;
                        } else {
                        }
                        if ($request->has('packing_list_'.$copyKey) && $request->file('packing_list_'.$copyKey)!=null) {
                            $originalName = 'shipment/packing_list/'. basename($request->file('packing_list_'.$copyKey)->getClientOriginalName(), '.'. $request->file('packing_list_'.$copyKey)->guessExtension()) . '_'.$model->id.'_'.$copyKey.'_packing.' .$request->file('packing_list_'.$copyKey)->guessExtension();

                            Storage::disk('local')->putFileAs('/public', $request->file('packing_list_'.$copyKey), $originalName);

                            $suppliers_group[$key]->packing_list = $originalName;
                        } else {

                        }

                        if ($request->has('commercial_documents_'.$copyKey) && $request->file('commercial_documents_'.$copyKey)!=null) {
                            $originalName = 'shipment/commercial_documents/'. basename($request->file('commercial_documents_'.$copyKey)->getClientOriginalName(), '.'. $request->file('commercial_documents_'.$copyKey)->guessExtension()) . '_'.$model->id.'_'.$copyKey.'_cdocuments.' .$request->file('commercial_documents_'.$copyKey)->guessExtension();

                            Storage::disk('local')->putFileAs('/public', $request->file('commercial_documents_'.$copyKey), $originalName);
                            $suppliers_group[$key]->commercial_documents = $originalName;
                        } else {

                        }

                        if ($request->has('commercial_invoice_'.$copyKey) && $request->file('commercial_invoice_'.$copyKey)!=null) {
                            $originalName = 'shipment/commercial_invoice/'. basename($request->file('commercial_invoice_'.$copyKey)->getClientOriginalName(), '.'. $request->file('commercial_invoice_'.$copyKey)->guessExtension()) . '_'.$model->id.'_'.$copyKey.'_cinvoice.' .$request->file('commercial_invoice_'.$copyKey)->guessExtension();

                            Storage::disk('local')->putFileAs('/public', $request->file('commercial_invoice_'.$copyKey), $originalName);
                            $suppliers_group[$key]->commercial_invoice = $originalName;
                        } else {

                        }

                        $suppliers_group[$key]->supplier_id = (isset($supplier->supplier_id)) ? $supplier->supplier_id : null;
                    }

                    $model->suppliers_group = json_encode($suppliers_group);
                } else {
                    $model->suppliers_group = json_encode([]);
                }
            } else {
                $model->suppliers_group = json_encode([]);
            }
        } else {
            $model->suppliers_group = json_encode([]);
        }
    }
}
