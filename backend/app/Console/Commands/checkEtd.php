<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class CheckEtd extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'check:etd';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Check shipment etd daily then set status to In Transit if current status is Approved.';

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
     * @return mixed
     */
    public function handle()
    {
        /* DB::table('shipments')->whereDate('etd', '<', now())->update(['shipment_status' => 'In Transit - Pending AN/Customs/Freight Payment']); */

        $departedShipments = DB::table('shipments')
           ->where('shipment_status', '=', 'Approved')
           ->whereDate('etd', '<', now())
           /* ->where(function ($query) {
               $query->whereDate('etd', '<', now());
           }) */
           ->get();

        foreach ($departedShipments as $departedShipment) {
            DB::table('shipments')
            ->where('id', '=', $departedShipment->id)
            ->update(['shipment_status' => 'In Transit - Pending AN/Customs/Freight Payment']);
            //$shipment->update(['shipment_status' => 'In Transit - Pending AN/Customs/Freight Payment']);
        }
    }
}
