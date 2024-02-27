<?php

namespace Juliverdevshifl\MilestoneNotesUpdate;

use Laravel\Nova\Fields\Field;

class MilestoneNotesUpdate extends Field
{
    /**
     * The field's component.
     *
     * @var string
     */
    public $component = 'milestone-notes-update';

    public function initFields($data){
        return $this->withMeta($data);
    }
}
