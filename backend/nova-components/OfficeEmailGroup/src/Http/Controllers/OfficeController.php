<?php

namespace Kenetashi\OfficeEmailGroup\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\ShiflOffice;
use App\ShiflOfficeEmail;

class OfficeController extends Controller {

    public function findById(Request $request, $id) {

        $officeEmails = ShiflOfficeEmail::where('office_id', $id)->get();
        $office = ShiflOffice::find($id);
        return response()->json([
            'items' => $officeEmails,
            'office' => $office
        ]);
    }

    public function createOfficeEmails(Request $request) {

        $offices = ShiflOffice::all();
        $creations = [];

        foreach( $offices as $office ) {
            array_push($creations, [
                'office_id' => $office->id,
                'type' => 'from'
            ]);

            array_push($creations, [
                'office_id' => $office->id,
                'type' => 'to'
            ]);

            array_push($creations, [
                'office_id' => $office->id,
                'type' => 'all'
            ]);

        }

        ShiflOfficeEmail::insert($creations);
    }
}