<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Observers\ShipmentObserver;
use App\Observers\PurchaseOrderObserver;
use App\Observers\ItemObserver;
use App\Shipment;
use App\PurchaseOrder;
use App\Item;

use Illuminate\Support\Collection;
use Illuminate\Pagination\LengthAwarePaginator;


use App\AccountManager;
use App\Admin;
use App\CustomerAdmin;
use App\SaleAgent;
use App\Terminal49Shipment;

use App\Observers\AccountManagerObserver;
use App\Observers\AdminObserver;
use App\Observers\CustomerAdminObserver;
use App\Observers\SaleAgentObserver;
use App\Observers\Terminal49ShipmentObserver;

use App\Custom\AchReportMailbox;
use App\Custom\PaymentConfirmationMailbox;

use Illuminate\Support\Facades\Schema;


 
class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        // $this->app->bind('Laravel\Nova\Http\Controllers\ResourceUpdateController', 'App\Http\Controllers\Nova\ResourceUpdateController');
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        Schema::defaultStringLength(191);
        
        Shipment::observe(ShipmentObserver::class);
        PurchaseOrder::observe(PurchaseOrderObserver::class);
        Item::observe(ItemObserver::class);

        AccountManager::observe(AccountManagerObserver::class);
        Admin::observe(AdminObserver::class);
        CustomerAdmin::observe(CustomerAdminObserver::class);
        SaleAgent::observe(SaleAgentObserver::class);

        //
        Terminal49Shipment::observe(Terminal49ShipmentObserver::class);

        /**
        * Paginate a standard Laravel Collection.
        *
        * @param int $perPage
        * @param int $total
        * @param int $page
        * @param string $pageName
        * @return array
        */
        Collection::macro('paginate', function ($perPage, $total = null, $page = null, $pageName = 'page') {
            $page = $page ?: LengthAwarePaginator::resolveCurrentPage($pageName);

            return new LengthAwarePaginator(
                $this->forPage($page, $perPage),
                $total ?: $this->count(),
                $perPage,
                $page,
                [
                 'path' => LengthAwarePaginator::resolveCurrentPath(),
                 'pageName' => $pageName,
                ]
            );
        });

        // mark
        \Mailbox::catchAll(AchReportMailbox::class);

        // read email and forward
        \Mailbox::to(env('FORWARDER_EMAIL', "forwarder@parse.shifl.com"), PaymentConfirmationMailbox::class);

 


    }
}
