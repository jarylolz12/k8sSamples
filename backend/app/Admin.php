<?php

namespace App;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

use Spatie\Permission\Traits\HasRoles;

use Illuminate\Database\Eloquent\Builder;

class Admin extends Authenticatable
{
    use Notifiable;
    use HasRoles;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password',
    ];

    protected $table = 'users';


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

    public static function boot()
    {

        parent::boot();

        static::created(function (Admin $item) {

            $checkRoleId = \Spatie\Permission\Models\Role::where('name', 'Super Admin')->first();
            
            if (isset($checkRoleId->id)) {
                \DB::table('model_has_roles')->insert([
                    'model_type' => 'App\Admin',
                    'model_id' => $item->id,
                    'role_id'  => $checkRoleId->id
                ]);

                \DB::table('model_has_roles')->insert([
                    'model_type' => 'App\User',
                    'model_id' => $item->id,
                    'role_id'  => $checkRoleId->id
                ]);
            }
        });
    }
}
