<?php

namespace ModuleInstaller;


class ModuleInstallerConfig
{

    public static function getPage()
    {
        return "modules/moduleInstaller/moduleInstaller.php";
    }

    public static function getUri()
    {
        return "/module-installer";
    }

    public static function getModulesDir()
    {
        return APP_ROOT_DIR . "/class-modules";
    }

    public static function getUniverseWorkingDir()
    {
        return APP_ROOT_DIR . "/class-planets";
    }

    public static function getMainModuleRepoUrl()
    {
        return "http://nullos/repo-module";
    }

    public static function getProgressFile()
    {
        return tempnam('/tmp', '');
    }

    public static function getCoreModules()
    {
        /**
         * A core module means that its functionality can be used by other modules.
         */
        return [
            'ApplicationLog',
            'ApplicationDetector',
            'Authentication',
            'Boot',
            'Crud',
            'Lang',
            'Layout',
            'LayoutDynamicHead',
            'ModuleInfo',
            'ModuleInstaller',
            'Router',
            'ToolsLeftMenuSection',
        ];
    }

    public static function getCorePlanets()
    {
        /**
         * A core planet means that its functionality is used by the core or core modules and shouldn't be removed.
         *
         */
        return [
            'AdminTable',
            'ArrayExport',
            'ArrayStore',
            'ArrayToString',
            'AssetsList',
            'BabyYaml',
            'Bat',
            'BumbleBee',
            'CopyDir',
            'DirScanner',
            'DirTransformer',
            'Explorer',
            'Icons',
            'Installer',
            'Privilege',
            'PublicException',
            'QuickForm',
            'QuickPdo',
            'SequenceMatcher',
            'Tokens',
        ];
    }

    public static function getStatesFile()
    {
        return APP_ROOT_DIR . "/assets/modules/moduleInstaller/states.php";
    }

    public static function getDefaultStates()
    {
        return [];
    }

}