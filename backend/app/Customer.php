<?php

namespace App;

use App\Helpers\Helper;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Notifications\Notifiable;

//use Laravel\Nova\Http\Requests\NovaRequest;
class Customer extends Model
{
    use Notifiable;

    protected $appends = ['qbo_id','primary_email','customer_company'];
    protected $fillable = ['company_name', 'address', 'phone','managers','sale_persons','booking_email_recipients', 'loi', 'offices_managers', 'qbo_customer', 'company_key', 'all_email_emails', 'booking_and_updated_emails', 'booking_confirmation_emails', 'agent_booking_confirmation_emails','departure_notice_emails', 'arrival_notice_emails', 'delivery_order_emails', 'eta_alert_emails', 'eta_alert_trucker_emails', 'agent_booking_updated_emails', 'ssfour_doc', 'wnine_doc', 'documents_from', 'approval_requirements', 'customs_approval', 'approved_hs_codes', 'billing_requirements', 'billing_notes'];

    protected $casts = [
        'emails' => 'array',
        'all_email_emails' => 'array',
        'booking_and_updated_emails' => 'array',
        'booking_confirmation_emails' => 'array',
        'agent_booking_confirmation_emails' => 'array',
        'agent_booking_updated_emails' => 'array',
        'departure_notice_emails' => 'array',
        'arrival_notice_emails' => 'array',
        'delivery_order_emails' => 'array',
        'booking_email_recipients' => 'array',
        'eta_alert_emails' => 'array',
        'eta_alert_trucker_emails' => 'array'
    ];

    /*
    public function user()
    {
        return $this->belongsToMany(User::class, 'customers_has_managers');
                   // ->role(new Builder,['account_manager'],'web');
        //return $this->belongsTo(User::class, 'admin_user_id');
        //return $this->belongsToMany(User::class, 'customers_has_managers','customer_id','manager_id');
        //return $this->belongsToMany(User::class, 'customers_has_managers', 'customer_id','manager_id')
          //          ->role(new Builder,['account_manager'],'web');
    }*/

    public static function boot()
    {
        parent::boot();
        static::created(function (Customer $item) {
            if($item->company_name){
                $companyKey = Helper::generateKey($item->company_name);
                $item->company_key = $companyKey;
                $item->save();
            }
            /*
            if($request->has('account_manager_ids') && $request->has('is_create')) {
                if($request->input('account_manager_ids')!=null) {
                    $account_manager_ids = explode(',',$request->input('account_manager_ids'));
                    if(count($account_manager_ids)>0) {
                        $build_records = [];
                        $account_manager_ids = $account_manager_ids;
                        foreach ($account_manager_ids as $key => $account_manager_id) {
                            array_push($build_records, [
                                'customer_id' => $item->id,
                                'user_id' => intval($account_manager_id)
                            ]);
                        }

                        \DB::table('customers_has_managers')->insert($build_records);
                    }
                }
            }

            /*
            if(count($item->account_manager_ids)>0) {

                $build_records = [];
                $account_manager_ids = $item->account_manager_ids;
                foreach ($account_manager_ids as $key => $account_manager_id) {
                    array_push($build_records, [
                        'customer_id' => $item->id,
                        'user_id' => intval($account_manager_id)
                    ]);
                }

                \DB::table('customers_has_managers')->insert($build_records);
            }*/

            /*
            $checkRoleId = \Spatie\Permission\Models\Role::where('name','Account Manager')->first();

            if(isset($checkRoleId->id)) {

                $checkCurrentRoles = Auth::user()->roles;
                if(count($checkCurrentRoles)>0) {
                    $checkCurrentRoles = $checkCurrentRoles->pluck('id')->toArray();
                    if(in_array($checkRoleId->id, $checkCurrentRoles)) {

                        $checkIfExisting = \DB::table('customers_has_managers')
                                              ->where('customer_id',$item->id)
                                              ->where('user_id',Auth::user()->id)
                                              ->first();

                        if(!isset($checkIfExisting->id)) {
                            \DB::table('customers_has_managers')->insert([
                            'customer_id' => $item->id,
                            'user_id'  => Auth::user()->id
                            ]);
                        }
                    }
                }



            }*/
        });
    }

    /*
    public function accountManagers()
    {
        $checkRoleId= \Spatie\Permission\Models\Role::where('name','Account Manager')->first()->id;
        return $this->belongsToMany(User::class, 'customers_has_managers','customer_id','user_id')
            ->where(function(Builder $query) use($checkRoleId) {
                $query->role($checkRoleId);
            });
        /*
        return $this->belongsTo(AccountManager::class, 'customers_has_managers','customer_id','user_id')
            ->where(function(Builder $query) use($checkRoleId) {
                $query->role($checkRoleId);
            });*/
    //}


    /*

    public function managers() {
        $checkRoleId= \Spatie\Permission\Models\Role::where('name','Account Manager')->first()->id;

        return $this->belongsTo(User::class,'managers')
                    ->where(function(Builder $query) use($checkRoleId) {
                        $query->role($checkRoleId);
                    });
    }*/
    public function accountManagers()
    {
        $checkRoleId= \Spatie\Permission\Models\Role::where('name', 'Account Manager')->first()->id;
        return $this->belongsToMany(AccountManager::class, 'customers_has_managers', 'customer_id', 'user_id')
            ->where(function (Builder $query) use ($checkRoleId) {
                $query->role($checkRoleId);
            });
        /*
        return $this->belongsTo(AccountManager::class, 'customers_has_managers','customer_id','user_id')
            ->where(function(Builder $query) use($checkRoleId) {
                $query->role($checkRoleId);
            });*/
    }

    public function manager()
    {
        return $this->hasOne('App\User', 'id', 'managers');
    }

    public function salesRepresentative()
    {
        return $this->hasOne('App\User', 'id', 'sale_persons');
    }


    /*
    public function suppliers()
    {
       return $this->belongsToMany(Supplier::class);
    }*/

    public function suppliers()
    {
        return $this->belongsToMany(Supplier::class, 'customer_supplier', 'customer_id', 'supplier_id');
    }

    public function buyer()
    {
        return $this->belongsToMany(Buyer::class, 'customer_buyer', 'customer_id', 'buyer_id');
    }

    public function customerAdmins()
    {
        $checkRoleId= \Spatie\Permission\Models\Role::where('name', 'Customer Admin')->first()->id;
        return $this->belongsToMany(CustomerAdmin::class, 'customer_admins', 'customer_id', 'user_id')
            ->where(function (Builder $query) use ($checkRoleId) {
                $query->role($checkRoleId);
            });
    }

    public function customerAdminsApi()
    {
        return $this->belongsToMany(CustomerAdmin::class, 'customer_admins', 'customer_id', 'user_id');
    }

    public function shipments()
    {
        return $this->hasMany(Shipment::class);
    }

    public function items(){
        return $this->hasMany(Item::class);
    }

    public function routeNotificationForMail($notification)
    {
        return ['shabsie@shifl.com','raizy@shifl.com','mendy@shifl.com','frady@shifl.com'];
    }

    public function importNames()
    {
        return $this->hasMany(ImportNames::class);
        // return $this->belongsToMany(ImportNames::class, 'customer_import_name', 'customer_id', 'import_name_id');
    }

    public function truckerCustomers()
    {
        return $this->hasMany(TRUCKING_Customer::class,  'central_customer_key', 'company_key')->with('truckers');
    }

    public function getConnectedTruckers()
    {
        return \DB::connection('mysql-trucking')
        ->table('customers')
        ->where('central_customer_key', $this->company_key)
        ->select('customers.central_customer_key','truckers.*')
        ->join('truckers','customers.trucker_id', '=','truckers.id')
        ->get();
    }

    public function getQboIdAttribute(){
        if( !empty($this->qbo_customer) ){
            $q = json_decode($this->qbo_customer);
            return !empty($q) && isset($q->customer) ? $q->customer->Id : null;
        }

        return null;
    }

    public function getCustomerCompanyAttribute(){
        if( !empty($this->qbo_customer) ){
            $q = json_decode($this->qbo_customer);
            return !empty($q) && isset($q->company) ? $q->company : null;
        }

        return null;
    }

    public function getPrimaryEmailAttribute(){
        if( !empty($this->qbo_customer) ){
            $q = json_decode($this->qbo_customer);
            
            if( !empty($q) && isset($q->customer->PrimaryEmailAddr) ){
                if( isset( $q->customer->PrimaryEmailAddr->Address ) ){
                    return $q->customer->PrimaryEmailAddr->Address;
                }else{
                    return $q->customer->PrimaryEmailAddr;
                }
            }
        }


        // if( !empty($this->emails) ){
        //     $q = $this->emails;
            
        //     if( !empty($q) ){
        //         return $q[0]['email'];
        //     }
        // }

        return null;
    }

    public function qboTerm(){
        $quickbooks = app('QuickBooks');

        $q = $quickbooks->getDataService()->findById("Customer",$this->qbo_id);

        $error = $quickbooks->getDataService()->getLastError();

        if( $error ){
            return '';
        }

        return isset($q->SalesTermRef) && isset($q->SalesTermRef->value) ? $q->SalesTermRef->value : '';

        
    }

    public function qboOverDueBalance(){
        if( !$this->qbo_id ) return 'No data';

        $quickbooks = app('QuickBooks');

        $q = $quickbooks->getDataService()->findById("Customer",$this->qbo_id);

        $error = $quickbooks->getDataService()->getLastError();

        if( $error || empty($q) ){
            return 'No data';
        }

        return isset($q->OverDueBalance) ? $q->OverDueBalance : 'No data';
    }

    public function qboLastPayment(){
        if( !$this->qbo_id ) return 'No data';

        $quickbooks = app('QuickBooks');

        $q = $quickbooks->getDataService()->query("select * from Payment where CustomerRef = '".$this->qbo_id."' ORDERBY Id DESC STARTPOSITION 1 MAXRESULTS 1");

        $error = $quickbooks->getDataService()->getLastError();

        if( $error || empty($q) ){
            return 'No data';
        }

        return count($q) > 0 && isset($q[0]->MetaData->CreateTime) ? date_format(date_create($q[0]->MetaData->CreateTime),'F d, Y') : 'No data';
    }

}
