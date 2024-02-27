<?php

namespace App\Http\Controllers\API\Quickbooks;

use App\User;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use QuickBooksOnline\API\Core\OAuth\OAuth2\OAuth2LoginHelper;
use QuickBooksOnline\API\DataService\DataService;
use QuickBooksOnline\API\Exception\SdkException;
use QuickBooksOnline\API\Exception\ServiceException;

class QuickbooksFactory
{
    protected $details;

    public function __construct()
    {
        $this->details['QUICKBOOKS_API_URL'] = env('QUICKBOOKS_API_URL', config('app.env') === 'production' ? 'Production' : 'Development');
        $this->details['QUICKBOOKS_ACCOUNT_EMAIL'] = env('QUICKBOOKS_ACCOUNT_EMAIL', 'shabsie@shifl.com');
    }

    public static function quickbooksDataService()
    {
        try {
            $self = new static;

            $qbUser = User::where('email', $self->details['QUICKBOOKS_ACCOUNT_EMAIL'])->first();
            if (!$qbUser)
                throw new Exception("Quickbooks account connection user not found.", 404);

            $oauth2LoginHelper = new OAuth2LoginHelper(config('quickbooks.data_service.client_id'), config('quickbooks.data_service.client_secret'));
            $accessTokenObj = $oauth2LoginHelper->
            refreshAccessTokenWithRefreshToken($qbUser->quickbookstoken->refresh_token);
            $accessTokenValue = $accessTokenObj->getAccessToken();
            $refreshTokenValue = $accessTokenObj->getRefreshToken();

            DB::table('quickbooks_tokens')->where('user_id', $qbUser->id)
                ->update([
                    'access_token' => $accessTokenValue,
                    'refresh_token' => $refreshTokenValue
                ]);

            $dataServicePayload = [
                'auth_mode' => 'oauth2',
                'ClientID' => config('quickbooks.data_service.client_id'),
                'ClientSecret' => config('quickbooks.data_service.client_secret'),
                'accessTokenKey' => $accessTokenValue,
                'refreshTokenKey' => $refreshTokenValue,
                'QBORealmID' => $qbUser->quickbookstoken->realm_id,
                'baseUrl' => $self->details['QUICKBOOKS_API_URL']
            ];
            return DataService::Configure($dataServicePayload);
        } catch (SdkException | ServiceException | Exception $e) {
            Log::error(__FUNCTION__ . ":" . __LINE__ . ": " . $e->getMessage());
            throw new Exception($e->getMessage(), 500);
        }
    }
}
