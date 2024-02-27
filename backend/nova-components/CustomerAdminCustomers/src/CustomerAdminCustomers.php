<?php

namespace Kenetashi\CustomerAdminCustomers;

use Laravel\Nova\Fields\Field;
use Laravel\Nova\Http\Requests\NovaRequest;

class CustomerAdminCustomers extends Field
{
    /**
     * The field's component.
     *
     * @var string
     */
    public $component = 'customer-admin-customers';
    /*
    public function initFields() {

        $request = new NovaRequest();
        if ( !$request->isResourceIndexRequest() ) {
            
            $customers = [];
            return $this->withMeta(
                [
                    'customers' => $customers
                ]
            
            )
        } else {
            return null;
        }
        
    }*/
}
