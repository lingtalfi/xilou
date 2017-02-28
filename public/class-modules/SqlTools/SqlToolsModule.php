<?php

namespace SqlTools;

use DirScanner\YorgDirScannerTool;
use Privilege\Privilege;
use QuickPdo\QuickPdo;

class SqlToolsModule
{

    public static function decorateUri2PagesMap(array &$uri2pagesMap)
    {
        $uri2pagesMap[self::getUrl()] = 'modules/sqltools/sqltools.php';
    }

    public static function displayToolsLeftMenuLinks()
    {

        $ll = "modules/sqltools/sqltools";
        if (true === SqlToolsConfig::showLeftMenuLinks()) {
            if (Privilege::has('sqlTools.access')):
                ?>
                <li>
                    <a href="<?php echo self::getUrl('execute-sql'); ?>"><?php echo __("Execute SQL", $ll); ?></a>
                </li>
                <?php
            endif;
        }
    }

    public static function getUrl($action = null)
    {
        return '/sqltools';
    }

    public static function executeSqlStatements($string)
    {
        return (false !== QuickPdo::freeExec($string));
    }


    /**
     * Returns array of
     *          dir => [files],
     *          ...,
     */
    public static function getFavoriteFiles()
    {
        $allFiles = [];
        $dirs = SqlToolsConfig::getFavoriteDirs();
        foreach ($dirs as $dir) {
            // doesn't matter if it doesn't exist, allow us to plan location without creating the actual path
            if (file_exists($dir)) {
                $dir = realpath($dir);
                $len = strlen($dir) + 1; // +1 for the trailing slash
                $files = YorgDirScannerTool::getFilesWithExtension($dir, ['sql', 'txt'], false, true, false);
                if (count($files) > 0) {
                    $fileItems = [];
                    foreach ($files as $file) {
                        $label = $file;
                        if (0 === strpos($file, $dir)) {
                            $label = substr($file, $len);
                        }
                        $fileItems[$file] = $label;
                    }
                    $allFiles[$dir] = $fileItems;
                }


            }
        }
        return $allFiles;
    }
}