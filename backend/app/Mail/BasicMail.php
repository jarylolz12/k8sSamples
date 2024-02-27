<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class BasicMail extends Mailable
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
    public function __construct($subject, $content, $attachment, $markdown)
    {
        //
        $this->subject = $subject;
        $this->content = $content;
        $this->attachment = $attachment;
        $this->markdown = $markdown;
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

        foreach ($this->attachment as $item) {
            $email->attach($item);
        }
        return $email;
    }
}
