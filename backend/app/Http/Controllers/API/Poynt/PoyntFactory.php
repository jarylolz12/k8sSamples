<?php

namespace App\Http\Controllers\API\Poynt;

use Exception;
use Firebase\JWT\JWT;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class PoyntFactory
{

    public static function cardTokenizeURL()
    {
        return sprintf('https://services.poynt.net/businesses/%s/cards/tokenize', self::getBusinessId());
    }

    public static function getBusinessId()
    {
        return env('POYNT_BUSINESS_ID');
    }

    public static function getNonce($request, $uuid)
    {
        try {
            $response = Http::withHeaders([
                "Content-type" => "application/json",
                'Poynt-Request-Id' => $uuid
            ])->post(self::openTokenizeURL(), self::poyntNoncePayload($request));

            Log::info("openTokenizeURL: ". self::openTokenizeURL());
            Log::info("getNonceResponse: ". json_encode($response->body()));

            if (!$response->successful())
                throw new Exception("Error generating nonce in exchange of credit card for poynt");

            return $response['nonce'];
        } catch (Exception $e) {
            Log::Error(__FUNCTION__ . ":" . __LINE__ . ": " . $e->getMessage());
            return false;
        }
    }

    public static function openTokenizeURL()
    {
        return sprintf('https://services.poynt.net/businesses/%s/cards/open-tokenize', self::getBusinessId());
    }

    public static function poyntNoncePayload($request)
    {
        return [
            'applicationId' => self::getApplicationId(),
            'card' => [
                'number' => $request->input('number'),
                'expirationMonth' => $request->input('expiration_month'),
                'expirationYear' => $request->input('expiration_year'),
                'cardHolderFirstName' => $request->input('cardHolder_first_name'),
            ],
            'verificationData' => [
                'cvData' => $request->input('cv_data')
            ],
        ];
    }

    public static function getApplicationId()
    {
        return env('POYNT_APPLICATION_ID');
    }

    public static function getAccessToken()
    {
        try {
            return Http::asForm()
                ->post('https://services.poynt.net/token', [
                    'grantType' => 'urn:ietf:params:oauth:grant-type:jwt-bearer',
                    'assertion' => JWT::encode(self::jwtPayload(), self::getKey(), 'RS256')
                ]);
        } catch (Exception $e) {
            Log::Error(__FUNCTION__ . ":" . __LINE__ . ": " . $e->getMessage());
            return false;
        }
    }

    public static function jwtPayload()
    {
        return [
            "iss" => self::getApplicationId(),
            "sub" => self::getApplicationId(),
            "iat" => time(),
            "exp" => time() + 86400,
            "aud" => "https://services.poynt.net"
        ];
    }

    public static function getKey()
    {
        if(env('POYNT_ENV') == 'sandbox'){
            $key = "-----BEGIN RSA PRIVATE KEY-----
MIIEowIBAAKCAQEAoyVbc460D482BmPoS1dvPSnaspJUdOQh0MiUefvFww3zRkDD
tY29Biy+s/18SMwCt/+JqMlNd54UhgjAn+dagQOtGpVsiQ518K0Y1czHgEmHP30M
DQvElR/ZfPz9XE5P+79Vz16/nGDo+8K3Lb7JAEoGxgzFO5zEfIAO6StBpNyGP/JS
N3KD8Ha0QV8ZRQXOgkReuwRxVbF9q60LDWyO2MEHOYfoT0xa0IbfBKgYdLc0Qqlq
vzlVZ4nydkeHyjaDax5RBLncPLmYTPPaYYov01hXx3pvU1gqG25/srR0Q4z2avTh
nMdTgXELwHW8PurtcDsi+jPU7qGb5b7Oe7Ql0QIDAQABAoIBAAeuC7NZko84Mo7L
05C+4J4aZxiwf9xqDA+3Dnq8y/Ef+i2KoU/dyIn2r6I7TrdCT8cztroo4j49FTof
RbqNRm7T8olRowSjZzDFhDmVyIco5u6Jm1hunzIWKAu8wMxAZT5cghyHOTnCO9t0
ncD/9fgvE+ktKxhwm3UBJfqK0k3xboc5Bbp2Ag6QltqdF7G59aDVaFe3zwOSilfF
guGoB1huqwGX2cW++SEzoFDvgj1+MyrpznjR62cXp9pt1+HyM55PGKuKs3THZt+g
0iUHVGn8yD0a7DHZRp5uS7rcHZGac8nhaFOC76cYCfMtutSNi/YdwqepLH+xwiT0
WcX85cECgYEA4EfEBSknmsMac9ozlKPwCFBMTqEoQr/noWuBO+/RARCSLemSjTDy
iWBRIV2uSmmLeczgfe0iUGuJ0CrgGaCfe5mWJ/LP314CSuwn7N2A6UIJki68sgc/
IhrIOu0D8LM0XSzffMWbk//hl6YsLzmXD/AbWkY3+UrvZEK5N476UBECgYEAujgs
5PafoegN6L+XE6VEm6YHoLsUILy7pDPcUmB7V3l26tdOaQvLcbynu9pIW3PfCS8C
hJOIGSR4K3T0rG5NGunab/R4yXhtrPAiauFyzkAtkOsnQV/ezWlaHZJfq8FC5yop
j891NrMsB2VQDGycueIVwIFBZd60f0MuBgyKOcECgYA1h6t3d90mCQ8VO603+vDd
axzrX3eWhusoEdqkjZaa5HOfVbJO1LHuq/hxOETTKeHKTmX+aEzaWPBpqPIX68hk
3cGDA2ct7ugpLbhRxoRwJ/zU0SbkbupYn3/O4KciGgOTgZRN2XRKnLYhVd4R33L4
sNw2bbAKZiJBEdPXON7xoQKBgHFla9ExbRvGl1G3+WN5LEIr5FE4GpWk2A6xleSD
5BfsW0G+Qn0tNYHZA7scvRsTgCmzerEItcO5tYOhAK1PrZxJ8z5hfHwS3ZaF1C+J
gcOeySCKUak5nA8xrDJ9w8xvLzJdNlngVtwsVw9Z3ljyeq2mwuAq1YwXBYrJeekt
Ea8BAoGBALzaQ1XmvNEHJBnID+i+B8aHinebXGiPGwQE0OUejM1GNhf1gKVYlhHM
ZEyR8jdarJazSuZ7x9aewMKv3BeznJnkDWaNpIl1fbbS7nmMQSPfqVgN4C2fh61X
n0N1Jj4TAv8eRMRCuCpYptOgzk2hxveXu80igKHk+B+LJxV7NiO8
-----END RSA PRIVATE KEY-----";
        }else{
            $key = "-----BEGIN RSA PRIVATE KEY-----
MIIEowIBAAKCAQEAnNqSmxODnhAAOjcfNJPZT2h8Ilxp2BItM+/gEj6J2jq3sbA8
XqVirnLcukuilVXFj1iBbI+JZTaeCFkdxf13Rid1do8FTAK0vpHoEWW8rPfULG00
Rn8AwnVpSiD/jU/dzjn+PBuVliA0Ca6fWiUYXKDOdfXVuyjbMvYDK4Dca7Khsq3d
AflCykIqsvaN7CwN52B1mfvQF4o4FoG3pVJYsU7115Vh65UNf21+YIJQbgzZSYBi
KAclBhTUyRVPEBbXpnC5DkYdaWmoAh4D9kadlRxXP6IK9z/b4yifEioFbq1CpXk/
L4pREHNhS3vxLk/DxOn+bJDI8+unf40vTlsaHQIDAQABAoIBAChhLn/EsMv2s5BJ
E3v+GeIS9G4GG2t4FnH57VXVCrkHB7YzN/9nlTVytXcyNHs8Vv01nC/97bGEM3/T
rVIEk/mX++nXHNZ6Fdy/hB5CciWXMWR0gpIpbOEix8XBI/jVmDTYgiYoNcStqqfZ
cAWNai2iajQ84tZpSLDgW2WOKXCB5vRMwvvGqh/j2AmutKb9agdcDVB0ZkAmNWcy
bRo/wxhftyVgpR0Rr0dn/UfqiwLFTEQw2lgGuPJxOkecRjeJ6380ckxwNMrANkrP
IU/XLMVQ7m6p0MIdaxqP5mw6rnSY0aDh8H6TJQZaYVXD4lRrS/3WORXcktQb9r+4
N1PnGSMCgYEA3CL+jAl9UoCL6Nxfl2yvKQFb7XqPM2X31+M2sAKKZiQTzom3V+2V
8Ws+d5SeVfEYF2TydTvchOE+7At3gPSpWhIz9OADPREjiVStX8BspmDl0qg0T641
jlOAk8mBMvIFeaPpZ4vRA6YlrYp7SV4djq+7qc5GzQ1h7H8AMU5DMXMCgYEAtmhO
aATFvn7GMrPHKA8wCjyDW2A+Brm2hqD0ZlDiUFZOSXsp2JdvJzrhMuB/tJQ2X+zW
WxGk4vcu85mMU+zbrCjsbCkvhN82QVlCLaUaloS4d77mFtAijfU2dJImm52CbqEr
zDDflWvUdI/EzVkTEplQ+Q1szegmSQ+5jGrNYi8CgYAeqtlfVOsalUHofNrsU33L
bp8J5GsSs//lGYhC2PfC5v/DZDggjb631ULskc3FF0NN7whAAMjl0iNb6vUJCAyX
ubV3RcXtufeDaEKG9l2siA88W6Z9+z0GyOc+r3HKUSEmIwmfHUFPCtRw6rvmKrBf
cmfobg46/sRHmCHEvKxrtQKBgQCmaMBnGzuAD84ngWXGwRxVqmE4IrRTlGfVqM9u
7ImbOzGOzgMeKwFn68p5jOBU0QZOOxZP3OftfA5khIfGJQc/p9IQ1v4YHT81HQZH
+CEqgoh8DjeBRn7PvY7hiFgajfbpkLYRQeEoQ0h+0o57vLCuvhpmK/5AV3NeIi9n
0CPxVwKBgA7D0uKDcgxdZ1lYpoX9H6XInuzrrN5wrh0gP8jUCrn/JavNKHo6TGGp
IN5wr+lvYSrXbYPInrxj69SY7BF9n2JtQsH27IkNpsqytzLGKyKZfIZsttL3v4Pg
dmp6u2iK/3+xITJfSqIPP6aGFnoHr9o656mAtvE+YaUSQAMDhvA1
-----END RSA PRIVATE KEY-----";
        }
        return $key;
    }

}
