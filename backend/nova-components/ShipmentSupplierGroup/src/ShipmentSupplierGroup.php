<?php

namespace Kenetashi\ShipmentSupplierGroup;

use Laravel\Nova\Fields\Field;
use Laravel\Nova\Http\Requests\NovaRequest;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\File;
use App\Shipment;
//use App\Incoterm;
//use App\PurchaseOrder;
//use App\Supplier;
//use App\Container;

class ShipmentSupplierGroup extends Field
{
    /**
     * The field's component.
     *
     * @var string
     */
    public $component = 'shipment-supplier-group';

     public function initFields($id) {

        /* Start New Code  02/13/2021 - Kenneth */

        $r = new NovaRequest();

        $fetchSuppliers = [];

        if (!$r->isResourceIndexRequest()) {

            $findShipment = ($id!=null) ? Shipment::find($id) : [];

            //$incoTerms = Incoterm::all();
            //$purchaseOrders = PurchaseOrder::all();
            //$fetchSuppliers = Supplier::all();
            //$containers = Container::all();


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

            /*
            $mergeSuppliers = (is_array(json_decode($findShipment->suppliers_group))) ? json_decode($findShipment->suppliers_group) : [];

            $mergeToSupplierBookings = (is_array(json_decode($findShipment->suppliers_group_bookings))) ? json_decode($findShipment->suppliers_group_bookings) : [];


            $newSuppliers = [];
            if(count($mergeSuppliers) > 0) {
                foreach($mergeSuppliers as $mergeSupplier) {

                    $hasSimilarity = false;

                    if (count($mergeToSupplierBookings) > 0) {

                        foreach($mergeToSupplierBookings as $mergeToSupplierBooking) {

                            if (isset($mergeToSupplierBooking->id) && isset($mergeSupplier->id)) {
                                if ($mergeToSupplierBooking->id==$mergeSupplier->id) {
                                    $hasSimilarity = true;
                                }
                            }

                        }
                    }

                    if (!$hasSimilarity) {
                        array_push($newSuppliers, $mergeSupplier);
                    }
                }
            }

            //store final suppliers data here
            $finalSuppliers = (count($newSuppliers) > 0) ? array_merge($newSuppliers, $mergeToSupplierBookings) : $mergeToSupplierBookings;*/

            $finalSuppliers = $newFinalSuppliers;
            $finalSuppliersIds = [];
            $finalSupplierIncoTermIds = [];


            if (is_array($finalSuppliers) && count($finalSuppliers) > 0) {
                foreach($finalSuppliers as $key => $finalSupplier) {

                    if (!isset($finalSupplier->containers)) {
                        $finalSuppliers[$key]->containers = [];
                    } else {
                        /*
                        if (is_array($finalSupplier->containers) && count($finalSupplier->containers) > 0) {
                            $tempContainers = $finalSupplier->containers;

                            foreach ($tempContainers as $keySecond => $tempContainer) {

                                if (count($containers) > 0) {
                                    foreach($containers as $c) {
                                        if (isset($tempContainer->container_id) && $tempContainer->container_id==$c->unique_id) {
                                            $tempContainers[$keySecond]->container_num = $c->container_num;
                                        }
                                    }
                                }
                            }
                            $finalSupplier->containers = $tempContainers;
                        }*/
                    }
                    //inco terms
                    /*
                    if (count($incoTerms) > 0) {
                        foreach($incoTerms as $incoTerm) {
                            if (isset($finalSupplier->incoterm_id) && $finalSupplier->incoterm_id==$incoTerm->id) {
                                $finalSuppliers[$key]->incoterm_name = $incoTerm->name;
                            }
                        }
                    }*/

                    //purchase orders
                    /*
                    if (count($purchaseOrders) > 0) {
                        foreach($purchaseOrders as $purchaseOrder) {
                            if (isset($finalSupplier->po_id) && $finalSupplier->po_id==$purchaseOrder->id) {
                                $finalSuppliers[$key]->po_name = $purchaseOrder->po_num;
                            }
                        }
                    }*/

                    //suppliers
                    /*
                    if (count($fetchSuppliers) > 0) {
                        foreach($fetchSuppliers as $fetchSupplier) {
                            if (isset($finalSupplier->supplier_id) && $finalSupplier->supplier_id==$fetchSupplier->id) {
                                $finalSuppliers[$key]->company_name = $fetchSupplier->company_name;
                            }
                        }
                    }*/
                }
            }
            /*

            if (count($newSuppliers) > 0) {
                $findShipment->suppliers_group_bookings = json_encode(array_merge($newSuppliers, $mergeToSupplierBookings));
            } else {
                $findShipment->suppliers_group_bookings = json_encode($mergeToSupplierBookings);
            }*/
            $finalSuppliers = json_encode($finalSuppliers);

            $findShipment->suppliers_group_bookings = $finalSuppliers;
            $findShipment->suppliers_group = $finalSuppliers;

            //$findShipment->suppliers_group_bookings = json_encode(array_merge($mergeSupplier, $mergeToSupplierBookings));

            //$findShipment->suppliers_group = $findShipment->suppliers_group_bookings;

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
                //$model->suppliers_group = $request->input('suppliers_group');

                if (count($suppliers_group)>0) {
                    foreach ($suppliers_group as $key => $supplier) {
                        $copyKey = intval($key + 1);

                        if ($request->has('hbl_copy_'.$copyKey) && $request->file('hbl_copy_'.$copyKey)!=null) {
                            $originalName = 'shipment/hbl_copy/'. basename($request->file('hbl_copy_'.$copyKey)->getClientOriginalName(), '.'. $request->file('hbl_copy_'.$copyKey)->guessExtension()) . '_'.$model->id.'_'.$copyKey.'_hbl.' .$request->file('hbl_copy_'.$copyKey)->guessExtension();

                            // $originalName = 'shipment/hbl_copy/'. $request->file('hbl_copy_'.$copyKey)->getClientOriginalName();

                            Storage::disk('local')->putFileAs('/public', $request->file('hbl_copy_'.$copyKey), $originalName);
                            //  $get_path = $request->file('hbl_copy_'.$copyKey)->store('/', 'public');
                            $suppliers_group[$key]->hbl_copy = $originalName;
                        } else {
                            //if($suppliers_group[$key])
                 //$suppliers_group[$key]->hbl_copy = null;
                        }
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
