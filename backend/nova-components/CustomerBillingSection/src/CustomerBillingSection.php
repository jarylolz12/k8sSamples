<?php

namespace Kenetashi\CustomerBillingSection;

use Laravel\Nova\Fields\Field;
use Laravel\Nova\Http\Requests\NovaRequest;

class CustomerBillingSection extends Field
{
    /**
     * The field's component.
     *
     * @var string
     */
    public $component = 'customer-billing-section';


    public function initFields($fields) {
        return $this->withMeta($fields);
    }
    

    protected function fillAttributeFromRequest(
        NovaRequest $request,
        $requestAttribute,
        $model,
        $attribute
    ) {

        /*
        if ( $request->has('billing_requirements') ) {
            $model->billing_requirements = $request->input('billing_requirements');
        }*/

        if ( $request->has('billing_notes') ) {
            $model->billing_notes = $request->input('billing_notes');
        }
    }
}
