<?php


namespace QuickDoc\Util;

use DirScanner\YorgDirScannerTool;
use QuickDoc\QuickDocPreferences;


class TodoUtil
{
    private static $todos = null;

    public static function getTodos()
    {
        if (null === self::$todos) {
            $ret = [];
            $prefs = QuickDocPreferences::getPreferences();
            $srcDir = $prefs['srcDir'];
            $files = YorgDirScannerTool::getFiles($srcDir, true, true);
            foreach ($files as $file) {
                $realFile = $srcDir . '/' . $file;
                $todos = self::getTodosByFile($realFile);
                if (count($todos) > 0) {
                    $ret[$file] = $todos;
                }
            }
            self::$todos = $ret;
        }
        return self::$todos;
    }


    public static function getCountTodos()
    {
        $todos = self::getTodos();
        $n = 0;
        foreach ($todos as $_todos) {
            $n += count($_todos);
        }
        return $n;
    }

    private static function getTodosByFile($f)
    {
        $c = file_get_contents($f);
        $ret = [];
        if (preg_match_all('!todo:(.*)!i', $c, $matches)) {
            $ret = array_map('trim', $matches[1]);
        }
        return $ret;
    }
}