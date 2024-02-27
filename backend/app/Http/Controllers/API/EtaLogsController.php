<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\EtaLog;
/** 
 * @group Eta Logs
 * 
 * APIs to manage the eta logs resource 
*/
class EtaLogsController extends Controller
{
    /** 
     *  
     * Display the specified resource.
     * 
     * @authenticated
     * 
     * @urlParam mbl_num string required eta_logs String. Example: WHLC031B545167
     * 
     * @apiResource App\Http\Resources\Standard
     * @apiResourceModel App\EtaLog 
     * 
     * @response 404 {
     *      "data"`: []
     * }
     * 
     * @response status=400 scenario="Unauthenticated" {
     *     "message": "Unauthenticated."
     * }
    */ 
    public function index($mbl_num)
    {
        $eta_logs = [];
        if (!empty($mbl_num)) {
            $eta_logs = EtaLog::where("mbl_num", $mbl_num)
                               ->orderBy('id', 'asc')
                               ->get();
        }
        return response($eta_logs, 200);
    }
}
