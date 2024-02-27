<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Terminal49Shipment;
use Carbon\Carbon;
use Illuminate\Support\Facades\Http;
use GuzzleHttp\Client;
use App\Custom\Traits\Resyncable;
use App\User;
use Auth;

class ResyncDaily extends Command
{
    use Resyncable;
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'resync {--frequency=} {--init} {--all}';

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

    private function loginByTerminal49DummyUser(){ // to prevent resync failure because nova action logs saving
        $user = User::where("email", "terminal49@resync.api")->first();
        if($user){
            Auth::login($user);
        }else{
            $user = User::create([
                  'name' => "Terminal49 Daily Resync (Automated)",
                  'email' => "terminal49@resync.api",
                  'password' => bcrypt(uniqid(uniqid())), //password is not for human use
              ]);
            Auth::login($user);
        }
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $this->loginByTerminal49DummyUser();
        // all
        if ($this->option("all")) {
            $shipments = Terminal49Shipment::all();
            $bar = $this->output->createProgressBar(count($shipments));
            foreach ($shipments as $shipment) {
                // code...
                $this->resync($shipment);
                $bar->advance();
            }
            $bar->finish();
        }
        // init
        if ($this->option("init")) {
            $shipments = Terminal49Shipment::all();
            $bar = $this->output->createProgressBar(count($shipments));
            foreach ($shipments as $shipment) {
                // code...
                $attributes = json_decode($shipment->attributes);
                $eta = $attributes->pod_eta_at ?? $attributes->pod_ata_at;
                $etd = $attributes->pol_etd_at ?? $attributes->pol_atd_at;
                if ($eta ?? false && !empty(trim($eta))) {
                    $shipment->eta = Carbon::parse($eta);
                }
                if ($etd ?? false && !empty(trim($etd))) {
                    $shipment->etd = Carbon::parse($etd);
                }
                $shipment->save();
                $bar->advance();
            }
            $bar->finish();
        }

        // twice daily
        if ($this->option("frequency") === "twiceDaily") {
            $this->twiceDaily();
        } elseif ($this->option("frequency") === "everyTwoHours") {
            $this->everyTwoHours();
        }

        if(Auth::check()) Auth::logout();

        return 0;
    }
}
