<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class InvitationBuyer extends Model
{
    protected $table = 'invitation_buyer';
    protected $fillable = ['default_customer_id', 'buyer_id', 'status', 'emails'];
}
