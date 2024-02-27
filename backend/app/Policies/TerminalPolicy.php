<?php

namespace App\Policies;

use App\Terminal;
use App\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class TerminalPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view any models.
     *
     * @param  \App\User  $user
     * @return mixed
     */
    public function viewAny(User $user)
    {
        //
        return true;
    }

    /**
     * Determine whether the user can view the model.
     *
     * @param  \App\User  $user
     * @param  \App\Terminal  $terminal
     * @return mixed
     */
    public function view(User $user, Terminal $terminal)
    {
        //
        return true;
    }

    /**
     * Determine whether the user can create models.
     *
     * @param  \App\User  $user
     * @return mixed
     */
    public function create(User $user)
    {
        //
        return true;
    }

    /**
     * Determine whether the user can update the model.
     *
     * @param  \App\User  $user
     * @param  \App\Terminal  $terminal
     * @return mixed
     */
    public function update(User $user, Terminal $terminal)
    {
        //
        return true;
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param  \App\User  $user
     * @param  \App\Terminal  $terminal
     * @return mixed
     */
    public function delete(User $user, Terminal $terminal)
    {
        //
        return false;
    }

    /**
     * Determine whether the user can restore the model.
     *
     * @param  \App\User  $user
     * @param  \App\Terminal  $terminal
     * @return mixed
     */
    public function restore(User $user, Terminal $terminal)
    {
        //
        return true;
    }

    /**
     * Determine whether the user can permanently delete the model.
     *
     * @param  \App\User  $user
     * @param  \App\Terminal  $terminal
     * @return mixed
     */
    public function forceDelete(User $user, Terminal $terminal)
    {
        //
        return false;
    }
}
