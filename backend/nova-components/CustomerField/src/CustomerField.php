<?php

namespace Cyrus\CustomerField;

use Laravel\Nova\Fields\Field;

class CustomerField extends Field
{
    /**
     * The field's component.
     *
     * @var string
     */
    public $component = 'customer-field';

    public function initFields($customer = null)
    {
        return $this->withMeta(['defaultCustomer' =>  $customer]);
    }
}
