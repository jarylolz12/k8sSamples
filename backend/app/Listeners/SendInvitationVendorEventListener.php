<?php

namespace App\Listeners;

use App\Events\SendInvitationVendorEvent;
use App\Mail\SendInvitationVendor;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Mail;

class SendInvitationVendorEventListener
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
     * @param SendInvitationVendorEvent $event
     * @return void
     */
    public function handle(SendInvitationVendorEvent $event)
    {

        $email = $event->resource;
        $vendor = $event->vendor;
        $customer = $event->customer;

        Mail::to($email)->send(new SendInvitationVendor($email, $vendor, $customer));

    }
}
