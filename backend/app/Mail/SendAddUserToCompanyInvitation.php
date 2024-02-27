<?php


namespace App\Mail;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class SendAddUserToCompanyInvitation extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public $email;
    public $invitedByUser;
    public $company;
    public $firstName;
    public $lastName;
    public function __construct($email, $invitedByUser, $company, $firstName, $lastName)
    {
        $this->email = $email;
        $this->invitedByUser = $invitedByUser;
        $this->company = $company;
        $this->firstName = $firstName;
        $this->lastName = $lastName;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $invitedByName = $this->invitedByUser->name ?? '';
        $invitedByEmail = $this->invitedByUser->email ?? '';
        $companyName =  $this->company->company_name ?? '';
        $companyId = base64_encode($this->company->id ?? '');
        $firstName =  $this->firstName ?? '';
        $lastName =  $this->lastName ?? '';
        return $this->subject("You have been invited by " . $companyName . " on Shifl platform")->view('mails.send_add_user_to_company_invitation_email')
            ->from('shifl@shifl.com')
            ->with('content', ["toEmail" => $this->email ,"invitedByName" => $invitedByName, "invitedByEmail" => $invitedByEmail, "companyName" => $companyName, "companyId" => $companyId, "firstName" => $firstName, "lastName" => $lastName]);
    }
}
