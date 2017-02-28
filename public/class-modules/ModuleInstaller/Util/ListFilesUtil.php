<?php

namespace ModuleInstaller\Util;


use DirScanner\YorgDirScannerTool;
use ModuleInstaller\ModuleInstallerUtil;

class ListFilesUtil
{
    public static function getModuleFiles($module)
    {
        $mdir = ModuleInstallerUtil::getModuleDir($module);
        return YorgDirScannerTool::getFiles($mdir, true, true);
    }
}