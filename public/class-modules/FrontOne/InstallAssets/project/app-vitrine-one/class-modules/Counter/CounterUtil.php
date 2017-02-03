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
        return false;
    }

    public static function capture()
    {
        $dir = APP_ROOT_DIR . "/stats-counter/days/" . date("Y/m");
        if (!is_dir($dir)) {
            mkdir($dir, 0777, true);
        }
        PerDayCounter::$captureDir = $dir;
        PerDayCounter::capture();
    }
}