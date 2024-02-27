<?php

namespace Ezea\PoItemField;

use Laravel\Nova\Fields\Field;

class PoItemField extends Field
{
    /**
     * The field's component.
     *
     * @var string
     */
    public $component = 'po-item-field';

    public function customer(int $customer)
    {
        return $this->withMeta(['customer' => $customer]);
    }
}
