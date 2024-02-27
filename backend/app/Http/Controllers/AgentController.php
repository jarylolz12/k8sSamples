<?php

namespace App\Http\Controllers;

use App\Coloaders;
use Illuminate\Http\Request;

class AgentController extends Controller
{
    public function index()
    {
        $response = ['items' => []];

        $vendors  = Coloaders::select('id', 'name')->get();
        $response['items'] = $vendors;
        return response()->json($response);
    }

    public function show($id)
    {
        $response = ['item' => []];

        $response['item'] = Coloaders::find($id);
        return response()->json($response);
    }
}
