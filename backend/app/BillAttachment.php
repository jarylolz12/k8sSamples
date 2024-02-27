<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class BillAttachment extends Model
{
    protected $table = 'bill_attachment';

    protected $fillable = [
        'bill_id',
        'qbo_bill_id',
        'shipment_id',
        'qbo_attachable_id',
        'filename',
        'path'
    ];
}