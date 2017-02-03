<?php


namespace Stat\Analyzer;


class PerDayAnalyzerUtil
{


    /**
     * Scan the given dir and returns the array of available months in the yyyy-mm format
     */
    public static function getAvailableMonths($dir)
    {
        $dir = $dir . '/days';
        $ret = [];
        if (file_exists($dir)) {
            $files = scandir($dir);
            foreach ($files as $f) {
                if ('.' !== $f && '..' !== $f) {
                    $file = $dir . "/" . $f;
                    if (is_dir($file)) {
                        $months = scandir($file);
                        foreach ($months as $m) {
                            if ('.' !== $m && '..' !== $m) {
                                $fi = $file . "/" . $m;
                                if (is_dir($fi)) {
                                    $ret[] = $f . "-" . $m;
                                }
                            }
                        }
                    }
                }
            }
        }
        return $ret;
    }


    /**
     * Scan the given dir and returns an array containing two entries:
     * - 0: startDay
     * - 1: endDay
     */
    public static function getAvailableRange($dir)
    {
        $dir = $dir . '/days';
        $ret = [];
        $foundFirst = false;
        $d = null;
        if (file_exists($dir)) {
            $files = scandir($dir);
            foreach ($files as $f) {
                if ('.' !== $f && '..' !== $f) {
                    $file = $dir . "/" . $f;
                    if (is_dir($file)) {
                        $months = scandir($file);
                        foreach ($months as $m) {
                            if ('.' !== $m && '..' !== $m) {
                                $monthDir = $file . "/" . $m;
                                if (is_dir($monthDir)) {
                                    $dayFiles = scandir($monthDir);
                                    foreach ($dayFiles as $d) {
                                        if ('.' !== $d && '..' !== $d) {
                                            $dayFile = $monthDir . "/" . $d;
                                            if (is_file($dayFile)) {
                                                if (false === $foundFirst) {
                                                    $ret[] = substr($d, 0, -4);
                                                    $foundFirst = true;
                                                }
                                            }
                                        }
                                    }
                                }
                            }
                        }
                    }
                }
            }
        }
        if (null !== $d) {
            if ('.' !== $d && '..' !== $d) {
                $ret[] = substr($d, 0, -4);
                return $ret;
            }
        }
        return false;
    }

}