<?php

namespace App\Console\Commands;

use App\Shipment;
use App\ShipmentSupplier;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class backFillShipmentSupplier extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'backFill:shipmentSupplier';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Backfill Shipment Supplier Data';

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
        try{
            Shipment::select('shipments.id as id','suppliers_group')
                ->join('shipment_suppliers', function ($join) {
                    $join->on('shipments.id', '=', 'shipment_suppliers.shipment_id')
                        ->whereNull('shipment_suppliers.supplier_id');
                })
                ->where('suppliers_group','!=', '[]')
                ->where('suppliers_group','!=', '')
                ->whereNotNull('suppliers_group')
                ->get()
                ->each(function ($shipment) {
                    Log::info("Processing Shipment :". $shipment->id);
                    $suppliers_group = json_decode($shipment->suppliers_group);
                    if(is_array($suppliers_group)){
                        collect($suppliers_group)->each(function ($group) use ($shipment) {
                            $shipmentSupplier = ShipmentSupplier::where('unique_id', $group->id)->get();
                            if(!$shipmentSupplier->isEmpty()){
                                if(!empty($group->buyer_id)){
                                    ShipmentSupplier::where('unique_id', $group->id)
                                        ->update(['buyer_id' => $group->buyer_id]);
                                    Log::info("Added buyer id :". $shipment->id ." : ". $group->id);
                                }
                            }else{
                                Log::info("Record not found for shipment :". $shipment->id ." : ". $group->id);
                            }
                        });
                    }
                });
            $this->info("Done");
        }catch (\Exception $e){
            Log::error("Backfill shipment supplier failed : ". $e->getMessage(), $e->getTrace());
        }
    }
}
