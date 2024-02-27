<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class SimpleString extends Mailable
{
    use Queueable, SerializesModels;
    public $subject;
    public $message;
    public $attachment;
    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($subject, $message, $attachment = [])
    {
        //
        $this->subject = $subject;
        $this->message = $message;
        $this->attachment = $attachment;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $email = $this->html($this->message)->subject($this->subject);
        foreach ($this->attachment as $item) {
            $email->attach($item);
        }
        return $email;
    }
}
