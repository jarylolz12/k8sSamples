<?php

namespace App;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

use Spatie\Permission\Traits\HasRoles;

use Illuminate\Database\Eloquent\Builder;

class SaleAgent extends Authenticatable
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

    /*
    public function customers()
    {
        //return $this->belongsToMany(Customer::class, 'customers_has_managers','user_id','customer_id');

        //$checkRoleId= \Spatie\Permission\Models\Role::where('name','Account Manager')->first()->id;
        return $this->hasMany(\App\Customer::class, 'customers_has_managers','user_id','customer_id');

    }*/

    public static function boot()
    {

        parent::boot();

        static::created(function (SaleAgent $item) {

            $checkRoleId = \Spatie\Permission\Models\Role::where('name', 'Sales Representative')->first();
            
            if (isset($checkRoleId->id)) {
                \DB::table('model_has_roles')->insert([
                    'model_type' => 'App\SaleAgent',
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

    /*
    public function customers()
    {
        $checkRoleId= \Spatie\Permission\Models\Role::where('name','Account Manager')->first()->id;
        return $this->belongsToMany(Customer::class, 'customers_has_managers','customer_id','user_id')
            ->where(function(Builder $query) use($checkRoleId) {
                $query->role($checkRoleId);
            });
        /*
        return $this->belongsTo(SalesAgent::class, 'customers_has_managers','customer_id','user_id')
            ->where(function(Builder $query) use($checkRoleId) {
                $query->role($checkRoleId);
            });*/
  //  }
}
