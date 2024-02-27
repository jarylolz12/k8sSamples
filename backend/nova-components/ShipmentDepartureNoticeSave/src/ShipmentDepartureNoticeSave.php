<?php

namespace Kenetashi\ShipmentDepartureNoticeSave;

use Laravel\Nova\Fields\Field;
use Laravel\Nova\Fields\TextArea;
use App\Shipment;
use App\TerminalRegion;
use Kenetashi\ShipmentScheduleGroup\ShipmentScheduleGroup;
use App\Http\Resources\Standard as StandardResource;
use Illuminate\Support\Collection;
use App\Rules\EstimatedRule;
//use App\Carrier;
//use App\ContainerSize;
use Laravel\Nova\Http\Requests\NovaRequest;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\File;
use Carbon\Carbon;
//use App\Service;

//use App\Supplier;
//use App\Incoterm;
//use App\PurchaseOrder;
//use App\ContainerSize;
class ShipmentDepartureNoticeSave extends Field
{
    /**
     * The field's component.
     *
     * @var string
     */
    public $component = 'shipment-departure-notice-save';

    protected function fillAttributeFromRequest(
        NovaRequest $request,
        $requestAttribute,
        $model,
        $attribute
    ) {

        if ($request->has('memo_customer')) {
            $model->memo_customer = $request->input('memo_customer');
        }

        if ($request->has('mbl_copy_surrendered_remove')) {
            $check_value = $request->input('mbl_copy_surrendered_remove');

            if ( $check_value==1 && $model->mbl_copy_surrendered!=='' && $model->mbl_copy_surrendered!==null) {

                if (Storage::exists('public/shipment/mbl_copy_surrendered/'.$model->mbl_copy_surrendered)) {
                    Storage::delete('public/shipment/mbl_copy_surrendered/'.$model->mbl_copy_surrendered);
                    $model->mbl_copy_surrendered = '';
                }
            }
        }

        if ($request->has('mbl_copy_surrendered_file')) {
            if (!is_null($file = $request->file('mbl_copy_surrendered_file')) && $file->isValid()) {
                $hash_file = md5($request->input('mbl_copy_surrendered_name') . now());
                $final_display_name = $hash_file . '.' . $request->file('mbl_copy_surrendered_file')->guessExtension();
                $final_name = 'shipment/mbl_copy_surrendered/'.$final_display_name;
                Storage::disk('local')->putFileAs('/public', $request->file('mbl_copy_surrendered_file'), $final_name);
                $model->mbl_copy_surrendered = $final_display_name;
            }
        }

        //containers group
        if($request->has('containers_group')) {
            if($request->input('containers_group')!=null) {
                $containers_group = json_decode($request->input('containers_group'));
                if(is_array($containers_group) && count($containers_group)>0) {
                  $model->containers_group = json_encode($containers_group);

                }else {
                  $model->containers_group = json_encode([]);
                }
            }else {
                $model->containers_group = json_encode([]);
            }
        }else {
            $model->containers_group = json_encode([]);
        }
        //schedules group
        if ($request->has('schedules_group')) {
            if ($request->input('schedules_group')!=null) {
                $schedules_group = json_decode($request->input('schedules_group'));

                if (is_array($schedules_group) && count($schedules_group)>0) {
               // if (isset($schedules_group[0]) && count($schedules_group)>0) {

                    foreach($schedules_group as $key => $schedule_group) {
                       // $schedules_group[$key]->eta = Carbon::parse($schedule_group->eta)->format('MM/DD/YYYY');
                        //$schedules_group[$key]->etd = Carbon::parse($schedule_group->etd)->format('MM/DD/YYYY');

                        $schedules_group[$key]->eta = ($schedules_group->eta!==null && $schedules_group->eta!=='') ? Carbon::createFromFormat('Y-m-d',$schedules_group->eta)->format('Y-m-d H:i:s') : null;
                        $schedules_group[$key]->etd = ($schedules_group->etd!==null && $schedules_group->etd!=='') ? Carbon::createFromFormat('Y-m-d',$schedules_group->etd)->format('Y-m-d H:i:s') : null;
                    }

                    $model->schedules_group = json_encode($schedules_group);

                } else {
                    $model->schedules_group = json_encode([]);
                }
            }

        } else {
            $model->schedules_group = json_encode([]);
        }

        //schedules group
        /*
        if($request->has('schedules_group')) {
            if($request->input('schedules_group')!=null) {
                $schedules_group = json_decode($request->input('schedules_group'));
                if(count($schedules_group)>0) {
                  foreach($schedules_group as $key => $schedule) {
                    if($key==0) {
                      $model->etd = (isset($schedule->etd) && $schedule->etd!='') ? $schedule->etd : null;
                      $model->eta = (isset($schedule->eta) && $schedule->eta!='') ? $schedule->eta : null;
                    }
                  }
                  $model->schedules_group = json_encode($schedules_group);

                }else {
                  $model->schedules_group = json_encode([]);
                }
            }else {
                $model->schedules_group = json_encode([]);
            }

        }else {
            $model->schedules_group = json_encode([]);
        }*/

        //suppliers group
        //s
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
        //e

        if ($request->has('carrier')) {
            if ($request->carrier!='')
               $model->carrier_id = $request->carrier;
        }

        if ($request->has('booking_confirmed')) {
            if ($request->booking_confirmed!='')
               $model->booking_confirmed = $request->booking_confirmed;
        }

        if ($request->has('vessel')) {
            if ($request->vessel!='')
               $model->vessel = $request->vessel;
        }

        if ($request->has('type')) {
            if ($request->type!='')
               $model->type = $request->type;
        }


        if ($request->has('mbl_num')) {
            if ($request->mbl_num!='')
               $model->mbl_num = $request->mbl_num;
        }

        //mbl copy
        if ($request->has('mbl_copy')) {
            if (!is_null($file = $request->file('mbl_copy')) && $file->isValid()) {
                $resultMblCopy = call_user_func(
                    $this->storageMblCopyCallback,
                    $request,
                    $model,
                    'mbl_copy',
                    'mbl_copy'
                );

                if ($request->exists('mbl_copy')) {
                    $model->mbl_copy = $resultMblCopy['mbl_copy'];
                }
            }

        }

        //debit note
        if ($request->has('debit_note')) {
            if (!is_null($file = $request->file('debit_note')) && $file->isValid()) {
                $resultDebitNote = call_user_func(
                $this->storageDebitNoteCallback,
                $request,
                $model,
                'debit_note',
                'debit_note'
                );

                if ($request->exists('debit_note')) {
                    $model->debit_note = $resultDebitNote['debit_note'];
                }
            }

        }

    }

    private function ifNull($firstValue, $secondValue, $nullValue = null){
        return (empty($firstValue) ? (empty($secondValue) ? $nullValue : $secondValue) : $firstValue);
    }

    public function initFields($id,$etalogs=[],$terminal_etalogs = []) {

        $r = new NovaRequest();
        $types = [
            'LCL' => 'LCL',
            'FCL' => 'FCL',
            'LTL' => 'LTL',
            'FTL' => 'FTL',
            'Air' => 'Air'
        ];

        if (!$r->isResourceIndexRequest()) {

            $findShipment = ($id!=null) ? Shipment::find($id) : [];
            $newTerminalRegions = [];
            $newServices = [];
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

            if (isset($findShipment->id)) {

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

                    }
                }

                //schedule merge
                $mergeScheduleBookings = $scheduleBookings;

                $finalMergeScheduleBookings = [];
                //$hasConfirmed = false;
                $hasBookingsRecord = false;
                if (count($mergeScheduleBookings) > 0) {
                    $hasBookingsRecord = true;
                }

                if ($hasBookingsRecord) {
                    $findShipment->schedules_group = $mergeScheduleBookings;
                } else {
                    $findShipment->schedules_group = (is_array(json_decode($findShipment->schedules_group))) ? json_decode($findShipment->schedules_group) : [];
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
                        if (!isset($value->containers)) {
                            $newFinalSuppliers[$key]->containers = [];
                        }

                        if (!isset($value->cargo_ready_date)) {
                            $newFinalSuppliers[$key]->cargo_ready_date = '';
                        }
                    }
                }

                $findShipment->suppliers_group = json_encode($newFinalSuppliers);

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
                $findShipment->containers_group = json_encode($newFinalContainers);

            }

            return $this->withMeta([
                'shipment' => $findShipment,
                'types'   => $types,
                'rateSizes' => $rateSizes,
                'baseUrl' => url('/'),
                'etalogs' => $etalogs,
                'terminal_etalogs' => $terminal_etalogs
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

    public function storeMblCopy(callable $storageCallback)
    {
        $this->storageMblCopyCallback = $storageCallback;
        return $this;
    }
}
