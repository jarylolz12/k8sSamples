<?php

namespace App\Console\Commands;
use App\Shipment;
use App\Supplier;
use Illuminate\Console\Command;


class UpdateShipmentPendingDocsCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'update:shipment-pending-docs-status';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Shipment pending docs status';

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
        $checkShipments = Shipment::where('has_check_docs', 0)
                                  ->orderBy('id', 'desc')
                                  ->paginate(20);

        if ( count ($checkShipments) > 0 ) {
            foreach ($checkShipments as $checkShipment ) {
                $this->processShipment($checkShipment);
            }
        } else {

            //disable for now
            /*
            \DB::table('shipments')->update([
                'has_check_docs' => 0
            ]);*/
        }
    }

    private function processShipment($shipment) {

        $suppliers = $shipment->suppliers_group_bookings;
        $supplierIds = [];
        $has_pass_test = false;

        if ( $suppliers!='' && $suppliers!=null ) {
            try {
                $suppliers = json_decode($suppliers);
            } catch(Exception $e) {
                $suppliers = [];
            }

            if ( is_countable($suppliers) ) {
                foreach ($suppliers as $supplier ) {
                    array_push($supplierIds, $supplier->supplier_id);
                }

                $checkSuppliers = Supplier::whereIn('id', $supplierIds)->get();

                if ( count($checkSuppliers) > 0 ) {
                    
                    $has_pass_count = 0;
                    foreach($checkSuppliers as $checkSupplier ) {

                        if ( isset($checkSupplier->documents)) {
                            
                            $documents = $checkSupplier->documents;
                            $has_packing_list = false;
                            $has_commercial_invoice = false;
                            $has_invoice_packing_list = false;

                            if ( count($documents) > 0 ) {
                                foreach($documents as $document ) {
                                    if ( $document->shipment_id == $shipment->id ) {
                                        if ( $document->type === 'Packing List' ) {
                                            $has_packing_list = true;
                                        }

                                        if ( $document->type === 'Commercial Invoice' ) {
                                            $has_commercial_invoice = true;
                                        }

                                        if ( $document->type === 'Invoice And Packing List' ) {
                                            $has_invoice_packing_list = true;
                                        }
                                    }
                                }
                            }

                            if ( $has_commercial_invoice && $has_packing_list ) {
                                $has_pass_count++;
                            } else {
                                if ( $has_invoice_packing_list ) {
                                    $has_pass_count++;
                                }
                            }
                        }
                        
                    }

                    if ( $has_pass_count == count($checkSuppliers) ) {
                        $has_pass_test = true;
                    }

                }


            }

        }

        \DB::table('shipments')
        ->where('id', $shipment->id)
        ->update([
            'has_check_docs' => 1,
            'has_pending_suff_docs' =>  $has_pass_test ? 0 : 1
        ]);

    }

}
