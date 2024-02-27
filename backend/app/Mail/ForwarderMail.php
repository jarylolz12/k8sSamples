<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Storage;

class ForwarderMail extends Mailable
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
        $shifRef = $this->content['shifRef'];
        $pdfName = 'shipment-booking-form' . '-' . $shifRef .'.pdf';
        if(!Storage::exists(storage_path('app').'/shipment-booking-form/')){
            Storage::makeDirectory('shipment-booking-form');
        }
        \PDF::loadView('pdf.shipment_booking_form', ["content" => $this->content])->setWarnings(false)->save(storage_path('app').'/shipment-booking-form/'.$pdfName);

        $email = $this->markdown($this->markdown)
                     ->subject($this->subject)
                     ->with('content', $this->content);

        $email->attach(storage_path('app').'/shipment-booking-form/'.$pdfName,
            [
                'as' => $pdfName,
                'mime' => 'application/pdf'
            ]
        );
        return $email;
    }
}
