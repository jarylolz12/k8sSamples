<?php

namespace App;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

use Spatie\Permission\Traits\HasRoles;

use Illuminate\Database\Eloquent\Builder;
use Laravel\Passport\HasApiTokens;

use Spinen\QuickBooks\HasQuickBooksToken;

class TRUCKING_User extends Authenticatable
{
    // use Notifiable , HasApiTokens, HasQuickBooksToken;
    // use HasRoles;

    /**
     * The table associated with the model.
     *
     * @var string
     */

    protected $table = 'users';

    // database connection

    /**
     * The database connection that should be used by the model.
     *
     * @var string
     */

    protected $connection = 'mysql-trucking';


    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
      'name', 'email', 'password','phone','central_app_user_id'
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
}
