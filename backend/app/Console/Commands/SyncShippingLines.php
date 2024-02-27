<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Custom\Traits\Terminal49Helpers;

class SyncShippingLines extends Command
{
    use Terminal49Helpers;
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'sync:shipping_lines';

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
        $this->syncShippingLines();
        $this->info("Shipping Lines updated Successfully!");
        return 0;
    }
}
