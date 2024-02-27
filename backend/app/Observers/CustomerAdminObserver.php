<?php

namespace App\Observers;

use App\CustomerAdmin;
use App\TRUCKING_User;

class CustomerAdminObserver
{
    /**
     * Handle the customer admin "created" event.
     *
     * @param  \App\CustomerAdmin  $customerAdmin
     * @return void
     */
    public function created(CustomerAdmin $customerAdmin)
    {
        //
    }

    /**
     * Handle the customer admin "updating" event.
     *
     * @param  \App\CustomerAdmin  $customerAdmin
     * @return void
     */
    public function updating(CustomerAdmin $customerAdmin)
    {
        //
        // if user has access to trucking app then update the user in trucking app too.
        if ($customerAdmin->has_access_to_trucking_app) {
            $user = TRUCKING_User::where("central_app_user_id", "=", $customerAdmin->id)->first();
            if (!is_null($user)) {
                if ($customerAdmin->isDirty("name")) {
                    $user->name = $customerAdmin->name;
                }
                if ($customerAdmin->isDirty("email")) {
                    $user->email = $customerAdmin->email;
                }
                if ($customerAdmin->isDirty("password")) {
                    $user->password = $customerAdmin->password;
                }
                $user->save();
            }
        }
    }
    /**
     * Handle the customer admin "updated" event.
     *
     * @param  \App\CustomerAdmin  $customerAdmin
     * @return void
     */
    public function updated(CustomerAdmin $customerAdmin)
    {
        //
    }

    /**
     * Handle the customer admin "deleting" event.
     *
     * @param  \App\CustomerAdmin  $customerAdmin
     * @return void
     */
    public function deleting(CustomerAdmin $customerAdmin)
    {
        //
        // if user has access to trucking app then delete the user from trucking app too.
        if ($customerAdmin->has_access_to_trucking_app) {
            $user = TRUCKING_User::where("central_app_user_id", "=", $customerAdmin->id)->first();
            if (!is_null($user)) {
                $user->delete();
            }
        }
    }
    /**
     * Handle the customer admin "deleted" event.
     *
     * @param  \App\CustomerAdmin  $customerAdmin
     * @return void
     */
    public function deleted(CustomerAdmin $customerAdmin)
    {
        //
    }

    /**
     * Handle the customer admin "restored" event.
     *
     * @param  \App\CustomerAdmin  $customerAdmin
     * @return void
     */
    public function restored(CustomerAdmin $customerAdmin)
    {
        //
    }

    /**
     * Handle the customer admin "force deleted" event.
     *
     * @param  \App\CustomerAdmin  $customerAdmin
     * @return void
     */
    public function forceDeleted(CustomerAdmin $customerAdmin)
    {
        //
    }
}
