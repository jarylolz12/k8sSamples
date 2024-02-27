<?php

namespace App\Policies;

use App\Admin;
use Illuminate\Auth\Access\HandlesAuthorization;

class CustomerPolicy
{
    use HandlesAuthorization;

    public function view($user)
    {
        return true;
       //return false;
    }

    public function viewAny($user)
    {
        return true;
    }

    public function update($user)
    {
        return ($user->hasRole('Super Admin|Account Manager|Sales Representative'));
    }

    public function delete($user)
    {
        return false;
    }

    public function create($user)
    {
        return ($user->hasRole('Super Admin|Account Manager'));
    }
}
