<?php

namespace Kenetashi\MultipleSelectField\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\ShiflOffice;

class OfficeController extends Controller {

    public function findById(Request $request, $id ) {
        $office = ShiflOffice::find($id);

        return response()->json([
            'status' => 'ok',
            'item' => $office   
        ]);
    }
}