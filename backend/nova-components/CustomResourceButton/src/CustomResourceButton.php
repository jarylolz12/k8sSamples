<?php

namespace Kenetashi\CustomResourceButton;

use Laravel\Nova\ResourceTool;

class CustomResourceButton extends ResourceTool
{
    /**
     * Get the displayable name of the resource tool.
     *
     * @return string
     */
    public function name()
    {
        return 'Custom Resource Button';
    }

    /**
     * Get the component name for the resource tool.
     *
     * @return string
     */
    public function component()
    {
        return 'custom-resource-button';
    }
}
