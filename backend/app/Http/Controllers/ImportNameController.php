<?php

namespace App\Http\Controllers;

use App\ImportNames;
use Illuminate\Http\Request;

class ImportNameController extends Controller
{
    public function getImportNameByCustomerId($id)
    {
        $response = ['status' => false, 'results' => []] ;
        $importNames = (new Importnames)->getImportNameByCustomerId($id)->get();
        if($importNames){
            $response = ['status' => true, 'results' => $importNames] ;
        }
        return response()->json($response);
         
    }
}
