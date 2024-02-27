<?php

namespace App\Http\Controllers\API\GroupPermissions;

use App\GroupPermissions;
use App\Groups;
use App\Http\Controllers\Controller;
use App\User;
use Illuminate\Http\Request;
use Auth;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class GroupController extends Controller
{

    /**
     * Get all Group in the database.
     *
     * @param  \Illuminate\Http\Request  $request
     */
    public function getGroupsByCompany(Request $request) {
        $statusCode = 200;
        try {
            $groups = Groups::where('company_id', $request->company_id)
                ->get();

            $groupsWithUsers = $groups->map(function ($group) use ($request) {

                $users = User::select('users.id','users.name','users.email','users.created_at','users.updated_at','customer_admins.id as customer_admin_pk', 'customer_admins.is_invite_confirm as is_customer_invite_confirm', 'customer_admins.group_id', 'groups.group_name', 'customer_admins.group_added_by as group_added_by', 'customer_admins.group_added_at' )
                    ->join('customer_admins','customer_admins.user_id', '=' ,'users.id')
                    ->leftJoin('groups', 'groups.id', '=', 'customer_admins.group_id')
                    ->where('customer_admins.customer_id', $request->company_id)
                    ->where('customer_admins.group_id', $group->id)
                    ->get();

                $permissions = GroupPermissions::select('group_permissions.id', 'group_permissions.group_id', 'group_permissions.user_id', 'group_permissions.module_id', 'group_permissions.is_view', 'group_permissions.is_add', 'group_permissions.is_update', 'group_permissions.is_delete', 'group_permissions.created_at', 'group_permissions.updated_at', 'modules.module_name', 'modules.const_name', 'modules.module_description')
                    ->join('modules','modules.id', '=' ,'group_permissions.module_id')
                    ->where('group_permissions.group_id', $group->id)
                    ->get();

                $data = $users->map(function ($user) {
                    $userBy = User::where('id',$user->group_added_by)
                        ->select('id', 'name')
                        ->first();
                    $user['group_added_by_user'] = $userBy;
                    return $user;
                });
                $group['users'] = $data;
                $group['permissions'] = $permissions;
                return $group;
            });

            $response = [
                'success' => true,
                'data' => $groupsWithUsers,
                'message' => "Groups fetched successfully",
            ];
        } catch (\Exception $e) {
            Log::debug($e->getMessage());
            $response = [
                'success' => false,
                'message' => "Something went wrong",
            ];
            $statusCode = 500;
        }

        return response()->json($response, $statusCode);
    }

    /**
     * Get all Group in the database.
     *
     * @param  \Illuminate\Http\Request  $request
     */
    public function getGroup(Request $request) {
        $statusCode = 200;
        try {
            $group = Groups::where('id', $request->group_id)
                ->first();

            $response = [
                'success' => true,
                'data' => $group,
                'message' => "Groups fetched successfully",
            ];
        } catch (\Exception $e) {
            Log::debug($e->getMessage());
            $response = [
                'success' => false,
                'message' => "Something went wrong",
            ];
            $statusCode = 500;
        }

        return response()->json($response, $statusCode);
    }

    /**
     * Store a new Group in the database.
     *
     * @param  \Illuminate\Http\Request  $request
     */
    public function store(Request $request)
    {
        $statusCode = 200;
        try {
            $user = Auth::user();
            $validator = Validator::make($request->all(), [
                'company_id' => 'numeric|required',
                'group_name' => 'string|required',
                'group_description' => 'string|required'
            ]);

            if ($validator->fails()) {
                return $this->getValidationErrorResponse($validator);
            }
            $checkGroup = Groups::where('company_id', $request->company_id)
                ->where('group_name', $request->group_name)
                ->first();
            if($checkGroup)
            {
                $response = [
                    'success' => false,
                    'message' => $request->group_name . ' is already exists.',
                ];
                return response()->json($response, 400);
            }
            $group = new Groups();
            $group->group_name = $request->group_name;
            $group->group_description = $request->group_description;
            $group->company_id = $request->company_id;
            $group->created_by = $user->id;
            $group->save();
            $response = [
                'success' => true,
                'data' => $group,
                'message' => "Group added successfully",
            ];
        } catch (\Exception $e) {
            Log::debug($e->getMessage());
            $response = [
                'success' => false,
                'message' => $e->getMessage(),
            ];
            $statusCode=500;
        }
        return response()->json($response, $statusCode);
    }

    /**
     * Update a Group in the database.
     *
     * @param  \Illuminate\Http\Request  $request
     */
    public function update(Request $request)
    {
        $statusCode = 200;
        try {
            $user = Auth::user();
            $validator = Validator::make($request->all(), [
                'company_id' => 'numeric|required',
                'group_name' => 'string|required',
                'group_description' => 'string|required'
            ]);

            if ($validator->fails()) {
                return $this->getValidationErrorResponse($validator);
            }

            $group = Groups::where('company_id', $request->company_id)
                ->where('id', $request->id)
                ->first();
            if ($group) {
                $group->group_name = $request->group_name;
                $group->group_description = $request->group_description;
                $group->company_id = $request->company_id;
                $group->created_by = $user->id;
                $group->save();
                $response = [
                    'success' => true,
                    'data' => $group,
                    'message' => "Group has been updated successfully",
                ];
            } else {
                $response = [
                    'success' => false,
                    'message' =>  'Group not exists.'
                ];
                $statusCode = 400;
            }
        } catch (\Exception $e) {
            Log::debug($e->getMessage());
            $response = [
                'success' => false,
                'message' => $e->getMessage(),
            ];
            $statusCode = 500;
        }

        return response()->json($response, $statusCode);
    }

    /**
     * Delete a Group with permission and user in the database.
     *
     * @param  \Illuminate\Http\Request  $request
     */
    public function delete(Request $request)
    {
        $statusCode = 200;
        try {
            $validator = Validator::make($request->all(), [
                'company_id' => 'numeric|required',
            ]);

            if ($validator->fails()) {
                return $this->getValidationErrorResponse($validator);
            }

            $group = Groups::where('id', $request->id)
                ->where('company_id', $request->company_id);
            $data = $group->get();
            if ($data->count() > 0) {
                GroupPermissions::where('group_id', $request->id)
                    ->delete();

                \DB::table('customer_admins')
                    ->where('group_id', $request->id)
                    ->where('customer_id', $request->company_id)
                    ->update([
                        'group_id' => null
                    ]);
                $group->delete();
                $response = [
                    'success' => true,
                    'message' => "Group with permissions has been deleted successfully",
                ];
            } else {
                $response = [
                    'success' => false,
                    'message' =>  'Group not exists.'
                ];
                $statusCode = 400;
            }
        } catch (\Exception $e) {
            Log::debug($e->getMessage());
            $response = [
                'success' => false,
                'message' => $e->getMessage()
            ];
            $statusCode = 500;
        }

        return response()->json($response, $statusCode);
    }

    /**
     * Get users of Group in the database.
     *
     * @param  \Illuminate\Http\Request  $request
     */
    public function getGroupUsers(Request $request)
    {
        $statusCode = 200;
        try {
            $validator = Validator::make($request->all(), [
                'company_id' => 'numeric|required',
            ]);

            if ($validator->fails()) {
                return $this->getValidationErrorResponse($validator);
            }

            $users = User::join('customer_admins', function ($join) {
                $join->on('users.id', '=', 'customer_admins.user_id');
            })
                ->where('customer_admins.group_id', $request->id)
                ->where('customer_admins.customer_id', $request->company_id)
                ->get();

            $users = $users->map(function ($user) {
                $userBy = User::where('id', $user->group_added_by)
                    ->select('id', 'name')
                    ->first();
                $user['group_added_by_user'] = $userBy;
                return $user;
            });

            $response = [
                'success' => true,
                'data' => $users,
                'message' => "Group users fetched successfully",
            ];
        } catch (\Exception $e) {
            Log::debug($e->getMessage());
            $response = [
                'success' => false,
                'message' => "Something went wrong",
            ];
            $statusCode = 500;
        }

        return response()->json($response, $statusCode);
    }

    /**
     * Add users into the Group in the database.
     *
     * @param  \Illuminate\Http\Request  $request
     */
    public function addUsersInGroup(Request $request)
    {
        $statusCode = 200;
        $loggedUser = Auth::user();
        try {
            $validator = Validator::make($request->all(), [
                'user_ids' => 'required|array',
                'user_ids.*' => 'integer',
                'company_id' => 'required|integer',
            ]);
            if ($validator->fails()) {
                return $this->getValidationErrorResponse($validator);
            }

            $existing = \DB::table('customer_admins')
                ->whereIn('user_id', $request->user_ids)
                ->where('customer_id', $request->company_id)
                ->whereNotNull('group_id')
                ->get();

            if($existing->count() > 0){
                $response = [
                    'success' => false,
                    'message' => "Some users are in other groups",
                ];
                return $response;
            }

            $result =  \DB::table('customer_admins')
                ->whereIn('user_id', $request->user_ids)
                ->where('customer_id', $request->company_id)
                ->update([
                    'group_id' => $request->id,
                    'group_added_by' => $loggedUser->id,
                    'group_added_at' => Carbon::now()
                ]);

            if(!$result){
                throw new \Exception('Users are not added into the group.');
            }
            $response = [
                'success' => true,
                'message' => "Users are added in the group successfully",
            ];
        } catch (\Exception $e) {
            Log::debug($e->getMessage());
            $response = [
                'success' => false,
                'message' => $e->getMessage(),
            ];
            $statusCode = 500;
        }

        return response()->json($response, $statusCode);
    }

    /**
     * Remove users from Group in the database.
     *
     * @param  \Illuminate\Http\Request  $request
     */
    public function removeGroupUsers(Request $request)
    {
        $statusCode = 200;
        try {
            $validator = Validator::make($request->all(), [
                'user_ids' => 'required|array',
                'user_ids.*' => 'integer',
                'company_id' => 'required|integer',
            ]);
            if ($validator->fails()) {
                return $this->getValidationErrorResponse($validator);
            }
            $result =  \DB::table('customer_admins')
                ->whereIn('user_id', $request->user_ids)
                ->where('group_id', $request->id)
                ->where('customer_id', $request->company_id)
                ->update([
                    'group_id' => null,
                    'group_added_by' => null,
                    'group_added_at' => null
                ]);

            if(!$result){
                throw new \Exception('Users are not found in the group.');
            }
            $response = [
                'success' => true,
                'message' => "Users are removed from the group successfully",
            ];
        } catch (\Exception $e) {
            Log::debug($e->getMessage());
            $response = [
                'success' => false,
                'message' => $e->getMessage(),
            ];
            $statusCode = 500;
        }

        return response()->json($response, $statusCode);
    }

    /**
     * Delete a Group with permission and user in the database.
     *
     * @param  \Illuminate\Http\Request  $request
     */
    public function deleteGroupAndMove(Request $request)
    {
        $statusCode = 200;
        try {
            $validator = Validator::make($request->all(), [
                'company_id' => 'numeric|required',
                'target_group_id' => 'numeric|required',
            ]);

            if ($validator->fails()) {
                return $this->getValidationErrorResponse($validator);
            }

            $group = Groups::where('id', $request->id)
                ->where('company_id', $request->company_id);
            $data = $group->get();
            if ($data->count() > 0) {
                GroupPermissions::where('group_id', $request->id)
                    ->delete();

                \DB::table('customer_admins')
                    ->where('group_id', $request->id)
                    ->where('customer_id', $request->company_id)
                    ->update([
                        'group_id' => $request->target_group_id
                    ]);
                $group->delete();

                $response = [
                    'success' => true,
                    'message' => "Group has been moved successfully",
                ];
            } else {
                $response = [
                    'success' => false,
                    'message' =>  'This Group is not exists in your company.'
                ];
                $statusCode = 400;
            }
        } catch (\Exception $e) {
            Log::debug($e->getMessage());
            $response = [
                'success' => false,
                'message' => $e->getMessage(),
            ];
            $statusCode = 500;
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
