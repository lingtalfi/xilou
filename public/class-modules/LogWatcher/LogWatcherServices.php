<?php


namespace LogWatcher;


class LogWatcherServices
{


    /**
     * logs: array of label => file
     */
    public static function decorateLogToWatch(array &$logs)
    {
        \ApplicationLog\ApplicationLogModule::decorateLogToWatch($logs);
    }

}