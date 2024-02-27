<?php

namespace Juliverdevshifl\SelectCity;

use Laravel\Nova\Fields\Field;
use Laravel\Nova\Http\Requests\NovaRequest;

class SelectCity extends Field
{
    /**
     * The field's component.
     *
     * @var string
     */
    public $component = 'select-city';

    public function initFields($data){
        return $this->withMeta($data);
    }

    protected function fillAttributeFromRequest(NovaRequest $request,$requestAttribute,$model,$attribute){
        if ($request->exists($requestAttribute)) {
            $model->{$attribute} = $request[$requestAttribute];
        }
    }
}
