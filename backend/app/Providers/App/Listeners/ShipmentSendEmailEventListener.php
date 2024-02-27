<?php

namespace App\Providers\App\Listeners;

use App\Providers\App\Events\ShipmentSendEmailEvent;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class ShipmentSendEmailEventListener
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  ShipmentSendEmailEvent  $event
     * @return void
     */
    public function handle(ShipmentSendEmailEvent $event)
    {
        //
    }
}
