<?php

namespace Kenetashi\CustomButton;

use Laravel\Nova\Fields\Field;

class CustomButton extends Field
{
    /**
     * The field's component.
     *
     * @var string
     */
    public $component = 'custom-button';


    public function initFields( $shipment_id ) {

        return $this->withMeta([
            'shipment_id' => $shipment_id,
            'base_url' => url('/')
        ]);
    }
}
