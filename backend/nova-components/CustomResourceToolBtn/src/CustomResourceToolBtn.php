<?php

namespace Cyrus\CustomResourceToolBtn;

use Laravel\Nova\ResourceTool;

class CustomResourceToolBtn extends ResourceTool
{
    /**
     * Get the displayable name of the resource tool.
     *
     * @return string
     */
    public function name()
    {
        return 'Custom Resource Tool Btn';
    }

    /**
     * Get the component name for the resource tool.
     *
     * @return string
     */
    public function component()
    {
        return 'custom-resource-tool-btn';
    }
}
