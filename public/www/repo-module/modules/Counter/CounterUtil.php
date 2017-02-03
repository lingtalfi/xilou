<?php


namespace Counter;


use Bat\FileSystemTool;
use ModuleInfo\ModuleInfoServices;

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
}