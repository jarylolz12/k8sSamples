<?php
namespace App\Helpers;

use Firebase\JWT\JWT;
use PhpOffice\PhpSpreadsheet\Reader\Xls\MD5;

class Helper
{

    public static function generateRandomValue($length = 3)
    {
        //map numbers and letters
        $numbers = '0123456789';
        $letters = 'abcdefghijklmnopqrstuvwxyz';

        //placeholder of the generated value
        $build_value = '';

        //iterate through the given length
        for ($x =0; $x<$length; $x++) {
            //decide whether to use numbers or letters
            $character_to_be_used = rand(0, 1);
            switch ($character_to_be_used) {
            //use numbers
                case 0:
                    //get random key from the $numbers
                    $key = rand(0, strlen($numbers)-1);
                    //concatonate the value of the selected key value
                    $build_value.=$numbers[$key];
                    break;
            //use letters
                default:
                    //get random key from the $letters
                    $key = rand(0, strlen($letters)-1);
                    $use_uppercase = rand(0, 1);
                    $build_value.=($use_uppercase==1) ? ucwords($letters[$key]) : $letters[$key];
                    break;
            }
        }

        return $build_value;
    }

    public static function numberHashed($cardData){
        return MD5(json_encode($cardData));
    }

    public static function numberMasked($number){
        return substr_replace($number,"******",6,6);
    }

    public static function generateRandomString($length = 10) {
        $characters = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $charactersLength = strlen($characters);
        $randomString = '';
        for ($i = 0; $i < $length; $i++) {
            $randomString .= $characters[rand(0, $charactersLength - 1)];
        }
        return $randomString;
    }

    public static function generateKey($string, $length = 8){
        $characters = strtoupper(preg_replace("/[^a-zA-Z]+/", "", $string));

        if(strlen($characters) >=  3){
            $abbreviation = substr($characters, 0, 3);
        }
        else{
            $strLength = 3 - strlen($characters);
            $abbreviation = $characters.''.self::generateRandomString($strLength);
        }
        $key = $abbreviation.''.self::generateRandomValue($length - 3);
        return strtoupper($key);
    }

    public static function dateFormatFrontend($dateTime){
        return $dateTime? date("M d, Y", strtotime($dateTime)) : null;
    }

    public static function timeFormatFrontend($dateTime){
        return $dateTime? date("h:iA", strtotime($dateTime)) : null;
    }

    public static function accountNumberMasked($number)
    {
        $remainLength = strlen($number) - 4;
        $replaceStr = '';
        for ($i = 1; $i <= $remainLength; $i++) {
            $replaceStr .= '*';
        }
        return substr_replace($number, $replaceStr, 0, $remainLength);
    }

}
