<?php

namespace Juliverdevshifl\CustomerStatement\Http\Controllers;

use Illuminate\Routing\Controller;
use Illuminate\Http\Request;
use \Juliverdevshifl\CustomerStatement\Models\CustomerStatement as Statement;
use \Juliverdevshifl\CustomerStatement\Models\CustomerStatementAutomation;
use App\Mail\CustomerStatement;
use Carbon\Carbon;
use PDF;

class CustomerStatementController extends Controller{

    public function singleStatement(Request $request){

        return response()->json(Statement::find($request->statement_id));
    }

    public function list(Request $request){

        if( empty($request->customer) ){
            return response()->json(false);
        }

        $data = Statement::where('contact_id',$request->contact_id);

        if( $request->input('search','') != '' ){
            $data = $data->where('json','like','%"statement_id":"'.$request->input('search').'"%');
        }

        if( $request->input('filter_type','') != '' && $request->input('filter_type') != 'all' ){
            $data = $data->where('type',$request->input('filter_type'));
        }

        if( $request->input('filter_status','') != '' && $request->input('filter_status') != 'all' ){
            $data = $data->where('status',$request->input('filter_status'));
        }

        return response()->json($data->paginate($request->per_page));
    }

    public function company(){
        $company = $customer = $this->getCompany();

        return response()->json([ 'success' => true, 'message' => $company, 'customer' => $customer ]);
    }

    public function customers(Request $request){

        if( $request->id == 'active' ){
            $company = $this->queryQBOCompanyInfo();

            if( $company['success'] ){

                $customers = \App\Customer::all();
                $q = collect($customers)->firstWhere('qbo_id',$company['result']->Id);

                if( !empty($q) ){
                    $cc = $this->getQBOCustomer($q->qbo_id);
                    $q->currency_code = $cc['success'] ? $cc['result']->CurrencyRef : 'USD';
                }

                return response()->json([ 'success' => true, 'message' => empty($q) ? false : $q ]);
            }
        }elseif( $request->id == 'all' ){
            $per_page = $request->input('per_page',false);
            $order_field = $request->input('order_field','company_name');
            $order_type = $request->input('order_type','asc');
            $q = $request->input('q','');

            $customers = \App\Customer::all();
            
            if( $q != '' ){
                $customers = \App\Customer::where('company_name','like','%'.$q.'%')->orderBy($order_field,$order_type)->paginate($per_page);
            }else{
                $customers = \App\Customer::orderBy($order_field,$order_type)->paginate($per_page);
            }

            return response()->json([ 'success' => true, 'message' => $customers ]);
        }else{
            $customer = \App\Customer::find($request->id);

            $cc = $this->getQBOCustomer($customer->qbo_id);
            $customer->currency_code = $cc['success'] ? $cc['result']->CurrencyRef : 'USD';

            if( empty($customer->primary_email) ){
                $customer->primary_email = $cc['success'] && isset($cc['result']->PrimaryEmailAddr->Address) ? $cc['result']->PrimaryEmailAddr->Address : false;
            }

            return response()->json([ 'success' => true, 'message' => $customer ]);
        }
    }

    public function invoices(Request $request){

        if( empty($request->id) ){
            return response()->json([ 'success' => true, 'message' => [] ]);            
        }

        $q = \App\Invoice::where('qbo_customer_id',$request->id)
        ->where(function($q){
            $q->where('balance','!=','0.00')
            ->orWhereNull('balance');
        })
        ->where('is_email_sent',1);


        if( !empty($request->start_date) && !empty($request->end_date) ){
            $q = $q->whereBetween('due_date',[$request->start_date,$request->end_date ]);
        }

        $q = $q->get();
        
        for($i = 0; $i < $q->count(); $i++ ){
            $qbi = $this->queryQBOInvoice($q[$i]->qbo_id);

            if( empty($qbi) ){
                $q[$i]->in_qb = 0;
            }else{
                $q[$i]->in_qb = 1;
                $q[$i]->total_amount = $qbi->TotalAmt;
                $q[$i]->balance = 0;
                $q[$i]->currency_ref = $qbi->CurrencyRef;
                $q[$i]->total_paid_amount = $this->invoiceTotalPaidAmount($q[$i]);
                $q[$i]->opening_balance = $this->invoiceOpeningBalance($q[$i]);
            }
        }

        return response()->json([ 'succes' => true, 'message' => $q, 'dd' => $request->id ]);
    }

    private function invoiceOpeningBalance($data){
        $cc = empty($data->currency_ref) ? 'USD' : $data->currency_ref;
        $precision = config('money.' . $cc. '.precision');

        return round($data->total_amount - $data->total_paid_amount, $precision);
    }

    private function invoiceTotalPaidAmount($data){
        return empty($data->balance) ? 0 : ( $data->total_amount - $data->balance );
    }

    private function queryQBOInvoice($id){
        $quickbooks = app('QuickBooks');

        if( empty($id) ){
            return false;
        }

        $q = $quickbooks->getDataService()->findById("Invoice",$id);

        $error = $quickbooks->getDataService()->getLastError();

        if( $error || empty($q) ){
            return false;
        }

        return $q;
    }

    public function createStatement(Request $request){
        $statement_id = false;
        $statement = false;
        $customer_data = json_decode(json_encode($request->input('customer_data')));
        $company_data = json_decode(json_encode($request->input('company')));
        $documents = $request->input('invoices');

        if( count($documents) == 0 ){
            return response()->json([ 'message' => 'Unable to create statement, no invoices found!'],403);
        }

        $date = Carbon::now();

        $due1_start_date = \App\Invoice::select('due_date')
        ->where('qbo_customer_id',$customer_data->qbo_id)
        ->where(function($q){
            $q->where('balance','!=','0.00')
            ->orWhereNull('balance');
        })
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

        $due1 = \App\Invoice::where('qbo_customer_id',$customer_data->qbo_id)
        ->where(function($q){
            $q->where('balance','!=','0.00')
            ->orWhereNull('balance');
        })
        ->where('is_email_sent',1)
        ->where('due_date', '<=', $due1_date)
        ->get();

        $due2 = \App\Invoice::where('qbo_customer_id',$customer_data->qbo_id)
        ->where(function($q){
            $q->where('balance','!=','0.00')
            ->orWhereNull('balance');
        })
        ->where('is_email_sent',1)
        ->where('due_date', '>', $due1_date)
        ->where('due_date', '<=', $due2_date)
        ->get();

        $due3 = \App\Invoice::where('qbo_customer_id',$customer_data->qbo_id)
        ->where(function($q){
            $q->where('balance','!=','0.00')
            ->orWhereNull('balance');
        })
        ->where('is_email_sent',1)
        ->where('due_date', '>', $due2_date)
        ->where('due_date', '<=', $due3_date)
        ->get();

        $due4 = \App\Invoice::where('qbo_customer_id',$customer_data->qbo_id)
        ->where(function($q){
            $q->where('balance','!=','0.00')
            ->orWhereNull('balance');
        })
        ->where('is_email_sent',1)
        ->where('due_date', '>=', $due3_date)
        ->get();

        if( !empty($request->input('statement_id')) ){
            $statement = Statement::where('statement_id',$request->input('statement_id'))->first();

            if( $statement ){

                $statement_id = $statement->statement_id;
                
                $statement->update([
                    'company' => $company_data,
                    'customer' => $customer_data,
                    'json' => [ 'invoices' => collect($documents)->map( fn($item) => $item['id'] )->all() ],
                    'type'=> $request->input('type'),
                    'contact_id'=> $request->input('contact_id'),
                    'start_date' => $request->input('start_date'),
                    'end_date' => $request->input('end_date'),
                    'amount' => $request->input('amount'),
                    'amount_due' => $request->input('amount_due'),
                    'opening_balance' => $request->input('opening_balance'),
                    'total_paid_amount' => $request->input('total_paid_amount'),
                    'closing_balance' => $request->input('closing_balance'),
                    'status' => $request->input('is_send') == 1 ? 'sent' : 'unsent',
                    'currency' => $request->input('currency_code','USD'),
                ]);
            }

        }else{
            $statement_id = 'SOA'.$date->month.$date->day.'-'.(Statement::count() > 0 ? Statement::latest()->first()->id+1 : 1 );

            $statement = Statement::create([
                'statement_id' => $statement_id,
                // 'company_id' => $company_data->Id,
                'company' => $company_data,
                'customer' => $customer_data,
                'json' => [ 'invoices' => collect($documents)->map( fn($item) => $item['id'] )->all() ],
                'type'=> $request->input('type'),
                'contact_id'=> $request->input('contact_id'),
                'start_date' => $request->input('start_date'),
                'end_date' => $request->input('end_date'),
                'amount' => $request->input('amount'),
                'amount_due' => $request->input('amount_due'),
                'opening_balance' => $request->input('opening_balance'),
                'total_paid_amount' => $request->input('total_paid_amount'),
                'closing_balance' => $request->input('closing_balance'),
                'status' => $request->input('is_send') == 1 ? 'sent' : 'unsent',
                'currency' => $request->input('currency_code','USD'),
                'created_by' => \Auth::user()->id
            ]);

            if( !$statement ){
                return response()->json([ 'message' => 'Unable to create statement.'],403);
            }
        }

        if( $statement_id && $request->input('is_send') == 1 && !empty($customer_data->qbo_customer) && !empty($customer_data->primary_email) ){

            $documents_arr = collect($documents)->toArray();

            $email_data = [
                'statement_real_id' => $statement->id,
                'email' => $customer_data->primary_email,
                'shifl_linkedin_url' => 'https://www.linkedin.com/company/shifl?original_referer=https%3A%2F%2Fwww.google.com%2F',
                'shifl_whatsapp_url' => 'https://api.whatsapp.com/send?phone=888-447-4435',
                'download_statement_url' => url('/nova-vendor/customer-statement/download/statement/'.$statement->id),
                'payment_link' => 'https://poynt.godaddy.com/checkout/57fa9f63-6419-423b-89b7-a4589f394dae/shifl-payment',
                'google_app_url' => 'https://play.google.com/store/apps/details?id=com.shifl.app.twa',
                'apple_app_url' => 'https://apps.apple.com/us/app/shifl/id1569381044',
                'statement_date' => date('Y-m-d'),
                'statement_id' => $statement_id,
                'customer_data' => [ 'name' => $customer_data->company_name, 'address' => $customer_data->primary_email, 'address' => $customer_data->address, 'phone' => $customer_data->phone ],
                'company_data' => [ 'name' => $company_data->name, 'address' => $company_data->address, 'phone' => $company_data->phone, 'email' => $company_data->email ],
                'currency' => $request->input('currency_code','USD'),
                'start_date' => $request->input('start_date'),
                'end_date' => $request->input('end_date'),
                'invoices' => json_decode(json_encode($documents)),
                'total_amount' => $request->input('amount_due'),
                'opening_balance' => $request->input('opening_balance'),
                'total_paid_amount' => $request->input('total_paid_amount'),
                'closing_balance' => $request->input('closing_balance'),
                'invoice_amount' => collect($documents_arr)->map(function($item){
                    return (float)$item['total_amount'];
                })
                ->reduce(function($a,$b){
                    return $a+$b;
                },0),
                'total_balance' => collect($documents_arr)->map(function($item){
                    return (float)$item['opening_balance'];
                })
                ->reduce(function($a,$b){
                    return $a+$b;       
                },0),
                'due1' => collect($due1)->map(function($item){
                    return (float)$item->opening_balance;
                })
                ->reduce(function($a,$b){
                    return $a+$b;       
                },0),
                'due2' => collect($due2)->map(function($item){
                    return (float)$item->opening_balance;
                })
                ->reduce(function($a,$b){
                    return $a+$b;       
                },0),
                'due3' => collect($due3)->map(function($item){
                    return (float)$item->opening_balance;
                })
                ->reduce(function($a,$b){
                    return $a+$b;       
                },0),
                'due4' => collect($due4)->map(function($item){
                    return (float)$item->opening_balance;
                })
                ->reduce(function($a,$b){
                    return $a+$b;       
                },0),
            ];

            $email_data['statement_date_readable'] = $this->readableStatementDate($email_data['statement_date']);

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

        return response()->json([ 'success' => true, 'message' => 'Successfully saved '.( $request->input('is_send') == 1 ? 'and sent' : '' ) ]);
    }

    private function readableStatementDate($date){
        $a = date('F dS Y', strtotime($date));

        if( strpos($a,'0') == 0 ){
            $b = explode(' ',$a);
            $b[1] = str_replace('0','',$b[1]);

            return implode(' ',[$b[1],$b[0].', ',$b[2]]);
        }

        return $a;
    }

    public function getCompany(){
        $company = new \stdClass();

        $company->name = 'Shifl';
        $company->address = '343 Spook Rock Rd Suffern NY 10901';
        $company->phone = '';
        $company->email = 'ar@shifl.com';
        $company->currency_code = 'USD';

        return $company;
    }

    public function deleteStatement(Request $request){

        $request->validate([
            'id' => 'required'
        ]);

        $item = Statement::find($request->id);

        if( $item ){
            $item->delete();
        }

        return response()->json([ 'message' => true, 'message' => 'Successfully deleted' ]);
    }

    public function getFinancialDateRaw(){
        $invoice = \App\Invoice::select('invoice_date')->whereNotNull('invoice_date')->orderBy('invoice_date','asc')->first();

        return !empty($invoice) ? $invoice->invoice_date : date('Y-m-d');
    }

    public function getFinancialDate(){
        return response()->json([ 'success' => true, 'message' => $this->getFinancialDateRaw() ]);   
    }

    public function getStatementOpeningBalance(Request $request)
    {
        $invoices = \App\Invoice::where('qbo_customer_id',$request->contact_id)
            ->where(function($q){
                $q->where('balance','!=','0.00')
                ->orWhereNull('balance');
            })
            ->where('is_email_sent',1)
            ->whereBetween('invoice_date',[$this->getFinancialDateRaw(),$request->start_date])
            ->get();

        $balance = collect($invoices)->map(function($inv){
            return $inv->opening_balance;
        })->sum();

        return response()->json([
            'balance' => $balance,
            'balance_formatted' => money($balance,$request->currency_code,true)->format(),
        ]);
    }

    public function getAutoSendStatus(){
        $q = CustomerStatementAutomation::first();
        return response()->json([ 'success' => $q ? true : false, 'message' => $q ]);
    }

    public function SaveAutoSendStatus(Request $request){
        $data = [ 
            'sched_enabled' => $request->sched_enabled,
            'sched_type' => $request->sched_type,
            'sched_day' => $request->sched_day,
            'sched_time' => $request->sched_time
        ];

        if( !empty($request->id ) ){
            $data['updated_by'] = \Auth::user()->id;
        }else{
            $data['created_by'] = \Auth::user()->id;
        }

        CustomerStatementAutomation::updateOrCreate(
            [ 'id' => $request->id ],
            $data
        );

        return response()->json([ 'success' => true, 'message' => 'Successfully saved!' ]);
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

    public function downloadInitPage(Request $request){
        return view('custom.statement-download-checkpoint',[ 'redirect_url' => url('/nova-vendor/customer-statement/download/statement-doc/'.$request->id) ]);
    }


    public function handleDownload(Request $request){
        if( empty($request->id) || !Statement::where('id',$request->id)->exists() ) abort(404);

        $statement = Statement::find($request->id);
        $customer_data = $statement->customer;
        $company_data = $statement->company;
        $documents_arr = $statement->json;

        $documents_arr = collect($documents_arr->invoices)->map( fn($item) => \App\Invoice::find($item) )->all();

        $date = Carbon::now();

        $due1_start_date = \App\Invoice::select('due_date')
        ->where('qbo_customer_id',$customer_data->qbo_id)
        ->where(function($q){
            $q->where('balance','!=','0.00')
            ->orWhereNull('balance');
        })
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

        $due1 = \App\Invoice::where('qbo_customer_id',$customer_data->qbo_id)
        ->where(function($q){
            $q->where('balance','!=','0.00')
            ->orWhereNull('balance');
        })
        ->where('is_email_sent',1)
        ->where('due_date', '<=', $due1_date)
        ->get();

        $due2 = \App\Invoice::where('qbo_customer_id',$customer_data->qbo_id)
        ->where(function($q){
            $q->where('balance','!=','0.00')
            ->orWhereNull('balance');
        })
        ->where('is_email_sent',1)
        ->where('due_date', '>', $due1_date)
        ->where('due_date', '<=', $due2_date)
        ->get();

        $due3 = \App\Invoice::where('qbo_customer_id',$customer_data->qbo_id)
        ->where(function($q){
            $q->where('balance','!=','0.00')
            ->orWhereNull('balance');
        })
        ->where('is_email_sent',1)
        ->where('due_date', '>', $due2_date)
        ->where('due_date', '<=', $due3_date)
        ->get();

        $due4 = \App\Invoice::where('qbo_customer_id',$customer_data->qbo_id)
        ->where(function($q){
            $q->where('balance','!=','0.00')
            ->orWhereNull('balance');
        })
        ->where('is_email_sent',1)
        ->where('due_date', '>=', $due3_date)
        ->get();

        $data = [
            'statement_real_id' => $statement->id,
            'email' => $customer_data->primary_email,
            'shifl_linkedin_url' => 'https://www.linkedin.com/company/shifl?original_referer=https%3A%2F%2Fwww.google.com%2F',
            'shifl_whatsapp_url' => 'https://api.whatsapp.com/send?phone=888-447-4435',
            'download_statement_url' => '#',
            'payment_link' => 'https://poynt.godaddy.com/checkout/57fa9f63-6419-423b-89b7-a4589f394dae/shifl-payment',
            'google_app_url' => 'https://play.google.com/store/apps/details?id=com.shifl.app.twa',
            'apple_app_url' => 'https://apps.apple.com/us/app/shifl/id1569381044',
            'statement_date' => date('Y-m-d'),
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
            'due1' => collect($due1)->map(function($item){
                return (float)$item->opening_balance;
            })
            ->reduce(function($a,$b){
                return $a+$b;       
            },0),
            'due2' => collect($due2)->map(function($item){
                return (float)$item->opening_balance;
            })
            ->reduce(function($a,$b){
                return $a+$b;       
            },0),
            'due3' => collect($due3)->map(function($item){
                return (float)$item->opening_balance;
            })
            ->reduce(function($a,$b){
                return $a+$b;       
            },0),
            'due4' => collect($due4)->map(function($item){
                return (float)$item->opening_balance;
            })
            ->reduce(function($a,$b){
                return $a+$b;       
            },0),
        ];

        $data['statement_date_readable'] = $this->readableStatementDate($data['statement_date']);

        view()->share('mails.customer-statement-step2',$data);
        $pdf = PDF::loadView('mails.customer-statement-step2', $data);
        // download PDF file with download method
        return $pdf->download('statement-'.$statement->statement_id.'.pdf');
    }

}