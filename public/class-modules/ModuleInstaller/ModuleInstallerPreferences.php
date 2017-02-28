<?php

namespace ModuleInstaller;


use Module\ModulePreferences;

class ModuleInstallerPreferences extends ModulePreferences
{

    public static function getDefaultPreferences()
    {
        return [
            'warpZone' => '/tmp/explorer-script/warp',
            'mainRepoId' => 0,
        ];
    }

}