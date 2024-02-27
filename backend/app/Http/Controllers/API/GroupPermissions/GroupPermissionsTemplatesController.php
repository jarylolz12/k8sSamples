<?php

namespace App\Http\Controllers\API\GroupPermissions;

use App\GroupPermissionsTemplates;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class GroupPermissionsTemplatesController extends Controller
{
    /**
     *  * Get a Group Template from the database.
     *
     * @param  \Illuminate\Http\Request  $request
     */
    public function getGroupPermissionsTemplate(Request $request)
    {
        $statusCode = 200;
        try {
            $data = GroupPermissionsTemplates::where('group_template_id', $request->group_template_id)->get();
            $response = [
                'success' => true,
                'data' => $data,
                'message' => "Group Templates Permissions fetched successfully",
            ];
        } catch (\Exception $e) {
            Log::debug($e->getMessage());
            $response = [
                'success' => false,
                'message' => "Something went wrong"
            ];
            $statusCode=500;
        }
        return response()->json($response, $statusCode);
    }
}
