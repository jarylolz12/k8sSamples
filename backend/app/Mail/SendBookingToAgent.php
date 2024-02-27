<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Carbon\Carbon;
use Illuminate\Support\Facades\Storage;

class SendBookingToAgent extends Mailable
{
    use Queueable, SerializesModels;
    public $content;
    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($content)
    {
        $this->content = $content;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $etd = Carbon::parse($this->content['schedules']['items']['etd'])->format('m/d');
        $eta = Carbon::parse($this->content['schedules']['items']['eta'])->format('m/d');
        $ID  = $this->content['shipment']->shifl_ref;
        $files = array_key_exists('files', $this->content) ? $this->content['files'] : [];

        $mailSubjectContainerInfo = ' ';
        foreach($this->content['container_sizes'] as $container_row) {
            $mailSubjectContainerInfo .= $container_row['qty'] . 'X'. $container_row['container_size_name'] . '+';
        }

        if($this->content['agent_booking_sent'] == true){
            $bookingString = 'Updated Booking Request :ID#'.$ID. ' - ETD '. $etd . ' > ' . 'ETA ' . $eta;
        } else {
            $bookingString = 'Booking Request Confirmed :ID#'.$ID. ' - ETD '. $etd . ' > ' . 'ETA ' . $eta;
        }

        $pdfName = 'agent-booking' . '_' . strval($ID) .'.pdf';
        if(!Storage::exists(storage_path().'/agent-booking-email/')){
            Storage::makeDirectory('agent-booking-email');
        }
        \PDF::loadView('pdf.agent_booking_email', ["content" => $this->content])->setWarnings(false)->save(storage_path('app').'/agent-booking-email/'.$pdfName);

        $emailRender = $this->view('mails.SendBookingToAgent')
            ->from('shifl@shifl.com')
            ->subject($bookingString.trim($mailSubjectContainerInfo, '+'))
            ->with('content', $this->content);

        foreach ($files as $file){
            $emailRender->attach($file->getRealPath(),
                [
                    'as' => $file->getClientOriginalName(),
                    'mime' => $file->getClientMimeType()
                ]
            );
        }

        $emailRender->attach(storage_path('app').'/agent-booking-email/'.$pdfName,
            [
                'as' => $pdfName,
                'mime' => 'application/pdf'
            ]
        );
        return $emailRender;
    }
}
