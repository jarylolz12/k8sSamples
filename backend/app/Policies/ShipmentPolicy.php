<?php

namespace App\Policies;

use App\Shipment;
use App\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class ShipmentPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view any shipments.
     *
     * @param  \App\User  $user
     * @return mixed
     */
    public function viewAny(User $user)
    {
        return ($user->hasRole('Super Admin|Account Manager|Sales Representative'));
    }

    /**
     * Determine whether the user can view the shipment.
     *User $loggedInUser, User $user
     * @param  \App\User  $user
     * @param  \App\Shipment  $shipment
     * @return mixed
     */
    public function view(User $user, Shipment $shipment)
    {
        return ($user->hasRole('Super Admin|Account Manager|Sales Representative'));
    }

    /**
     * Determine whether the user can create shipments.
     *
     * @param  \App\User  $user
     * @return mixed
     */
    public function create(User $user)
    {
        return ($user->hasRole('Super Admin|Account Manager|Sales Representative'));
    }

    /**
     * Determine whether the user can update the shipment.
     *
     * @param  \App\User  $user
     * @param  \App\Shipment  $shipment
     * @return mixed
     */
    public function update(User $user, Shipment $shipment)
    {
        return ($user->hasRole('Super Admin|Account Manager|Sales Representative'));
    }

    /**
     * Determine whether the user can delete the shipment.
     *
     * @param  \App\User  $user
     * @param  \App\Shipment  $shipment
     * @return mixed
     */
    public function delete(User $user, Shipment $shipment)
    {
        return false;
    }

    /**
     * Determine whether the user can restore the shipment.
     *
     * @param  \App\User  $user
     * @param  \App\Shipment  $shipment
     * @return mixed
     */
    public function restore(User $user, Shipment $shipment)
    {
        return ($user->hasRole('Super Admin|Account Manager|Sales Representative'));
    }

    /**
     * Determine whether the user can permanently delete the shipment.
     *
     * @param  \App\User  $user
     * @param  \App\Shipment  $shipment
     * @return mixed
     */
    public function forceDelete(User $user, Shipment $shipment)
    {
        return false;
    }
}
