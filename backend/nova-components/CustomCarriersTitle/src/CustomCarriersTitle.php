<?php

namespace Pancake\CustomCarriersTitle;

use Laravel\Nova\Fields\Field;

class CustomCarriersTitle extends Field
{
    /**
     * The field's component.
     *
     * @var string
     */
    public $component = 'custom-carriers-title';

    public function textToAdd($text) {
        return $this->withMeta([
            'text' => $text
        ]);
    }
}
