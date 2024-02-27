<?php

namespace App\Http\Controllers\API\PaymentMethod;

use App\Helpers\Helper;

class PaymentMethodFactory
{
    public static function createCardData($request)
    {
        $returnData = [
            'number' => $request->input('number'),
            'expiration_month' => $request->input('expiration_month'),
            'expiration_year' => $request->input('expiration_year'),
            'default_customer_id' => $request->input('default_customer_id')
        ];
        $returnData['number_hashed'] = Helper::numberHashed($returnData);
        $returnData['cv_data'] = $request->input('cv_data');
        $returnData['number_masked'] = Helper::numberMasked($request->input('number'));
        $returnData['first_name'] = $request->input('cardHolder_first_name');
        $returnData['is_default'] = $request->input('is_default');

        return $returnData;
    }
}
