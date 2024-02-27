<?php

namespace App;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

use Spatie\Permission\Traits\HasRoles;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Mail;
use App\Mail\TestingEmail;

class CustomerAdmin extends Authenticatable
{
    use Notifiable;
    use HasRoles;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password', 'is_warehouse_employee',
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
        'report_recipients' => 'array',
        'ach_report_recipients' => 'array'
    ];



    public static function boot()
    {
        parent::boot();

        static::created(function (CustomerAdmin $item) {
            $checkRoleId = \Spatie\Permission\Models\Role::where('name', 'Customer Admin')->first();

            if (isset($checkRoleId->id)) {
                \DB::table('model_has_roles')->insert([
                    'model_type' => 'App\CustomerAdmin',
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

    public function customers()
    {
        $checkRoleId= \Spatie\Permission\Models\Role::where('name', 'Customer Admin')->first()->id;
        return $this->belongsToMany(Customer::class, 'customer_admins', 'customer_id', 'user_id')
            ->where(function (Builder $query) use ($checkRoleId) {
                $query->role($checkRoleId);
            });
        /*
        return $this->belongsTo(AccountManager::class, 'customers_has_managers','customer_id','user_id')
            ->where(function(Builder $query) use($checkRoleId) {
                $query->role($checkRoleId);
            });*/
    }

    public function customersApi()
    {
        return $this->belongsToMany(Customer::class, 'customer_admins', 'user_id', 'customer_id');
    }

    public function EmailReportSchedule(){
        return $this->hasMany(EmailReportSchedule::class);
    }
}
