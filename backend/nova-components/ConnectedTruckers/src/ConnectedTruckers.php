<?php

namespace Tanvirofficials\ConnectedTruckers;

use Laravel\Nova\ResourceTool;

class ConnectedTruckers extends ResourceTool
{
    /**
     * Get the displayable name of the resource tool.
     *
     * @return string
     */
    public function name()
    {
        return 'Connected Truckers';
    }

    /**
     * Get the component name for the resource tool.
     *
     * @return string
     */
    public function component()
    {
        return 'connected-truckers';
    }
}
