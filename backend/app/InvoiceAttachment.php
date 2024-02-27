<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class InvoiceAttachment extends Model
{
    protected $table = 'invoice_attachments';

    protected $fillable = [
        'invoice_id',
        'qbo_invoice_id',
        'shipment_id',
        'qbo_attachable_id',
        'mime_type',
        'file_name',
        'file_path',
        'include_on_send',
        'is_invoice_origin',
    ];
}
