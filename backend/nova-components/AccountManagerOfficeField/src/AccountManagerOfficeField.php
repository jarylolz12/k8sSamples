<?php

namespace Kenetashi\AccountManagerOfficeField;
use Laravel\Nova\Http\Requests\NovaRequest;
use Laravel\Nova\Fields\Field;

class AccountManagerOfficeField extends Field
{
    /**
     * The field's component.
     *
     * @var string
     */
    public $component = 'account-manager-office-field';

    /*
    protected function fillAttributeFromRequest(
        NovaRequest $request,
        $requestAttribute,
        $model,
        $attribute
    ) {
        $model->shifl_office_id = $request->input('shifl_office_id');
    }*/
    
    public function initFields($id) {

    	$response = [
            'office' => '',
            'baseUrl' => url('/')
        ];
        
    	//check matching office
    	$checkOffice = \DB::table('shifl_offices_managers')
    					  ->select('name', 'shifl_offices.id as id')
    					  ->leftJoin('shifl_offices', 'shifl_offices_managers.shifl_office_id', '=', 'shifl_offices.id')
    					  ->where('shifl_offices_managers.manager_id', $id)
    					  ->get();

    	if ( count($checkOffice)>0 ) {
    		
    		if (isset($checkOffice[0]->name)) {
    			$response['office'] = $checkOffice[0];
    		}
    	}
    	return $this->withMeta($response);

    }
}
