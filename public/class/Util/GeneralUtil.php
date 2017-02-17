<?php


namespace Util;

class GeneralUtil
{
    public static function toDecimal($string)
    {
        if (preg_match('!([0-9]+(,|.[0-9]+)?)!', trim($string), $m)) {
            return str_replace(',', '.', $m[1]);
        }
        return "0.00";
    }

    public static function getDollarToEuroRate()
    {
        return 0.94;
    }
}