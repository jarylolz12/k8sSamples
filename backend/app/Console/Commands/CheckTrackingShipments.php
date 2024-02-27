<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class CheckTrackingShipments extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'check:tracking-shipments';

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
        if(\DB::table('shipments')->where('is_tracking_shipment','=',1)->update([
            'booking_confirmed' => 1
        ])){
            $this->info("Done...");
            return 0;
        }
        $this->warn("Something went wrong Or everything is already up to date");
        return 0;
    }
}
