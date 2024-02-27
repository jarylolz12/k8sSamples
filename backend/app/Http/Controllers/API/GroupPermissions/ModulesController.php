<?php

namespace App\Http\Controllers\API\GroupPermissions;

use App\Http\Controllers\Controller;
use App\Modules;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class ModulesController extends Controller
{
    /**
     * Get a new Module from the database.
     *
     * @param  \Illuminate\Http\Request  $request
     */
    public function getModules(Request $request)
    {
        $statusCode = 200;
        try {
            $data = Modules::all();
            $data = $this->buildTree($data->toArray());
            $response = [
                'success' => true,
                'data' => $data,
                'message' => "Modules fetched successfully",
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

    /**
     * Get a new Module from the database.
     *
     * @param  \Illuminate\Http\Request  $request
     */
    public function getModuleConstant(Request $request)
    {
        $statusCode = 200;
        try {
            $data = Modules::select('const_name')->get();
            $data = $data->map(function ($name) {
                return $name->const_name;
            });
            $response = [
                'success' => true,
                'data' => $data,
                'message' => "Modules fetched successfully",
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

    function buildTree(array $elements, $options = [
        'parent_id_column_name' => 'parent_id',
        'children_key_name' => 'sub_modules',
        'id_column_name' => 'id'], $parentId = null)
    {
        $branch = array();
        foreach ($elements as $element) {
            if ($element[$options['parent_id_column_name']] == $parentId) {
                $children = $this->buildTree($elements, $options, $element[$options['id_column_name']]);
                if ($children) {
                    $element[$options['children_key_name']] = $children;
                }
                $branch[] = $element;
            }
        }
        return $branch;
    }
}
