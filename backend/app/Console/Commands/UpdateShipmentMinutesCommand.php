<?php

namespace App\Console\Commands;
use App\Shipment;
use Illuminate\Console\Command;
class UpdateShipmentMinutesCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'update:shipment-status-minutes';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Shipment update status fallback minute';

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
        /*
        $checkShipments = Shipment::where('is_tracking_processing', 0)
                                  ->orderBy('id', 'desc')
                                  ->paginate(100);


        if ( count ($checkShipments) > 0 ) {
            foreach( $checkShipments as $key => $shipment ) {
                $status = $shipment->getStatusV2();
                $this->processShipment($shipment, $status);
            }
        }*/

        $checkShipments = Shipment::orderBy('id', 'desc')
                                  ->paginate(200);

        if ( count ($checkShipments) > 0 ) {
            foreach( $checkShipments as $key => $shipment ) {
                $status = $shipment->getStatusV2();
                $this->processShipment($shipment, $status);
            }
        }


    }
    
    private function ifNull($firstValue, $secondValue, $nullValue = null){
        return (empty($firstValue) ? (empty($secondValue) ? $nullValue : $secondValue) : $firstValue);
    }

    private function processShipment($getShipment, $status) {

        
        $tracking_eta = null;
        $tracking_etd = null;

        if(!empty($getShipment->mbl_num) || isset($getShipment->terminal_fortynine)){
            $terminal49_shipment = $getShipment->terminal_fortynine;
            if ( isset($terminal49_shipment) && isset ($terminal49_shipment->attributes) ) {
                $attributes = json_decode($terminal49_shipment->attributes, true);
                $tracking_eta = $this->ifNull($attributes['pod_eta_at'],$attributes['pod_ata_at']);
                $tracking_etd = $this->ifNull($attributes['pol_etd_at'],$attributes['pol_atd_at']);
            }     
        }
        
        \DB::table('shipments')->where('id', $getShipment->id)
        ->update([
            'status_fallback' => $status,
            'tracking_eta' => $tracking_eta,
            'tracking_etd' => $tracking_etd,
            'is_processing' => 1,
            'is_tracking_processing' => 1
        ]);
        
    }
}
