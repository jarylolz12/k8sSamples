<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Card extends Model
{
    protected $table = 'cards';

    protected $fillable = [
        'id',
        'type',
        'source',
        'expiration_month',
        'expiration_year',
        'card_id',
        'number_masked',
        'number_hashed',
        'first_name',
        'last_name',
        'card_card_id',
        'poynt_token',
        'cardknox_token',
        'customer_admin_id',
        'default_customer_id',
        'is_default'
    ];

    public function customerAdmin() {
        return $this->belongsTo(User::class, 'customer_admin_id');
    }

}
