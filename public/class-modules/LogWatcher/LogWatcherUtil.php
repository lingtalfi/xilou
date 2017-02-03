<?php


namespace LogWatcher;


class LogWatcherUtil
{


    public static function getTabUri($tab)
    {
        return LogWatcherConfig::getUri() . "?tab=" . $tab;
    }


    public static function getLogList()
    {
        $logs = [];
        $phpLog = APP_ROOT_DIR . "/log/php.err.log";
        if (file_exists($phpLog)) {
            $logs["PhpLog"] = $phpLog;
        }
        LogWatcherServices::decorateLogToWatch($logs);
        return $logs;
    }
}