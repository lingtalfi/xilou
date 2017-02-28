<?php

namespace Counter;


class CounterConfig
{

    public static function getPage()
    {
        return "modules/counter/counter.php";
    }

    public static function getUri()
    {
        return "/counter";
    }

    public static function statsDirName()
    {
        return "stats-counter";
    }

    public static function statsCacheDirName()
    {
        return "stats-counter-range-cache";
    }
}