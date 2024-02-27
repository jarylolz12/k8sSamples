<?php

namespace Kenetashi\BillPaylist;

use Laravel\Nova\Nova;
use Laravel\Nova\Tool;

class BillPaylist extends Tool
{
    /**
     * Perform any tasks that need to happen when the tool is booted.
     *
     * @return void
     */
    public function boot()
    {
        Nova::script('bill-paylist', __DIR__.'/../dist/js/tool.js');
        Nova::style('bill-paylist', __DIR__.'/../dist/css/tool.css');
    }

    /**
     * Build the view that renders the navigation links for the tool.
     *
     * @return \Illuminate\View\View
     */
    public function renderNavigation()
    {
        return view('bill-paylist::navigation');
    }
}
