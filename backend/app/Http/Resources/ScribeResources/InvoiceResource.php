<?php

namespace App\Http\Resources\ScribeResources;

use Illuminate\Http\Resources\Json\JsonResource;

class InvoiceResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'id'=>$this->id,
            'qbo_customer_id'=>$this->qbo_customer_id,
            'qbo_customer_name'=>$this->qbo_customer_name,
            'invoice_num'=>$this->invoice_num,
            'qbo_bill_to_email'=>$this->qbo_bill_to_email,
            'qbo_bill_to_address'=>$this->qbo_bill_to_address,
            'term'=>$this->term,
            'invoice_date'=>$this->invoice_date,
            'due_date'=>$this->due_date,
            'shifl_office_from_id'=>$this->shifl_office_from_id,
            'shifl_office_to_id'=>$this->shifl_office_to_id,
            'total_tax'=>$this->total_tax,
            'total_amount'=>$this->total_amount,
            'home_total_amount'=>$this->home_total_amount,
            'balance'=>$this->balance,
            'home_balance'=>$this->home_balance,
            'currency_ref'=>$this->currency_ref,
            'home_currency_ref'=>$this->home_currency_ref,
            'exchange_rate'=>$this->exchange_rate,
            'exchange_rate_info'=>$this->exchange_rate_info,
            'qbo_id'=>$this->qbo_id,
            'qbo_term_id'=>$this->qbo_term_id,
            'qbo_term_name'=>$this->qbo_term_name,
            'qbo_term_days'=>$this->qbo_term_days,
            'qbo_customer_memo'=>$this->qbo_customer_memo,
            'qbo_country'=>$this->qbo_country,
            'qbo_company'=>$this->qbo_company,
            'qbo_company_realmid'=>$this->qbo_company_realmid,
            'qbo_tax_detail'=>$this->qbo_tax_detail,
            'global_tax_calculation'=>$this->global_tax_calculation,
            'allow_credit_card_payment'=>$this->allow_credit_card_payment,
            'allow_online_ach_payment'=>$this->allow_online_ach_payment,
            'is_email_sent'=>$this->is_email_sent,
            'email_sent_count'=>$this->email_sent_count,
            'email_sent_at'=>$this->email_sent_at,
            'qbo_customer_gstin'=>$this->qbo_customer_gstin,
            'sync_token'=>$this->sync_token 
        ];
        //return parent::toArray($request);
    }
}
