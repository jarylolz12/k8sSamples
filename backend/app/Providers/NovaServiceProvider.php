<?php

namespace App\Providers;

use Illuminate\Support\Facades\Gate;
use Laravel\Nova\Cards\Help;
use Laravel\Nova\Nova;
use Laravel\Nova\NovaApplicationServiceProvider;
use Laravel\Nova\Fields\Field;
use App\Observers\CustomerObserver;
use App\Observers\CustomerAdminObserver;
use App\Customer;
use App\CustomerAdmin;
use Kenetashi\BillPaylist\BillPaylist;
use Mwo\AchReport\AchReport;
use Mwo\AchDaily\AchDaily;
use Tanvirofficials\LfdTool\LfdTool;
use Ezea\ProfitMonitor\ProfitMonitor;
use Vishalmarakana\PriceCheck\PriceCheck;
use Juliverdevshifl\ContainerBuyRates\ContainerBuyRates;
use Juliverdevshifl\InvoiceTool\InvoiceTool;
use Shifl\QuickbooksAudits\QuickbooksAudits;
use Tanvirofficials\UntrackedShipments\UntrackedShipments;
use Illuminate\Support\Facades\Auth;
use Vishalmarakana\ShipmentOperations\ShipmentOperations;
use Juliverdevshifl\CustomerStatement\CustomerStatement;

class NovaServiceProvider extends NovaApplicationServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        Field::macro('default', function ($default) {
            return $this->resolveUsing(function ($value) use ($default) {
                return $value ?? $default;
            });
        });

        parent::boot();

        Nova::style('custom-admin', public_path('css/custom-admin.css'));

        Nova::serving(function () {
            Customer::observe(CustomerObserver::class);
            CustomerAdmin::observe(CustomerAdminObserver::class);
        });


    }

    /**
     * Register the Nova routes.
     *
     * @return void
     */
    protected function routes()
    {
        Nova::routes()
                ->withAuthenticationRoutes()
                ->withPasswordResetRoutes()
                ->register();
    }

    /**
     * Register the Nova gate.
     *
     * This gate determines who can access Nova in non-local environments.
     *
     * @return void
     */
    protected function gate()
    {
        Gate::define('viewNova', function ($user) {
            /* return in_array($user->hasRole, [

                'Sales Representative',

                'Super Admin',

                'Account Manager',

            ]); */


            /* return $user->hasAnyRole(['Sales Representative', 'Super Admin', 'Account Manager']); */


            if ($user->hasAnyRole(['Sales Representative', 'Super Admin', 'Account Manager'])){

                return true;

            }


            // If user doesn't have access to nova, log them out.
            // This prevents them for being stuck in 403 page.
            Auth::logout();
            //Session::flush(); return redirect()->route('your route');
        });
    }

    /**
     * Get the cards that should be displayed on the default Nova dashboard.
     *
     * @return array
     */
    protected function cards()
    {
        return [
            new Help,
        ];
    }

    /**
     * Get the extra dashboards that should be displayed on the Nova dashboard.
     *
     * @return array
     */
    protected function dashboards()
    {
        return [];
    }

    /**
     * Get the tools that should be listed in the Nova sidebar.
     *
     * @return array
     */
    public function tools()
    {
        //return [];
        if (\Illuminate\Support\Facades\Auth::user()->hasRole('Super Admin')) {
            return [
                \Vyuldashev\NovaPermission\NovaPermissionTool::make(),
                new \Sbine\RouteViewer\RouteViewer,
                new BillPaylist,
                new LfdTool,
                new AchReport,
                new ProfitMonitor,
                new ContainerBuyRates,
                new InvoiceTool,
                new QuickbooksAudits,
                new UntrackedShipments,
                new PriceCheck,
                new ShipmentOperations,
                new CustomerStatement,
            ];
        } else {
            return [
                new AchReport,
                new ProfitMonitor,
                (new ContainerBuyRates)->canSee(function ($request) {
                    return $request->user()->hasRole('Super Admin') || $request->user()->hasRole('Sales Representative');
                }),
                (new InvoiceTool)->canSee(function ($request) {
                    return $request->user()->hasRole('Super Admin') || $request->user()->hasRole('Sales Representative');
                }),
                new QuickbooksAudits,
                new UntrackedShipments,
                new PriceCheck,
                new ShipmentOperations,
                new CustomerStatement,
            ];
        }
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }
}
