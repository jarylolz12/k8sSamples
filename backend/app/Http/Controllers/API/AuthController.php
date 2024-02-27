<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Utilities\ProxyRequest;
use App\User;


class AuthController extends Controller
{
    protected $proxy;

    public function __construct(ProxyRequest $proxy)
    {
        $this->proxy = $proxy;
    }


    /**
     * @group User
     *
     * Login Credentials and make sure you already logout your account from administrator/login
     * 
     * @bodyParam email string required User email. Example: anthony@shifl.com
     * @bodyParam password string required User password. Example: password
     * 
     * @response 200 {
     *   "message": "You have been logged in"
     * }
     * 
     * @response status=401 scenario="Validation error" {  
     *    "status": "not ok" 
     * }   
     * 
     * @response 404 {
     *      "errors": {
     *          "email": ["This combination does not exists."] 
     *      } 
     * }
     * 
     * @response 403 {
     *      "errors": {
     *          "password": ["This combination does not exists."] 
     *      } 
     * }
     *  
    */  
    public function login()
    {
        $user = User::where('email', request('email'))->first();

        abort_unless($user, 404, 'This combination does not exists.');
        abort_unless(
            \Hash::check(request('password'), $user->password),
            403,
            'This combination does not exists.'
        );
        $resp = $this->proxy
            ->grantPasswordToken(request('email'), request('password'));

        return response([
            'token' => $resp->access_token,
            'refresh_token' => $resp->refresh_token,
            'expiresIn' => $resp->expires_in,
            'message' => 'You have been logged in',
        ], 200);
    } 
    /**
     * @group User
     *
     * Refresh Token
     *
     * @bodyParam refresh_token string required. No-example
     *
     * @response 200 {
     *   "message": "Token has been refreshed."
     * }
     *
    */  
    public function refreshToken()
    {

        try{
            $resp = $this->proxy->refreshAccessToken();
        }catch (\Exception $exception){
            return response([
                'messsage'=>'No results found'
            ], 404);
        }
        return response([
            'token' => $resp->access_token,
            'expiresIn' => $resp->expires_in,
            'refresh_token' => $resp->refresh_token,
            'message' => 'Token has been refreshed.',
        ], 200);

    }
    /**
     * @group User
     *
     * Logged out
     *
     * @authenticated
     *
     * @response 200 {
     *   "message": "You have been successfully logged out."
     * }
     *
    */
    public function logout()
    {
        $token = request()->user()->token();
        $token->delete();
        // remove the httponly cookie
        cookie()->queue(cookie()->forget('refresh_token'));

        return response([
            'message' => 'You have been successfully logged out',
        ], 200);
    }


}
