<?php

namespace App\Custom;

use BeyondCode\Mailbox\InboundEmail;
use App\Bill;
use App\Vendor;
use App\User;

class PaymentConfirmationMailbox
{
    public function __invoke(InboundEmail $email)
    {
        // Handle the incoming email

        $subject = $email->subject();
        $prefix = 'payment to ';
        $index = strpos($subject, $prefix) + strlen($prefix);
        $data['company'] = trim(substr($subject, $index));

        $string  = trim(preg_replace('/\s+/', ' ',$email->text()));

        $prefixes = ["Amount:", "Method:", "Description:"];
        foreach($prefixes as $prefix) {
            $index = strpos($string, $prefix) + strlen($prefix);
            $remain = substr($string, $index);
            $arr_result = explode(" ", trim($remain));
            $result = trim($arr_result[0]);
            $data[str_replace(":","",strtolower($prefix))] = $result;
        }

        $vendor = Vendor::where('company_name', $data['company'])->first();

        \Log::info('------- vendor -------');
        \Log::info('id: '.$vendor->id);
        \Log::info('company_name: '.$vendor->company_name);
        \Log::info('email: '.$vendor->email);

        $bill = Bill::with('shipment.customer')->where('qbo_bill_num', $data['description'])->first();

        if($bill) {

            $acc_managers = json_decode($bill->shipment->customer->offices_managers, true);

            \Log::info('------- account managers -------');
            \Log::info($acc_managers);

            $acc_mgr_emails = [];

            foreach($acc_managers as $k => $v) {
                $user = User::whereId($v['manager_id'])->first('email');
                if($user && $v['office_id'] == $bill->shifl_office_to_id) {

                    \Log::info('-------  user email -------');
                    \Log::info($user->email);

                    array_push($acc_mgr_emails, $user->email);
                }
            }

            $this->send($email, $vendor, $acc_mgr_emails, $bill);

        } else {
            \Log::info('bill not found.');
        }

    }

    function send($email, $vendor, $acc_mgr_emails, $bill)
    {
        // send to vendor and cc account managers
        if($vendor->email) {

            \Mail::send([], [], function ($message) use($email, $vendor, $acc_mgr_emails, $bill) {

                \Log::info('send to email: '. $vendor->email);
                \Log::info('cc email: '. implode(", ", $acc_mgr_emails));

                $bill_id = $bill->qbo_bill_id ?? 'N/A';
                $carrier = $bill->shipment->carrier->name ?? 'N/A';

                $message->to($vendor->email)
                        ->cc($acc_mgr_emails ?? [])
                        ->subject($email->subject() . ' - ' . $bill_id . ' - ' . $carrier)
                        ->setBody($email->html(), 'text/html');
            });

            \Log::info('Message sent!');

        } else {
            // send to account managers
            \Log::info('sending to account manager');

            \Mail::send([], [], function ($message) use($email, $acc_mgr_emails, $bill) {

                \Log::info('send to email: '. $acc_mgr_emails[0]);

                $bill_id = $bill->qbo_bill_id;
                $carrier = $bill->shipment->carrier->name ?? '';

                $note = '<p style="color:rgb(80,0,80);font-family:Roboto,RobotoDraft,Helvetica,Arial,sans-serif">Please note, vendor email has not been set up yet - please set it up so this confirmation can go directly.</p>
                <p style="color:rgb(80,0,80);font-family:Roboto,RobotoDraft,Helvetica,Arial,sans-serif">Account Manager, Please provide an email for confirmation.</p>';

                $message->to($acc_mgr_emails[0])
                        ->subject($email->subject() . ' - ' . $bill_id . ' - ' . $carrier)
                        ->setBody($email->html() . $note, 'text/html');

            });

        }

    }
}
