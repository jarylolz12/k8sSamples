<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Custom\AddShipmentToTerminal49;
use App\Shipment;

class RetryTracking extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'tr:retry'; // tracking request reattempt/retry

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Reattempt Tracking Request';

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
        \Log::info("retrying");
        $shipments = Shipment::where('retry_tracking_request', '!=', false)
                               ->whereDate('eta', '>', now())
                               ->get();

        if ($shipments->count()>0) {
            foreach ($shipments as $shipment) {
                // code...
                if ($shipment->type != "Air") {
                    // \Log::info("add shipment to terminal49");
                    AddShipmentToTerminal49::build($shipment);
                }
            }
        }
        $this->info('Reattempted Tracking Request Successfully!');
        // application exits with a 0
        // representing a success status (0 errors)
        return 0;
    }
}
