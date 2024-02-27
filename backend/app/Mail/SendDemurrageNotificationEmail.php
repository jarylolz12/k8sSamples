<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class SendDemurrageNotificationEmail extends Mailable
{
    use Queueable, SerializesModels;
    public $content;

    public function __construct($content)
    {
        $this->content = $content;
    }

    public function build()
    {
        return $this->view('mails.demurrage_notification')
            ->from('shifl@shifl.com')
            ->subject('Demurrage Alert!! : ID# '. $this->content['shifl_ref'] . ', MBL# '.$this->content['mbl_num']);
    }
}
