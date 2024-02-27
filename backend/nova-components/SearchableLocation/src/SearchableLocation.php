<?php

namespace Juliverdevshifl\SearchableLocation;

use Laravel\Nova\Fields\Field;

class SearchableLocation extends Field
{
    /**
     * The field's component.
     *
     * @var string
     */
    public $component = 'searchable-select';

    public function initFields($data){

        $t = file_get_contents($data['data']);

        $data['data'] = $t;

        return $this->withMeta($data);
    }
}
