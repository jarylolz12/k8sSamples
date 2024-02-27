<?php

namespace Juliverdevshifl\CustomerTermField;

use Laravel\Nova\Fields\Field;
use Laravel\Nova\Http\Requests\NovaRequest;

class CustomerTermField extends Field
{
    /**
     * The field's component.
     *
     * @var string
     */
    public $component = 'customer-term-field';

    public function initFields($data){
        return $this->withMeta($data);
    }

    protected function fillAttributeFromRequest(NovaRequest $request,$requestAttribute,$model,$attribute){
        if ($request->exists($requestAttribute)) {
            $model->{$attribute} = $request[$requestAttribute];
        }
    }
}
