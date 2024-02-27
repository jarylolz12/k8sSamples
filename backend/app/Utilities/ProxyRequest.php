<?php

namespace App\Utilities;

class ProxyRequest
{
    public function grantPasswordToken(string $email, string $password)
    {

        $params = [
            'grant_type' => 'password',
            'username' => $email,
            'password' => $password,
        ];


        return $this->makePostRequest($params);
    }

    public function refreshAccessToken()
    {
        //$refreshToken = request()->cookie('refresh_token');

        $refreshToken = (request()->has('refresh_token')) ? request()->input('refresh_token') : request()->cookie('refresh_token');


//        $refreshToken = "def50200d7c4e304e1d87881e8fe7405783fb7706    a8a5216ecb0a627be30f6532681f62372cd97ca98e96c01f0a4adc10ac0f91688426270dcd4d7e69878767f8b3c0a7c2c248d1dfa98878c07f925924d699815f7119f6b71e6127991e00d6509ed58515f67484b253fa5206d7746d9d184155e116245760b47f765492ecfff473d649e0fc666cb418a35c84838d01e4c8cee809643dafd7ea508c7fb838a1ec3ddc8f122e49c8435710930926e2b9b418155100b888fb530b18865e8981c8266f3566379407a9a34b0ee4fa0ef8b94cf8d4c9d0ea1e16e92d0c95bbe169627ce4fd752af71ce284916018c4255f86840a11f00c9c8a191e33bd7a60deb336f0ae0ef7b03f95f36d3df2c9c6acb8e2a66a6a8e40e80e3f66272494f0011aa793140631e71fad3ccb478607a4765615567b1b7bf5c401bb1c1e7fb986902754029a53c41850107b03de26656cc213a37c54dab4bc913a91800ea2c2968578d74f9fbafeebb";

        abort_unless($refreshToken, 403, 'Your refresh token is expired.');

        $params = [
            'grant_type' => 'refresh_token',
            'refresh_token' => $refreshToken,
        ];

        return $this->makePostRequest($params);
    }

    protected function makePostRequest(array $params)
    {
        $params = array_merge([
            'client_id' => config('services.passport.password_client_id'),
            'client_secret' => config('services.passport.password_client_secret'),
            'scope' => '*',
        ], $params);


        $proxy = \Request::create('oauth/token', 'post', $params);

        $resp = json_decode(app()->handle($proxy)->getContent());

        $this->setHttpOnlyCookie($resp->refresh_token);

        return $resp;
    }

    protected function setHttpOnlyCookie(string $refreshToken)
    {
        cookie()->queue(
            'refresh_token',
            $refreshToken,
            14400, // 10 days
            null,
            null,
            false,
            true // httponly
        );
    }
}
