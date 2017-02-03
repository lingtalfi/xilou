<?php

namespace ModuleInstaller\Universe;


use DirScanner\YorgDirScannerTool;
use ModuleInstaller\ModuleInstallerUtil;
use Tokens\Util\UseStatementsUtil;

class UniverseUtil
{

    public static function getUseStatementsByModule($moduleName)
    {
        $dir = ModuleInstallerUtil::getModuleDir($moduleName);
        return self::getUseStatementsByDir($dir);
    }

    public static function getUseStatementsByDir($dir)
    {
        $ret = [];
        $files = YorgDirScannerTool::getFilesWithExtension($dir, 'php', false, true, false);
        foreach ($files as $file) {
            $ret = array_merge($ret, UseStatementsUtil::getUseStatements($file));
        }
        $ret = array_unique($ret);
        sort($ret);
        return $ret;
    }
}