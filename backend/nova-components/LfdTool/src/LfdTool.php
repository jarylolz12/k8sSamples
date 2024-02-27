<?php

namespace Tanvirofficials\LfdTool;

use Laravel\Nova\Nova;
use Laravel\Nova\Tool;

class LfdTool extends Tool
{
    /**
     * Perform any tasks that need to happen when the tool is booted.
     *
     * @return void
     */
    public function boot()
    {
        Nova::script('lfd-tool', __DIR__.'/../dist/js/tool.js');
        Nova::style('lfd-tool', __DIR__.'/../dist/css/tool.css');
    }

    /**
     * Build the view that renders the navigation links for the tool.
     *
     * @return \Illuminate\View\View
     */
    public function renderNavigation()
    {
        return view('lfd-tool::navigation');
    }
}
