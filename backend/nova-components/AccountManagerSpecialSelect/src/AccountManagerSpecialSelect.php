<?php

namespace Kenetashi\AccountManagerSpecialSelect;

use Laravel\Nova\Fields\Field;
use Laravel\Nova\Http\Requests\NovaRequest;
class AccountManagerSpecialSelect extends Field
{
    /**
     * The field's component.
     *
     * @var string
     */
    public $component = 'account-manager-special-select';

    public function __construct(){
    	parent::__construct('Account Managers','offices_managers');
    }

    public function initFields($context) {

        $response = ['status' => 'not ok'];
        $checkManager = (isset($context->id)) ? \App\Customer::find($context->id) : [];

        $response = [
            'baseUrl' => url('/'),
            'offices_managers' => (isset($checkManager->offices_managers)) ? $checkManager->offices_managers : json_encode([]),
        ];

        return $this->withMeta($response);

    }

     protected function fillAttributeFromRequest(NovaRequest $request,
                                                $requestAttribute,
                                                $model,
                                                $attribute)
    {
        //$model->managers = 'not-need';
        $model->offices_managers = $request->input('offices_managers');
    }

    /*
    protected function fillAttributeFromRequest(NovaRequest $request,
                                                $requestAttribute,
                                                $model,
                                                $attribute)
    {

        $model->offices_managers = $request->input('offices_managers');
        /*
        if($request->has('managers')) {
            if($request->input('managers')!=null) {
                $managers = intval($request->input('managers'));
                $model->managers = $managers;
                //$managers = explode(',',$request->input('managers'));
                //if(count($managers)>0) {
                 //   $model->managers = $managers;
               // }
                
            }
        }
        /*
        if($request->has('account_manager_ids') && $request->has('is_create')) {
            if($request->input('account_manager_ids')!=null) {
                //$account_manager_ids = explode(',',$request->input('account_manager_ids'));

                $account_manager_id = intval($request->input('account_manager_ids'));


                $checkLastCustomer = \DB::table('customers')
                                            ->orderBy('id','DESC')
                                            ->first();

                $assumeId = 1;
                if(isset($checkLastCustomer->id)) {
                    $assumeId = intval($checkLastCustomer->id) + 1;
                }
                $build_records = [];

                array_push($build_records,[
                 'customer_id' => $assumeId,
                 'user_id'     => $account_manager_id
                ]);

                \DB::table('customers_has_managers')->insert($build_records);

                /*
                if(count($account_manager_ids)>0) {
                    $checkLastCustomer = \DB::table('customers')
                                            ->orderBy('id','DESC')
                                            ->first();

                    $assumeId = 1;
                    if(isset($checkLastCustomer->id)) {
                        $assumeId = intval($checkLastCustomer->id) + 1;
                    }

                    $build_records = [];
                    $account_manager_ids = $account_manager_ids;
                    foreach ($account_manager_ids as $key => $account_manager_id) {
                        array_push($build_records,[
                         'customer_id' => $assumeId,
                         'user_id'     => $account_manager_id
                        ]);
                    }
                    \DB::table('customers_has_managers')->insert($build_records);
                }*/
        //    }
       // }

        /*
        if ($request->exists($requestAttribute)) {
            $model->{$attribute} = $request[$requestAttribute];
        }*/
    //} */
}