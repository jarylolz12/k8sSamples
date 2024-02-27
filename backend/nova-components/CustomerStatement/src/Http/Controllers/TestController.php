<?php

namespace Juliverdevshifl\CustomerStatement\Http\Controllers;

use Illuminate\Routing\Controller;
use Illuminate\Http\Request;
use \Juliverdevshifl\CustomerStatement\Models\CustomerStatement as Statement;
use App\Mail\CustomerStatement;
use Carbon\Carbon;

class TestController extends Controller{
    public function handle()
    {  
        $customers =  \App\Customer::whereNotNull('qbo_customer')->where('qbo_customer','!=','')->limit(1)->get();

        // \App\Customer::whereNotNull('qbo_customer')->where('qbo_customer','!=','')->chunk(200,function($customers){
            foreach( $customers as $customer ){
                if( empty($customer->primary_email) ){
                    continue;
                }

                // get the latest statement
                $statement = Statement::where('contact_id',$customer->qbo_id)->latest()->first();

                if( empty($statement) ){
                    $statement = $this->reCreateStatement($customer);
                }else{
                    $statement = $this->createStatement($customer,$statement);
                }

                if( empty($statement) || ( !empty($statement) && count($statement->invoices) == 0 ) ){
                    dd(1);
                    continue;
                }

                $due1_start_date = \App\Invoice::select('due_date')
                ->where('qbo_customer_id',$statement->contact_id)
                ->where('balance','!=','0.00')
                ->where('balance','!=','00.00')
                ->where('is_email_sent',1)
                ->orderBy('due_date','asc')
                ->first();

                $due1_start_date = !empty($due1_start_date) ? $due1_start_date->due_date->format('Y-m-d') : $this->getFinancialDateRaw();

                $due1_date = Carbon::createFromFormat('Y-m-d', $due1_start_date)->addDays(30);
                $due2_date = $due1_date->addDays(60);
                $due3_date = $due2_date->addDays(90);

                // convert to readable
                $due1_date = $due1_date->format('Y-m-d');
                $due2_date = $due2_date->format('Y-m-d');
                $due3_date = $due3_date->format('Y-m-d');

                $due1 = \App\Invoice::where('qbo_customer_id',$statement->contact_id)
                ->where('balance','!=','0.00')
                ->where('balance','!=','00.00')
                ->where('is_email_sent',1)
                ->where('due_date', '<=', $due1_date)
                ->get();

                $due2 = \App\Invoice::where('qbo_customer_id',$statement->contact_id)
                ->where('balance','!=','0.00')
                ->where('balance','!=','00.00')
                ->where('is_email_sent',1)
                ->where('due_date', '>', $due1_date)
                ->where('due_date', '<=', $due2_date)
                ->get();

                $due3 = \App\Invoice::where('qbo_customer_id',$statement->contact_id)
                ->where('balance','!=','0.00')
                ->where('balance','!=','00.00')
                ->where('is_email_sent',1)
                ->where('due_date', '>', $due2_date)
                ->where('due_date', '<=', $due3_date)
                ->get();

                $due4 = \App\Invoice::where('qbo_customer_id',$statement->contact_id)
                ->where('balance','!=','0.00')
                ->where('balance','!=','00.00')
                ->where('is_email_sent',1)
                ->where('due_date', '>=', $due3_date)
                ->get();

                $data = collect($statement)->toArray();

                $statement = Statement::create($data);

                $mail_data = [
                    'documents' => $statement->invoices,
                    'customer_data' => $statement->customer,
                    'company_data' => $statement->company,
                    'statement' => $statement,
                    'due1' => $due1,
                    'due2' => $due2,
                    'due3' => $due3,
                    'due4' => $due4,
                    'statement_date' => date('Y-m-d')
                ];

                $this->initMail($mail_data);

            }
        // });
    }

    private function getFinancialDateRaw(){
        $invoice = \App\Invoice::select('invoice_date')->whereNotNull('invoice_date')->orderBy('invoice_date','asc')->first();

        return !empty($invoice) ? $invoice->invoice_date : date('Y-m-d');
    }

    private function createStatementID(){
        $date = Carbon::now();

        return 'SOA'.$date->month.$date->day.'-'.(Statement::count() > 0 ? Statement::latest()->first()->id+1 : 1 );
    }

    private function createStatement($customer,$statement){
        $company = $this->getCompany();
        $documents = $this->getInvoices($customer->qbo_id,$statement->end_date,date('Y-m-d'));

        if( count($documents) == 0 ){
            return false;
        }

        $q = new \stdClass();
        $q->statement_id = $this->createStatementID();
        // $q->company_id = $company->Id;
        $q->currency = $customer->currency_code;
        $q->json = [ 'invoices' => collect($documents)->map( fn($item) => $item['id'] )->all() ];
        $q->type = 'open item';
        $q->contact_id = $customer->qbo_id;
        $q->customer = $customer;
        $q->company = $company;
        $q->start_date = $this->getFinancialDate();
        $q->end_date = date('Y-m-d');
        $q->opening_balance = $this->getOpeningBalance($customer->qbo_id);
        $q->invoices = $documents;
        $q->amount = collect($q->invoices)->map( fn($item) => ( empty($item->total_amount) ? 0 : floatval($item->total_amount) ) )->reduce( fn($a,$b) => $a+$b,0);
        $q->total_paid_amount = collect($q->invoices)->map( fn($item) => ( empty($item->total_paid_amount) ? 0 : floatval($item->total_paid_amount) ) )->reduce( fn($a,$b) => $a+$b,0);
        $q->amount_due = $q->amount - $q->total_paid_amount;
        $q->closing_balance = ( $q->opening_balance + $q->amount ) - $q->total_paid_amount;

        return $q;
    }

    private function reCreateStatement($customer){
        $company = $this->getCompany();

        $documents = \App\Invoice::where('qbo_customer_id',$customer->qbo_id)
        ->where('balance','!=','0.00')
        ->where('balance','!=','00.00')
        ->where('is_email_sent',1)
        ->whereDate('invoice_date','<=',date('Y-m-d'))
        ->get();

        if( count($documents) == 0 ){
            return false;
        }

        $q = new \stdClass();
        $q->statement_id = $this->createStatementID();
        // $q->company_id = $company->Id;
        $q->currency = $customer->currency_code;
        $q->json = [ 'invoices' => collect($documents)->map( fn($item) => $item['id'] )->all() ];
        $q->type = 'open item';
        $q->contact_id = $customer->qbo_id;
        $q->customer = $customer;
        $q->company = $company;
        $q->start_date = $this->getFinancialDate();
        $q->end_date = date('Y-m-d');
        $q->opening_balance = $this->getOpeningBalance($customer->qbo_id);
        $q->invoices = $documents;
        $q->amount = collect($q->invoices)->map( fn($item) => ( empty($item->total_amount) ? 0 : floatval($item->total_amount) ) )->reduce( fn($a,$b) => $a+$b,0);
        $q->total_paid_amount = collect($q->invoices)->map( fn($item) => ( empty($item->total_paid_amount) ? 0 : floatval($item->total_paid_amount) ) )->reduce( fn($a,$b) => $a+$b,0);
        $q->amount_due = $q->amount - $q->total_paid_amount;
        $q->closing_balance = ( $q->opening_balance + $q->amount ) - $q->total_paid_amount;

        return $q;
    }

    private function getFinancialDate(){
        $invoice = \App\Invoice::select('invoice_date')->whereNotNull('invoice_date')->orderBy('invoice_date','asc')->first();
        return $invoice ? $invoice->invoice_date : date('Y-m-d');
    }

    private function getCompany(){
        // $company = $this->queryQBOCompanyInfo();

        // if( $company['success'] ){

        //     $q = collect(\App\Customer::all())->firstWhere('qbo_id',$company['result']->Id);

        //     if( !empty($q) ){
        //         $cc = $this->getQBOCustomer($q->qbo_id);
        //         $q->currency_code = $cc['success'] ? $cc['result']->CurrencyRef : 'USD';
        //     }

        //     return [ 'company' => $company['result'], 'customer' => empty($q) ? false : $q ];
        // }

        // return false;


        $company = new \stdClass();

        $company->name = 'Shifl';
        $company->address = '343 Spook Rock Rd Suffern NY 10901';
        $company->phone = '';
        $company->email = 'ar@shifl.com';
        $company->currency_code = 'USD';

        return $company;
    }

    private function initMail($data){

        $documents_arr = $data['documents'];
        $company_data = $data['company_data'];
        $customer_data = $data['customer_data'];
        $statement = $data['statement'];

        $email_data = [
            'statement_real_id' => $statement->id,
            'email' => $customer_data->primary_email,
            'shifl_linkedin_url' => 'https://www.linkedin.com/company/shifl?original_referer=https%3A%2F%2Fwww.google.com%2F',
            'shifl_whatsapp_url' => '888-447-4435',
            'download_statement_url' => url('/nova-vendor/customer-statement/download/statement/'.$statement->id),
            'payment_link' => 'https://poynt.godaddy.com/checkout/57fa9f63-6419-423b-89b7-a4589f394dae/shifl-payment',
            'google_app_url' => 'https://play.google.com/store/apps/details?id=com.shifl.app.twa',
            'apple_app_url' => '',
            'statement_date' => date_format(date_create( $statement->created_at ),'Y-m-d' ),
            'statement_id' => $statement->statement_id,
            'customer_data' => [ 'name' => $customer_data->company_name, 'address' => $customer_data->primary_email, 'address' => $customer_data->address, 'phone' => $customer_data->phone ],
            'company_data' => [ 'name' => $company_data->name, 'address' => $company_data->address, 'phone' => $company_data->phone, 'email' => $company_data->email ],
            'currency' => $statement->currency,
            'start_date' => $statement->start_date,
            'end_date' => $statement->end_date,
            'invoices' => $documents_arr,
            'total_amount' => $statement->amount_due,
            'opening_balance' => $statement->opening_balance,
            'total_paid_amount' => $statement->total_paid_amount,
            'closing_balance' => $statement->closing_balance,
            'invoice_amount' => collect($documents_arr)->map(function($item){
                return (float)$item->total_amount;
            })
            ->reduce(function($a,$b){
                return $a+$b;
            },0),
            'total_balance' => collect($documents_arr)->map(function($item){
                return (float)$item->opening_balance;
            })
            ->reduce(function($a,$b){
                return $a+$b;
            },0),
            'due1' => collect($data['due1'])->map(function($item){
                return (float)$item->opening_balance;
            })
            ->reduce(function($a,$b){
                return $a+$b;       
            },0),
            'due2' => collect($data['due2'])->map(function($item){
                return (float)$item->opening_balance;
            })
            ->reduce(function($a,$b){
                return $a+$b;       
            },0),
            'due3' => collect($data['due3'])->map(function($item){
                return (float)$item->opening_balance;
            })
            ->reduce(function($a,$b){
                return $a+$b;       
            },0),
            'due4' => collect($data['due4'])->map(function($item){
                return (float)$item->opening_balance;
            })
            ->reduce(function($a,$b){
                return $a+$b;       
            },0),
        ];

        try{
            $emails = $customer_data->primary_email;
            $emails = explode(',',$emails);
            $emails = collect($emails)->map( fn($item)=> trim($item) )->filter( fn($item) => !empty($item) );

            foreach( $emails as $email ){
                \Mail::to($email)
                ->cc(['ar@shifl.com'])
                ->queue(new CustomerStatement($email_data));
            }
            
            Statement::where('id',$statement->id)->update([ 'sent_count' => ( $statement->sent_count + 1 ) ]);

        } catch (Throwable $e) {
            \report($e);
        }
    }

    private function getInvoices($qbo_customer_id,$start_date,$end_date){
        return \App\Invoice::where('qbo_customer_id',$qbo_customer_id)
            ->where('balance','!=','0.00')
            ->where('balance','!=','00.00')
            ->where('is_email_sent',1)
            ->whereBetween('due_date',[$start_date,$end_date ])
            ->get();
    }

    private function getOpeningBalance($id){
        $invoices = \App\Invoice::where('qbo_customer_id',$id)
            ->where('balance','!=','0.00')
            ->where('balance','!=','00.00')
            ->where('is_email_sent',1)
            ->whereDate('invoice_date','<=',date('Y-m-d'))
            ->get();

        $balance = collect($invoices)->map(function($inv){
            return $inv->opening_balance;
        })->sum();

        return $balance;
    }

    private function queryQBOCompanyInfo(){
        $response = ['success' => false, 'result'=> [], 'errors' => []];
        $quickbooks = app('QuickBooks');
        try{
            $companyInfo = $quickbooks->getDataService()->getCompanyInfo();
            $error = $quickbooks->getDataService()->getLastError();
            if($error){
                return $error->getResponseBody();
            }else{
                $response['success'] = true;
                $response['result'] = $companyInfo;
            }
            return $response;
        }catch(Throwable $e){
            $response['errors'] = $e;
            return $response;
        }
    }

    private function queryQBOByID($table,$id){
        $response = ['success' => false, 'result'=> [], 'errors' => []];
        $quickbooks = app('QuickBooks');
        try{
            $q = $quickbooks->getDataService()->findById($table,$id);
            $error = $quickbooks->getDataService()->getLastError();
            if($error){
                $response['success'] = false;
                $response['errors'] =  $error->getResponseBody();
            }else{
                $response['success'] = true;
                $response['result'] = $q;
            }
            return $response;
        }catch(Throwable $e){
            $response['errors'] = $e;
            return $response;
        }
    }

    private function getQBOCustomer($id){
        $response = ['success' => false, 'result'=> [], 'errors' => []];
        $quickbooks = app('QuickBooks');
        try{
            $q = $quickbooks->getDataService()->findById('Customer',$id);
            $error = $quickbooks->getDataService()->getLastError();
            if($error){
                $response['success'] = false;
                $response['errors'] =  $error->getResponseBody();
            }else{
                $response['success'] = true;
                $response['result'] = $q;
            }
            return $response;
        }catch(Throwable $e){
            $response['errors'] = $e;
            return $response;
        }
    }
}