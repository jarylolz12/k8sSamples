<?php

namespace Tanvirofficials\CustomFileField;

use Illuminate\Support\ServiceProvider;
use Laravel\Nova\Events\ServingNova;
use Laravel\Nova\Nova;

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
            Nova::script('custom-file-field', __DIR__.'/../dist/js/field.js');
            Nova::style('custom-file-field', __DIR__.'/../dist/css/field.css');
        });
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        // $this->app->bind('Laravel\Nova\src\fields\File.php', 'Tanvirofficials\CustomFileField\CustomFileField.php');
    }
}
