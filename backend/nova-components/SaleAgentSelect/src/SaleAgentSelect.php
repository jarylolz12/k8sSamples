<?php

namespace Kenetashi\SaleAgentSelect;

use Laravel\Nova\Fields\Field;
use Laravel\Nova\Http\Requests\NovaRequest;
class SaleAgentSelect extends Field
{
    /**
     * The field's component.
     *
     * @var string
     */
    public $component = 'sale-agent-select';
    
    public function __construct(){
    	parent::__construct('Sales Representative','sale_persons');
    }

    public function initFields($context) {

        $response = [
            'baseUrl' => url('/')
        ];

        return $this->withMeta($response);

    }


    /*
    protected function fillAttributeFromRequest(NovaRequest $request,
                                                $requestAttribute,
                                                $model,
                                                $attribute)
    {
        
        if($request->has('sale_persons')) {
            if($request->input('sale_persons')!=null) {
                $sale_persons = intval($request->input('sale_persons'));
                $model->sale_persons = $sale_persons;
            }
        }

        if ($request->has('sale_persons') ) {
            $model->sale_persons = intval($request->input('sale_persons'));
        }

    }*/


}
