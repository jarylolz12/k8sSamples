<?php

namespace App\Http\Controllers\API\CLIENT;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\Request;

/**
 * 
 * @group Shipment
 * 
 * APIs to manage the Shipment resource
 * 
 */
 
class FileDownloadController extends Controller
{

    /** 
     * Downloadable File DO
     * 
     * @authenticated
     *
     * @urlParam id int required
     *
     * @urlParam shifl_ref string required Pdf file name
     * 
     * @response {  
     *      "success": ""
     * }
     * 
     * @response status=404{
     *    "message": "File does not exist."
     * }
     * 
     * @response status=400 scenario="Unauthenticated" {
     *     "message": "Unauthenticated."
     * }
     */
    public function downloadDO(Request $request)
    {
        // check if file exist
        $file = '/public/shipment/pdf/do_#'.$request->shifl_ref.'.pdf';

        if( !Storage::exists($file) ){
            return response()->json([ 'success' => false, 'message' => 'File does not exist.' ]);
        }

        return Storage::download($file);
    }
}