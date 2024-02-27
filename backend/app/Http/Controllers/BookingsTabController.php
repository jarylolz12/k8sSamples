<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use DateTime;
use Illuminate\Http\Request;

use App\Http\Resources\Standard as StandardResource;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Laravel\Nova\Nova;
use Validator;
use App\Shipment;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\File;

use Carbon\Carbon;

class BookingsTabController extends Controller
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

    public function selectSchedule(Request $request)
    {
        $response = ['status' => 'not ok'];

        if ($request->has('id')) {
            $model = Shipment::find($request->input('id'));
            if (isset($model->id)) {
                if ($request->has('schedules_group_bookings')) {
                    if ($request->input('schedules_group_bookings')!=null) {
                        $schedules_group_bookings = json_decode($request->input('schedules_group_bookings'));
                        if (isset($schedules_group_bookings[0]) && count($schedules_group_bookings)>0) {
                            $done = false;
                            foreach ($schedules_group_bookings as $k => $schedule) {
                                if ($schedule->is_confirmed==1 && !$done) {
                                    $date_create = date_create($schedule->eta);
                                    $date_format = date_format($date_create, 'Y-m-d');

                                    $schedules_group_bookings[$k]->eta = Carbon::createFromFormat('Y-m-d', $date_format)->format('Y-m-d H:i:s');

                                    $date_create = date_create($schedule->etd);
                                    $date_format = date_format($date_create, 'Y-m-d');

                                    $schedules_group_bookings[$k]->etd = Carbon::createFromFormat('Y-m-d', $date_format)->format('Y-m-d H:i:s');

                                    $model->etd = $schedule->etd;
                                    $model->eta = $schedule->eta;
                                    $model->booking_confirmed = 1;
                                    $model->shipment_status = 'Approved';
                                    $model->booking_confirmed_at = Carbon::now();
                                    $done = true;
                                }
                            }
                            $actionLogData = Nova::actionEvent()->forResourceUpdate(Auth::user(), $model)->toArray();
                            $actionLogData['original'] = json_encode($actionLogData['original']);
                            $actionLogData['changes'] = json_encode($actionLogData['changes']);
                            $actionLogData['name'] = 'Add Selected Schedule/Customer Approved';
                            $actionLogData['created_at'] = new DateTime;
                            $actionLogData['updated_at'] = new DateTime;
                            DB::connection()->table('action_events')->insert($actionLogData);

                            $model->schedules_group_bookings = json_encode($schedules_group_bookings);
                            $model->save();

                            //Send booking email only for internal shipments
                            if(!$model->is_tracking_shipment){
                                \App\Events\SendBookingEmailCustomerEvent::dispatch($model);
                            }

                            $response['status'] = 'ok';
                        } else {
                            $model->schedules_group_bookings = json_encode([]);
                        }
                    } else {
                        $model->schedules_group_bookings = json_encode([]);
                    }
                } else {
                    $model->schedules_group_bookings = json_encode([]);
                }
            }
        }

        return response()->json($response);
    }


    public function cancelSchedule(Request $request)
    {
        $response = ['status' => 'not ok'];

        if ($request->has('id')) {
            $model = Shipment::find($request->input('id'));
            if (isset($model->id)) {
                if ($request->has('schedules_group_bookings')) {
                    if ($request->input('schedules_group_bookings')!=null) {
                        $schedules_group_bookings = json_decode($request->input('schedules_group_bookings'));
                        if (isset($schedules_group_bookings[0]) && count($schedules_group_bookings)>0) {

                            $actionLogData = Nova::actionEvent()->forResourceUpdate(Auth::user(), $model)->toArray();
                            $actionLogData['original'] = json_encode($actionLogData['original']);
                            $actionLogData['changes'] = json_encode($actionLogData['changes']);
                            $actionLogData['name'] = 'Add UnSelected Schedule/Customer Approved';
                            $actionLogData['created_at'] = new DateTime;
                            $actionLogData['updated_at'] = new DateTime;
                            DB::connection()->table('action_events')->insert($actionLogData);

                            $model->schedules_group_bookings = json_encode($schedules_group_bookings);
                            $model->etd = null;
                            $model->eta = null;
                            $model->booking_confirmed = 0;
                            $model->shipment_status = 'Pending Approval';
                            $model->save();
                            $response['status'] = 'ok';
                        } else {
                            $model->schedules_group_bookings = json_encode([]);
                        }
                    } else {
                        $model->schedules_group_bookings = json_encode([]);
                    }
                } else {
                    $model->schedules_group_bookings = json_encode([]);
                }
            }
        }

        return response()->json($response);
    }

    public function selectAgentConfirmation(Request $request)
    {
        $response = ['status' => 'not ok'];

        if ($request->has('id')) {
            $model = Shipment::find($request->input('id'));
            if (isset($model->id)) {
                if ($request->has('schedules_group_bookings')) {
                    if ($request->input('schedules_group_bookings')!=null) {
                        $schedules_group_bookings = json_decode($request->input('schedules_group_bookings'));
                        if (isset($schedules_group_bookings[0]) && count($schedules_group_bookings)>0) {
                            $done = false;
                            foreach ($schedules_group_bookings as $k => $schedule) {
                                if ($schedule->is_agent_booking_confirm==1 && !$done) {
                                    $model->is_agent_booking_confirm = '1';
                                    $done = true;
                                }
                            }

                            $model->schedules_group_bookings = json_encode($schedules_group_bookings);
                            $model->save();

                            $response['status'] = 'ok';
                        } else {
                            $model->schedules_group_bookings = json_encode([]);
                        }
                    } else {
                        $model->schedules_group_bookings = json_encode([]);
                    }
                } else {
                    $model->schedules_group_bookings = json_encode([]);
                }
            }
        }

        return response()->json($response);
    }

    public function cancelAgentConfirmation(Request $request)
    {
        $response = ['status' => 'not ok'];

        if ($request->has('id')) {
            $model = Shipment::find($request->input('id'));
            if (isset($model->id)) {
                if ($request->has('schedules_group_bookings')) {
                    if ($request->input('schedules_group_bookings')!=null) {
                        $schedules_group_bookings = json_decode($request->input('schedules_group_bookings'));
                        if (isset($schedules_group_bookings[0]) && count($schedules_group_bookings)>0) {
                            $model->schedules_group_bookings = json_encode($schedules_group_bookings);
                            $model->is_agent_booking_confirm = '0';
                            $model->save();
                            $response['status'] = 'ok';
                        } else {
                            $model->schedules_group_bookings = json_encode([]);
                        }
                    } else {
                        $model->schedules_group_bookings = json_encode([]);
                    }
                } else {
                    $model->schedules_group_bookings = json_encode([]);
                }
            }
        }

        return response()->json($response);
    }

    public function save(Request $request)
    {
        $response = ['status' => 'not ok'];


        if ($request->has('id')) {
            $updateShipment = [];

            $model = Shipment::find($request->input('id'));
            if (isset($model->id)) {

                /*
                if ($request->has('mbl_copy_surrendered_remove')) {
                    $check_value = $request->input('mbl_copy_surrendered_remove');

                    if ( $check_value==1 && $model->mbl_copy_surrendered!=='' && $model->mbl_copy_surrendered!==null) {
                        if (Storage::exists('public/shipment/mbl_copy_surrendered/'.$model->mbl_copy_surrendered)) {
                            Storage::delete('public/shipment/mbl_copy_surrendered/'.$model->mbl_copy_surrendered);
                        }
                        $model->mbl_copy_surrendered = '';
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
                }*/


                if($request->has('multi_purchase_orders')){
                    $model->multi_purchase_orders = $request->input('multi_purchase_orders');
                }

                if ($request->has('schedules_group_bookings')) {
                    if ($request->input('schedules_group_bookings')!=null) {
                        $schedules_group_bookings = json_decode($request->input('schedules_group_bookings'));
                        if (isset($schedules_group_bookings[0]) && count($schedules_group_bookings)>0) {
                            $selected_schedule_key = 0;

                            $push_schedules = [];

                            foreach ($schedules_group_bookings as $k => $getSchedule) {
                                $item = $model;
                                //Begin Transaction
                                // \DB::Transaction();

                                $selectedSchedules = [];
                                array_push($selectedSchedules, $getSchedule->id);

                                $eta = '';
                                $etd = '';
                                if (isset($getSchedule->etd)) {
                                    $date_create = date_create($getSchedule->etd);
                                    $date_format = date_format($date_create, 'Y-m-d');
                                    $schedules_group_bookings[$k]->etd = Carbon::createFromFormat('Y-m-d', $date_format)->format('Y-m-d H:i:s');
                                    $etd = $schedules_group_bookings[$k]->etd;
                                }

                                if (isset($getSchedule->eta)) {
                                    $date_create = date_create($getSchedule->eta);
                                    $date_format = date_format($date_create, 'Y-m-d');

                                    $schedules_group_bookings[$k]->eta = Carbon::createFromFormat('Y-m-d', $date_format)->format('Y-m-d H:i:s');
                                    $eta = $schedules_group_bookings[$k]->eta;
                                }


                                if (isset($getSchedule->is_confirmed) && $getSchedule->is_confirmed==1) {
                                    $updateShipment['etd'] = $etd;
                                    $updateShipment['eta'] = $eta;
                                    //$model->etd = $etd;
                                    //$model->eta = $eta;
                                }


                                $check_buy_rates = (isset($getSchedule->buyrates)) ? $getSchedule->buyrates : [];

                                $check_sell_rates = (isset($getSchedule->sellrates)) ? $getSchedule->sellrates : [];
                                $check_legs = (isset($getSchedule->legs)) ? $getSchedule->legs : [];

                                $push_buyrates = [];
                                $push_sellrates = [];
                                $push_legs = [];
                                $selectedBuyRates = [];
                                $selectedSellRates = [];
                                $selectedLegs = [];

                                if (count($check_legs) > 0) {

                                    foreach($check_legs as $check_leg) {
                                        array_push($selectedLegs, $check_leg->id);

                                        if (isset($check_leg->schedule_id))
                                            $insert_leg['schedule_unique_id'] = $check_leg->schedule_id;

                                        if (isset($check_leg->mode)) {
                                            $insert_leg['mode'] = $check_leg->mode;
                                        }

                                        if (isset($check_leg->eta)) {
                                            $insert_leg['eta'] = $check_leg->eta;
                                        }

                                        if (isset($check_leg->location_to)) {
                                            $insert_leg['location_to'] = $check_leg->location_to;
                                        }

                                        $checkLeg = \DB::table('shipment_schedules_legs')
                                                                ->where('schedule_unique_id', $check_leg->schedule_id)
                                                                ->where('unique_id', $check_leg->id);

                                        if (isset($checkLeg->id)) {
                                            \DB::table('shipment_schedules_legs')
                                                ->where('unique_id', $check_leg->id)
                                                ->update($insert_leg);
                                        } else {
                                            array_push($push_legs, $insert_leg);
                                        }
                                    }

                                    if (count($selectedLegs) > 0) {
                                        //delete all not selected buy rates
                                        \DB::table('shipment_schedules_legs')
                                        ->whereNotIn('unique_id', $selectedLegs)
                                        ->where('shipment_id', $item->id)
                                        ->delete();
                                    }

                                    \DB::table('shipment_schedules_legs')->insert($push_legs);

                                }

                                if (count($check_sell_rates)>0) {
                                    foreach ($check_sell_rates as $check_sell_rate) {
                                        array_push($selectedSellRates, $check_sell_rate->id);
                                        //$insert_leg = [];
                                        $insert_sell_rate['schedule_unique_id'] = $getSchedule->id;

                                        if (isset($item->id)) {
                                            $insert_sell_rate['shipment_id'] = $item->id;
                                        }

                                        if (isset($check_sell_rate->service_id)) {
                                            $insert_sell_rate['service_id'] = $check_sell_rate->service_id;
                                        }

                                        if (isset($check_sell_rate->container_size_id)) {
                                            $insert_sell_rate['container_size_id'] = $check_sell_rate->service_id;
                                        }

                                        if (isset($check_sell_rate->amount)) {
                                            $insert_sell_rate['amount'] = $check_sell_rate->amount;
                                        }

                                        if (isset($check_sell_rate->qty)) {
                                            $insert_sell_rate['qty'] = $check_sell_rate->qty;
                                        }

                                        if (isset($check_sell_rate->valid_to)) {
                                            $insert_sell_rate['valid_to'] = $check_sell_rate->valid_to;
                                        }

                                        $checkSellRate = \DB::table('shipment_schedules_sellrates')
                                                                ->where('schedule_unique_id', $getSchedule->id)
                                                                ->where('unique_id', $check_sell_rate->id);

                                        if (isset($checkSellRate->schedule_id)) {
                                            \DB::table('shipment_schedules_sellrates')
                                                ->where('unique_id', $check_sell_rate->id)
                                                ->update($insert_sell_rate);
                                        } else {
                                            array_push($push_sell_rates, $insert_sell_rate);
                                        }
                                    }

                                    if (count($selectedSellRates) > 0) {
                                        //delete all not selected buy rates
                                        \DB::table('shipment_schedules_sellrates')
                                        ->whereNotIn('unique_id', $selectedSellRates)
                                        ->where('shipment_id', $item->id)
                                        ->delete();
                                    }

                                    \DB::table('shipment_schedules_sellrates')->insert($push_sell_rates);
                                }

                                if (count($check_buy_rates)>0) {
                                    foreach ($check_buy_rates as $check_buy_rate) {
                                        array_push($selectedBuyRates, $check_buy_rate->id);
                                        //$insert_leg = [];
                                        $insert_buy_rate['schedule_unique_id'] = $getSchedule->id;

                                        if (isset($item->id)) {
                                            $insert_buy_rate['shipment_id'] = $item->id;
                                        }

                                        if (isset($check_buy_rate->service_id)) {
                                            $insert_buy_rate['service_id'] = $check_buy_rate->service_id;
                                        }

                                        if (isset($check_buy_rate->container_size_id)) {
                                            $insert_buy_rate['container_size_id'] = $check_buy_rate->service_id;
                                        }

                                        if (isset($check_buy_rate->amount)) {
                                            $insert_buy_rate['amount'] = $check_buy_rate->amount;
                                        }

                                        if (isset($check_buy_rate->qty)) {
                                            $insert_buy_rate['qty'] = $check_buy_rate->amount;
                                        }

                                        if (isset($check_buy_rate->valid_to)) {
                                            $insert_buy_rate['valid_to'] = $check_buy_rate->valid_to;
                                        }

                                        //check if it is already in the schedule leg table
                                        $checkBuyRate = \DB::table('shipment_schedules_buyrates')
                                                        ->where('schedule_unique_id', $getSchedule->id)
                                                        ->where('unique_id', $check_buy_rate->id);

                                        if (isset($checkBuyRate->schedule_id)) {
                                            \DB::table('shipment_schedules_buyrates')
                                                ->where('unique_id', $check_buy_rate->id)
                                                ->update($insert_buy_rate);
                                        } else {
                                            array_push($push_buy_rates, $insert_buy_rate);
                                        }
                                    }

                                    if (count($selectedBuyRates) > 0) {
                                        //delete all not selected buy rates
                                        \DB::table('shipment_schedules_buyrates')
                                        ->whereNotIn('unique_id', $selectedBuyRates)
                                        ->where('shipment_id', $item->id)
                                        ->delete();
                                    }

                                    \DB::table('shipment_schedules_buyrates')->insert($push_buy_rates);
                                }

                            }

                            //delete all non selected schedules
                            /*
                            \DB::table('shipment_schedules')
                            ->whereNotIn('unique_id', $selectedSchedules)
                            ->where('shipment_id', $item->id)
                            ->delete();*/

                            $updateShipment['schedules_group_bookings'] = json_encode($schedules_group_bookings);
                            $model->schedules_group_bookings = json_encode($schedules_group_bookings);
                        } else {

                            if (!is_array($schedules_group_bookings) || (is_array($schedules_group_bookings) && count($schedules_group_bookings)==0)) {
                                $item = $model;

                                //delete all associated buy rates
                                \DB::table('shipment_schedules_buyrates')
                                    ->where('shipment_id', $item->id)
                                    ->delete();

                                //delete all associated sell rates
                                \DB::table('shipment_schedules_sellrates')
                                    ->where('shipment_id', $item->id)
                                    ->delete();

                                //delete all the schedule legs
                                \DB::table('shipment_schedules_legs')
                                    ->where('shipment_id', $item->id)
                                    ->delete();

                                //delete all the schedules
                                \DB::table('shipment_schedules')
                                    ->where('shipment_id', $item->id)
                                    ->delete();


                                //if empty delete all the schedules
                                $model->schedules_group_bookings = json_encode([]);
                                $model->schedules_group = json_encode([]);

                                $model->etd = null;
                                $model->eta = null;
                                $model->booking_confirmed = 0;
                                $model->shipment_status = 'Pending Approval';
                                $model->save();
                            }



                        }
                    }
                }

                if ($request->has('memo_customer')) {
                    $updateShipment['memo_customer'] = $request->input('memo_customer');
                    $model->memo_customer = $request->input('memo_customer');
                }

                if ($request->has('type')) {
                    $updateShipment['type'] = $request->input('type');
                    $model->type = $request->input('type');
                }

                if ($request->has('vessel')) {
                    $updateShipment['vessel'] = $request->input('vessel');
                    $model->vessel = $request->input('vessel');
                }

                if ($request->has('voyage_number')) {
                    $updateShipment['voyage_number'] = $request->input('voyage_number');
                    $model->voyage_number            = $request->input('voyage_number');
                }

                if ($request->has('booking_number')) {
                    $updateShipment['booking_number'] = $request->input('booking_number');
                    $model->booking_number            = $request->input('booking_number');
                }

                if ($request->has('mbl_num')) {
                    $updateShipment['mbl_num'] = $request->input('mbl_num');
                    $model->mbl_num = $request->input('mbl_num');
                }

                if ($request->has('mbl_shipper')) {
                    $updateShipment['mbl_shipper'] = $request->input('mbl_shipper');
                    $model->mbl_shipper = $request->input('mbl_shipper');
                }
                if ($request->has('mbl_shipper_address')) {
                    $updateShipment['mbl_shipper_address'] = $request->input('mbl_shipper_address');
                    $model->mbl_shipper_address = $request->input('mbl_shipper_address');
                }
                if ($request->has('mbl_shipper_phone_number')) {
                    $updateShipment['mbl_shipper_phone_number'] = $request->input('mbl_shipper_phone_number');
                    $model->mbl_shipper_phone_number = $request->input('mbl_shipper_phone_number');
                }

                if ($request->has('mbl_consignee')) {
                    $updateShipment['mbl_consignee'] = $request->input('mbl_consignee');
                    $model->mbl_consignee = $request->input('mbl_consignee');
                }
                if ($request->has('mbl_consignee_address')) {
                    $updateShipment['mbl_consignee_address'] = $request->input('mbl_consignee_address');
                    $model->mbl_consignee_address = $request->input('mbl_consignee_address');
                }
                if ($request->has('mbl_consignee_phone_number')) {
                    $updateShipment['mbl_consignee_phone_number'] = $request->input('mbl_consignee_phone_number');
                    $model->mbl_consignee_phone_number = $request->input('mbl_consignee_phone_number');
                }

                if ($request->has('mbl_notify')) {
                    $updateShipment['mbl_notify'] = $request->input('mbl_notify');
                    $model->mbl_notify = $request->input('mbl_notify');
                }
                if ($request->has('mbl_notify_address')) {
                    $updateShipment['mbl_notify_address'] = $request->input('mbl_notify_address');
                    $model->mbl_notify_address = $request->input('mbl_notify_address');
                }
                if ($request->has('mbl_notify_phone_number')) {
                    $updateShipment['mbl_notify_phone_number'] = $request->input('mbl_notify_phone_number');
                    $model->mbl_notify_phone_number = $request->input('mbl_notify_phone_number');
                }
                if ($request->has('mbl_description')) {
                    $updateShipment['mbl_description'] = $request->input('mbl_description');
                    $model->mbl_description = $request->input('mbl_description');
                }
                if ($request->has('mbl_marks')) {
                    $updateShipment['mbl_marks'] = $request->input('mbl_marks');
                    $model->mbl_marks = $request->input('mbl_marks');
                }

                if ($request->has('containers_group_bookings')) {
                    if ($request->input('containers_group_bookings')!=null) {
                        $containers_group = json_decode($request->input('containers_group_bookings'));
                        //$model->suppliers_group = $request->input('suppliers_group');

                        if (count($containers_group)>0) {
                            $getContainers = $containers_group;
                            $selectedContainers = [];
                            $setContainers = [];
                            $item = $model;

                            foreach ($getContainers as $key => $getContainer) {
                                array_push($selectedContainers, $getContainer->id);

                                $checkContainer = \DB::table('containers')
                                        ->where('unique_id', $getContainer->id)
                                        ->where('shipment_id', $item->id)
                                        ->first();

                                if (!isset($checkContainer->id)) {
                                    array_push($setContainers, ['shipment_id' => $item->id,'unique_id' => $getContainer->id,'size' => intval($getContainer->size),'cbm' => intval($getContainer->cbm),'kg' => intval($getContainer->kg),'cartons' => intval($getContainer->cartons),'container_num' => $getContainer->container_num, 'seal_num' => isset($getContainer->seal_num) ? $getContainer->seal_num : null]);
                                } else {
                                    \DB::table('containers')
                                    ->where('id', $checkContainer->id)
                                    ->update(['shipment_id' => $item->id,'size' => intval($getContainer->size),'cbm' => $getContainer->cbm != "" ? intval($getContainer->cbm) : 0,'kg' => intval($getContainer->kg),'cartons' => intval($getContainer->cartons),'container_num' => $getContainer->container_num, 'seal_num' => isset($getContainer->seal_num) ? $getContainer->seal_num : null]);
                                }
                            }

                            if (count($setContainers)>0) {
                                \DB::table('containers')->insert($setContainers);
                            }

                            if (count($selectedContainers) > 0) {
                                \DB::table('containers')
                                ->whereNotIn('unique_id', $selectedContainers)
                                ->where('shipment_id', $item->id)
                                ->delete();
                            }
                            $updateShipment['containers_group'] = json_encode($containers_group);
                            $updateShipment['containers_group_bookings'] = json_encode($containers_group);
                            $model->containers_group = json_encode($containers_group);
                            $model->containers_group_bookings = json_encode($containers_group);
                        } else {
                            \DB::table('containers')
                                    ->where('shipment_id', $model->id)
                                    ->delete();

                            $updateShipment['containers_group'] = json_encode([]);
                            $updateShipment['containers_group_bookings'] = json_encode([]);

                            $model->containers_group = json_encode([]);
                            $model->containers_group_bookings = json_encode([]);
                        }
                    } else {
                        $updateShipment['containers_group'] = json_encode([]);
                        $updateShipment['containers_group_bookings'] = json_encode([]);
                        $model->containers_group = json_encode([]);
                        $model->containers_group_bookings = json_encode([]);
                    }
                } else {
                    //$model->containers_group = json_encode([]);
                    //$model->containers_group_bookings = json_encode([]);
                }

                if ($request->has('suppliers_group_bookings')) {
                    if ($request->input('suppliers_group_bookings')!=null) {
                        $suppliers_group = json_decode($request->input('suppliers_group_bookings'));
                        //$model->suppliers_group = $request->input('suppliers_group');

                        if (count($suppliers_group)>0) {
                            foreach ($suppliers_group as $key => $supplier) {
                                $copyKey = intval($key + 1);

                                if ($request->has('hbl_copy_'.$copyKey) && $request->file('hbl_copy_'.$copyKey)!=null) {
                                    $originalName = 'shipment/hbl_copy/'. basename($request->file('hbl_copy_'.$copyKey)->getClientOriginalName(), '.'. $request->file('hbl_copy_'.$copyKey)->guessExtension()) . '_'.$model->id.'_'.$copyKey.'_hbl.' .$request->file('hbl_copy_'.$copyKey)->guessExtension();

                                    // $originalName = 'shipment/packing_list/'. $request->file('packing_list_'.$copyKey)->getClientOriginalName();

                                    Storage::disk('local')->putFileAs('/public', $request->file('hbl_copy_'.$copyKey), $originalName);
                                    //  $get_path = $request->file('hbl_copy_'.$copyKey)->store('/', 'public');
                                    $suppliers_group[$key]->hbl_copy = $originalName;
                                }

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

                                if (isset($supplier->containers) && is_array($supplier->containers)) {
                                    if (count($supplier->containers) > 0) {
                                        $item = $model;
                                        $check_containers = (isset($supplier->containers)) ? $supplier->containers : [];

                                        $push_suppliers_containers = [];
                                        $selectedContainers = [];

                                        if (count($check_containers)>0) {
                                            foreach ($check_containers as $check_container) {
                                                $insert_suppliers_containers = [
                                                    'supplier_unique_id' => $supplier->id,
                                                    'unique_id' => $check_container->id
                                                ];

                                                array_push($selectedContainers, $check_container->id);
                                                if (isset($item->id)) {
                                                    $insert_suppliers_containers['shipment_id'] = $item->id;
                                                }

                                                if (isset($check_container->container_id)) {
                                                    $insert_suppliers_containers['container_id'] = intval($check_container->container_id);
                                                }

                                                if (isset($check_container->weight)) {
                                                    $insert_suppliers_containers['weight'] = $check_container->weight;
                                                }

                                                if (isset($check_container->size)) {
                                                    $insert_suppliers_containers['size'] = $check_container->size;
                                                }

                                                if (isset($check_container->cartons)) {
                                                    $insert_suppliers_containers['cartons'] = $check_container->cartons;
                                                }

                                                $checkSupplierContainer = \DB::table('shipment_suppliers_containers')
                                                                //->where('schedule_id', $getSchedule->id)
                                                                ->where('supplier_unique_id', $supplier->id)
                                                                ->where('unique_id', $check_container->id);

                                                if (isset($checkSupplierContainer->supplier_unique_id)) {
                                                    \DB::table('shipment_suppliers_containers')
                                                        ->where('unique_id', $check_container->id)
                                                        ->update($insert_suppliers_containers);
                                                } else {
                                                    array_push($push_suppliers_containers, $insert_suppliers_containers);
                                                }
                                            }


                                            if (count($selectedContainers) > 0) {
                                                //delete all not selected buy rates
                                                \DB::table('shipment_suppliers_containers')
                                                ->whereNotIn('unique_id', $selectedContainers)
                                                ->where('shipment_id', $item->id)
                                                ->delete();
                                            }

                                            \DB::table('shipment_suppliers_containers')->insert($push_suppliers_containers);
                                        }
                                    }
                                }

                                $suppliers_group[$key]->supplier_id = (isset($supplier->supplier_id)) ? $supplier->supplier_id : null;

                                $shipmentMetaData = [];

                                if (!empty($supplier->supplier_id)) {
                                    $shipmentMetaData = ['shipper_id' => $supplier->supplier_id];
                                } else if (!empty($supplier->buyer_id)) {
                                    $shipmentMetaData = ['consignee_id' => $supplier->buyer_id];
                                }

                                \DB::table('shipment_metas')->where('shipment_id', $model->id)
                                    ->update($shipmentMetaData);
                            }

                            $updateShipment['suppliers_group'] = json_encode($suppliers_group);
                            $updateShipment['suppliers_group_bookings'] = json_encode($suppliers_group);

                            $model->suppliers_group = json_encode($suppliers_group);
                            $model->suppliers_group_bookings = json_encode($suppliers_group);
                        } else {
                            $updateShipment['suppliers_group'] = json_encode([]);
                            $updateShipment['suppliers_group_bookings'] = json_encode([]);

                            $model->suppliers_group = json_encode([]);
                            $model->suppliers_group_bookings = json_encode([]);
                        }
                    } else {
                        $updateShipment['suppliers_group'] = json_encode([]);
                        $updateShipment['suppliers_group_bookings'] = json_encode([]);
                        $model->suppliers_group = json_encode([]);
                        $model->suppliers_group_bookings = json_encode([]);
                    }
                } else {
                    $updateShipment['suppliers_group'] = json_encode([]);
                    $updateShipment['suppliers_group_bookings'] = json_encode([]);
                    $model->suppliers_group = json_encode([]);
                    $model->suppliers_group_bookings = json_encode([]);
                }


                if ($request->has('debit_note') && $request->file('debit_note')!=null) {
                    $originalName = 'shipment/debit_note/'.  basename($request->file('debit_note')->getClientOriginalName(), '.'. $request->file('debit_note')->guessExtension()) . '_'.$model->id.'_debit.' .$request->file('debit_note')->guessExtension();

                    Storage::disk('local')->putFileAs('/public', $request->file('debit_note'), $originalName);

                    $updateShipment['debit_note'] = $originalName;
                    $model->debit_note = $originalName;
                }

                if ($request->has('mbl_copy') && $request->file('mbl_copy')!=null) {
                    $originalName = 'shipment/mbl_copy/'. basename($request->file('mbl_copy')->getClientOriginalName(), '.'. $request->file('mbl_copy')->guessExtension()) . '_'.$model->id.'_mbl.' .$request->file('mbl_copy')->guessExtension();

                    Storage::disk('local')->putFileAs('/public', $request->file('mbl_copy'), $originalName);
                    $updateShipment['mbl_copy'] = $originalName;
                    $model->mbl_copy = $originalName;
                }

                if ($request->has('mbl_copy_surrendered') && $request->file('mbl_copy_surrendered')!=null) {

                    $final_display_name = '';
                    if ($request->has('mbl_copy_surrendered')) {
                        if (!is_null($file = $request->file('mbl_copy_surrendered')) && $file->isValid()) {
                            $hash_file = md5($request->file('mbl_copy_surrendered')->getClientOriginalName() . now());
                            $final_display_name = $hash_file . '.' . $request->file('mbl_copy_surrendered')->guessExtension();
                            $final_name = 'shipment/mbl_copy_surrendered/'.$final_display_name;
                            Storage::disk('local')->putFileAs('/public', $request->file('mbl_copy_surrendered'), $final_name);
                        }
                    }
                    $updateShipment['mbl_copy_surrendered'] = $final_display_name;
                    $updateShipment['mbl_released_confirmed'] = ($final_display_name=='' || $final_display_name==null) ? 0 : 1;

                    $model->mbl_copy_surrendered = $final_display_name;
                }


                // \DB::table('shipments')
                //     ->where('id', $model->id)
                //     ->update($updateShipment);

                $model->update($updateShipment);

                //update tracking info
                if ( method_exists($model, 'getStatusV2')) {
                    $status = $model->getStatusV2();
                    $this->updateTrackingInfo($model, $status);

                }

                //$model->save();
                $response['status'] = 'ok';
            }
        }

        return response()
            ->json($response);
    }

    private function ifNull($firstValue, $secondValue, $nullValue = null){
        return (empty($firstValue) ? (empty($secondValue) ? $nullValue : $secondValue) : $firstValue);
    }

    private function updateTrackingInfo($getShipment, $status) {

        $tracking_eta = null;
        $tracking_etd = null;

        if(!empty($getShipment->mbl_num) || isset($getShipment->terminal_fortynine)){
            $terminal49_shipment = $getShipment->terminal_fortynine;
            if ( isset($terminal49_shipment) && isset ($terminal49_shipment->attributes) ) {
                $attributes = json_decode($terminal49_shipment->attributes, true);
                $tracking_eta = $this->ifNull($attributes['pod_eta_at'],$attributes['pod_ata_at']);
                $tracking_etd = $this->ifNull($attributes['pol_etd_at'],$attributes['pol_atd_at']);
            }
        }

        \DB::table('shipments')->where('id', $getShipment->id)
        ->update([
            'status_fallback' => $status,
            'tracking_eta' => $tracking_eta,
            'tracking_etd' => $tracking_etd,
            'is_processing' => 1,
            'is_tracking_processing' => 1
        ]);
    }
}
