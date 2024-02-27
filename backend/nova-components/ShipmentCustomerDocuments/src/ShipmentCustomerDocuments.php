<?php

namespace Kenetashi\ShipmentCustomerDocuments;

use Laravel\Nova\Fields\Field;

class ShipmentCustomerDocuments extends Field
{
    /**
     * The field's component.
     *
     * @var string
     */
    public $component = 'shipment-customer-documents';

    public function initFields($params) {

        return $this->withMeta([
            'shipment_id' => $params['id'],
            'documents' => $params['documents'],
            'suppliers' => $params['suppliers'],
            'base_url' => url('/')
        ]);
    }
}
