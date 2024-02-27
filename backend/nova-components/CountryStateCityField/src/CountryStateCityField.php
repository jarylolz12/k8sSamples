<?php

namespace Noor\CountryStateCityField;

use Laravel\Nova\Fields\Field;

class CountryStateCityField extends Field
{
    /**
     * The field's component.
     *
     * @var string
     */
    public $component = 'country-state-city-field';

    public function initFields($default = null)
    {
        return $this->withMeta(['defaultCountry' =>  $default["country"], 'defaultState' =>  $default["state"], 'defaultCity' =>  $default["city"]]);
    }
    
}
