<?php
namespace App\Custom\Traits;

trait HelperMethods{
    private function ifNull($firstValue, $secondValue, $nullValue = null){
        return (empty($firstValue) ? (empty($secondValue) ? $nullValue : $secondValue) : $firstValue);
    }
}
