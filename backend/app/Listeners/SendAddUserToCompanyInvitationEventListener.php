<?php

namespace App\Listeners;

use App\Events\SendAddUserToCompanyInvitationEvent;
use App\Mail\SendAddUserToCompanyInvitation;
use Illuminate\Support\Facades\Mail;

class SendAddUserToCompanyInvitationEventListener
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
    }

    /**
     * Handle the event.
     *
     * @param SendAddUserToCompanyInvitationEvent $event
     * @return void
     */
    public function handle(SendAddUserToCompanyInvitationEvent $event)
    {
        $email = $event->resource;
        $invitedByUser = $event->invitedByUser;
        $company = $event->company;
        $firstName = $event->firstName;
        $lastName = $event->lastName;

        Mail::to($email)->send(new SendAddUserToCompanyInvitation($email, $invitedByUser, $company, $firstName, $lastName));
    }
}
