<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Terminal49Shipment;
use App\Shipment;

class SyncLfdShipments extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'sync:lfd-tool';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Sync lfd tool fields';

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
        $this->info("Syncing Terminal49 Shipment's Lfd status -> ");
        $this->terminal49LfdSync();
        $this->info("\nDone\n");
        $this->info("Syncing Manual tracking Shipment's Lfd status -> ");
        $this->mtLfdSync();
        $this->info("\nCompleted!");

        return 0;
    }

    private function terminal49LfdSync()
    {
        $terminal49_shipments = Terminal49Shipment::where('is_released', 0)->where('is_completed', 0)->get();
        $bar = $this->output->createProgressBar(count($terminal49_shipments));
        $lfd_shipment_ids = [];
        if ($terminal49_shipments) {
            foreach ($terminal49_shipments as $key => $shipment) {
                $released_at_terminal = false;
                $containers = json_decode($shipment->containers);
                $ignore_demurrage = json_decode($shipment->ignore_demurrage ?? '[]', true);

                if (!empty($shipment->containers) && !empty($containers)) {
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

                        if (isset($ignore_demurrage[$container->attributes->number]) && $ignore_demurrage[$container->attributes->number]) $skip_container = true;


                        if ($passed_lfd && empty($released_at_terminal) && !$skip_container) {
                            // here goes the code
                            array_push($lfd_shipment_ids,$shipment->id);
                            break;
                        }
                    }
                }
                $bar->advance();
            }
            $bar->finish();
        }

        Terminal49Shipment::whereIn('id', $lfd_shipment_ids)->update([
            'is_past_lfd_not_released' => 1
        ]);
        Terminal49Shipment::whereNotIn('id', $lfd_shipment_ids)->update([
            'is_past_lfd_not_released' => 0
        ]);
    }

    public function mtLfdSync(){
        $shipments = Shipment::where('containers_group_untracked','!=',null)->whereNotIn('mbl_num', Terminal49SHipment::pluck('mbl_num'))->get();
        $lfd_shipment_ids = [];
        $bar = $this->output->createProgressBar(count($shipments));
        foreach ($shipments as $key => $shipment) {
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
                    array_push($lfd_shipment_ids, $shipment->id);
                    break;
                }
            }
            $bar->advance();
        }
        $bar->finish();

        Shipment::whereIn('id', $lfd_shipment_ids)->update([
            'is_mt_past_lfd_not_released' => 1
        ]);

        Shipment::whereNotIn('id', $lfd_shipment_ids)->update([
            'is_mt_past_lfd_not_released' => 0
        ]);

    }
}
