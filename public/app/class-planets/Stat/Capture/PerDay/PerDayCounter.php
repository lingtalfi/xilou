<?php


namespace Stat\Capture\PerDay;


class PerDayCounter
{
    public static $captureDir;
    public static $captureSuffix = '';

    public static function capture()
    {
        /**
         * todo: try to chunk in lines to make human readable files
         */
        $f = self::$captureDir . '/' . date('Y-m-d') . self::$captureSuffix . '.txt';
        $fp = fopen($f, 'ab');
        fwrite($fp, '-');
        fclose($fp);

    }
}