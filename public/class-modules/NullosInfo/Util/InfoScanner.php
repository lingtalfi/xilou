<?php


namespace NullosInfo\Util;


use DirScanner\YorgDirScannerTool;

class InfoScanner
{


    public static function getLogCalls()
    {
        $ret = [];
        $dir = APP_ROOT_DIR;
        $files = YorgDirScannerTool::getFilesWithExtension($dir, 'php', false, true, true);
        foreach ($files as $relFile) {
            $file = $dir . "/" . $relFile;
            $c = file_get_contents($file);
            if (preg_match_all('!Logger::log\\((.*)\\);!Usm', $c, $matches, PREG_PATTERN_ORDER)) {
                foreach ($matches[1] as $args) {
                    $p = explode(',', $args);
                    $id = trim(array_pop($p));
                    $id = self::unquote($id);
                    $ret[$relFile][] = $id;
                }
            }
        }
        return $ret;
    }

    public static function getPrivilegeHasCalls()
    {
        $ret = [];
        $dir = APP_ROOT_DIR;
        $files = YorgDirScannerTool::getFilesWithExtension($dir, 'php', false, true, true);
        foreach ($files as $relFile) {
            $file = $dir . "/" . $relFile;
            $c = file_get_contents($file);
            if (preg_match_all('!Privilege::has\\((.*)\\)!Usm', $c, $matches, PREG_PATTERN_ORDER)) {

                foreach ($matches[1] as $args) {
                    $id = trim($args);
                    $id = self::unquote($id);
                    $ret[$relFile][] = $id;
                }

            }
        }
        return $ret;
    }

    //------------------------------------------------------------------------------/
    //
    //------------------------------------------------------------------------------/
    public static function unquote($id){
        if ("'" === substr($id, 0, 1) && "'" === substr($id, -1)) {
            $id = substr($id, 1, -1);
        } elseif ('"' === substr($id, 0, 1) && '"' === substr($id, -1)) {
            $id = substr($id, 1, -1);
        }
        return $id;
    }
}