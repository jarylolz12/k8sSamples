<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class CustomerEmailSettings extends Model
{
    protected $table = 'customer_email_settings';

    protected $fillable = ['customer_id', 'from_email', 'reply_to_email', 'logo', 'theme_settings', 'created_by'];
}
