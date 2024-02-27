<?php

namespace App\Console\Commands;
use App\Shipment;
use Illuminate\Console\Command;
use App\Custom\ProcessShipmentReport;
use Illuminate\Support\Arr;
class UpdateShipmentCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'update:shipment-status';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Shipment update status fallback';

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


        $checkShipments = Shipment::where('is_tracking_info_processing', 0)
                                  ->orderBy('id', 'desc')
                                  ->paginate(100);

        if ( count ($checkShipments) > 0 ) {
            foreach( $checkShipments as $key => $shipment ) {
                $status = $shipment->getStatusV2();
                $containers_information = $shipment->getContainersInformation();
                $container_grp = (new ProcessShipmentReport())->getShipmentContainerAndT49($shipment);
                $containers = $container_grp['all_containers'] ?? [];
                $container_num = Arr::pluck($containers, 'container_num') ?? [];
                $tracking_info =  (new ProcessShipmentReport())->getTruckingShipmentByMBLAndCon($shipment->mbl_num, $container_num);
                
                $this->processShipment($shipment, $status, $containers, $tracking_info, $containers_information);
                // $this->processShipment($shipment, $status);
            }
        } else {
            \DB::table('shipments')->update([
                'is_tracking_processing' => 0,
                'is_tracking_info_processing' => 0
            ]);
        }
    }
    
    private function ifNull($firstValue, $secondValue, $nullValue = null){
        return (empty($firstValue) ? (empty($secondValue) ? $nullValue : $secondValue) : $firstValue);
    }

    private function processShipment($getShipment, $status, $containers, $tracking_info, $containers_information) {

        /*
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
        ]);*/

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

        $containers_count = (isset($containers_information['containers_count'])) ? $containers_information['containers_count'] : 0;

        $containers_return_count = (isset($containers_information['containers_return_count'])) ? $containers_information['containers_return_count'] : 0;

        $containers_array =  (isset($containers_information['containers_array'])) ? $containers_information['containers_array'] : [];
        
        $full_out_array = [];
        if ( is_countable($containers_array) ) {
            foreach ($containers_array as $container ) {
                if ( isset($container['pod_full_out_at'])) {
                    array_push($full_out_array, $container['pod_full_out_at']);    
                }
            }
        }

        \DB::table('shipments')->where('id', $getShipment->id)
        ->update([
            'status_fallback' => $status,
            'tracking_eta' => $tracking_eta,
            'tracking_etd' => $tracking_etd,
            'is_processing' => 1,
            'is_tracking_processing' => 1,
            'containers_count' => $containers_count,
            'containers_return_count' => $containers_return_count,
            'containers_array' => json_encode($containers_array), 
            'containers_data' => json_encode($containers),
            'tracking_info' => json_encode($tracking_info),
            'full_out_at_array' => json_encode($full_out_array),
            'is_tracking_info_processing' => 1,
        ]);
    }
}
