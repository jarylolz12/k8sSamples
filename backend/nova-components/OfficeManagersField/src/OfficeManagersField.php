<?php

namespace Kenetashi\OfficeManagersField;

use Laravel\Nova\Fields\Field;
use Laravel\Nova\Http\Requests\NovaRequest;
class OfficeManagersField extends Field
{
    /**
     * The field's component.
     *
     * @var string
     */
    public $component = 'office-managers-field';


    public function initFields($context) {


    	$managers_lists = [];

    	if (isset($context->id) && $context->id!==null) {
    		$checkOfficesManagers = \DB::table('shifl_offices_managers')
    								   ->select('manager_id')
    								   ->where('shifl_office_id', $context->id)
    								   ->get();

  			if (count($checkOfficesManagers) > 0) {
  				
  				foreach ($checkOfficesManagers as $key => $value) {
  					array_push($managers_lists, $value->manager_id);
  				}
  			}
    	}

        $response = [
        	'managers_lists_custom' => json_encode($managers_lists),
            'baseUrl' => url('/')
        ];

        return $this->withMeta($response);

    }


    protected function fillAttributeFromRequest(NovaRequest $request,
                                                $requestAttribute,
                                                $model,
                                                $attribute)
    {
        $model->managers_lists = $request->input('managers_lists');
    }

}
