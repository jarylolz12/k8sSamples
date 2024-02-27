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
        'name', 'email', 'password', 'default_customer_id', 'notification_token'
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


    public function cards() {
        return $this->hasMany(Card::class,'customer_admin_id','id');
    }

    public function shipmentNotes() {
        return $this->hasMany(ShipmentNotes::class,'id','created_by');
    }

    public function office() {
        return $this->belongsTo(Office::class,'id','manager_id');
    }

    public function customersApi()
    {
        return $this->belongsToMany(Customer::class, 'customer_admins', 'user_id', 'customer_id')
                    ->where('customer_admins.is_invite_confirm', '=', 1);
    }

    public function relatedCustomerAdmins(){
        $listOfCustomAdmins = [];
        $customerAdminCustomers = $this->customersApi;
        foreach($customerAdminCustomers as $customer){
            $customerAdminsCollection = $customer->customerAdminsApi;
            if(count($customerAdminsCollection)>0){
                $customerAdmins = collect($customerAdminsCollection)->map(function($customerAdmin,$key){
                    return $customerAdmin["id"];
                })->toArray();
                $listOfCustomAdmins = array_merge($listOfCustomAdmins,$customerAdmins);
                $listOfCustomAdmins = array_unique($listOfCustomAdmins);
            }
        }
        return $listOfCustomAdmins;
    }

    public function getCustomerAdminSuppliers(){
        $listOfSuppliers = [];
        $customerAdminCustomers = $this->customersApi;
        foreach($customerAdminCustomers as $customer){
            $customerSuppliers = $customer->suppliers;
            if(count($customerSuppliers) > 0){
                $listOfSuppliers = array_merge($listOfSuppliers,$customerSuppliers->toArray());
                $listOfSuppliers = array_unique($listOfSuppliers, SORT_REGULAR);
            }
        }
        return $listOfSuppliers;
    }

    public function getReportRecipientInArray($id)
    {
        $users = self::where('id', $id)->first();

        $recipients = (is_array(json_decode($users->report_recipients))) ? json_decode($users->report_recipients) : [];
           
        $arRecipients = [];
        foreach($recipients as $key => $value){
            array_push($arRecipients, $value->report_recipients);
        }
        
        return $arRecipients;
    }

}
