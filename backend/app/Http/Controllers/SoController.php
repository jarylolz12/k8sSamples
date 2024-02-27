<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Shipment;

class SoController extends Controller
{

    public function SaveAmsCutOff(Request $request)
    {
        try {
            $shipment = Shipment::find($request->id);
            $shipment->ams_cut_off = $request->ams_cut_off;
            $shipment->save();

            return response()->json($shipment, 200);

        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => "Something went wrong",
            ], 500);
        }

    }
}
