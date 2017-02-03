<?php


namespace Linguist\Util;


use Bat\FileSystemTool;
use Linguist\LinguistConfig;
use ModuleInstaller\ModuleInstallerUtil;

class LinguistModuleScanner
{


    public static function getModuleLangs($moduleName)
    {
        $ret = [];
        $nullosDir = self::getInstallAssetsAppDir($moduleName);
        if (null !== $nullosDir) {
            $langDir = $nullosDir . "/lang";
            if (is_dir($langDir)) {
                $files = scandir($langDir);
                foreach ($files as $f) {
                    if ('.' !== $f && '..' !== $f) {
                        if (is_dir($langDir . '/' . $f)) {
                            $ret[] = $f;
                        }
                    }
                }

            }
        }
        return $ret;
    }


    /**
     * replace:
     *  - false, the translation file will be overwritten
     *  - true, the existing translation file will be completed
     */
    public static function createModuleTranslationsFile($moduleName, $langs, $replace = false)
    {
        $translations = self::getTranslationsByModule($moduleName);
        if (is_string($langs)) {
            $langs = [$langs];
        }
        $camelModule = lcfirst($moduleName);
        foreach ($langs as $lang) {
            $dir = LinguistConfig::getLangDir() . "/" . $lang . "/modules/$camelModule";
            $file = $dir . "/$camelModule.php";
            $content = TranslationFileTemplate::getContent($translations);
            if (true === $replace) {
                FileSystemTool::mkdir($dir, 0777, true);
                file_put_contents($file, $content);
            } else {
                $removeObsoleteFromSrc = true;
                LinguistEqualizer::complete($file, $translations, $removeObsoleteFromSrc);
            }
        }
    }


    public static function getTranslationsByModule($moduleName)
    {
        $ret = [];
        $modDir = ModuleInstallerUtil::getModuleDir($moduleName);
        if (is_dir($modDir)) {

            //------------------------------------------------------------------------------/
            // MODULE
            //------------------------------------------------------------------------------/
            $mods = [];
            $files = scandir($modDir);
            foreach ($files as $f) {
                if ('.' !== $f && '..' !== $f) {
                    $file = $modDir . "/" . $f;
                    $trans = [];
                    if (is_file($file)) {
                        $trans = LinguistScanner::scanTranslationsByFile($file);
                    } elseif (is_dir($file)) {
                        $trans = LinguistScanner::scanTranslationsByDir($file);
                    }
                    foreach ($trans as $info) {
                        $mods[] = $info['id'];
                    }
                }
            }

            //------------------------------------------------------------------------------/
            // DEPLOYED ASSETS
            //------------------------------------------------------------------------------/
            $nullosDir = self::getInstallAssetsAppDir($moduleName);
            if (null !== $nullosDir) {
                $trans = LinguistScanner::scanTranslationsByDir($nullosDir);
                /**
                 * Note: at first I intended to differentiate between pages translations,
                 * layout-elements translations and so on...
                 * but now I'm thinking that all translations in one file is better.
                 * I might change my mind, so I keep the module entry in the returned array.
                 */
                foreach ($trans as $info) {
                    $mods[] = $info['id'];
                }
            }

            $mods = array_unique($mods);
            $ret = $mods;

        } else {
            throw new \Exception("module directory does not exist");
        }

        return $ret;
    }

    private static function getInstallAssetsAppDir($moduleName)
    {
        $modDir = ModuleInstallerUtil::getModuleDir($moduleName);
        $assetsDir = $modDir . "/InstallAssets";
        $nullosDir = null;
        if (file_exists("$assetsDir/app-nullos")) {
            $nullosDir = "$assetsDir/app-nullos";
        } elseif (file_exists("$assetsDir/project/app-nullos")) {
            $nullosDir = "$assetsDir/project/app-nullos";
        }
        return $nullosDir;
    }
}