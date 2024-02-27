<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class ForwarderMailAdmin extends Mailable
{
    use Queueable, SerializesModels;
    public $subject;
    public $content;
    public $attachment;
    public $markdown;
    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($subject, $markdown, $content)
    {
        //
        $this->subject = $subject;
        $this->markdown = $markdown;
        $this->content = $content;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $email = $this->markdown($this->markdown)
                     ->subject($this->subject)
                     ->with('content', $this->content);
        return $email;
    }
}
