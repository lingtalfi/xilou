<?php


namespace ApplicationDetector;


class ApplicationDetectorUtil
{


    public static function getApplicationName($rootDir)
    {
        //------------------------------------------------------------------------------/
        // KIF TEST
        //------------------------------------------------------------------------------/
        $entriesList = [
            'class',
            'class-modules',
            'class-planets',
            'functions',
            'pages',
            'www',
            'init.php',
        ];
        $isKif = true;
        foreach ($entriesList as $file) {
            if (false === file_exists($file)) {
                $isKif = false;
                break;
            }
        }
        if (true === $isKif) {
            return "kif";
        }
        return false;
    }
}