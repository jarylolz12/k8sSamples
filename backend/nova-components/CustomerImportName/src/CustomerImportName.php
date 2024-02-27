<?php

namespace Gale\CustomerImportName;

use Laravel\Nova\Fields\Field;
use Laravel\Nova\Http\Requests\NovaRequest;

class CustomerImportName extends Field
{
    /**
     * The field's component.
     *
     * @var string
     */
    public $component = 'customer-import-name';

    public function initFields($fields = null)
    {
        
        return $this->withMeta(['defaultFields' =>  $fields]);
    }

}
