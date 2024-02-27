<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\ImportNames;

class GetImportNamesController extends Controller
{
    public function index(Request $request)
    {
        $response = [];
        try {
            $customers = Auth::user()->customersApi->pluck('id');
            $get_authenticated_user = Auth::user();
            if (count($customers) > 0) {
                $defaultCustomerId = ($get_authenticated_user->default_customer_id !== null) ? $get_authenticated_user->default_customer_id : $customers[0];
                $ImportNames = ImportNames::where('customer_id', $defaultCustomerId)->get();
                $response = [
                    "status" => 'success',
                    "data" => $ImportNames,
                    "code" => 200
                ];
            }
        } catch (Exception $e) {
            $response = [
                "status" => 'error',
                "message" => $e->getMessage(),
                "code" => ($e->getCode() && $e->getCode() !== 0) ? $e->getCode() : 400
            ];
        }
        return response()->json($response);
    }

    public function getImportName($id)
    {
        $importName = ImportNames::select('import_name')->find($id);
        return response()->json($importName);
    }
}
