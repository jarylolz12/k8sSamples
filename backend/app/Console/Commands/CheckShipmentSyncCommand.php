<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Shipment;


class CheckShipmentSyncCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'check:shipment-queue';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Check Shipment queue';

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
        $checkShipment = Shipment::where('is_processing', 0)->first();
        
        if ( !isset($checkShipment->id) ) {
            \DB::table('shipments')->update([
                'is_processing' => 0
            ]);
        }
        
    }
}
