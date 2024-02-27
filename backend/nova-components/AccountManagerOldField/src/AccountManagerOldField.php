<?php

namespace Kenetashi\AccountManagerOldField;

use Laravel\Nova\Fields\Field;

class AccountManagerOldField extends Field
{
    /**
     * The field's component.
     *
     * @var string
     */
    public $component = 'account-manager-old-field';


    public function initFields() {

    	return $this->withMeta([
    		'status' => 'ok',
    		'base_url' => url('/')
    	]);
    }
}
