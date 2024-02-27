<?php

namespace Kenetashi\MultipleSelectField;

use Laravel\Nova\Fields\Field;
use Laravel\Nova\Http\Requests\NovaRequest;
class MultipleSelectField extends Field
{
    /**
     * The field's component.
     *
     * @var string
     */
    public $component = 'multiple-select-field';


    protected function fillAttributeFromRequest(
        NovaRequest $request,
        $requestAttribute,
        $model,
        $attribute
    ) {

    	$model->{$attribute} = $request->{$requestAttribute};
    }

    public function loadOptions($options, $emptyFieldsMessage = '', $entity = '', $id = 0) {

    	$options = ($options!==null && $options!=='') ? $options : [];
    	$finalOptions = [];

    	if ( is_array($options) && count($options) > 0) {
    		foreach ($options as $key => $value) {
                if ( isset($value['email']) ) {
                    array_push($finalOptions, [
                        'id' => $value['email'],
                        'text' => $value['email']
                    ]);
                }
	    		
	    		
    		}
    	}

    	 return $this->withMeta([
            'entity' => $entity,
    	 	'options' => $finalOptions,
            'id' => $id,
            'baseUrl' => url('/'),
            'emptyFieldsMessage' => $emptyFieldsMessage
        ]);
    }
}
