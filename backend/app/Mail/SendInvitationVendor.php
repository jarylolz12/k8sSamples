<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class SendInvitationVendor extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     *
     * @return void
     */

    public $email;
    public $vendor;
    public $customer;

    public function __construct($email, $vendor, $customer)
    {
        $this->email = $email;
        $this->vendor = $vendor;
        $this->customer = $customer;

    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->view('mails.send-invitation-vendor')
            ->from('shifl@shifl.com')
            ->with('content', $this->vendor);
    }
}
