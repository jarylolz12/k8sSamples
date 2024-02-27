<?php

namespace Kenetashi\SelectJsonValueField;

use Laravel\Nova\Fields\Field;

class SelectJsonValueField extends Field
{
    /**
     * The field's component.
     *
     * @var string
     */
    public $component = 'select-json-value-field';

    public function jsonField($name) {
    	return $this->withMeta([
    		'data' => $name
    	]);
    }
}
