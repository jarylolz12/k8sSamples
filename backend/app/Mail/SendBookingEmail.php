<?php

namespace App\Mail;

use App\Customer;
use App\CustomerEmailSettings;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class SendBookingEmail extends Mailable
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
    public function __construct($subject, $content, $attachment, $reply_to = "shifl@shifl.com", $markdown = "mails.sendBookingEmailNew")
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
        $from = 'shifl@shifl.com';
        $customerId = isset($this->content['customer_id']) ? $this->content['customer_id'] : '';
        $customer = Customer::where('id', $customerId)->first();
        if($customer && $customer->is_use_custom_email_template){
            $customerEmailSettings = CustomerEmailSettings::where('customer_id', $customerId)->first();
            if($customerEmailSettings && !empty($customerEmailSettings->from_email)){
                $from = $customerEmailSettings->from_email;
            }
            if($customerEmailSettings && !empty($customerEmailSettings->reply_to_email)){
                $this->reply_to = $customerEmailSettings->reply_to_email;
            }
        }

        $email =  $this->view("mails.sendBookingEmailNew")
                    ->markdown($this->markdown)
                    ->from($from)
                    ->replyTo($this->reply_to)
                    ->subject($this->subject)
                    ->with('content', $this->content);

        foreach ($this->attachment as $item) {
            $email->attach($item);
        }
        return $email;
    }
}
