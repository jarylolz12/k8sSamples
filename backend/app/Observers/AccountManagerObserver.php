<?php

namespace App\Observers;

use App\AccountManager;
use App\TRUCKING_User;

class AccountManagerObserver
{
    /**
     * Handle the account manager "created" event.
     *
     * @param  \App\AccountManager  $accountManager
     * @return void
     */
    public function created(AccountManager $accountManager)
    {
        //
    }

    /**
     * Handle the account manager "updating" event.
     *
     * @param  \App\AccountManager  $accountManager
     * @return void
     */
    public function updating(AccountManager $accountManager)
    {
        // if user has access to trucking app then update the user in trucking app too.
        if ($accountManager->has_access_to_trucking_app) {
            $user = TRUCKING_User::where("central_app_user_id", "=", $accountManager->id)->first();
            if (!is_null($user)) {
                if ($accountManager->isDirty("name")) {
                    $user->name = $accountManager->name;
                }
                if ($accountManager->isDirty("email")) {
                    $user->email = $accountManager->email;
                }
                if ($accountManager->isDirty("password")) {
                    $user->password = $accountManager->password;
                }
                $user->save();
            }
        }
    }
    /**
     * Handle the account manager "updated" event.
     *
     * @param  \App\AccountManager  $accountManager
     * @return void
     */
    public function updated(AccountManager $accountManager)
    {
        //
    }

    /**
     * Handle the account manager "deleting" event.
     *
     * @param  \App\AccountManager  $accountManager
     * @return void
     */
    public function deleting(AccountManager $accountManager)
    {
        //
        // if user has access to trucking app then delete the user from trucking app too.
        if ($accountManager->has_access_to_trucking_app) {
            $user = TRUCKING_User::where("central_app_user_id", "=", $accountManager->id)->first();
            if (!is_null($user)) {
                $user->delete();
            }
        }
    }
    /**
     * Handle the account manager "deleted" event.
     *
     * @param  \App\AccountManager  $accountManager
     * @return void
     */
    public function deleted(AccountManager $accountManager)
    {
        //
    }

    /**
     * Handle the account manager "restored" event.
     *
     * @param  \App\AccountManager  $accountManager
     * @return void
     */
    public function restored(AccountManager $accountManager)
    {
        //
    }

    /**
     * Handle the account manager "force deleted" event.
     *
     * @param  \App\AccountManager  $accountManager
     * @return void
     */
    public function forceDeleted(AccountManager $accountManager)
    {
        //
    }
}
