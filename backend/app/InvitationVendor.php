<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class InvitationVendor extends Model
{
    protected $table = 'invitation_vendor';
    protected $fillable = ['default_customer_id', 'vendor_id', 'status', 'emails'];
}

