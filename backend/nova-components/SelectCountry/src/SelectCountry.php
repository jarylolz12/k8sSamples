<?php

namespace Juliverdevshifl\SelectCountry;

use Laravel\Nova\Fields\Field;
use Laravel\Nova\Http\Requests\NovaRequest;

class SelectCountry extends Field
{
    /**
     * The field's component.
     *
     * @var string
     */
    public $component = 'select-country';

    public function initFields($data){
        return $this->withMeta($data);
    }

    protected function fillAttributeFromRequest(NovaRequest $request,$requestAttribute,$model,$attribute){
        if ($request->exists($requestAttribute)) {
            $model->{$attribute} = $request[$requestAttribute];
        }
    }
}
