<?php


namespace ModuleInstaller\Util;

use ArrayStore\ArrayStore;

class RepoCachedListUtil
{

    private static $store = null;


    public static function getCachedRepoList()
    {
        $ret = self::getStore()->retrieve();
        if (0 === count($ret)) {
            $ret = static::getDefaults();
        } else {
            $ret = array_replace(static::getDefaults(), $ret);
        }
        return $ret;
    }


    public static function setCachedRepoList(array $repoList)
    {
        $list = self::getCachedRepoList();
        $newList = array_replace_recursive($list, $repoList);
        self::getStore()->store($newList);
    }


    /**
     * @return ArrayStore
     */
    private static function getStore()
    {
        if (null === self::$store) {
            $file = APP_ROOT_DIR . "/assets/modules/moduleInstaller/repo-list.php";
            self::$store = ArrayStore::create()->path($file);
        }
        return self::$store;
    }

    private static function getDefaults()
    {
        return [];
    }
}