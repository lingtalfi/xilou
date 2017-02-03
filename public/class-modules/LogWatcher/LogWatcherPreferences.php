<?php

namespace LogWatcher;


use Module\ModulePreferences;

class LogWatcherPreferences extends ModulePreferences
{

    public static function getDefaultPreferences()
    {
        return [
            'nbLinesPerPageList' => [
                5,
                10,
                20,
                50,
                100,
                200,
                500,
                1000,
            ],
        ];
    }
}