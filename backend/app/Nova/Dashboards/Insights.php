<?php

namespace App\Nova\Dashboards;

use Laravel\Nova\Dashboard;
use App\Metrics\NewCustomers;

class Insights extends Dashboard
{
    /**
     * Get the cards for the dashboard.
     *
     * @return array
     */
    public function cards()
    {
        return [
            new NewCustomers
            //
        ];
    }

    /*
    /**
     * Get the URI key for the dashboard.
     *
     * @return string
     */
    //public static function uriKey()
   // {
    //    return 'insights';
    //}

    /**
     * Get the displayable name of the dashboard.
     *
     * @return string
     */
    public static function label()
    {
        return 'User Insights';
    }


}
