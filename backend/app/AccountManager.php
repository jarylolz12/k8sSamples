<?php

namespace App;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

use Spatie\Permission\Traits\HasRoles;

use Illuminate\Database\Eloquent\Builder;
use Carbon\Carbon;

class AccountManager extends Authenticatable
{
    use Notifiable;
    use HasRoles;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password', 'shifl_office_id', 'phone'
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

        static::created(function (AccountManager $item) {

            $checkRoleId = \Spatie\Permission\Models\Role::where('name', 'Account Manager')->first();
            
            if (isset($checkRoleId->id)) {
                \DB::table('model_has_roles')->insert([
                    'model_type' => 'App\AccountManager',
                    'model_id' => $item->id,
                    'role_id'  => $checkRoleId->id
                ]);
                
                \DB::table('model_has_roles')->insert([
                    'model_type' => 'App\User',
                    'model_id' => $item->id,
                    'role_id'  => $checkRoleId->id
                ]);
            }


            if (isset($item->shifl_office_id) && $item->shifl_office_id!=='') {
                static::saveOffice($item);
            }
    

        });


        static::saved(function (AccountManager $item) {

            if (isset($item->shifl_office_id) && $item->shifl_office_id!=='') {
                static::saveOffice($item);
            }

        });


        static::updated(function (AccountManager $item) {

            if (isset($item->shifl_office_id) && $item->shifl_office_id!=='') {
                static::saveOffice($item);
            }

        });



    }

    public static function saveOffice($getItem) {

        
        $checkOfficesManagers = \DB::table('shifl_offices_managers')
                                   ->where('manager_id', $getItem->id)
                                   ->first();

        if (isset($checkOfficesManagers->id)) {

            //updates now the record
            \DB::table('shifl_offices_managers')
                ->where('manager_id', $getItem->id)
                ->update([
                    'shifl_office_id' => $getItem->shifl_office_id,
                    'updated_at' => Carbon::now()
                ]);
        } else {
            \DB::table('shifl_offices_managers')
               ->insert([[
                    'manager_id' => $getItem->id,
                    'shifl_office_id' => $getItem->shifl_office_id,
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now()
               ]]);
        }

    }

    /*
    public function office()
    {
        
        //return $this->belongsTo(ShiflOffice::class, 'shifl_offices_managers', 'manager_id');
        return $this->belongsTo(ShiflOffice::class, 'shifl_office_id');
    }*/

    public function customers()
    {
        $checkRoleId= \Spatie\Permission\Models\Role::where('name', 'Account Manager')->first()->id;
        return $this->belongsToMany(Customer::class, 'customers_has_managers', 'customer_id', 'user_id')
            ->where(function (Builder $query) use ($checkRoleId) {
                $query->role($checkRoleId);
            });
        /*
        return $this->belongsTo(AccountManager::class, 'customers_has_managers','customer_id','user_id')
            ->where(function(Builder $query) use($checkRoleId) {
                $query->role($checkRoleId);
            });*/
    }


    
}
