<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class SendInvitationBuyer extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public $email;
    public $buyer;
    public $customer;

    public function __construct($email, $buyer, $customer)
    {
        $this->email = $email;
        $this->buyer = $buyer;
        $this->customer = $customer;

    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->view('mails.send-invitation-buyer')
            ->from('shifl@shifl.com')
            ->with('content', $this->buyer);
    }
}
