<?php


namespace QuickDoc\Util;

use Bat\FileSystemTool;
use DirScanner\YorgDirScannerTool;

class TreeUtil
{

    public static $prefix = '';


    public static function hasTree($content)
    {
        $hasTree = false;
        if (preg_match('!^\\[TREE\\]\s*$!m', $content)) {
            $hasTree = true;
        }
        return $hasTree;
    }


    /**
     * @return false|string
     */
    public static function getTreeSymbolReplacedContent($srcDir, $prefix, $content)
    {
        if (true === self::hasTree($content)) {
            self::$prefix = $prefix;
            $tree = self::createTree($srcDir);
            $tree .= PHP_EOL;
            return preg_replace('!^\\[TREE\\]\s*$!m', $tree, $content);
        }
        return false;
    }


    public static function createTree($srcDir, $filter = null, $transformer = null)
    {
        $string = '';
        if (null === $filter) {
            $filter = function ($file, $realFile, $level) {
                if (is_dir($realFile)) {
                    return true;
                }
                $ext = strtolower(FileSystemTool::getFileExtension($file));
                return ('md' === $ext);
            };
        }
        if (null === $transformer) {
            $n = strlen($srcDir) + 1;
            $transformer = function ($file, $realFile) use ($n) {
                return '[' . $file . '](' . self::$prefix . substr($realFile, $n) . ')';
            };
        }
        self::scan($string, $srcDir, $filter, $transformer, 1);
        return $string;
    }

    //------------------------------------------------------------------------------/
    //
    //------------------------------------------------------------------------------/
    private static function scan(&$string, $dir, $filter, $transformer, $level)
    {
        $files = scandir($dir);
        foreach ($files as $file) {
            if ('.' !== $file && '..' !== $file) {
                $realFile = $dir . '/' . $file;
                if (true === call_user_func($filter, $file, $realFile, $level)) {
                    if (is_dir($realFile)) {
                        self::decorate($string, $level, $transformer, $file, $realFile);
                        self::scan($string, $realFile, $filter, $transformer, $level + 1);
                    } else {
                        self::decorate($string, $level, $transformer, $file, $realFile);
                    }
                }
            }
        }
    }


    private static function decorate(&$string, $level, $transformer, $file, $realFile)
    {
        $string .= str_repeat(' ', $level * 2);
        $string .= '- ' . call_user_func($transformer, $file, $realFile);
        $string .= PHP_EOL;
    }

}