<?php


class Helper
{

    public static function isLocal()
    {
        if (
            '/Volumes/' === substr(__DIR__, 0, 9) ||
            '/Users/' === substr(__DIR__, 0, 7)
        ) {
            return true;
        }
        return false;
    }

    public static function defaultLogMsg()
    {
        return __("Oops, an error occurred, please check the logs");
    }

}