<?php

namespace Thond1st\InfoSaveButton;

use Laravel\Nova\Fields\Field;

class InfoSaveButton extends Field
{
    /**
     * The field's component.
     *
     * @var string
     */
    public $component = 'info-save-button';

    public function initFields($fields = null)
    {
        return $this->withMeta(['defaultFields' =>  $fields]);
    }
}
