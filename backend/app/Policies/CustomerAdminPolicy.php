<?php

namespace App\Policies;

use Illuminate\Auth\Access\HandlesAuthorization;

class CustomerAdminPolicy
{
    use HandlesAuthorization;

    public function viewAny($user)
    {
        return ($user->hasRole('Super Admin|Customer Admin'));
    }


    public function view($user)
    {
        return true;
    }

    public function update($user)
    {
        return ($user->hasRole('Super Admin|Customer Admin'));
    }

    public function delete($user)
    {
        return ($user->hasRole('Super Admin|Customer Admin'));
    }


    public function create($user)
    {
        return ($user->hasRole('Super Admin|Customer Admin'));
    }
}
