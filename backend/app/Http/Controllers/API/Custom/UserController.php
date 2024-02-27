<?php

namespace App\Http\Controllers\API\Custom;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\User;

/**
 * @group Custom User
 *
 * APIs to manage the User resource
 */
class UserController extends Controller
{
    //

    public $successStatus = 200;
    /**
     * Display customer details
     *
     * @authenticated
     *
     * @apiResourceCollection App\Http\Resources\ScribeResources\UserResource
     * @apiResourceModel App\User with=relatedCustomerAdmins
     *
     * @response status=400 scenario="Unauthenticated" {
     *     "message": "Unauthenticated."
     * }
     *
     */
    public function details()
    {
        $user = Auth::user();
        $user->roles = $user->roles;
        $user->customersApi;
        $user->customer_admins = $user->relatedCustomerAdmins();
        return response()->json(['success' => $user], $this->successStatus);
    }

    public function userDetails($creator_id, $editor_id = null)
    {
        $creatorDetails = User::find($creator_id);
        $editorDetails = User::find($editor_id);
        return response()->json([
            "results" =>
                [
                    "creatorDetails" => $creatorDetails,
                    "editorDetails" => $editorDetails,
                ]
        ]);
    }

    /**
     *
     * Validate token and return user
     *
     * @authenticated
     *
     * @apiResourceCollection App\Http\Resources\ScribeResources\UserResource
     * @apiResourceModel App\User with=customersApi
     *
     * @response 401{
     *      "message": ""
     * }
     *
     * @response status=400 scenario="Unauthenticated" {
     *     "message": "Unauthenticated."
     * }
    */
    public function validateTokenAndReturnUser()
    {
        $hasToken = Auth::guard('api')->check();
        // \Debugbar::info("API API TEST", $hasToken);
        if($hasToken){
            $user = Auth::user();
            $user->roles = $user->roles;
            if(count($user->customersApi) > 0){
                $user->customer = $user->customersApi[0];
            }
            $user->customer_admins = $user->relatedCustomerAdmins();
            return response()->json($user, 200);
        }
        return response()->json([], 401);
    }

}
