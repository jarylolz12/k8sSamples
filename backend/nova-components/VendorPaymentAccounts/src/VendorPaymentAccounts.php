<?php

namespace Kenetashi\VendorPaymentAccounts;

use Laravel\Nova\Fields\Field;
use Laravel\Nova\Http\Requests\NovaRequest;
use Illuminate\Support\Facades\Auth;

class VendorPaymentAccounts extends Field
{
    /**
     * The field's component.
     *
     * @var string
     */
    public $component = 'vendor-payment-accounts';


    protected function fillAttributeFromRequest(
        NovaRequest $request,
        $requestAttribute,
        $model,
        $attribute
    ) {
        $currentUserRealmId = Auth::user()->quickbookstoken->realm_id;
    	$model->payment_accounts = $request->input('payment_accounts');
        $model->realm_id = $currentUserRealmId;
    }

}
