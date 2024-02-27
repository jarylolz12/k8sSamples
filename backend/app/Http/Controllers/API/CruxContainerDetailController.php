<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\CruxContainer;
use App\Http\Controllers\Controller;
use Carbon\Carbon;

class CruxContainerDetailController extends Controller
{
    //
    // milestones
    // Gate out (origin)
    // Gate in (origin)
    // ETD  (same for all containers) - done
    // ETA (same for all containers) - done
    // Freight release status (same for all containers) - done
    // Customs release status (same for all containers) - done
    // Discharged - done
    // Last free day - done
    // Get out (destination)
    // Empty returned - done
    //
    public function index($container_num)
    {
        $crux_container = CruxContainer::where("container_number", trim($container_num))->first();

        if ($crux_container) {
            $container = json_decode($crux_container->container);
            $vessel = json_decode($crux_container->vessel);
        }

        return response([
            'crux_container' => [
                'container_num' => $container_num,
                'customs_hold' => isset($container->customs_hold) ? $container->customs_hold : null,
                'eta' => isset($vessel->eta) ? Carbon::parse($vessel->eta)->format("Y-m-d") : null ,
                'discharged_at' => isset($container->discharged_at) ? Carbon::parse($container->discharged_at)->format("Y-m-d") : null,
                'last_free_day'  => isset($container->last_free_day) ? $container->last_free_day : null,
                'return_text' => isset($container->return_text) ? $container->return_text : null,
                'departed_at' => isset($container->departed_at) ? $container->departed_at : null,
                'line_hold' => isset($container->line_hold) ? $container->line_hold : null ,
                'returned_at' => isset($container->returned_at) ? Carbon::parse($container->returned_at)->format("Y-m-d") : null ,
                'firms_code' => isset($container->firms_code) ? $container->firms_code : null
            ]
        ]);
    }
}
