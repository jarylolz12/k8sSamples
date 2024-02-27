<?php

namespace App\Policies;

use App\Admin;
use Illuminate\Auth\Access\HandlesAuthorization;

class AdminPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view any posts.
     *
     * @param  \App\Admin $user
     * @return mixed
     */
    public function viewAny($user)
    {
        return ($user->hasRole('Super Admin'));
    }

    public function view($user)
    {
        return ($user->hasRole('Super Admin'));
    }

    public function update($user)
    {
        return ($user->hasRole('Super Admin'));
    }

    public function delete($user)
    {
        return ($user->hasRole('Super Admin'));
    }


    public function create($user)
    {
        return ($user->hasRole('Super Admin'));
    }
}
