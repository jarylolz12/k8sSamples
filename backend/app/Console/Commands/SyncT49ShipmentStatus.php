<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Terminal49Shipment;
use Carbon\Carbon;
use App\Custom\Traits\Terminal49Helpers;

class SyncT49ShipmentStatus extends Command
{
    use Terminal49Helpers;
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 't49:sync-status';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Sync Terminal49 internal status';

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
        $terminal49_shipments = Terminal49Shipment::where('is_completed', 0)->get();
        $mbl_nums = $terminal49_shipments->pluck('mbl_num');
        $now = now();
        $bar = $this->output->createProgressBar(count($terminal49_shipments));
        $completed_shipment_ids = [];
        $released_shipment_ids = [];
        foreach ($terminal49_shipments as $key => $terminal49_shipment) {
            // code...
            $containers = json_decode($terminal49_shipment->containers ?? '[]');
            $is_released = 0;
            $is_completed = 0;
            $container_count = 0;
            foreach ($containers as $key => $container) {
                // code...
                $container_count++;

                $pod_full_out_at = $container->attributes->pod_full_out_at;
                $empty_terminated_at = $container->attributes->empty_terminated_at;
                $event_empty_in = $this->getTransportEvent($container->id, $terminal49_shipment->transport_events, "container.transport.empty_in");
                $event_full_out = $this->getTransportEvent($container->id, $terminal49_shipment->transport_events, "container.transport.full_out");

                $is_released += (!empty($container->attributes->empty_terminated_at ?? $container->attributes->pod_full_out_at) || $event_full_out || $event_empty_in);

                if($container->attributes->empty_terminated_at || $event_empty_in) $is_completed++;
                else if($container->attributes->pod_full_out_at || $event_full_out){
                    $pod_full_out_at = Carbon::parse($container->attributes->pod_full_out_at ?? $event_full_out['attributes']['timestamp']);
                    if($now->gt($pod_full_out_at) && $now->diffInDays($pod_full_out_at) > 40) $is_completed++;
                }
            }

            if($is_completed == $container_count) array_push($completed_shipment_ids, $terminal49_shipment->id);
            if($is_released == $container_count) array_push($released_shipment_ids, $terminal49_shipment->id);
            $bar->advance();
        }
        
        $bar->finish();

        Terminal49Shipment::whereIn('id', $completed_shipment_ids)->update([
            'is_completed' => 1
        ]);

        Terminal49Shipment::whereIn('id', $released_shipment_ids)->update([
            'is_released' => 1
        ]);

        $this->info("\nTerminal49 is_completed and is_released milestone synced");
        return 0;
    }
}
