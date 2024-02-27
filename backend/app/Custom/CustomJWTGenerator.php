<?php

namespace App\Custom;

use Firebase\JWT\JWT;
use Firebase\JWT\Key;
use Illuminate\Support\Carbon;

// This class use for Server to Server token validation for PO Instance
class CustomJWTGenerator {

    private static $key = 'KcNaeDRMPwkRHDHsuIh1L2B01VApiu3aTOkWplFjoYI';

    public static function generateToken(){
        $jwt = JWT::encode([
            "iss" => Carbon::now()->timestamp + 500
        ], self::$key, 'HS256');

        return $jwt;
    }

    public static function validateToken($jwt){
        try{
            $decoded = JWT::decode($jwt, new Key(self::$key, 'HS256'));
            return true;
        }catch(\Exception $err){
            return false;
        }
    }
}






