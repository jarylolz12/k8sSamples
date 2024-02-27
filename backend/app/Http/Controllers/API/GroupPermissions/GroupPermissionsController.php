<?php

namespace App\Http\Controllers\API\GroupPermissions;

use App\GroupPermissions;
use App\Groups;
use App\Http\Controllers\Controller;
use App\Modules;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class GroupPermissionsController extends Controller
{
    /**
     * Store a new Group permissions in the database.
     *
     * @param  \Illuminate\Http\Request  $request
     */
    public function store(Request $request)
    {
        $statusCode = 200;
        try {
            $validator = Validator::make($request->all(), [
                'group_id' => 'numeric|required',
                'permissions' => 'array|required',
                'permissions.*' => ['required', 'array'],
                'permissions.*.module_id' => 'required|integer',
                'permissions.*.is_view' => 'required|boolean',
                'permissions.*.is_add' => 'required|boolean',
                'permissions.*.is_update' => 'required|boolean',
                'permissions.*.is_delete' => 'required|boolean',
            ]);

            if ($validator->fails()) {
                return $this->getValidationErrorResponse($validator);
            }
            $existGroup = Groups::find($request->group_id);
            if(!$existGroup){
                return $response = [
                    'success' => false,
                    'message' => "Group not found",
                ];
            }
            $permissions = $request->permissions;
            $permissions = collect($permissions)->map(function($permission) use($request) {
                $newObj = $permission;
                $newObj['group_id'] = $request->group_id;
                return $newObj;
            });
            $moduleIds = collect($permissions)->map(function($permission) {
                return $permission['module_id'];
            });
            $exist = GroupPermissions::where('group_id', $request->group_id)
                ->whereIn('module_id', $moduleIds->toArray())
                ->get();
            $modules = Modules::all();
            if($exist->count() == $modules->count()){
                return [
                    'success' => false,
                    'message' => "Permissions already added in the group",
                ];
            } else {
                $insertableData = $permissions->filter(function($item) use ($exist){
                    $moduleData = $exist->where('module_id', $item["module_id"]);
                    return count($moduleData) == 0;
                });

                $result = GroupPermissions::insert($insertableData->toArray());
                if ($result) {
                    $data = GroupPermissions::where('group_id', $request->group_id)->get();
                    $response = [
                        'success' => true,
                        'data' => $data,
                        'message' => "Group permissions added successfully",
                    ];
                } else {
                    $response = [
                        'success' => false,
                        'message' => "Group permissions not added.",
                    ];
                }
            }
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
     * update a new Group permissions in the database.
     *
     * @param  \Illuminate\Http\Request  $request
     */
    public function update(Request $request)
    {
        $statusCode = 200;
        try {
            $validator = Validator::make($request->all(), [
                'group_id' => 'numeric|required',
                'permissions' => 'array|required',
                'permissions.*' => ['required', 'array'],
                'permissions.*.module_id' => 'required|integer',
                'permissions.*.is_view' => 'required|boolean',
                'permissions.*.is_add' => 'required|boolean',
                'permissions.*.is_update' => 'required|boolean',
                'permissions.*.is_delete' => 'required|boolean',
            ]);

            if ($validator->fails()) {
                return $this->getValidationErrorResponse($validator);
            }
            $existGroup = Groups::find($request->group_id);
            if(!$existGroup){
                return $response = [
                    'success' => false,
                    'message' => "Group not found",
                ];
            }
            $permissions = $request->permissions;
            $permissions = collect($permissions)->map(function($permission) use($request) {
                $newObj = $permission;
                $newObj['group_id'] = $request->group_id;
                return $newObj;
            });
            $moduleIds = collect($permissions)->map(function($permission) {
                return $permission['module_id'];
            });
            $exist = GroupPermissions::where('group_id', $request->group_id)
                ->whereIn('module_id', $moduleIds->toArray())
                ->get();

            $modules = Modules::all();
            if($exist->count() == $modules->count()){
                foreach($permissions->toArray() as $permission) {
                    GroupPermissions::where('group_id', $request->group_id)
                        ->where('module_id', $permission['module_id'])
                        ->update($permission);
                }
                $data = GroupPermissions::where('group_id', $request->group_id)->get();
                $response = [
                    'success' => true,
                    'data' => $data,
                    'message' => "Group permissions updated successfully",
                ];
            } else {
                $insertableData = $permissions->filter(function($item) use ($exist){
                    $moduleData = $exist->where('module_id', $item["module_id"]);
                    return count($moduleData) == 0;
                });
                $updatableData = $permissions->filter(function($item) use ($exist){
                    $moduleData = $exist->where('module_id', $item["module_id"]);
                    return count($moduleData) > 0;
                });
                if($insertableData->count() > 0) {
                    GroupPermissions::insert($insertableData->toArray());
                }
                if($updatableData->count() > 0) {
                    foreach ($updatableData->toArray() as $permission) {
                        GroupPermissions::where('group_id', $request->group_id)
                            ->where('module_id', $permission['module_id'])
                            ->update($permission);
                    }
                }
                $data = GroupPermissions::where('group_id', $request->group_id)->get();
                $response = [
                    'success' => true,
                    'data' => $data,
                    'message' => "Group permissions updated successfully",
                ];
            }
        } catch (\Exception $e) {
            Log::debug($e->getMessage());
            $response = [
                'success' => false,
                'message' => "Something went wrong" . $e->getMessage()
            ];
            $statusCode=500;
        }
        return response()->json($response, $statusCode);
    }

    /**
     * get a new Group permissions in the database.
     *
     * @param  \Illuminate\Http\Request  $request
     */
    public function getGroupPermissions(Request $request)
    {
        $statusCode = 200;
        try {
            $data = GroupPermissions::select('group_permissions.id', 'group_permissions.group_id', 'group_permissions.user_id', 'group_permissions.module_id', 'group_permissions.is_view', 'group_permissions.is_add', 'group_permissions.is_update', 'group_permissions.is_delete', 'group_permissions.created_at', 'group_permissions.updated_at', 'modules.module_name', 'modules.const_name', 'modules.module_description')
                ->join('modules','modules.id', '=' ,'group_permissions.module_id')
                ->where('group_permissions.group_id', $request->group_id)
                ->get();

            $response = [
                'success' => true,
                'data' => $data,
                'message' => "Group permissions fetched successfully",
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

    private function getValidationErrorResponse($validator) {
        $response = [
            'success' => false,
            'message' =>  $validator->errors()->first(),
        ];
        return response()->json($response, 400);
    }
}
