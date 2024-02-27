<?php
namespace App\Custom;

use App\CustomerAdmin;
use Illuminate\Support\Facades\Mail;
use App\Mail\ShipmentLeave;
use App\Mail\Test;
use App\Customer;

class SendExcelReportMail
{
    private $customerAdmin;
    private $reportOptions;
    private $emailReport;

    public function __construct(CustomerAdmin $customerAdmin, $reportBy = 'BYREFERENCE', $emailReport = null)
    {
        $this->customerAdmin = $customerAdmin;
        $this->reportOptions = $reportBy;
        $this->emailReport = $emailReport;
        $this->emailReport = (gettype($emailReport) == 'array') ? (object) $emailReport : $emailReport;
    }

    public function handle()
    {
        $fileName = $this->store();
        $this->send($fileName);
        return $fileName;
    }


    public function store()
    {
        $current = date('Y_m_d');

        $fileName = 'Shifl Shipment Report_' . $current . '.xlsx';

        if($this->reportOptions == 'BYCONTAINER'){
            $fileName = 'Shifl Shipment Report_By_Container_' . $current . '.xlsx';
        }elseif($this->reportOptions == 'BYREFERENCEADV'){
            $fileName = 'Shifl Shipment Report_By_Ref_Adv_' . $current . '.xlsx';
        }    

        $shipments = $this->shipments()->sortBy('eta', SORT_REGULAR, false);

        // \Excel::store(new \App\Custom\ReportExport($shipments, $this->reportOptions), 'reports/excel/'.$fileName, 'public');
        \Excel::store(new \App\Custom\ReportExport($shipments, $this->reportOptions, $this->emailReport), 'reports/excel/'.$fileName, 'public');
        return $fileName;
    }

    private function shipments()
    {
        $customers = $this->customerAdmin->customersApi;

        $shipments = collect();

        foreach ($customers as $customer) {
            $shipments = $shipments->merge($customer->shipments);
        }

        $selectedCustomer = null;

        if($this->emailReport){
            //display only the shipment for the selected company
            $selectedCustomer = $this->emailReport->selected_customer ?? '';
        }
        
        if($selectedCustomer){
            $shipments = $shipments->where('customer_id', $selectedCustomer);
        }
        // return $shipments;
        return $shipments->filter->isValidForReport()->values();
    }

    private function send($fileName)
    {
        $cc = [];

        $subjects =  "Shipment Report_" . date('m/d/Y');

        $attachment = [];


        $path = storage_path('/app/public/reports/excel/'.$fileName);
        // \Log::info($path);
        if (file_exists($path)) {
            array_push($attachment, $path);
        }

        $to = [];
        $content = [];
        //if it's a company report don't send report to customer admin
        if(isset($this->emailReport->report) && $this->emailReport->report == 1){
            $to = [];
            $content['report'] = $this->emailReport->report;
            $content['company'] = Customer::where('id',$this->emailReport->selected_customer)->pluck('company_name')->first() ?? '';
        }else{
            array_push($to, $this->customerAdmin->email);
        }
        $content['name'] = $this->customerAdmin->name ?? '';

        $bFromAPI = false;

        if($this->emailReport){
            if (is_array($this->emailReport->report_recipients)) {
                $bFromAPI = true;
                foreach ($this->emailReport->report_recipients as $email) {
                    if ($email['report_recipients'] != '' && $email['report_recipients'] != $this->customerAdmin->email) {
                        array_push($cc, $email['report_recipients']);
                    }
                }
            }
        }
        
        if(count($cc) == 0 && !$bFromAPI){
            if (is_array($this->customerAdmin->report_recipients)) {
                foreach ($this->customerAdmin->report_recipients as $email) {
                    if ($email['report_recipients'] != '') {
                        array_push($cc, $email['report_recipients']);
                    }
                }
            }
        }
        $adminUsers = 'shabsie@shifl.com';

        // Mail::to($to)->cc($cc)->bcc($adminUsers)->send(new ShipmentLeave($subjects, $this->customerAdmin->name ?? '', $attachment, "shifl@shifl.com", "mails.reports"));
        Mail::to($to)->cc($cc)->bcc($adminUsers)->send(new ShipmentLeave($subjects, $content, $attachment, "shifl@shifl.com", "mails.reports"));
    }
}
