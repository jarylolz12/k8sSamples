<?php

namespace App\Http\Controllers\API;

use App\Custom\CustomJWTGenerator;
use App\Customer;
use App\CustomerAdmin;
use App\Events\SendAddUserToCompanyInvitationEvent;
use App\Http\Controllers\Controller;
use Auth;
use App\User;
use App\CustomerAdminsRelation;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Log;
use Validator;
/**
 * @group Customer Admin
 *
 * APIs to manage the customer admin resource
 *
*/
class CustomerAdminController extends controller
{

    /**
     * Display the specified resource.
     *
     * @authenticated
     *
     * @urlParam customer_id int required Customer ID
     *
     * @apiResource App\Http\Resources\Standard
     * @apiResourceModel App\CustomerAdmin
     * @apiResourceAdditional success=true message="Customer admin fetched successfully"
     *
     * @response status=500 scenario="Something went wrong" {
     *    "message": "Something went wrong"
     * }
     *
     * @response status=400 scenario="Unauthenticated" {
     *     "message": "Unauthenticated."
     * }
     *
     */

     public function getCustomerAdmin(Request $request){
        $statusCode=200;
        try{

            $currentUser = Auth::user();

            if($currentUser->for_testing == 1){
                $result = User::select('users.id','users.name','users.email','users.created_at','users.updated_at','customer_admins.id as customer_admin_pk', 'customer_admins.is_invite_confirm as is_customer_invite_confirm', 'customer_admins.group_id', 'groups.group_name')
                ->join('customer_admins','customer_admins.user_id', '=' ,'users.id')
                ->leftJoin('groups', 'groups.id', '=', 'customer_admins.group_id')
                ->where('customer_admins.customer_id', $request->customer_id)
                ->where('users.name', 'like', '%' . $request->serach_string . '%')
                ->get();
            } else {
                $result = User::select('users.id','users.name','users.email','users.created_at','users.updated_at','customer_admins.id as customer_admin_pk', 'customer_admins.is_invite_confirm as is_customer_invite_confirm', 'customer_admins.group_id', 'groups.group_name')
                ->join('customer_admins','customer_admins.user_id', '=' ,'users.id')
                ->leftJoin('groups', 'groups.id', '=', 'customer_admins.group_id')
                ->where('customer_admins.customer_id', $request->customer_id)
                ->where('for_testing',0)
                ->where('users.name', 'like', '%' . $request->serach_string . '%')
                ->get();
            }


            $response = [
                'success' => true,
                'data' => isset($result->resource) ? $result->resource : $result,
                'message' => "Customer admin fetched successfully",
            ];
        } catch (\Exception $e){
            Log::error($e->getMessage());
            $response = [
                'success' => false,
                'message' => "Something went wrong" . $e->getMessage(),
            ];
            $statusCode=500;
        }
        return response()->json($response, $statusCode);
    }


     /**
     *
     * Remove the specified resource from storage.
     *
     * @authenticated
      *
     * @urlParam id int required Customer Admin ID
      *
     * @response 200 {
     *   "message": "User Deleted Successfully"
     * }
     *
     * @response status=404 scenario="User Doesn't exists" {
     *   "message": "User Doesn't exists"
     * }
     *
     * @response status=400 scenario="Unauthenticated" {
     *     "message": "Unauthenticated."
     * }
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     *
     */

    public function deleteCustomerAdmin($id){
        $customerAdmin = CustomerAdminsRelation::find($id);
        if($customerAdmin){
             $customerAdmin->delete();
            return response()->json([
                'message' => "User Deleted Successfully",
            ],200);
        }else{
            return response()->json([
                'message' => "User Doesn't exists",
            ], 400);
        }
    }

    /**
     * Send user invitation for company.
     *
     * @authenticated
     *
     * @urlParam companyId int required Customer ID
     * @invitedById invitedById int required User ID
     * @invitedToEmail invitedToEmail string required Email
     *
     * @apiResource App\Http\Resources\Standard
     * @apiResourceModel App\CustomerAdmin
     * @apiResourceAdditional success=true message="Invitation has been sent."
     *
     * @response status=500 scenario="Something went wrong" {
     *    "message": "Something went wrong"
     * }
     *
     * @response status=400 scenario="UserNotFound" {
     *     "message": "Invited by user not found."
     * }
     *
     */

    public function sendAddUserInvitation(Request $request) {
        try {
            $validator = Validator::make($request->all(), [
                'companyId' => 'numeric|required',
                'invitedById' => 'numeric|required',
                'invitedToEmail' => 'email|required'
            ]);
            if ($validator->fails()) {
                return response()->json(['error' => $validator->errors()], 400);
            }
            $name = $request->name ?? '';
            $groupId = $request->group_id;
            $isWarehouseEmployee = $request->isWarehouseEmployee;
            $warehouseIds = $request->warehouseIds;

            $input = $request->all();
            $user = User::where('id', $input['invitedById'])->first();
            $company = Customer::where('id', $input['companyId'])->first();
            $invitedToUser = User::where('email', $input['invitedToEmail'])->first();

            if(empty($user)){
                $response = [
                    'success' => false,
                    'message' => "Invited by user not found",
                ];
                return response()->json($response, 400);
            }
            if(empty($company)){
                $response = [
                    'success' => false,
                    'message' => "Company not found",
                ];
                return response()->json($response, 400);
            }

            $customerAdmin = User::select('users.id','users.email', 'customer_admins.is_invite_confirm')
                ->join('customer_admins','customer_admins.user_id', '=' ,'users.id')
                ->where('customer_admins.customer_id', $input['companyId'])
                ->where('users.email', $input['invitedToEmail'])
                ->first();

            if(!empty($customerAdmin)){
                if($customerAdmin->is_invite_confirm) {
                    $message = "User already added.";
                }else{
                    $message = "User invitation already sent to " . $input['invitedToEmail'];
                }
                return response()->json(
                    [
                        'success' => false,
                        'message' => $message
                    ]
                );
            }else{
                $companyId = $company->id;
                $userId = null;
                if(!empty($invitedToUser)){
                    $userId  = $invitedToUser->id;

                    \DB::table('customer_admins')
                        ->insert([
                            'user_id' => $userId,
                            'customer_id' => $companyId,
                            'is_invite_confirm' => 0,
                            'group_id' => $groupId,
                            'group_added_by' => $input['invitedById'],
                            'group_added_at' => Carbon::now()
                        ]);
                    if($isWarehouseEmployee) {
                        CustomerAdmin::whereId($userId)
                            ->update([
                                'is_warehouse_employee' => $isWarehouseEmployee
                            ]);
                    }
                } else {
                    $chkUser = CustomerAdmin::create([
                        'name' => $name,
                        'email' => $input['invitedToEmail'],
                        'password' => '',
                        'is_warehouse_employee' => $isWarehouseEmployee,
                    ]);

                    if($chkUser) {
                        $userId = $chkUser->id;
                        \DB::table('customer_admins')
                            ->insert([
                                'user_id' => $chkUser->id,
                                'customer_id' => $companyId,
                                'is_invite_confirm' => 0,
                                'group_id' => $groupId,
                                'group_added_by' => $input['invitedById'],
                                'group_added_at' => Carbon::now()
                            ]);
                    }
                }
                if($userId && is_array($warehouseIds) && count($warehouseIds) > 0) {
                    $token = $request->bearerToken();
                    $this->addWarehousesToUserInPO($token, $userId, $warehouseIds);
                }
            }

            $nameArray = explode(' ', $name);
            $firstName = isset($nameArray[0]) ? $nameArray[0] : '';
            $lastName = isset($nameArray[1]) ? $nameArray[1] : '';
            event(new SendAddUserToCompanyInvitationEvent($input["invitedToEmail"], $user, $company, $firstName, $lastName));

            return response()->json(
                [
                    'success' => true,
                    'message' => 'Invitation has been sent.'
                ]
            );

        } catch (\Exception $e) {
            Log::error($e->getMessage());
            $response = [
                'success' => false,
                'message' => "Something went wrong",
            ];
            return response()->json($response, 500);
        }
    }

    public function reSendAddUserInvitation(Request $request) {
        try {
            $validator = Validator::make($request->all(), [
                'companyId' => 'numeric|required',
                'invitedById' => 'numeric|required',
                'invitedToEmail' => 'email|required'
            ]);
            if ($validator->fails()) {
                return response()->json(['error' => $validator->errors()], 400);
            }

            $input = $request->all();
            $user = User::where('id', $input['invitedById'])->first();
            $company = Customer::where('id', $input['companyId'])->first();
            $invitedToUser = User::where('email', $input['invitedToEmail'])->first();
            if(empty($invitedToUser)){
                $response = [
                    'success' => false,
                    'message' => "Invited to user not found",
                ];
                return response()->json($response, 400);
            }
            if(empty($user)){
                $response = [
                    'success' => false,
                    'message' => "Invited by user not found",
                ];
                return response()->json($response, 400);
            }
            if(empty($company)){
                $response = [
                    'success' => false,
                    'message' => "Company not found",
                ];
                return response()->json($response, 400);
            }
            $nameArray = explode(' ', $invitedToUser->name ?? '');
            $firstName = isset($nameArray[0]) ? $nameArray[0] : '';
            $lastName = isset($nameArray[1]) ? $nameArray[1] : '';
            event(new SendAddUserToCompanyInvitationEvent($input["invitedToEmail"], $user, $company, $firstName, $lastName));

            return response()->json(
                [
                    'success' => true,
                    'message' => 'Invitation has been sent.'
                ]
            );

        } catch (\Exception $e) {
            Log::error($e->getMessage());
            $response = [
                'success' => false,
                'message' => "Something went wrong",
            ];
            return response()->json($response, 500);
        }
    }

    /**
     *  User invitation confirm.
     *
     * @authenticated
     *
     * @urlParam companyId int required Customer ID
     * @invitedToEmail invitedToEmail string required Email
     *
     * @apiResource App\Http\Resources\Standard
     * @apiResourceModel App\CustomerAdmin
     * @apiResourceAdditional success=true message="Invitation has been confirm."
     *
     * @response status=500 scenario="Something went wrong" {
     *    "message": "Something went wrong"
     * }
     *
     * @response status=400 scenario="UserNotFound" {
     *     "message": "Invited user not found"
     * }
     *
     */
    public function inviteExistsUserConfirm(Request $request) {
        try {
            $validator = Validator::make($request->all(), [
                'companyId' => 'numeric|required',
                'invitedToEmail' => 'email|required'
            ]);
            if ($validator->fails()) {
                return response()->json(['error' => $validator->errors()], 400);
            }

            $input = $request->all();
            $company = Customer::where('id', $input['companyId'])->first();
            $invitedUser = User::where('email', $input['invitedToEmail'])->first();

            if(empty($invitedUser) || (empty($invitedUser->name) || empty($invitedUser->password))){
                $response = [
                    'success' => false,
                    'message' => "Invited user not found or user not completed the sign up process",
                ];
                return response()->json($response, 200);
            }
            if(empty($company)){
                $response = [
                    'success' => false,
                    'message' => "Company not found",
                ];
                return response()->json($response, 200);
            }

            if(!empty($invitedUser)){
                $userId  = $invitedUser->id;
                $companyId = $company->id;
                $customerAdmin = \DB::table('customer_admins')
                    ->where('user_id', $userId)
                    ->where('customer_id', $companyId)
                    ->first();
                if(empty($customerAdmin)){
                    \DB::table('customer_admins')
                        ->insert([
                            'user_id' => $userId,
                            'customer_id' => $companyId,
                            'is_invite_confirm' => 1
                        ]);
                }else{
                    \DB::table('customer_admins')
                        ->where('user_id', $userId)
                        ->where('customer_id', $companyId)
                        ->update([
                            'is_invite_confirm' => 1
                        ]);
                }
            }

            return response()->json(
                [
                    'success' => true,
                    'message' => 'Invitation has been confirm.'
                ]
            );

        } catch (\Exception $e) {
            Log::error($e->getMessage());
            $response = [
                'success' => false,
                'message' => "Something went wrong",
            ];
            return response()->json($response, 500);
        }
    }

    private function addWarehousesToUserInPO($token, $userId, $warehouseIds) {
        try {
            $client = new \GuzzleHttp\Client([
                'base_uri' => getenv('PO_URL'),
                'headers' => [
                    'Content-Type' => 'application/json',
                    'Accept' => 'application/json',
                    'Authorization' => 'Bearer ' . $token,
                ],
            ]);

            $res = $client->post('/api/add-user-warehouses', [
                'headers' => ['Content-Type' => 'application/json'],
                'body' => json_encode([
                    'user_id' => $userId,
                    'warehouses' => $warehouseIds
                ])
            ]);
            return response()->json(json_decode($res->getBody()));
        } catch(\Exception $e) {
            Log::error($e->getMessage());
        }
    }

}
