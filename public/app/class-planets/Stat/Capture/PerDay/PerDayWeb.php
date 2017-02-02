<?php


namespace Stat\Capture\PerDay;


class PerDayWeb
{
    public static $captureDir;
    public static $captureSuffix = '';

    public static function capture()
    {
        $f = self::$captureDir . '/' . date('Y-m-d') . self::$captureSuffix . '.txt';
        $content = '-' .$_SERVER['HTTP_ACCEPT_LANGUAGE'] . PHP_EOL;
        $content .= '+' . $_SERVER['HTTP_USER_AGENT'] . PHP_EOL;
        file_put_contents($f, $content, FILE_APPEND);
    }
}