<?php


namespace Counter;


use Bat\ClassTool;
use Bat\FileSystemTool;
use ModuleInfo\ModuleInfoServices;
use Stat\Capture\PerDay\PerDayCounter;

class CounterUtil
{


    public static function getTabUri($tab)
    {
        return CounterConfig::getUri() . "?tab=" . $tab;
    }


    public static function getTargetSitesList()
    {
        $items = [];
        ModuleInfoServices::getFrontWebsites($items);
        return $items;
    }


    public static function initStats($targetDir)
    {
        $counterPath = $targetDir . "/" . CounterConfig::statsDirName();
        if (true === FileSystemTool::mkdir($counterPath)) {
            $counterCachePath = $targetDir . "/" . CounterConfig::statsCacheDirName();
            FileSystemTool::mkdir($counterCachePath);
            return true;
        }
        return false;
    }


    public static function isInstalled($frontDir)
    {
        $moduleDir = $frontDir . "/class-modules/Counter";
        if (true === is_dir($moduleDir)) {
            $counterPath = $frontDir . "/" . CounterConfig::statsDirName();
            if (true === is_dir($counterPath)) {
                $counterCachePath = $frontDir . "/" . CounterConfig::statsCacheDirName();
                if (true === is_dir($counterCachePath)) {
                    return true;
                }
            }
        }
        return false;
    }

    /**
     * Assuming the front is a kif application.
     * https://github.com/lingtalfi/kif
     */
    public static function installCaptureSystem($frontDir)
    {
        //------------------------------------------------------------------------------/
        // COPY THIS COUNTER MODULE IF NOT ALREADY EXIST
        //------------------------------------------------------------------------------/
        $moduleDir = $frontDir . "/class-modules/Counter";
        if (false === is_dir($moduleDir)) {
            $thisModuleDir = __DIR__;
            FileSystemTool::copyDir($thisModuleDir, $moduleDir);
        }

        //------------------------------------------------------------------------------/
        // REGISTER THE EVENTS SERVICE
        //------------------------------------------------------------------------------/
        $eventsFile = $frontDir . '/class-modules/Events/EventsServices.php';
        if (file_exists($eventsFile)) {
            $method = 'onPageRenderedAfter';
            ClassTool::rewriteMethodContentByFile($eventsFile, $method, function (array &$lines) {
                $lines[] = "onPageRenderedAfter();";
            });
        }

        //------------------------------------------------------------------------------/
        // CREATE THE STATS DIR IF NOT EXIST
        //------------------------------------------------------------------------------/
        self::createCaptureDirs($frontDir);
        return false;
    }

    public static function capture()
    {
        $dir = self::createCaptureDirs(APP_ROOT_DIR);
        PerDayCounter::$captureDir = $dir;
        PerDayCounter::capture();
    }


    //------------------------------------------------------------------------------/
    //
    //------------------------------------------------------------------------------/
    private static function createCaptureDirs($appDir)
    {
        $dir = $appDir . "/" . CounterConfig::statsDirName() . "/days/" . date("Y/m");
        if (!is_dir($dir)) {
            mkdir($dir, 0777, true);
        }
        $dir2 = $appDir . "/" . CounterConfig::statsCacheDirName();
        if (!is_dir($dir2)) {
            mkdir($dir2, 0777, true);
        }
        return $dir;
    }
}