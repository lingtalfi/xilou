<?php


namespace Linguist;


use Linguist\Util\LinguistModuleScanner;
use ModuleInstaller\ModuleInstallerUtil;

class LinguistUtil
{

    //--------------------------------------------
    // Locations
    public static function getTabUri($tab)
    {
        return LinguistConfig::getUri() . "?tab=" . $tab;
    }


    /**
     * Notes:
     * - this method scans the InstallAssets directory to guess the "module" languages
     * - it then complete the translation files in the application (not in the InstallAssets dir)
     *
     *
     */
    public static function completeAllModules()
    {
        $list = ModuleInstallerUtil::getModulesList();
        foreach ($list as $info) {
            $moduleName = $info['name'];
            $langs = LinguistModuleScanner::getModuleLangs($moduleName);
            LinguistModuleScanner::createModuleTranslationsFile($moduleName, $langs);
        }
    }
}