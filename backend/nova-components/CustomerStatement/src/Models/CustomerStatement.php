<?php

namespace Juliverdevshifl\CustomerStatement\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class CustomerStatement extends Model
{
    use SoftDeletes;
    
	protected $guarded = ['id'];
    protected $appends = ['invoices'];
    protected $fillable = ['statement_id','company_id','json','start_date','end_date','currency','status','contact_id','type', 'amount', 'amount_due', 'opening_balance', 'closing_balance', 'total_paid_amount','created_by','customer','company'];

    public function emailRecipients(){
        return $this->hasMany('Juliverdevshifl\CustomerStatement\Models\EmailRecipient','statement_id');
    }

    public function getJsonAttribute($value){
        return !empty($value) ? json_decode($value) : [];
    }

    public function setJsonAttribute($value){
        $this->attributes['json'] = json_encode($value);
    }

    public function getCustomerAttribute($value){
        return !empty($value) ? json_decode($value) : [];
    }

    public function setCustomerAttribute($value){
        $this->attributes['customer'] = json_encode($value);
    }

    public function getCompanyAttribute($value){
        return !empty($value) ? json_decode($value) : [];
    }

    public function setCompanyAttribute($value){
        $this->attributes['company'] = json_encode($value);
    }

    public function getCreatedAtAttribute($value){
        return date_format(date_create($value),'Y-m-d');
    }

    public function getStartDateAttribute($value){
        return date_format(date_create($value),'Y-m-d');
    }

    public function getEndDateAttribute($value){
        return date_format(date_create($value),'Y-m-d');
    }

    public function getInvoicesAttribute(){
        $inv_model = \App\Invoice::Class;

        return collect( isset($this->json->invoices) ? $this->json->invoices : [] )->map(function($item) use($inv_model){
            return $inv_model::find($item);
        });
    }

    public function getSentCountAttribute($value){
        return (int)$value;
    }
}