<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Terminal;
use App\Http\Resources\Standard as StandardResource;
use Illuminate\Support\Collection;
use Validator;
use App\Shipment;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\File;

class DepartureNoticeController extends Controller
{

    private function storeDebitNote(callable $storageCallback)
    {
        $this->storageDebitNoteCallback = $storageCallback;
        return $this;
    }

    private function storeMblCopy(callable $storageCallback)
    {
        $this->storageMblCopyCallback = $storageCallback;
        return $this;
    }

    public function save(Request $request)
    {

        $response = ['status' => 'not ok'];
        if ($request->has('id')) {
            $model = Shipment::find($request->input('id'));

            if (isset($model->id)) {
                //s

                if ($request->has('memo_customer')) {
                    $model->memo_customer = $request->input('memo_customer');
                }
                
                //schedules group
                if ($request->has('schedules_group')) {
                    if ($request->input('schedules_group')!=null) {
                        $schedules_group = json_decode($request->input('schedules_group'));
                        if (count($schedules_group)>0) {
                            foreach ($schedules_group as $key => $schedule) {
                                if ($key==0) {
                                    $model->etd = (isset($schedule->etd) && $schedule->etd!='') ? $schedule->etd : null;
                                    $model->eta = (isset($schedule->eta) && $schedule->eta!='') ? $schedule->eta : null;
                                }
                            }
                            $model->schedules_group = json_encode($schedules_group);
                            $model->schedules_group_bookings = json_encode($schedules_group);

                        }else {
                            $model->schedules_group = json_encode([]);
                            $model->schedules_group_bookings = json_encode([]);
                        }
                    }else {
                        $model->schedules_group = json_encode([]);
                        $model->schedules_group_bookings = json_encode([]);
                    }
                }else {
                    $model->schedules_group = json_encode([]);
                    $model->schedules_group_bookings = json_encode([]);
                }

                //containers group
                if($request->has('containers_group')) {
                    if($request->input('containers_group')!=null) {
                        $containers_group = json_decode($request->input('containers_group'));
                        if(count($containers_group)>0) {
                          $model->containers_group_bookings = json_encode($containers_group);
                          $model->containers_group = json_encode($containers_group);

                        }else {
                          $model->containers_group_bookings = json_encode([]);
                          $model->containers_group = json_encode([]);
                        }
                    }else {
                        $model->containers_group_bookings = json_encode([]);
                        $model->containers_group = json_encode([]);
                    }

                }else {
                    $model->containers_group_bookings = json_encode([]);
                    $model->containers_group = json_encode([]);
                }

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
                            $model->suppliers_group_bookings = json_encode($suppliers_group);
                            $model->suppliers_group = json_encode($suppliers_group);
                        } else {
                            $model->suppliers_group_bookings = json_encode([]);
                            $model->suppliers_group = json_encode([]);
                        }
                    } else {
                        $model->suppliers_group_bookings = json_encode([]);
                        $model->suppliers_group = json_encode([]);
                    }
                } else {
                    $model->suppliers_group_bookings = json_encode([]);
                    $model->suppliers_group = json_encode([]);
                }
                //e

                if ($request->has('carrier')) {
                    if ($request->carrier!='') {
                        $model->carrier_id = $request->carrier;
                    }
                }

                if ($request->has('booking_confirmed')) {
                    if ($request->booking_confirmed!='') {
                        $model->booking_confirmed = $request->booking_confirmed;
                    }
                }

                if ($request->has('vessel')) {
                    if ($request->vessel!='') {
                        $model->vessel = $request->vessel;
                    }
                }

                if ($request->has('type')) {
                    if ($request->type!='') {
                        $model->type = $request->type;
                    }
                }

                if ($request->has('mbl_num')) {
                    if ($request->mbl_num!='') {
                        $model->mbl_num = $request->mbl_num;
                    }
                }

                //mbl copy
                if ($request->has('mbl_copy')) {
                    if (!is_null($file = $request->file('mbl_copy')) && $file->isValid()) {
                        $resultMblCopy = call_user_func(
                            function (Request $request, $model) {
                                $originalName = 'shipment/mbl_copy/'. basename($request->file('mbl_copy')->getClientOriginalName(), '.'. $request->file('mbl_copy')->guessExtension()) . '_'.$request->input('id').'_mbl.' .$request->file('mbl_copy')->guessExtension();
                                Storage::disk('local')->putFileAs('/public', $request->file('mbl_copy'), $originalName);
                                return [
                                    'mbl_copy' => $originalName
                                ];
                            },
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
                            function (Request $request, $model) {
                                $originalName = 'shipment/debit_note/'.  basename($request->file('debit_note')->getClientOriginalName(), '.'. $request->file('debit_note')->guessExtension()) . '_'.$request->input('id').'_debit.' .$request->file('debit_note')->guessExtension();
                                Storage::disk('local')->putFileAs('/public', $request->file('debit_note'), $originalName);
                                return [
                                'debit_note' => $originalName
                                ];
                            },
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
                $model->save();
                $response['status'] = 'ok';
                //e
            }
        }

        return response()
               ->json($response);
    }


    public function search(Request $request)
    {
        /*

        $response = ['status' => 'not ok','results' => null];

        if ($request->has('search')) {
            $response['results'] = Terminal::where('name','LIKE','%'.$request->input('search').'%')
                                  ->get();

            if (count($response['results'])>0) {
                $newResults = [];
                foreach ($response['results'] as $key => $result) {
                    array_push($newResults, [
                        'label' => $result->name,
                        'value' => $result->id
                    ]);
                }

                if (is_null($request->per_page)) {
                    $response['results'] = StandardResource::collection((new Collection($newResults)));
                }
                if (is_numeric($request->per_page)) {
                    $response['results'] = StandardResource::collection((new Collection($newResults))->paginate($request->per_page));
                }
            }

            $response['status'] = 'ok';
        }

        return response()->json($response);*/
    }
}
