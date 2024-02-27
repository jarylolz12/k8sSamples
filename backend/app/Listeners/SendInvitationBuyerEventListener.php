<?php

namespace App\Listeners;

use App\Events\SendInvitationBuyerEvent;
use App\Mail\SendInvitationBuyer;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Mail;

class SendInvitationBuyerEventListener
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
     * @param SendInvitationBuyerEvent $event
     * @return void
     */
    public function handle(SendInvitationBuyerEvent $event)
    {
        $email = $event->resource;
        $buyer = $event->buyer;
        $customer = $event->customer;

        Mail::to($email)->send(new SendInvitationBuyer($email, $buyer, $customer));
    }
}
