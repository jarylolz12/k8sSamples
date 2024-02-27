<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class DocumentSupplier extends Model
{
    protected $table = 'documents_suppliers';

    protected $fillable = [
        'supplier_id',
        'document_id'
    ];

}