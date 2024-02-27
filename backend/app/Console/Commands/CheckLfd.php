<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Terminal49Shipment;
use Illuminate\Support\Facades\Mail;
use App\Mail\SimpleString;

class CheckLfd extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'check:lfd';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Check if the lfd is passed and terminal is not released';

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
        $terminal49_shipments = Terminal49Shipment::all();
        $report_containers = [];
        if ($terminal49_shipments) {
            foreach ($terminal49_shipments as $key => $shipment) {
                $released_at_terminal = false;
                $containers = json_decode($shipment->containers);
                $ignore_demurrage = json_decode($shipment->ignore_demurrage ?? '[]', true);

                if (!empty($shipment->containers) && !empty($containers)) {
                    foreach ($containers as $key => $container) {
                        $passed_lfd = false;
                        $skip_container = false;
                        // code...
                        //
                        // check lfd
                        $lfd = $container->attributes->pickup_lfd;
                        if (empty($lfd)) {
                            continue;
                        }
                        $lfd = \Carbon\Carbon::parse($lfd);
                        if (now()->gt($lfd)) {
                            $passed_lfd = true;
                        }
                        //
                        $released_at_terminal = $container->attributes->pod_full_out_at ?? $container->attributes->empty_terminated_at;

                        if (isset($ignore_demurrage[$container->attributes->number]) && $ignore_demurrage[$container->attributes->number]) {
                            $skip_container = true;
                        }

                        if ($passed_lfd && empty($released_at_terminal) && !$skip_container) {
                            array_push($report_containers, [
                              'mbl_num' => $shipment->mbl_num,
                              'container_num' => $container->attributes->number,
                              'pickup_lfd' => $lfd,
                            ]);
                        }
                    }
                }
            }
            if (!empty($report_containers)) {
                $report_containers = array_unique($report_containers, SORT_REGULAR);

                $fileName = "lfd_report_". now()->format('D_M_Y_H_i_s') . ".xlsx";
                // \Excel::store(new Report\ContainerExport($report_containers), 'reports/lfd/excel/'. $fileName);
                \Excel::store(new Report\ContainerExport($report_containers), 'reports/lfd/excel/'.$fileName, 'public');
                $path = storage_path('/app/public/reports/lfd/excel/'.$fileName);
                if (file_exists($path)) {
                    $subject = "LFD Daily Report";
                    $message = "Please see Attached.";
                    $attachment= [$path];
                    if (file_exists($path)) {
                        $to = ['shia@shifl.com','jayakumar@shifl.com'];
                        $cc = ['Shabsie@shifl.com','chana@shifl.com','tanvir@shifl.com'];

                        Mail::to($to)->cc($cc)->send(new SimpleString($subject, $message, $attachment));
                    }
                }
                // dd($report_containers);
            }
        }

        return 0;
    }
}
