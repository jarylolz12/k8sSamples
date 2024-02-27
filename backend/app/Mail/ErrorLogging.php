<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class ErrorLogging extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    private $subjects;
    private $errors;
    public $view;

    public function __construct($subject, $errors, $view)
    {
        //
        $this->subject= $subject;
        $this->view = $view;
        $this->errors = $errors;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->view($this->view)
                    ->subject($this->subject)
                    ->with("errors", $this->errors);
    }
}
