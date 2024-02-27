<?php

namespace Tanvirofficials\UntrackedShipments\Http\Controllers;

use Illuminate\Routing\Controller;
use Illuminate\Http\Request;
use App\Shipment;
use Carbon\Carbon;
use App\Terminal49Shipment;
use App\ShiflOffice;
use App\Terminal;
use App\User;

ini_set('memory_limit', '512M');

class ShipmentController extends Controller
{
    public function index(Request $request)
    {
        // $rail_shipments_ids = $this->getRailShipmentsIds();
        // $rail_shipments_ids = [];
        $shipment_query = Shipment::where('booking_confirmed', 1)
            ->where(function ($query) {
                $query
                      // ->whereNotIn('mbl_num', Terminal49Shipment::whereNotIn('mbl_num',$demmurage_shipments_mbl)->get()->pluck('mbl_num'))
                      ->where('should_manually_track',1)
                      ->orWhere('mbl_num', null)
                      ->orWhere('mbl_num', '')
                      ->orWhere('type', 'LCL');
                      // ->orWhereIn('id', $rail_shipments_ids);
            })
            ->where('not_tracking_manually' , 0)
            ->where('cancelled', 0)
            // ->whereNotNull('mbl_num')
            // ->where('mbl_num','!=','')
            // ->where(function($q){
            //   $q->where('etd', '<', now()->addWeek())
            //     ->orWhere('is_tracking_shipment', true);
            //
            // })
            // ->limit(100)
            ->orderBy($request->sortby, $request->sorttype);


        if ($request->filter) {
            if ($request->filter == 'tracked') {
                $shipment_query->where('tracked_up_to', '>=', now());
            } else if($request->filter == 'untracked') {
                $shipment_query->where(function ($query) {
                    $query->where('tracked_up_to', '<', now())->orWhere('tracked_up_to', null);
                });
            }
        }
        // apply mbl filter
        if($request->filterMBL){
            if($request->filterMBL == 'yes'){
                $shipment_query->where('mbl_num', '!=', '')->where('mbl_num', '!=', null);
            }else{
                $shipment_query->where(function($q){
                    $q->where('mbl_num', '')
                      ->orWhere('mbl_num', null);
                });
            }
        }

        // apply office from filter
        if($request->filterOfficeFrom){
            $shipment_query->where('shifl_office_origin_from_id',$request->filterOfficeFrom);
        }

        // apply external internal filter
        if($request->filterExternalInternal){
            $shipment_query->where('is_tracking_shipment', $request->filterExternalInternal == 'external');
        }

        $shipments = $shipment_query->with('customer')->get();
        $filtered_shipments = [];
        foreach ($shipments as $key => $shipment) {
            $type = $shipment->getType();
            $containers_group_untracked = json_decode($shipment->containers_group_untracked ?? '[]', true);
            if (isset($containers_group_untracked['containers']) && count($containers_group_untracked['containers'])) {
                $full_out_count = 0;
                $empty_in_count = 0;
                foreach ($containers_group_untracked['containers'] as $container) {
                    // code...
                    if($type == 'FCL'){
                        if (isset($container['pod_full_out_at']) && !empty($container['pod_full_out_at'])) {
                            $full_out_count++;
                        }
                        if (isset($container['pod_empty_returned_at']) && !empty($container['pod_empty_returned_at'])){
                            $empty_in_count++;
                        }
                    }
                    // else if ($shipment->gettype() == 'LCL' && )
                }
                if($full_out_count == 0 && $empty_in_count == count($containers_group_untracked['containers'])) continue;

                if ($full_out_count && $full_out_count == count($containers_group_untracked['containers'])) {
                    $earlierDate = $this->getEarlierDate($shipment->containers_group_untracked,"pod_full_out_at");
                    if($earlierDate && now()->gt($earlierDate->addWeeks(2))){
                        continue;
                    }
                }
            }
            if ($type != 'FCL' && isset($containers_group_untracked['picked_up']) && !empty($containers_group_untracked['picked_up'])) {
                continue;
            }
            $shipment->type = $type;
            $shipment->isInDemurrage = $shipment->lfd_ignore_demurrage;
            $filtered_shipments[] = $shipment;
        }

        if( $request->sortby == 'mbl_num' ){
            return $this->sortMbl($this->applyFilters($filtered_shipments, $request),$request->sorttype);
        }else{
            if( $request->booted == 1 ){
                return $this->sortMbl($this->applyFilters($filtered_shipments, $request),'asc');
            }else{
                return $this->applyFilters($filtered_shipments, $request);
            }
        }
    }

    private function sortMbl($data,$type){

        if( $type == 'asc' ){

            // get all blanks
            $a = collect($data)->filter(function($item){
                return $item->sort_score == 4;
            })
            ->values()
            ->all();
            // --

            // get all numbers that start with zero
            $b = collect($data)->filter(function($item){
                return $item->sort_score == 3;
            })
            ->values()
            ->all();
            // --

            // get all numbers
            $c = collect($data)->filter(function($item){
                return $item->sort_score == 2;
            })
            ->values()
            ->all();

            // sort all numbers by mbl num ASC
            $c = collect($c)->sortBy('mbl_num')->values()->all();
            // --

            // get all abc that has number on front
            $d = collect($data)->filter(function($item){
                return $item->sort_score == 1;
            })
            ->values()
            ->all();

            // sort all abc that has number on front by mbl num ASC
            $d = collect($d)->sortBy('mbl_num')->values()->all();
            // --

            // get all abc
            $e = collect($data)->filter(function($item){
                return $item->sort_score == 0;
            })
            ->values()
            ->all();

            // sort all abc by mbl num ASC
            $e = collect($e)->sortBy('mbl_num')->values()->all();
            // --
        }else{

            // get all abc
            $a = collect($data)->filter(function($item){
                return $item->sort_score == 0;
            })
            ->values()
            ->all();

            // sort all abc by mbl num DESC
            $a = collect($a)->sortByDesc('mbl_num')->values()->all();
            // --

            // get all abc that has numbers on front
            $b = collect($data)->filter(function($item){
                return $item->sort_score == 1;
            })
            ->values()
            ->all();

             // sort all abc that has numbers on front by mbl num DESC
            $b = collect($b)->sortByDesc('mbl_num')->values()->all();
            // --

            // get all numbers
            $c = collect($data)->filter(function($item){
                return $item->sort_score == 2;
            })
            ->values()
            ->all();

            // sort all numbers by mbl num DESC
            $c = collect($c)->sortByDesc('mbl_num')->values()->all();
            // --

            // get all numbers that start with zero
            $d = collect($data)->filter(function($item){
                return $item->sort_score == 3;
            })
            ->values()
            ->all();
            // --

            // get all blanks
            $e = collect($data)->filter(function($item){
                return $item->sort_score == 4;
            })
            ->values()
            ->all();
            // --
        }

        // merge them all
        return collect($a)->concat($b)->concat($c)->concat($d)->concat($e);
    }

    private function applyFilters($filtered_shipments, $request)
    {
        if((count($request->filterTypeV2 ?? []) == 0 || in_array('all',$request->filterTypeV2)) && empty($request->filterMode)) return $filtered_shipments;

        $new_filtered_shipments = [];

        foreach ($filtered_shipments as $filtered_shipment) {
            // type check.
            if((empty($request->filterTypeV2) ||
               in_array('all',$request->filterTypeV2) ||
               in_array($filtered_shipment->type, $request->filterTypeV2))  // apply type filter
               &&
               (empty($request->filterMode) ||
               $this->checkMode($filtered_shipment,$request->filterMode)) // apply mode filter
            ){
                $new_filtered_shipments[] = $filtered_shipment;
            }
        }

        return $new_filtered_shipments;
    }
    private function getRailShipmentsIds()
    {
        $shipments = Shipment::where('type', '!=', 'LCL')
            ->where('mbl_num', '!=', '')
            ->where('mbl_num', '!=', null)
            ->where('booking_confirmed', 1)
            ->where('cancelled', 0)
            ->where('etd', '<', now()->addWeek())
            ->where('not_tracking_manually' , 0)
            ->get();

        return $shipments->filter(function ($shipment) {
            return $shipment->isRailShipment();
        })->map(function ($shipment) {
            return $shipment->id;
        });
    }

    public function show(Request $request, $id)
    {
        $shipment = Shipment::find($id);
        if ($shipment) {
            return response()->json([
                'containers_group_bookings' => json_decode($shipment->containers_group_bookings) ?? [] ,
                'containers_group_untracked' => json_decode($shipment->containers_group_untracked) ?? [] ,
                'etd' => $shipment->etd,
                'eta' => $shipment->eta,
                'shifl_ref' => $shipment->shifl_ref,
                'type' => $shipment->getType(),
                'is_tracking_shipment' => $shipment->is_tracking_shipment,
                'untracked_tool_last_updated_at' => $shipment->untracked_tool_last_updated_at,
                'untracked_tool_last_updated_by' => User::find($shipment->untracked_tool_last_updated_by),
                'terminal' => $shipment->terminal,
                'reasons' => [
                    'is_empty_mbl' => empty($shipment->mbl_num),
                    'is_lcl_type' => $shipment->type == 'LCL',
                    'is_rail_shipment' => $shipment->isRailShipment(),
                    'is_tracking_shipment' => $shipment->is_tracking_shipment,
                    'is_tracking_with_t49' => empty($shipment->mbl_num) ? false : Terminal49Shipment::where('mbl_num',$shipment->mbl_num)->exists(),
                ],
                'customer' => $shipment->is_tracking_shipment ? $shipment->customer : null,
                'not_tracking' => $shipment->not_tracking_manually,
                'logged_in_user' => auth()->user()->email,
                'schedules_group_bookings' => json_decode($shipment->schedules_group_bookings ?? '[]'),
                'is_admin' => auth()->user()->hasRole('Super Admin')
            ]);
        }
    }

    public function update(Request $request, $id)
    {
        $shipment = Shipment::find($id);
        if ($shipment) {
            $shipment->containers_group_untracked = json_encode($request->containers_group_untracked);

            $containerDates = [];
            foreach ($request->containers_group_untracked['containers'] as $container) {
                $containerDates[] = $container['pod_full_in_at'];
            }

            if(count(array_filter($containerDates)) == count($containerDates)) {
                $shipment->gate_full_in = 1;
            }

            if (Carbon::parse($shipment->eta)->format('Y-m-d') != Carbon::parse($request->eta)->format('Y-m-d') || Carbon::parse($shipment->etd)->format('Y-m-d') != Carbon::parse($request->etd)->format('Y-m-d')) {
                $shipment->schedules_group_bookings = $this->getUpdatedSchedules($shipment->schedules_group_bookings, $request->etd, $request->eta);
            }

            if ($shipment->isDirty()) {
                $shipment->untracked_tool_last_updated_at = now()->setTimezone('America/New_York');
            }
            $shipment->untracked_tool_last_updated_by = auth()->user()->id ?? null;
            $shipment->save();
            if($request->markAsTracked){
                return $this->moveToTracked($request,$shipment->id);
            }
        }
    }

    public function moveToTracked(Request $request, $id)
    {
        $shipment = Shipment::find($id);
        $shipment->untracked_tool_last_updated_by = auth()->user()->id ?? null;
        if ($shipment) {
            //
            $shipment->not_tracking_manually = 0;
            $shipment->mt_last_addressed_by = auth()->user()->id ?? null;
            $shipment->untracked_tool_last_tracked_at = now()->setTimezone('America/New_York');
            if ($shipment->eta) {
                $eta = Carbon::parse($shipment->eta)->startOfDay();
                if (now()->gt($eta)) {
                    if (!$this->checkFillStatus($shipment->containers_group_untracked, "pod_empty_returned_at")) {
                        // if full out at filled in and passed today then return to the tool after 2 days.
                        if($this->checkFillStatus($shipment->containers_group_untracked, "pod_full_out_at")){
                            $earlierDate = $this->getEarlierDate($shipment->containers_group_untracked,"pod_full_out_at");
                            if($earlierDate && now()->gt($earlierDate)){
                                $shipment->tracked_up_to = now()->addDays(2);
                                $shipment->save();
                                return response()->json([], 200);
                            }
                        }
                        $shipment->tracked_up_to = now()->addHours(2);
                        $shipment->save();
                        return response()->json([], 200);
                    }else{
                        // And if you fill in empty in - when click tracked will return to tool in two weeks
                        $shipment->tracked_up_to = now()->addWeeks(2);
                        $shipment->save();
                        return response()->json([], 200);
                    }
                }
            }
            if($shipment->etd){
                $etd = Carbon::parse($shipment->etd)->startOfDay();
                if(now()->addDays(8)->gt($etd)){
                    $shipment->tracked_up_to = (now()->addDay()->timezone('EST')->startOfDay())->timezone('UTC');
                }else{
                    $shipment->tracked_up_to = (now()->addWeek()->timezone('EST')->startOfDay())->timezone('UTC');
                }
            }else{
                $shipment->tracked_up_to = (now()->addDay()->timezone('EST')->startOfDay())->timezone('UTC');
            }
            $shipment->save();
        }
        return response()->json([], 200);
    }

    // get offices
    public function getOffices(){
        return ShiflOffice::select('id','name')->get();
    }
    // get terminals
    public function getTerminals(){
        return Terminal::select('id','firms_code')->get();
    }

    // save notes
    public function saveNotes(Request $request, $shipment_id){
        $shipment = Shipment::find($shipment_id);
        if($shipment){
            $shipment->containers_group_untracked = json_encode($request->containers_group_untracked);
            $shipment->not_tracking_manually = $request->not_tracking ?? false;
            $shipment->save();
            return response([],200);
        }else{
            return response([],500);
        }
    }



    // update eta etd
    private function getUpdatedSchedules($json, $etd, $eta)
    {
        if (!empty($etd)) {
            $etd = Carbon::parse($etd)->format("Y-m-d H:i:s");
        }
        if (!empty($eta)) {
            $eta = Carbon::parse($eta)->format("Y-m-d H:i:s");
        }

        $schedules = json_decode($json, true);
        foreach ($schedules ?? [] as $key => $schedule) {
            // code...
            if (isset($schedule['is_confirmed']) && $schedule['is_confirmed']) {
                $schedules[$key]['etd'] = $etd;
                $schedules[$key]['eta'] = $eta;
                return json_encode($schedules);
            }
        }
        return $json;
    }

    private function checkFillStatus($containers_group_untracked, $field_name)
    {
        $containers_group_untracked = json_decode($containers_group_untracked ?? '[]', true);
        if (is_array($containers_group_untracked) && count($containers_group_untracked) > 0) {
            $containers = $containers_group_untracked['containers'];
            if (isset($containers) && is_array($containers) && count($containers) > 0) {
                foreach ($containers as $key => $container) {
                    // code...
                    if (empty($container[$field_name])) {
                        return false;
                    }
                }
                return true;
            }
        }
        return false;
    }
    private function getEarlierDate($containers_group_untracked, $field_name)
    {
        $containers_group_untracked = json_decode($containers_group_untracked ?? '[]', true);
        $min = null;
        foreach ($containers_group_untracked['containers'] ?? [] as $key => $container) {
            if(!$container[$field_name]) return null;
            $date = Carbon::parse($container[$field_name]);
            if(!$min){
              $min = $date;
            }
            else if($min->gt($date)){
              $min = $date;
            }
        }
        return $min;
    }

    private function getLfdIgnoreDemurrage(){
        return Terminal49Shipment::select("mbl_num","containers")
                                        ->where('ignore_demurrage','like','%true%')
                                        ->get()->filter(function ($shipment){
                                            $containers = json_decode($shipment->containers ?? '[]');
                                            foreach ($containers as $key => $container) {
                                                $passed_lfd = false;
                                                $lfd = $container->attributes->pickup_lfd;
                                                if (empty($lfd)) {
                                                    continue;
                                                }
                                                $lfd = Carbon::parse($lfd)->startOfDay();
                                                if (now()->startOfDay()->gt($lfd)) {
                                                    $passed_lfd = true;
                                                }
                                                //
                                                $released_at_terminal = $container->attributes->pod_full_out_at ?? $container->attributes->empty_terminated_at;

                                                if ($passed_lfd && empty($released_at_terminal)) {
                                                    return true;
                                                }
                                            }
                                            return false;

                                        })->pluck("mbl_num")->toArray();
    }

    private function checkMode($shipment, $mode) : bool {
        $schedules_group_bookings = json_decode($shipment->schedules_group_bookings ?? '[]');
        foreach ($schedules_group_bookings as $schedule) {
            // code...
            if($schedule->is_confirmed ?? false){
                if(!empty($schedule->mode) && strtolower($schedule->mode) == strtolower($mode)) return true;
                foreach ($schedule->legs ?? [] as $leg) {
                  if(!empty($leg->mode) && strtolower($leg->mode) == strtolower($mode)) return true;
                }
                break;
            }
        }
        return false;
    }

}
