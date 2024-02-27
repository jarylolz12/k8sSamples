<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Terminal49Shipment;
use App\Shipment;

class SyncMilestones extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'sync:milestones';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'A Command to sync Shipments milestones value';

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
        $choice = $this->choice(
            "Which milestone's value you want to sync?",
            ['Released At Termnial']
        );

        switch ($choice) {
          case 'Released At Termnial':
            // code...
            $this->syncReleasedAtTerminal();
            break;

          default:
            // code...
            break;
        }
        $this->info("\n ".$choice." milestone was successfully synced.");
        return 0;
    }
    /**
     * determine if the shipment released at terminal
     *
     * @param  void
     * @return void
     */
    private function syncReleasedAtTerminal()
    {
        $terminal49Shipments = Terminal49Shipment::all();
        $bar = $this->output->createProgressBar(count($terminal49Shipments));

        foreach ($terminal49Shipments as $terminal49Shipment) {
            // code...
            if (!empty($terminal49Shipment->mbl_num)) {
                if (!empty($terminal49Shipment->containers)) {
                    $containers = json_decode($terminal49Shipment->containers);
                    if (!empty($containers)) {
                        $shipment = Shipment::where("mbl_num", "=", $terminal49Shipment->mbl_num)->first();
                        if ($shipment) {
                            // $shipment->released_at_terminal = $this->isReleasedAtTerminal($containers);
                            // $shipment->save();
                            \DB::table("shipments")->where("id", "=", $shipment->id)->update([
                              "released_at_terminal" => $this->isReleasedAtTerminal($containers),
                            ]);
                        }
                    }
                }
            }
            $bar->advance();
        }
        $bar->finish();
    }
    /**
     * determine if the shipment released at terminal
     *
     * @param  \App\Terminal49Shipment::containers  $containers
     * @return bool
     */

    private function isReleasedAtTerminal($containers)
    {
        if (count($containers) > 0) {
            $released_at_terminal = true;
            foreach ($containers as $container) {
                // code...
                if (!$container->attributes->available_for_pickup) {
                    $released_at_terminal = $container->attributes->available_for_pickup;
                }
            }
            return $released_at_terminal;
        } else {
            return false;
        }
    }
}
