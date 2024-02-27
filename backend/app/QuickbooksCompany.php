<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class QuickbooksCompany extends Model
{
    protected $table = 'quickbooks_companies';

    protected $fillable = ['company_name','company_realm_id','country','currency','currency_code'];
}
