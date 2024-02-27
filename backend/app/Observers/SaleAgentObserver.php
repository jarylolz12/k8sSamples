<?php

namespace App\Observers;

use App\SaleAgent;
use App\TRUCKING_User;

class SaleAgentObserver
{
    /**
     * Handle the sale agent "created" event.
     *
     * @param  \App\SaleAgent  $saleAgent
     * @return void
     */
    public function created(SaleAgent $saleAgent)
    {
        //
    }

    /**
     * Handle the sale agent "updating" event.
     *
     * @param  \App\SaleAgent  $saleAgent
     * @return void
     */
    public function updating(SaleAgent $saleAgent)
    {
        //
        // if user has access to trucking app then update the user in trucking app too.
        if ($saleAgent->has_access_to_trucking_app) {
            $user = TRUCKING_User::where("central_app_user_id", "=", $saleAgent->id)->first();
            if (!is_null($user)) {
                if ($saleAgent->isDirty("name")) {
                    $user->name = $saleAgent->name;
                }
                if ($saleAgent->isDirty("email")) {
                    $user->email = $saleAgent->email;
                }
                if ($saleAgent->isDirty("password")) {
                    $user->password = $saleAgent->password;
                }
                $user->save();
            }
        }
    }
    /**
     * Handle the sale agent "updated" event.
     *
     * @param  \App\SaleAgent  $saleAgent
     * @return void
     */
    public function updated(SaleAgent $saleAgent)
    {
        //
    }

    /**
     * Handle the sale agent "deleting" event.
     *
     * @param  \App\SaleAgent  $saleAgent
     * @return void
     */
    public function deleting(SaleAgent $saleAgent)
    {
        //
        // if user has access to trucking app then delete the user from trucking app too.
        if ($saleAgent->has_access_to_trucking_app) {
            $user = TRUCKING_User::where("central_app_user_id", "=", $saleAgent->id)->first();
            if (!is_null($user)) {
                $user->delete();
            }
        }
    }
    /**
     * Handle the sale agent "deleted" event.
     *
     * @param  \App\SaleAgent  $saleAgent
     * @return void
     */
    public function deleted(SaleAgent $saleAgent)
    {
        //
    }

    /**
     * Handle the sale agent "restored" event.
     *
     * @param  \App\SaleAgent  $saleAgent
     * @return void
     */
    public function restored(SaleAgent $saleAgent)
    {
        //
    }

    /**
     * Handle the sale agent "force deleted" event.
     *
     * @param  \App\SaleAgent  $saleAgent
     * @return void
     */
    public function forceDeleted(SaleAgent $saleAgent)
    {
        //
    }
}
