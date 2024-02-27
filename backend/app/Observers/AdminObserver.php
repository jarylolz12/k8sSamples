<?php

namespace App\Observers;

use App\Admin;
use App\TRUCKING_User;

class AdminObserver
{
    /**
     * Handle the admin "created" event.
     *
     * @param  \App\Admin  $admin
     * @return void
     */
    public function created(Admin $admin)
    {
        //
    }

    /**
     * Handle the admin "updating" event.
     *
     * @param  \App\Admin  $admin
     * @return void
     */
    public function updating(Admin $admin)
    {
        //
        // if user has access to trucking app then update the user in trucking app too.
        if ($admin->has_access_to_trucking_app) {
            $user = TRUCKING_User::where("central_app_user_id", "=", $admin->id)->first();
            if (!is_null($user)) {
                if ($admin->isDirty("name")) {
                    $user->name = $admin->name;
                }
                if ($admin->isDirty("email")) {
                    $user->email = $admin->email;
                }
                if ($admin->isDirty("password")) {
                    $user->password = $admin->password;
                }
                $user->save();
            }
        }
    }
    /**
     * Handle the admin "updated" event.
     *
     * @param  \App\Admin  $admin
     * @return void
     */
    public function updated(Admin $admin)
    {
        //
    }

    /**
     * Handle the admin "deleting" event.
     *
     * @param  \App\Admin  $admin
     * @return void
     */
    public function deleting(Admin $admin)
    {
        //
        // if user has access to trucking app then delete the user from trucking app too.
        if ($admin->has_access_to_trucking_app) {
            $user = TRUCKING_User::where("central_app_user_id", "=", $admin->id)->first();
            if (!is_null($user)) {
                $user->delete();
            }
        }
    }
    /**
     * Handle the admin "deleted" event.
     *
     * @param  \App\Admin  $admin
     * @return void
     */
    public function deleted(Admin $admin)
    {
        //
    }

    /**
     * Handle the admin "restored" event.
     *
     * @param  \App\Admin  $admin
     * @return void
     */
    public function restored(Admin $admin)
    {
        //
    }

    /**
     * Handle the admin "force deleted" event.
     *
     * @param  \App\Admin  $admin
     * @return void
     */
    public function forceDeleted(Admin $admin)
    {
        //
    }
}
