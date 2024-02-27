<?php

namespace App\Http\Controllers\API\PaymentMethod;

class CardknoxFactory
{
    public static function iFieldxKey()
    {
        return env('CARDKNOX_I_FIELD_KEY');
    }

    public static function gatewayURL()
    {
        return 'https://x1.cardknox.com/gatewayjson';
    }

    public static function ccSavePayload($number, $expiration_month, $expiration_year, $name)
    {
        return [
            "xCardNum" => $number,
            "xExp" => $expiration_month . $expiration_year,
            "xKey" => self::xKey(),
            "xVersion" => self::xVersion(),
            "xSoftwareName" => self::xSoftwareName(),
            "xSoftwareVersion" => self::xSoftwareVersion(),
            "xCommand" => "cc:save",
            "xName" => $name
        ];
    }

    public static function xKey()
    {
        return env('CARDKNOX_X_KEY');
    }

    public static function xVersion()
    {
        return '4.5.9';
    }

    public static function xSoftwareName()
    {
        return 'shiflcomdev';
    }

    public static function xSoftwareVersion()
    {
        return '1.0.0';
    }

    public static function ccSalePayload($xToken, $xAmount, $invoiceNumber)
    {
        return [
            "xToken" => $xToken,
            "xKey" => self::xKey(),
            "xVersion" => self::xVersion(),
            "xSoftwareName" => self::xSoftwareName(),
            "xSoftwareVersion" => self::xSoftwareVersion(),
            "xCommand" => "cc:Sale",
            "xAmount" => $xAmount,
            "xCustom01" => "Transaction via API - " . time(),
            "xInvoice" => $invoiceNumber,
            "xAllowDuplicate" => true
        ];
    }

    public static function ACHSavePayload($xName, $xRouting, $xAccount)
    {
        return [
            "xKey" => self::xKey(),
            "xVersion" => self::xVersion(),
            "xSoftwareName" => self::xSoftwareName(),
            "xSoftwareVersion" => self::xSoftwareVersion(),
            "xCommand" => "check:save",
            "xName" => $xName,
            "xRouting" => $xRouting,
            "xAccount" => $xAccount
        ];
    }

    public static function checkSalePayload($xName, $xRouting, $xAccount, $xAmount)
    {
        return [
            "xKey" => self::xKey(),
            "xVersion" => self::xVersion(),
            "xSoftwareName" => self::xSoftwareName(),
            "xSoftwareVersion" => self::xSoftwareVersion(),
            "xCommand" => "check:sale",
            "xAmount" => $xAmount,
            "xRouting" => $xRouting,
            "xAccount" => $xAccount,
            "xName" => $xName,
            "xAllowDuplicate" => True,
        ];
    }

}
