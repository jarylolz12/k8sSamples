<?php

namespace Tanvirofficials\LfdTool\Http\Controllers;

use App\Mail\SendDemurrageNotificationEmail;
use Illuminate\Routing\Controller;
use Illuminate\Http\Request;
use App\Shipment;
use Carbon\Carbon;
use App\Terminal49Shipment;
use Illuminate\Support\Facades\Mail;

class LfdToolController extends Controller
{
    public function index(Request $request)
    {
        $terminal49_shipments = Terminal49Shipment::where('is_released', 0)->where('is_completed', 0)->where('is_past_lfd_not_released',1)->with('shipment:id,mbl_num,shifl_ref,terminal_id')->get();
        $internal_shipments = Shipment::where('is_mt_past_lfd_not_released', 1)->where('cancelled', 0)->where('not_tracking_manually', 0)
                                        ->get(['mbl_num', 'containers_group_untracked', 'id', 'shifl_ref','terminal_id','lfd_daily_review_status','lfd_notes']);
        $report_containers = [];
        if ($terminal49_shipments) {
            foreach ($terminal49_shipments as $key => $t49_shipment) {
                $released_at_terminal = false;
                $containers = json_decode($t49_shipment->containers);
                $ignore_demurrage = json_decode($t49_shipment->ignore_demurrage ?? '[]', true);

                if (!empty($t49_shipment->containers) && !empty($containers)) {
                    foreach ($containers as $key => $container) {
                        $passed_lfd = false;
                        $skip_container = false;
                        // code...
                        //
                        // check lfd
                        $lfd = $container->attributes->pickup_lfd;
                        if (empty($lfd)) {
                            continue;
                        }
                        $lfd = \Carbon\Carbon::parse($lfd);
                        if (now()->gt($lfd)) {
                            $passed_lfd = true;
                        }
                        //
                        $released_at_terminal = $container->attributes->pod_full_out_at ?? $container->attributes->empty_terminated_at;

                        if (isset($ignore_demurrage[$container->attributes->number]) && $ignore_demurrage[$container->attributes->number]) {
                            $skip_container = true;
                        }

                        if ($passed_lfd && empty($released_at_terminal) && !$skip_container) {
                            $is_reviewed = false;
                            $lfd_daily_review_status = json_decode($t49_shipment->lfd_daily_review_status ?? '[]', true);
                            if (!empty($lfd_daily_review_status)) {
                                if (isset($lfd_daily_review_status[$container->attributes->number])) {
                                    $is_reviewed = Carbon::parse($lfd_daily_review_status[$container->attributes->number])->gt(now());
                                }
                            }
                            $lfd_notes = json_decode($t49_shipment->lfd_notes ?? '[]', true);
                            if($t49_shipment->shipment && !$t49_shipment->shipment->cancelled){
                                array_push($report_containers, [
                                    'mbl_num' => $t49_shipment->mbl_num,
                                    'container_num' => $container->attributes->number,
                                    'pickup_lfd' => $lfd,
                                    'is_reviewed' => $is_reviewed,
                                    'lfd_notes' => (isset($lfd_notes[$container->attributes->number]) ? $lfd_notes[$container->attributes->number] : ''),
                                    'shifl_ref' => $t49_shipment->shipment->shifl_ref,
                                    'id' => $t49_shipment->shipment->id,
                                    'terminal' => $t49_shipment->shipment->terminal ?? ['firms_code' => ''],
                                    'tracking_source' => 'Terminal49'
                                ]);
                            }

                        }
                    }
                }
            }
        }

        if($internal_shipments){
            foreach ($internal_shipments as $key => $shipment) {
                // code...
                $containers_group_untracked = json_decode($shipment->containers_group_untracked ?? '[]');
                foreach ($containers_group_untracked->containers ?? [] as $key => $container) {
                    // code...
                    $lfd = $container->pickup_lfd;
                    if (empty($lfd)) {
                        continue;
                    }
                    $lfd = \Carbon\Carbon::parse($lfd);
                    if (now()->gt($lfd)) {
                        $passed_lfd = true;
                    }

                    if($passed_lfd && empty($container->pod_empty_returned_at ?? $container->pod_full_out_at)){

                        $is_reviewed = false;
                        $lfd_daily_review_status = json_decode($shipment->lfd_daily_review_status ?? '[]', true);
                        if (!empty($lfd_daily_review_status)) {
                            if (isset($lfd_daily_review_status[$container->container_num])) {
                                $is_reviewed = Carbon::parse($lfd_daily_review_status[$container->container_num])->gt(now());
                            }
                        }
                        $lfd_notes = json_decode($shipment->lfd_notes ?? '[]', true);

                        array_push($report_containers, [
                            'mbl_num' => $shipment->mbl_num,
                            'container_num' => $container->container_num,
                            'pickup_lfd' => $lfd,
                            'is_reviewed' => $is_reviewed,
                            'lfd_notes' => (isset($lfd_notes[$container->container_num]) ? $lfd_notes[$container->container_num] : ''),
                            'shifl_ref' => $shipment->shifl_ref,
                            'id' => $shipment->id,
                            'terminal' => $shipment->terminal ?? ['firms_code' => ''],
                            'tracking_source' => 'Manual'
                        ]);
                    }
                }
            }
            $report_containers = array_unique($report_containers, SORT_REGULAR);
            $report_containers = array_values($report_containers);
        }
        return response()->json($report_containers);
    }

    public function markAsReviewed(Request $request, $mbl_num, $container_num)
    {
        if($request->tracking_source == 'Manual'){
            $shipment = Shipment::where('shifl_ref', $request->shifl_ref)->where('mbl_num',$mbl_num)->first();
        } else{
            $shipment = Terminal49Shipment::find($mbl_num);
        }

        if ($shipment) {
            $lfd_daily_review_status = json_decode($shipment->lfd_daily_review_status ?? '[]', true);
            $lfd_daily_review_status[$container_num] = (now()->addDay()->timezone('EST')->startOfDay())->timezone('UTC')->format("Y-m-d H:00");

            if($request->tracking_source == 'Manual'){
                Shipment::where('shifl_ref', $request->shifl_ref)->where('mbl_num',$mbl_num)->update(['lfd_daily_review_status' => json_encode($lfd_daily_review_status)]);
            }
            else{
                Terminal49Shipment::where('mbl_num', $mbl_num)->update(['lfd_daily_review_status' => json_encode($lfd_daily_review_status)]);
            }

        }
        return response()->json([], 200);
    }

    public function addNotes(Request $request)
    {
        $request->validate([
            'mbl_num' => 'required',
            'container_num' => 'required'
        ]);
        if($request->tracking_source == 'Manual'){
            $shipment = Shipment::where('shifl_ref', $request->shifl_ref)->where('mbl_num',$request->mbl_num)->first();
        }
        else{
            $shipment = Terminal49Shipment::find($request->mbl_num);
        }

        if ($shipment) {
            $lfd_notes = json_decode($shipment->lfd_notes ?? '[]', true);
            $lfd_notes[$request->container_num] = $request->notes;

            if($request->tracking_source == 'Manual'){
                Shipment::where('shifl_ref', $request->shifl_ref)->where('mbl_num',$request->mbl_num)->update(['lfd_notes' => json_encode($lfd_notes)]);
            }
            else{
                Terminal49Shipment::where('mbl_num', $request->mbl_num)->update(['lfd_notes' => json_encode($lfd_notes)]);
            }
        }

        return response()->json([], 200);
    }

    public function notifyDemurrage($id)
    {
        $shipment = Shipment::find($id);

        $customer = $shipment->customer->company_name;
        $manager  = $shipment->officeToAccountManager();

        $content['shifl_ref'] = $shipment['shifl_ref'] ?? 'shifl_ref';
        $content['mbl_num']   = $shipment['mbl_num'] ?? 'mbl_num';
        $content['customer']  = $customer ?? 'customer';

        Mail::to($manager)->cc([
            'carla@shifl.com',
            'Jayakumar@shifl.com',
            'shia@shifl.com',
            'aravinthsamy@shifl.com'
        ])->send(new SendDemurrageNotificationEmail($content));

        $result = [
            'message' => 'Successfully notify receiving team'
        ];

        return response()->json($result, 200);
    }
}
