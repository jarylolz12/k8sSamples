<?php
namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Validator;
use App\CustomerAdmin;
use Illuminate\Support\Facades\Mail;
use App\Mail\ForgotPassword;
use App\Rules\CheckIfOwnCustomer;
use App\Supplier;
use App\Buyer;
use App\Customer;

/**
 *
 * @group User
 *
 * APIs to manage the user
 *
 */

class UserController extends Controller
{
    public $successStatus = 200;


    public function getConnectedShipper(Request $request) {

        $customers = Auth::user()->customersApi->pluck('id');
        $get_authenticated_user = Auth::user();

        $customer_id = ( $get_authenticated_user->default_customer_id!==null ) ? $get_authenticated_user->default_customer_id : $customers[0];

        $checkShipper = Supplier::where('connected_customer', intval($customer_id))->first();

        if ( isset($checkShipper->id)) {
            return response()->json(['data' => $checkShipper]);
        } else {
            return response()->json(['data' => []]);
        }
    }

    public function getConnectedConsignee(Request $request) {

        $customers = Auth::user()->customersApi->pluck('id');
        $get_authenticated_user = Auth::user();

        $customer_id = ( $get_authenticated_user->default_customer_id!==null ) ? $get_authenticated_user->default_customer_id : $customers[0];

        $checkBuyer = Buyer::where('connected_customer', intval($customer_id))->first();

        if ( isset($checkBuyer->id)) {
            return response()->json(['data' => $checkBuyer]);
        } else {
            return response()->json(['data' => []]);
        }
    }


    /**
     * login api
     *
     * @return \Illuminate\Http\Response
    */
    public function login()
    {
        if (Auth::attempt(['email' => request('email'), 'password' => request('password')])) {
            $user = Auth::user();
            $success['token'] =  $user->createToken('MyApp')-> accessToken;
            return response()->json(['success' => $success], $this-> successStatus);
        } else {
            return response()->json(['error'=>'Unauthorised'], 401);
        }
    }
    /**
     * Update customer admin preference
     *
     * @authenticated
     *
     * @bodyParam customer_id int required customer ID
     *
     * @apiResource App\Http\Resources\Standard
     * @apiResourceModel App\User
     *
     * @response status=401 scenario="Validation error" {
     *    "errors": {
     *        "customer_id": [
     *            "The customer id field is required.",
     *        ],
     *        "status": "not ok"
     *    }
     * }
     *
     * @response status=400 scenario="Unauthenticated" {
     *     "message": "Unauthenticated."
     * }
     *
     * @return \Illuminate\Http\Response
     */
    public function updateCustomerPreference(Request $request)
    {
        $response = ['status' => 'not ok'];
        $validator = Validator::make($request->all(), [
            'customer_id' => ['required', new CheckIfOwnCustomer],
        ]);

        $args = $request->all();
        if ( $validator->fails() ) {

            return response()->json(
            [
                'errors' => $validator->errors(),
                'status' => 'not ok'
            ]
            ,401);

        } else {

            $check_user = Auth::user();
            $update_user = User::find($check_user->id);
            $update_user->default_customer_id = $args['customer_id'];
            $update_user->save();
            $response['status'] = 'ok';
        }

        return response()->json($response);

    }

    /**
     * Update Notification Token
     *
     * @authenticated
     *
     * @response status=200 {
     *    "status": "ok"
     * }
     *
     * @response status=401 {
     *     "message": "Unauthenticated."
     * }
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function updateNotificationToken(Request $request)
    {
        $response = ['status' => 'not ok'];
        $validator = Validator::make($request->all(), [
            'notification_token' => ['required'],
        ]);

        $args = $request->all();
        if ( $validator->fails() ) {

            return response()->json(
            [
                'errors' => $validator->errors(),
                'status' => 'not ok'
            ]
            ,401);

        } else {
            $check_user = Auth::user();
            $update_user = User::find($check_user->id);
            $update_user->notification_token = $args['notification_token'];
            $update_user->save();
            $response['status'] = 'ok';
        }

        return response()->json($response);
    }



    /**
     * Check Forgot Password Code
     *
     * @urlParam forgot_password_verification_code string required Forgot password verification code
     *
     * @response 200 {
     *   "status": "ok"
     * }
     *
     * @response status=401 scenario="Validation error" {
     *    "status": "not ok"
     * }
     *
     * @return \Illuminate\Http\Response
     */
    public function checkForgotPasswordCode(Request $request)
    {
        $response = ['status' => 'not ok'];

        //check if the data contains code field
        if ($request->has('code')) {
            //assign now the code
            $code = $request->input('code');

            //check for that forgot password code in the database
            $checkUser = User::where('forgot_password_verification_code', $code)
                             ->where('forgot_password_verification_code_created_at', '>=', now()
                                         ->subDays(1))
                             ->where('is_forgot_password_requested', 1)
                            ->first();

            $response['status'] = (isset($checkUser->email)) ? 'ok' : 'not ok';
        }

        return response()->json($response);
    }

    /**
     * Change Password
     *
     *
     * @bodyParam code string required
     * @bodyParam newPassword string required
     * @bodyParam renewPassword string required
     *
     * @response 200 {
     *   "status": "ok"
     * }
     * @response status=401 scenario="Validation error" {
     *    "message": null,
     *    "errors": {
     *        "customer_id": [
     *            "The customer id is required.",
     *        ],
     *        "code": [
     *            "The code is required."
     *        ],
     *        "newPassword": [
     *            "The new password field is required."
     *        ],
     *        "renewPassword": [
     *            "The renew password field is required.",
     *            "The renew password must be match to new password"
     *        ],
     *        "error": null,
     *        "status": "not ok"
     *    }
     * }
     *
     * @return \Illuminate\Http\Response
     */
    public function changePassword(Request $request)
    {
        $response = [
            'status' => 'not ok',
            'message' => null,
            'error' => null
        ];

        //check if the data contains code, password, and re_password
        if ($request->has('code') && $request->has('newPassword') && $request->has('reNewPassword')) {
            //validates the passed fields
            $validator = Validator::make($request->all(), [
                'code' => 'required',
                'newPassword' => 'required',
                'reNewPassword' => 'required|same:newPassword',
            ]);

            //if it didn't pass the validation
            if ($validator->fails()) {
                $response['error'] = $validator->errors();
                return response()->json($response, 401);
            } else {
                $code = $request->input('code');
                $password = $request->input('newPassword');
                $re_password = $request->input('reNewPassword');

                //check now the code if it actually exists and valid
                $checkUser = User::where('forgot_password_verification_code', $code)
                                 ->where('forgot_password_verification_code_created_at', '>=', now()
                                         ->subDays(1))
                                 ->where('is_forgot_password_requested', 1)
                                 ->first();

                //if it is valid then updates now the password and the forgot password status
                if (isset($checkUser->email)) {
                    $checkUser->is_forgot_password_requested = 0;
                    $checkUser->password = bcrypt($password);
                    $checkUser->save();
                    $response['status'] = 'ok';
                }
            }
        }

        return response()->json($response);
    }


    /**
     * Forgot Password
     *
     * @bodyParam email string required email EMAIL.
     *
     * @response 200{
     *   "status": "ok",
     *   "message": "We have sent you a change password link. Please check your email address"
     * }
     *
     * @response status=401 scenario="Validation error" {
     *    "errors": {
     *        "email": [
     *            "The email field is required.",
     *            "Please insert a valid email address."
     *        ]
     *    }
     * }
     *
     * @response 404 {
     *    "status": "not ok",
     *    "message": null,
     *    "error": [
     *          "Please make you sure you entered the correct email address.",
     *          null
     *    ]
     * }
     *
     * @return \Illuminate\Http\Response
     */

    public function forgotPassword(Request $request)
    {
        $response = [
            'status' => 'not ok',
            'message' => null,
            'error' => null
        ];

        //check if the data contains email field
        if ($request->has('email')) {
            //set our validation for the email
            $validator = Validator::make($request->all(), [
                'email' => 'required|email'
            ]);


            //if email is not valid then return an error message
            if ($validator->fails()) {
                $response['error'] = $validator->errors();
                return response()->json($response, 401);
            } else {
                //object cache the email request
                $email = $request->input('email');

                //check if the user exists in the database
                $checkUser = User::where('email', $email)
                                 ->first();

                //if it exists do the following actions
                if (isset($checkUser->email)) {
                    $generated = 0;
                    //generate now unique code
                    while ($generated==0) {
                        $generatedValue = \App\Helpers\Helper::generateRandomValue(5);
                        $checkUserWithSameGeneratedValue = User::where('forgot_password_verification_code', $generatedValue)
                                         ->where('forgot_password_verification_code_created_at', '>=', now()
                                         ->subDays(1))
                                         ->where('email', '<>', $checkUser->email)
                                         ->where('is_forgot_password_requested', 1)
                                         ->first();

                        if (!isset($checkUserWithSameGeneratedValue->id)) {
                            $generated = 1;
                        }
                    }

                    if ($generated==1) {
                        //store now the verification code
                        $checkUser->forgot_password_verification_code = $generatedValue;
                        $checkUser->forgot_password_verification_code_created_at = now()->toDateTimeString();
                        $checkUser->is_forgot_password_requested = 1;
                        $checkUser->save();

                        $content = [
                            'name' => $checkUser->name,
                            'code' => $generatedValue,
                            'link' => sprintf('https://app.shifl.com/change-password?code=%s', $generatedValue)
                        ];

                        //send now the email
                        try {
                            Mail::to($checkUser->email)
                                ->send(new ForgotPassword('Shifl: Forgot Password', $content));
                        } catch (Exception $e) {
                            $response['error'] = $e;
                        }
                    }

                    //updates forgot password fields
                    $response['status'] = 'ok';
                    $response['message'] = 'We have sent you a change password link. Please check your email address';
                    return response()->json($response);
                } else {
                    $response['error'] = 'Please make you sure you entered the correct email address.';
                    return response()->json($response);
                }
            }
        }


        return response()->json($response);
    }


    /**
         * Register api
         *
         * @return \Illuminate\Http\Response
         */
    /*
    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'email' => 'required|email',
            'password' => 'required',
            'c_password' => 'required|same:password',
        ]);
        if ($validator->fails()) {
            return response()->json(['error'=>$validator->errors()], 401);
        }
        $input = $request->all();
        $input['password'] = bcrypt($input['password']);
        $user = User::create($input);
        $success['token'] =  $user->createToken('MyApp')-> accessToken;
        $success['name'] =  $user->name;
        return response()->json(['success'=>$success], $this-> successStatus);
    }*/


    /**
     *
     * Register
     *
     * @bodyParam name string
     * @bodyParam email string required email EMAIL. Example: michaelsuarez@shifl.com
     * @bodyParam password required
     *
     * @response 200 {
     *    "success": true,
     *    "token": "eyJ0eXAiOiJKV1QiLCJhbGciOiJSUzI1NiJ9.eyJhdWQiOiIxIiwianRpIjoiNDc5OTg4MjFkMTZlZjUxOTRjOWZhYWFkMzFlZDY0NzljMzliZWMzMjg1NjU2YWViNzdkMmM3ZGMwOGNhNGFiOTZiOTVkZmEwNTE3OWE0ZTciLCJpYXQiOjE1OTAzOTEzNDMsIm5iZiI6MTU5MDM5MTM0MywiZXhwIjoxNjIxOTI3MzQzLCJzdWIiOiIyIiwic2NvcGVzIjpbXX0.onsWJtrF9UT2XEbSsYQbVLvr-TZKGbqIoj4w5W-sEqbrcGep-mRuHJDY1-tY7E_-KxSV3yTrOAtyWFIv51atVFs5m6abF-QWNUpGYMlfEhjQbN6S5QOLXD7deiatSGH0dIXX6v7j7IdUeLgJ5t_6z7oD2s0bAuzfhrHCM9wf7Plyqv7p4-E6ROJ5Atv6IzFFA8dA6eEZWqF_SwOXJMEqyo3Gas7AzNoUSVear8d2sToLZFUv9lPXubp3_5kNN65Ri7bVQXJh0GqCBNN2ySWlO1ODaiIoNPSMOYpBLUaERRTh2AVzDLMvEcKQ5HQFLSqA1wFzABlOweF-7O1mvzdYLSmx8yCvlrIZxnBE2-c69IGmSJKowoczc2lNp96hNept6K-h94xQomC_bd8RajojBP928x-NLPhCH2bg98He0np_xBkm6M91z6M1ZnReZ7s45ViPOTovR6nW0QuqmH6LdF6JEeLc026DKLDX7Ap7fGjq-jFH-tbB_jp0wGGoJfTBLQllftTz9f86oqTXCf85_4fnMgTxB2qX_LBfJw4s6oa1Oex-EpBEprM4C0Awtlo9IkNNRBKLhxa7wPwMHFR_y9w7ZgEbq2pd-qDb4WMcDFfR5mTpCcYYrhufHSa0gnDDDAOanVSaFf58V5T_kKnb72_md7JFf87rhOoSjML_1Ks",
     *    "user": {
     *        "id": 25,
     *        "name": "Temporary Name",
     *        "email": "demo@demo.com",
     *        "password": "$2y$10$9EwE4lBpmYpaSk1aQDbBJezQznD5Xm07nGcIGj5JoFJBB/ADHuuom",
     *        "created_at": "2022-05-25T06:21:47.000000Z",
     *        "updated_at": "2022-05-25T06:21:47.000000Z"
     *    }
     * }
     *
     * @response status=422 scenario="Validation error" {
     *    "message": "The given data was invalid.",
     *    "errors": {
     *        "email": [
     *            "The email field is required.",
     *            "Email must be unique",
     *            "Please insert a valid email address."
     *        ],
     *        "password": [
     *            "The password field is required.",
     *            "The password must be at least 5.",
     *            "The password may not be greater than 100."
     *        ],
     *        "re_password":[
     *            "The repassword field is required.",
     *            "The repassword does not match to password."
     *        ]
     *    }
     * }
     *
     */

    public function signup(Request $request) {

        $validator = Validator::make($request->all(), [
            'email' => 'required|email|unique:users',
            'password' => 'required|min:5|max:100',
            're_password' => 'required|same:password',
        ]);

        if ($validator->fails()) {
            return response()->json(['error'=>$validator->errors()], 401);
        }

        $input = $request->all();
        $input['password'] = bcrypt($input['password']);
        $name = isset($input['name']) ? $input['name'] : "Temporary Name";
        //create customer admin for this user
        $customerAdmin = CustomerAdmin::create([
            'name' => $name,
            'email' => $input['email'],
            'password' => $input['password']
        ]);

        $checkUser = User::where('email', $input['email'])->first();
        return response()->json([
            'success' => true,
            'user' => $checkUser
        ], $this->successStatus);
    }

    public function signUpForInvitedUser(Request $request) {

        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
            'password' => 'required|min:5|max:100',
            're_password' => 'required|same:password',
        ]);

        if ($validator->fails()) {
            return response()->json(['error'=>$validator->errors()], 401);
        }

        $input = $request->all();
        $input['password'] = bcrypt($input['password']);
        $name = isset($input['name']) ? $input['name'] : "Temporary Name";
        $userExists = User::where('email', $input['email'])->first();
        if($userExists) {
            //update customer admin for this user
            $userExists->name = $name;
            $userExists->password = $input['password'];
            $userExists->save();
        } else {
            //create customer admin for this user
            $customerAdmin = CustomerAdmin::create([
                'name' => $name,
                'email' => $input['email'],
                'password' => $input['password']
            ]);
        }

        $checkUser = User::where('email', $input['email'])->first();
        return response()->json([
            'success' => true,
            'user' => $checkUser
        ], $this->successStatus);
    }

    /**
     * Logout
     *
     * @authenticated
     *
     * @response {
     *    "status": "ok"
     * }
     * @return \Illuminate\Http\Response
    */


    public function logout(Request $request)
    {
        $user = Auth::user()->token();
        $user->revoke();
        return 'logged out';
    }
    /**
     * Details
     *
     * @authenticated
     *
     *
     * @apiResource App\Http\Resources\Standard
     * @apiResourceModel App\User with=customersApi
     *
     * @response 200 {
     *    "status": "ok",
     *    "success": true,
     * }
     *
     * @response status=400 scenario="Unauthenticated" {
     *     "message": "Unauthenticated."
     * }
     *
     * @return \Illuminate\Http\Response
    */
    public function details()
    {
        $user = Auth::user();
        $user->roles = $user->roles;
        if(count($user->customersApi) > 0){
            $user->customer = $user->customersApi[0];
        }

        //set default customer
        $customers = Auth::user()->customersApi->pluck('id');
        $customer_id = ( $user->default_customer_id!==null ) ? $user->default_customer_id : $customers[0] ?? null;
        $user->default_customer = Customer::find($customer_id);
        $user->has_supplier = false;
        $user->has_buyer = false;

        //find if connected to buyer
        $buyer = \App\Buyer::where('connected_customer', $customer_id)->first();
        $supplier = \App\Supplier::where('connected_customer', $customer_id)->first();

        if ( isset($buyer->id) ) {
            $user->has_buyer = true;
        }

        if ( isset($supplier->id) ) {
            $user->has_supplier = true;
        }
        return response()->json(['success' => $user], $this->successStatus);
    }


    public function fetchBuyerSupplier(Request $request) {
        $customer = null;
        $valid_roles = ['shipper', 'consignee'];
        if ( $request->has('role') && $request->has('id') ) {

            $id = $request->input('id');
            $role = $request->input('role');
            if ( in_array($role, $valid_roles)) {
                if ( $role === 'shipper' ) {
                    $checkSupplier= Supplier::find($id);
                    $customer = $checkSupplier;
                    /*
                    if ( isset($checkSupplier->id) ) {
                        $customer = Customer::find($checkSupplier->connected_customer);
                    }*/
                } else {
                    $checkBuyer= Buyer::find($id);
                    /*
                    if ( isset($checkBuyer->id) ) {
                        $customer = Customer::find($checkBuyer->connected_customer);
                    }*/
                    $customer = $checkBuyer;
                }
            }
        }
        return response()->json([
            'details' => $customer,
            'reflect' => 1
        ]);

    }


    public function getaccesstoken()
    {
        $hasToken = Auth::guard('api')->check();
        return response()->json(['success' => $hasToken], $this->successStatus);
    }

    /**
     * Check user exists
     *
     * @authenticated
     *
     *
     * @apiResource App\Http\Resources\Standard
     * @apiResourceModel App\User with=customersApi
     *
     * @response 200 {
     *    "success": true,
     *    "isUserExists": true|false,
     * }
     *
     * @response status=400 scenario="Unauthenticated" {
     *     "message": "Unauthenticated."
     * }
     *
     * @return \Illuminate\Http\Response
     */

    public function checkUserExistsByEmail(Request $request, $email) {
        try {
            $validator = Validator::make(['email' => $email], [
                'email' => 'required|email',
            ]);

            if ($validator->fails()) {
                return response()->json(['error' => $validator->errors()], 401);
            }
            $user = User::where('email', $email)->first();
            return response()->json([
                'success' => true,
                'isUserExists' => !empty($user)
            ], $this->successStatus);
        } catch (\Exception $e){
            Log::error($e->getMessage());
            $response = [
                'success' => false,
                'message' => "Something went wrong",
            ];
            return response()->json($response, 500);
        }
    }

    public function userRegisterCompleted(Request $request, $email) {
        $validator = Validator::make(['email' => $email], [
            'email' => 'required|email',
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 401);
        }
        $user = User::where('email', $email)->first();

        return response()->json([
            'success' => true,
            'isUserRegisterCompleted' => !empty($user) && !empty($user->password)
        ], $this->successStatus);
    }
}
