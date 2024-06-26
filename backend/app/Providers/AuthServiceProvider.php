<?php

namespace App\Providers;

use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;
use Laravel\Passport\Passport;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array
     */
    protected $policies = [
        'App\Model' => 'App\Policies\ModelPolicy',
        'App\Shipment' => 'App\Policies\ShipmentPolicy',
        'App\CustomerAdmin' =>'App\Policies\CustomerAdminPolicy'
    ];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerPolicies();
        Passport::routes();
        //Passport::tokensExpireIn(now()->addMinutes(60));
        Passport::tokensExpireIn(now()->addMinutes(525600));
        Passport::refreshTokensExpireIn(now()->addDays(30));
    }
}
