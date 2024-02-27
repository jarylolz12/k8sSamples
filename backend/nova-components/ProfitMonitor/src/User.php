<?php

namespace App;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

use Spatie\Permission\Traits\HasRoles;

use Illuminate\Database\Eloquent\Builder;
use Laravel\Passport\HasApiTokens;

use Spinen\QuickBooks\HasQuickBooksToken;

class User extends Authenticatable
{
    use Notifiable , HasApiTokens, HasQuickBooksToken;
    use HasRoles;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function customersApi()
    {
        return $this->belongsToMany(Customer::class, 'customer_admins', 'user_id', 'customer_id');
    }
}
