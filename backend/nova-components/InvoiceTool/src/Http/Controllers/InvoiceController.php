<?php

namespace Juliverdevshifl\InvoiceTool\Http\Controllers;

use Illuminate\Routing\Controller;
use Illuminate\Http\Request;

class InvoiceController extends Controller
{
    public function index(Request $request){

    }

    public function get(Request $request){
        
        $per_page = $request->query('per-page') ?? 50;
        $sort_by = $request->query('sortby') && $request->query('sortby') != '' ? $request->query('sortby') : 'created_at';
        $sort_type = $request->query('sorttype') && $request->query('sorttype') != '' ? $request->query('sorttype') : 'desc';

        $response = \App\Invoice::orderBy($sort_by,$sort_type);

        if( !empty($request->input('sent_status')) ){
            $response = $response->where('is_email_sent',$request->input('sent_status'));

            if( (int)$request->input('sent_status') == 1 ){
                $response = $response->whereNotNull('paid_on')->where('paid_on','!=','');
            }
        }

        if( (int)$request->input('paid_status') == 1 ){
            $response = $response->whereNotNull('paid_on')->where('paid_on','!=','');
        }
        
        if( !empty($request->input('customer')) ){
            $response = $response->select(\DB::raw("'' AS invoice_num, qbo_customer_name, '' AS invoice_date, '' AS paid_on, '' AS created_at, (SELECT SUM(total_amount) AS total FROM invoices WHERE qbo_customer_name = '".$request->input('customer')."' GROUP BY total_amount LIMIT 1) AS total_amount, (SELECT COUNT(*) AS total FROM invoices WHERE qbo_customer_name = '".$request->input('customer')."' LIMIT 1) AS total_open, is_email_sent"))->where('qbo_customer_name',$request->input('customer'));


            $response = $response->groupBy('qbo_customer_name')->take(1)->get();
        }else{

            $response = $response->paginate($per_page);
        }

        return response()->json($response);
    }

    public function customer_get(Request $request){
        return response()->json(\App\Customer::groupBy('company_name')->orderBy('company_name')->get()->pluck('company_name'));
    }
}
