<?php

namespace Kenetashi\MultipleSelectField\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Customer;

class CustomerController extends Controller {

    public function findById(Request $request, $id ) {
        $customer = Customer::find($id);

        return response()->json([
            'status' => 'ok',
            'item' => $customer   
        ]);
    }
}