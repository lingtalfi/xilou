<?php


namespace Stat\Analyzer;


class PerDayAnalyzerHelper
{

    public static function getDayPath($dir, $day, $suffix = "")
    {
        $p = explode('-', $day);
        return $dir . "/days/" . $p[0] . "/" . $p[1] . "/" . $day . $suffix . '.txt';
    }
}