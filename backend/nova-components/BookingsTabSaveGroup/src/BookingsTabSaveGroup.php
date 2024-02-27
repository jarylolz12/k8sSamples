<?php

namespace Kenetashi\BookingsTabSaveGroup;

use App\ShiflOffice;
use Illuminate\Support\Facades\Log;
use Laravel\Nova\Fields\Field;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\File;
use App\Shipment;
use Carbon\Carbon;
use App\Document;
//use App\Supplier;
//use App\Incoterm;
//use App\ContainerSize;
//use App\Carrier;
//use App\TerminalRegion;
//use App\Service;
//use App\PurchaseOrder;

use Laravel\Nova\Http\Requests\NovaRequest;
use App\Events\InsertDocumentEvent;
class BookingsTabSaveGroup extends Field
{
    /**
     * The field's component.
     *
     * @var string
     */
    public $component = 'bookings-tab-save-group';

    protected function fillAttributeFromRequest(
        NovaRequest $request,
        $requestAttribute,
        $model,
        $attribute
    ) {

        if ($request->has('schedules_group_bookings')) {
            if ($request->input('schedules_group_bookings')!=null) {
                $schedules_group_bookings = json_decode($request->input('schedules_group_bookings'));

                if (is_array($schedules_group_bookings) && count($schedules_group_bookings)>0) {

                    foreach($schedules_group_bookings as $key => $schedules_group_booking) {
                        $schedules_group_bookings[$key]->eta = ($schedules_group_booking->eta!==null && $schedules_group_booking->eta!=='') ? Carbon::createFromFormat('Y-m-d',$schedules_group_booking->eta)->format('Y-m-d H:i:s') : null;
                        $schedules_group_bookings[$key]->etd = ($schedules_group_booking->etd!==null && $schedules_group_booking->etd!=='') ? Carbon::createFromFormat('Y-m-d',$schedules_group_booking->etd)->format('Y-m-d H:i:s') : null;


                    }

                    $model->schedules_group_bookings = json_encode($schedules_group_bookings);

                } else {
                    $model->schedules_group_bookings = json_encode([]);
                }

                /*
                if (isset($schedules_group_bookings[1])) {
                    foreach($schedules_group_bookings as $key => $schedule_group_booking) {
                        if ($key>1) {

                            if (isset($schedules_group_bookings[$key]->suppliers_group_bookings)) {
                                if (count($schedules_group_bookings[$key]->suppliers_group_bookings) > 0) {
                                    foreach($schedules_group_bookings[$key]->suppliers_group_bookings as $keySecond => $suppliers_group_bookings) {


                                        //packing list
                                        if ($request->has('packing_list_'.$key.'_'.$keySecond) && $request->file('packing_list_'.$key.'_'.$keySecond)!=null) {
                                            $originalName = 'shipment/packing_list/'. basename($request->file('packing_list_'.$key.'_'.$keySecond)->getClientOriginalName(), '.'. $request->file('packing_list_'.$key.'_'.$keySecond)->guessExtension());

                                                Storage::disk('local')->putFileAs('/public', $request->file('packing_list_'.$key.'_'.$keySecond), $originalName);
                                                //  $get_path = $request->file('hbl_copy_'.$copyKey)->store('/', 'public');
                                                $schedules_group_bookings[$key]->suppliers_group_bookings[$keySecond]->packing_list = $originalName;
                                        }

                                        //commercial documents
                                        if ($request->has('commercial_documents_'.$key.'_'.$keySecond) && $request->file('commercial_documents_'.$key.'_'.$keySecond)!=null) {
                                            $originalName = 'shipment/commercial_documents/'. basename($request->file('commercial_documents_'.$key.'_'.$keySecond)->getClientOriginalName(), '.'. $request->file('commercial_documents_'.$key.'_'.$keySecond)->guessExtension());

                                            Storage::disk('local')->putFileAs('/public', $request->file('commercial_documents_'.$key.'_'.$keySecond), $originalName);

                                            //  $get_path = $request->file('hbl_copy_'.$copyKey)->store('/', 'public');
                                            $schedules_group_bookings[$key]->suppliers_group_bookings[$keySecond]->commercial_documents = $originalName;
                                        }

                                        //commercial invoice
                                        if ($request->has('commercial_invoice_'.$key.'_'.$keySecond) && $request->file('commercial_invoice_'.$key.'_'.$keySecond)!=null) {
                                            $originalName = 'shipment/commercial_invoice/'. basename($request->file('commercial_invoice_'.$key.'_'.$keySecond)->getClientOriginalName(), '.'. $request->file('commercial_invoice_'.$key.'_'.$keySecond)->guessExtension());

                                            Storage::disk('local')->putFileAs('/public', $request->file('commercial_invoice_'.$key.'_'.$keySecond), $originalName);

                                            //  $get_path = $request->file('hbl_copy_'.$copyKey)->store('/', 'public');
                                            $schedules_group_bookings[$key]->suppliers_group_bookings[$keySecond]->commercial_invoice = $originalName;
                                        }

                                    }
                                }
                            }

                        }
                    }

                }*/

            }
        } else {
            $model->schedules_group_bookings = json_encode([]);
        }

        $model->custom_content = ($request->has('custom_content')) ? $request->input('custom_content') : '';

        if ($request->has('containers_group_bookings')) {

            if ($request->input('containers_group_bookings')!=null) {
                $containers_group = json_decode($request->input('containers_group_bookings'));
                //$model->suppliers_group = $request->input('suppliers_group');
                if (is_array($containers_group) && count($containers_group)>0) {
                    $model->containers_group_bookings = json_encode($containers_group);
                } else {
                    $model->containers_group_bookings = json_encode([]);
                }
            } else {
                $model->containers_group_bookings = json_encode([]);
            }

        } else {
            $model->containers_group_bookings = json_encode([]);
        }



        if ($request->has('suppliers_group_bookings')) {
            if ($request->input('suppliers_group_bookings')!=null) {
                $suppliers_group = json_decode($request->input('suppliers_group_bookings'));
                //$model->suppliers_group = $request->input('suppliers_group');

                if (is_array($suppliers_group) && count($suppliers_group)>0) {
                    foreach ($suppliers_group as $key => $supplier) {
                        $copyKey = intval($key + 1);

                        if ($request->has('bookings_packing_list_'.$copyKey) && $request->file('bookings_packing_list_'.$copyKey)!=null) {
                            $originalName = 'shipment/packing_list/'. basename($request->file('bookings_packing_list_'.$copyKey)->getClientOriginalName(), '.'. $request->file('bookings_packing_list_'.$copyKey)->guessExtension()) . '_'.$model->id.'_'.$copyKey.'_packing.' .$request->file('bookings_packing_list_'.$copyKey)->guessExtension();

                            // $originalName = 'shipment/packing_list/'. $request->file('packing_list_'.$copyKey)->getClientOriginalName();

                            Storage::disk('local')->putFileAs('/public', $request->file('bookings_packing_list_'.$copyKey), $originalName);
                            //  $get_path = $request->file('hbl_copy_'.$copyKey)->store('/', 'public');
                            $suppliers_group[$key]->packing_list = $originalName;
                        } else {
                            // $suppliers_group[$key]->packing_list = null;
                        }

                        if ($request->has('bookings_commercial_documents_'.$copyKey) && $request->file('bookings_commercial_documents_'.$copyKey)!=null) {
                            $originalName = 'shipment/commercial_documents/'. basename($request->file('bookings_commercial_documents_'.$copyKey)->getClientOriginalName(), '.'. $request->file('bookings_commercial_documents_'.$copyKey)->guessExtension()) . '_'.$model->id.'_'.$copyKey.'_cdocuments.' .$request->file('bookings_commercial_documents_'.$copyKey)->guessExtension();


                            // $originalName = 'shipment/commercial_documents/'. $request->file('commercial_documents_'.$copyKey)->getClientOriginalName();

                            Storage::disk('local')->putFileAs('/public', $request->file('bookings_commercial_documents_'.$copyKey), $originalName);
                            $suppliers_group[$key]->commercial_documents = $originalName;
                        } else {
                            //$suppliers_group[$key]->commercial_documents = null;
                        }

                        if ($request->has('bookings_commercial_invoice_'.$copyKey) && $request->file('bookings_commercial_invoice_'.$copyKey)!=null) {
                            $originalName = 'shipment/commercial_invoice/'. basename($request->file('bookings_commercial_invoice_'.$copyKey)->getClientOriginalName(), '.'. $request->file('bookings_commercial_invoice_'.$copyKey)->guessExtension()) . '_'.$model->id.'_'.$copyKey.'_commercial_invoice.' .$request->file('bookings_commercial_invoice_'.$copyKey)->guessExtension();

                            // $originalName = 'shipment/commercial_invoice/'. $request->file('commercial_invoice_'.$copyKey)->getClientOriginalName();

                            Storage::disk('local')->putFileAs('/public', $request->file('bookings_commercial_invoice_'.$copyKey), $originalName);
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

    public function initFields($id, $shifl_ref) {
        $r = new NovaRequest();

        if (!$r->isResourceIndexRequest()) {
            $findShipment = ($id!=null) ? Shipment::find($id) : [];
            $newTerminalRegions = [];
            $types = [ 'LCL' => 'LCL',
                        'LTL' => 'LTL',
                        'FTL' => 'FTL',
                        'FCL' => 'FCL',
                        'Air' => 'Air',
                    ];

            $findContainerSizes = \App\ContainerSize::all();
            $rateSizes = [];

        if (count($findContainerSizes) > 0) {
            foreach ($findContainerSizes as $key => $findContainerSize) {
                array_push($rateSizes, [
                    'id' => $findContainerSize->id,
                    'name' => $findContainerSize->name
                ]);
            }
        }

        $response = ['status' => 'ok','results' => []];

        if (isset($findShipment->id)) {

            //schedule merge
            $mergeSchedule = (is_array(json_decode($findShipment->schedules_group_bookings))) ? json_decode($findShipment->schedules_group_bookings) : [];

            $mergeToScheduleBookings = (is_array(json_decode($findShipment->schedules_group))) ? json_decode($findShipment->schedules_group) : [];

            //ss
            $newSchedules = [];

            foreach ($mergeSchedule as $key => $value) {
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
                foreach ($mergeSchedule as $key => $value) {
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

            $findShipment->schedules_group_bookings = json_encode($newFinalSchedules);
            // $findShipment->schedules_group_bookings = json_encode(array_merge($mergeSchedule, $mergeToScheduleBookings));

            if (isset($findShipment->schedules_group_bookings)) {
                //$scheduleBookings = $findShipment->schedules_group_bookings;
                //$scheduleBookings = json_decode($findShipment->schedules_group_bookings);

                $scheduleBookings = (is_array(json_decode($findShipment->schedules_group_bookings))) ? json_decode($findShipment->schedules_group_bookings) : [];

                if (is_countable($scheduleBookings) && count($scheduleBookings) > 0) {

                    foreach ($scheduleBookings as $key => $scheduleBooking) {


                        if (!isset($scheduleBooking->carrier_name)) {
                            $scheduleBookings[$key]->carrier_name = [
                                'id' => 0
                            ];
                        } else {
                            if ( $scheduleBooking->carrier_name==null ) {
                                $scheduleBookings[$key]->carrier_name = [
                                    'id' => 0
                                ];
                            }
                        }

                        if (!isset($scheduleBooking->vendor_id)) {
                            $scheduleBookings[$key]->vendor_id = 0;
                        }

                        if (!isset($scheduleBooking->sell_rates)) {
                            $scheduleBookings[$key]->sell_rates = [];
                        } else {
                            if (isset($scheduleBooking->sell_rates) && $scheduleBooking->sell_rates!=='' && $scheduleBooking->sell_rates!==null) {
                                if (count($scheduleBooking->sell_rates) > 0) {

                                    foreach($scheduleBooking->sell_rates as $k => $sr) {
                                        if (!isset($scheduleBooking->sell_rates[$k]->valid_from)) {
                                            $scheduleBookings[$key]->sell_rates[$k]->valid_from = null;
                                        }

                                        if (!isset($scheduleBooking->sell_rates[$k]->valid_to)) {
                                            $scheduleBookings[$key]->sell_rates[$k]->valid_to = null;
                                        }

                                    }
                                }
                            }
                        }

                        if (!isset($scheduleBooking->buy_rates)) {
                            $scheduleBookings[$key]->buy_rates = [];
                        } else {
                            if (isset($scheduleBooking->buy_rates) && $scheduleBooking->buy_rates!=='' && $scheduleBooking->buy_rates!==null) {
                                if (count($scheduleBooking->buy_rates) > 0) {

                                    foreach($scheduleBooking->buy_rates as $k => $sr) {
                                        if (!isset($scheduleBooking->buy_rates[$k]->valid_from)) {
                                            $scheduleBookings[$key]->buy_rates[$k]->valid_from = null;
                                        }

                                        if (!isset($scheduleBooking->buy_rates[$k]->valid_to)) {
                                            $scheduleBookings[$key]->buy_rates[$k]->valid_to = null;
                                        }

                                    }
                                }
                            }
                        }

                        if (!isset($scheduleBooking->legs)) {
                            $scheduleBookings[$key]->legs = [];
                        }


                        foreach($rateSizes as $size) {
                            if (isset($scheduleBooking->selling_size_id)) {
                                if ($size['id']==$scheduleBooking->selling_size_id) {
                                    $scheduleBookings[$key]->selling_size_name = $size['name'];
                                }
                            }
                            if (isset($scheduleBooking->size_id)) {
                                if ($size['id']==$scheduleBooking->size_id) {
                                    $scheduleBookings[$key]->size_name = $size['name'];
                                }
                            }

                            if (isset($scheduleBooking->sell_rates[0]) && count($scheduleBooking->sell_rates) > 0) {
                                foreach ($scheduleBooking->sell_rates as $keySecond => $sell_rate) {
                                    if ($sell_rate->container_size_id == $size['id']) {
                                        $scheduleBooking->sell_rates[$keySecond]->container_size_name = $size['name'];
                                    }
                                }
                            }

                            if (isset($scheduleBooking->buy_rates[0]) && count($scheduleBooking->buy_rates) > 0) {
                                foreach ($scheduleBooking->buy_rates as $keySecond => $buy_rate) {
                                    if ($buy_rate->container_size_id == $size['id']) {
                                        $scheduleBooking->buy_rates[$keySecond]->container_size_name = $size['name'];
                                    }
                                }
                            }

                        }

                        if (!isset($scheduleBookings[$key]->size_name)) {
                            $scheduleBookings[$key]->size_name = '';
                        }

                        if (!isset($scheduleBookings[$key]->selling_size_name)) {
                            $scheduleBookings[$key]->selling_size_name = '';
                        }
                    }

                    $findShipment->schedules_group_bookings = json_encode($scheduleBookings);
                }

            }

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

                    if (!isset($value->cargo_ready_date)) {
                        $newFinalSuppliers[$key]->cargo_ready_date = '';
                    }
                }
            }

            $findShipment->suppliers_group_bookings = json_encode($newFinalSuppliers);
            //e

            //container merge
            $mergeContainers = (is_array(json_decode($findShipment->containers_group_bookings))) ? json_decode($findShipment->containers_group_bookings) : [];
            $mergeToContainerBookings = (is_array(json_decode($findShipment->containers_group))) ? json_decode($findShipment->containers_group) : [];

            $newContainers = [];

            foreach ($mergeContainers as $key => $value) {
                array_push($newContainers, $value->id);
            }

            foreach ($mergeToContainerBookings as $key => $value) {
                if (!in_array($value->id, $newContainers)) {
                    array_push($newContainers, $value->id);
                }
            }

            $newFinalContainers = [];

            foreach($newContainers as $n) {

                $setId = null;
                foreach ($mergeContainers as $key => $value) {
                   if ($n==$value->id) {
                        array_push($newFinalContainers, $value);
                        $setId = $value->id;
                   }
                }

                foreach ($mergeToContainerBookings as $key => $value) {
                    if ($n==$value->id && $setId!=$value->id) {
                        array_push($newFinalContainers, $value);
                   }
                }
            }

            $findShipment->containers_group_bookings = json_encode($newFinalContainers);

        }


        return $this->withMeta([
            'shipment' => $findShipment,
            'rateSizes' => $rateSizes,
            'types' => $types,
            'baseUrl' => url('/'),
            'shifl_ref' => $shifl_ref,
            'shiflOffices' => ShiflOffice::all(),
            'userEmail' =>  auth()->user()->email
        ]);
        } else {
            return null;
        }

    }

    public function storeDebitNote(callable $storageCallback)
    {
        $this->storageDebitNoteCallback = $storageCallback;
        return $this;
    }
}



