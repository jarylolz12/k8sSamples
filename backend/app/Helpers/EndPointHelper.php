<?php // Code within app\Helpers\Helper.php

namespace App\Helpers;

class EndPointHelper
{
    public static function endPoint(string $string)
    {
        return $string;
    }

    public static function getAll(string $string)
    {
        return "Get All $string";
    }

    public static function get(string $string)
    {
        return "Get $string";
    } 
    public static function create(string $string)
    {
        return "Create $string";
    } 

    public static function update(string $string)
    {
        return "Update $string";
    }
    public static function edit(string $string)
    {
        return "Edit $string";
    }
    public static function delete(string $string)
    {
        return "Delete $string";
    }

    public static function getAllDescription(string $string)
    {
        return "Get All $string for $string endpoint.
            To access their $string details, the api already get the data per page
            and the endpoint return json.";
    }

    public static function getAllDescriptionV2(string $string, string $param)
    {
        return " Get All $string for $string endpoint.
            To access their $string details, they have to provide $param.
            And the endpoint will return all their $param regarding the given $param.";
    }

        public static function getDescription(string $string, string $param)
    {
        return "Get $string endpoint.
            To access their $string specific detail, we need to have the $param.
            And the endpoint will return the specific $string regarding the given $param.";
    }
 
    public static function getDescriptionArray(string $string, array $params)
    {
        $num = count($params);
        $value="";
        foreach ($params as $key => $val){
            if(($num - 1) == $key){
                if($num > 1) {
                    $value .= "and $val";
                }else{
                    $value .= $val;
                }
            }else if(($num - 2) == $key){
                $value .= $val. " ";
            }else{
                $value .= $val . ", ";
            }
        }
        return "Get $string endpoint.
            To access their $string specific detail, we need to have the $value.
            And the endpoint will return the specific $string regarding the given $value.";
    }


    public static function createDescription(string $string, array $params)
    {
        $num = count($params);
        $value="";
        foreach ($params as $key => $val){
            if(($num - 1) == $key){
                if($num > 1) {
                    $value .= "and $val";
                }else{
                    $value .= $val;
                }
            }else if(($num - 2) == $key){
                $value .= $val. " ";
            }else{
                $value .= $val . ", ";
            }
        }


        return "Create $string for $string endpoint.
            To create the $string we have to input the ff. data: $value.
            And the endpoint will return json value once created.";
    }

 

    public static function updateDescription(string $string, array $params)
    {
        $num = count($params);
        $value="";
        foreach ($params as $key => $val){
            if(($num - 1) == $key){
                if($num > 1) {
                    $value .= "and $val";
                }else{
                    $value .= $val;
                }
            }else if(($num - 2) == $key){
                $value .= $val. " ";
            }else{
                $value .= $val . ", ";
            }
        }

        return "Update $string for $string endpoint.
            To update the $string we have to input the ff. data: $value.
            And the endpoint will return json value once updated.";
    }



    public static function deleteDescription(string $string, array $params)
    {

        $num = count($params);
        $value="";

            foreach ($params as $key => $val){
                if(($num - 1) == $key){
                    if($num > 1) {
                        $value .= "and $val";
                    }else{
                        $value .= $val;
                    }
                }else if(($num - 2) == $key){
                    $value .= $val. " ";
                }else{
                    $value .= $val . ", ";
                }
            }


        return "Destroy or delete $string for $string endpoint. 
        To delete the $string we should have valid $value. 
        And the endpoint will destroy or delete the data.";
    }

    public static function userGuide(){
        return "Everytime users want to access other api endpoints they have to provide validate token on header with every request.
            Otherwise The request will be considered as unauthorized attempt.";
    }
}