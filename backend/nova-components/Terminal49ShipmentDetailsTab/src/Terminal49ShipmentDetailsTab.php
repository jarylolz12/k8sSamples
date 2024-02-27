<?php

namespace Tanvirofficials\Terminal49ShipmentDetailsTab;

use Laravel\Nova\Fields\Field;

class Terminal49ShipmentDetailsTab extends Field
{
    /**
     * The field's component.
     *
     * @var string
     */
    public $component = 'terminal49-shipment-details-tab';


    public function isAdmin($is_admin)
    {
        return $this->withMeta([
            'is_admin' => $is_admin,
            'scacList' => \App\Terminal49ShippingLine::all()
          ]);
    }
}
