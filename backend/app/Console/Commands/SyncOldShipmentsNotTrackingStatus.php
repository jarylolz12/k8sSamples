<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Shipment;
use App\Terminal49Shipment;
use Carbon\Carbon;

class SyncOldShipmentsNotTrackingStatus extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'sync:not_tracking_status';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Sync old shipments not tracking status';

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
        if(Shipment::where('created_at','<',Carbon::parse('2021-11-10'))
                        ->whereNotIn('mbl_num',
                            Terminal49Shipment::all()->pluck('mbl_num'))
                        ->update([
                            'not_tracking_manually' => 1
                        ]))
        {
            $this->info("Done");
        }

        return 0;
    }
}
