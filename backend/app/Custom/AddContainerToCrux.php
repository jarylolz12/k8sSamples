<?php
namespace App\Custom;

/**
 * Helps to add container to the crux
 */
use App\Shipment;
use Illuminate\Support\Facades\Http;
use GuzzleHttp\Client;

class AddContainerToCrux
{
    private $shipment;

    public function __construct($shipment)
    {
        // \Log::info("entered to add to crux class");
        $this->shipment = $shipment;
        $this->handle();
    }

    public static function build($shipment)
    {
        (new AddContainerToCrux($shipment));
    }

    private function handle()
    {
        $containers = json_decode($this->shipment->containers_group);
        // \Log::info($containers);

        foreach ($containers as $container) {
            if ($this->isChanged($container)) {
                self::addToCrux($container);
            }
        }
        // \Log::info($containers);
    }

    private function isChanged($container)
    {
        $original_containers = $this->shipment->getOriginal("containers_group");
        // if ($original_containers == $this->shipment->containers_group) {
        //     return false;
        // }
        if ($original_containers) {
            foreach (json_decode($original_containers) as $original_container) {
                if ($original_container->container_num == $container->container_num) {
                    return false;
                }
            }
        }
        return true;
    }


    private static function addToCrux($container)
    {
        // \Log::info("Requesting to the crux server for contianer  " . $container->container_num);
        // $client = new Client();

        $response = Http::withHeaders([
                            //"Authorization" => 'Token token="'.env("CRUX_API_KEY", null).'"',b9f5dcd557a77504947fda980f4a41c2
                            "Authorization" => 'Token token="b9f5dcd557a77504947fda980f4a41c2"',
                            "Content-type" => "application/json"
                        ])
                        ->withBody('{"container": {"number": "'.$container->container_num.'"}}', 'json')
                        ->post('https://track.cruxsystems.com/api/v1/containers');
        // \Log::info($response);
    }

    // public static function addContainerToCruxOnCreate($shipment)
    // {
    // }
}
