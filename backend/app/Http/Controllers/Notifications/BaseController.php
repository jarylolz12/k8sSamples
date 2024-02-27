<?php

namespace App\Http\Controllers\Notifications;

use App\Http\Resources\Standard as StandardResource;
use Illuminate\Support\Collection;
use App\User;
use QuickBooksOnline\API\DataService\DataService;
use Illuminate\Support\Facades\Auth;
use QuickBooksOnline\API\Core\OAuth\OAuth2\OAuth2LoginHelper;
use App\Http\Controllers\Controller;

class BaseController extends Controller
{
    protected function getService() {

        $get_user = User::where('email', 'shabsie@shifl.com')->first();
        $set_realm_id = $get_user->quickbookstoken->realm_id;

        if (isset($get_user->id)) {

            $oauth2LoginHelper = new OAuth2LoginHelper(config('quickbooks.data_service.client_id'),config('quickbooks.data_service.client_secret'));
            $accessTokenObj = $oauth2LoginHelper->
                                refreshAccessTokenWithRefreshToken($get_user->quickbookstoken->refresh_token);
            $accessTokenValue = $accessTokenObj->getAccessToken();
            $refreshTokenValue = $accessTokenObj->getRefreshToken();

            \DB::table('quickbooks_tokens')->where('user_id', $get_user->id)
                                        ->update([
                                            'access_token' => $accessTokenValue,
                                            'refresh_token' => $refreshTokenValue
                                        ]);
            
            $data_service = DataService::Configure([
            'auth_mode' => 'oauth2',
            'ClientID' => config('quickbooks.data_service.client_id'),
            'ClientSecret' => config('quickbooks.data_service.client_secret'),
            'accessTokenKey' => $accessTokenValue,
            'refreshTokenKey' => $refreshTokenValue,
            'QBORealmID' => $set_realm_id,
            'baseUrl' => 'Production'
            ]);

            return $data_service;
        }
        return null;
    }
}