<?php

namespace App\Policies;

use App\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class QuickbooksCompanyPolicy
{
    use HandlesAuthorization;

    public function __construct()
    {
        //
    }

    public function viewAny($user)
    {
        return ($user->hasRole('Super Admin'));
    }


    public function view($user)
    {
        return true;
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
