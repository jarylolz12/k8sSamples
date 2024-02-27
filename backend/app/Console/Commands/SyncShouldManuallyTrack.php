<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

use App\Shipment;
use App\Terminal49Shipment;
use Carbon\Carbon;

class SyncShouldManuallyTrack extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'sync:manual-tracking';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $demmurage_shipments_mbl = $this->getLfdIgnoreDemurrage();
        $mbl_nums_connected_to_t49_and_not_in_demmurage = Terminal49Shipment::whereNotIn('mbl_num',$demmurage_shipments_mbl)->get(['mbl_num'])->pluck('mbl_num');

        Shipment::whereIn('mbl_num', $mbl_nums_connected_to_t49_and_not_in_demmurage)->update([
            'should_manually_track' => 0,
        ]);

        Shipment::whereNotIn('mbl_num', $mbl_nums_connected_to_t49_and_not_in_demmurage)->update([
            'should_manually_track' => 1,
        ]);

        Shipment::whereIn('mbl_num', $demmurage_shipments_mbl)->update([
            'lfd_ignore_demurrage' => 1
        ]);

        Shipment::whereNotIn('mbl_num', $demmurage_shipments_mbl)->update([
            'lfd_ignore_demurrage' => 0
        ]);

        $this->info("completed");
        
        return 0;
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
}
