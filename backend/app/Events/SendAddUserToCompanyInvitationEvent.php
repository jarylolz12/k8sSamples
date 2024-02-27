<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class SendAddUserToCompanyInvitationEvent
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public $resource;
    public $invitedByUser;
    public $company;
    public $firstName;
    public $lastName;

    public function __construct($resource, $invitedByUser, $company, $firstName, $lastName)
    {
        $this->resource = $resource;
        $this->invitedByUser = $invitedByUser;
        $this->company = $company;
        $this->firstName = $firstName;
        $this->lastName = $lastName;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return Channel|array
     */
    public function broadcastOn()
    {
        return new PrivateChannel('channel-name');
    }
}
