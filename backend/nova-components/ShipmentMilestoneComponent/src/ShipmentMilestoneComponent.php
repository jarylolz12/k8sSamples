<?php

namespace Cyrus\ShipmentMilestoneComponent;

use Laravel\Nova\Fields\Field;

class ShipmentMilestoneComponent extends Field
{
    /**
     * The field's component.
     *
     * @var string
     */
    public $component = 'shipment-milestone-component';

    public function initFields($fields = null)
    {
        return $this->withMeta(['defaultFields' =>  $fields]);
    }

}
