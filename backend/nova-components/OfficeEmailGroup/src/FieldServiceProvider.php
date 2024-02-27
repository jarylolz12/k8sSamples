<?php

namespace Kenetashi\OfficeEmailGroup;

use Illuminate\Support\ServiceProvider;
use Laravel\Nova\Events\ServingNova;
use Laravel\Nova\Nova;
use Illuminate\Support\Facades\Route;
use Kenetashi\OfficeEmailGroup\Http\Middleware\Authorize;

class FieldServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        Nova::serving(function (ServingNova $event) {
            Nova::script('office-email-group', __DIR__.'/../dist/js/field.js');
            Nova::style('office-email-group', __DIR__.'/../dist/css/field.css');
        });
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
        $this->app->booted(function () {
            $this->routes();
        });


        Nova::serving(function (ServingNova $event) {
            Nova::script('office-email-group', __DIR__.'/../dist/js/field.js');
            Nova::style('office-email-group', __DIR__.'/../dist/css/field.css');
        });

    }

    /**
     * Register the tool's routes.
     *
     * @return void
     */
    protected function routes()
    {
        if ($this->app->routesAreCached()) {
            return;
        }

        Route::middleware(['nova', Authorize::class])
                ->prefix('nova-vendor/kenetashi/office-email-group')
                ->group(__DIR__.'/../routes/api.php');
    }
}
