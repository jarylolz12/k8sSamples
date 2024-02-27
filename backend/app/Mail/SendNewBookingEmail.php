<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class SendNewBookingEmail extends Mailable
{
    use Queueable, SerializesModels;
    public $subject;
    public $content;
    public $attachment;
    public $reply_to;
    public $markdown;
    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($subject, $content, $attachment, $reply_to = "shifl@shifl.com", $markdown = "mails.sendNewBookingEmail")
    {
        $this->subject = $subject;
        $this->content = $content;
        $this->attachment = $attachment;
        $this->reply_to = $reply_to;
        $this->markdown = $markdown;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $email =  $this->view("mails.sendNewBookingEmail")
                    ->markdown($this->markdown)
                   //->from('kenjos75@gmail.com')
                   ->from('shifl@shifl.com')
                   ->replyTo($this->reply_to)
                   ->subject($this->subject)
                   ->with('content', $this->content);

        foreach ($this->attachment as $item) {
            $email->attach($item);
        }
        return $email;
    }
}
