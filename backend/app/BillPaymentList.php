<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class BillPaymentList extends Model
{

    protected $fillable = ['amount', 'account_representative_id', 'bill_id'];
    protected $table = 'bill_paylists';


    public function bill_num()
    {
        return $this->belongsTo(Bill::class, 'bill_id')->qbo_bill_num;
    }

    public function bill()
    {
        return $this->belongsTo(Bill::class, 'bill_id');
    }

    public function account_manager()
    {
        return $this->belongsTo(User::class, 'account_representative_id');
    }
}