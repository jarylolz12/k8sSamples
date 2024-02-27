<?php

namespace Vishalmarakana\CancelShipmentCheckbox;

use Laravel\Nova\Fields\Field;

class CancelShipmentCheckbox extends Field
{
    /**
     * The field's component.
     *
     * @var string
     */
    public $component = 'cancel-shipment-checkbox';

    public function initFields($mbl_num)
    {
        return $this->withMeta([
            'mbl_num' => $mbl_num
        ]);
    }


}
