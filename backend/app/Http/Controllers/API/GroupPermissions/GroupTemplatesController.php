<?php

namespace App\Http\Controllers\API\GroupPermissions;

use App\GroupTemplates;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class GroupTemplatesController extends Controller
{
    /**
     *  * Get a Group Template from the database.
     *
     * @param  \Illuminate\Http\Request  $request
     */
    public function getGroupTemplate(Request $request)
    {
        $statusCode = 200;
        try {
            $data = GroupTemplates::get();
            $response = [
                'success' => true,
                'data' => $data,
                'message' => "Group Templates fetched successfully",
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
