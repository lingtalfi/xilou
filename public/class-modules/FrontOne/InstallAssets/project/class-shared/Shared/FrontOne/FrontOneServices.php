<?php


namespace Shared\FrontOne;


use ArrayStore\ArrayStore;

class FrontOneServices
{

    private static $store;

    /**
     * @return ArrayStore
     */
    public static function getThemeStore()
    {
        if (null === self::$store) {
            self::$store = ArrayStore::create()->path(FrontOneConfig::getThemeFile());
        }
        return self::$store;
    }

    /**
     * @return ArrayStore
     */
    public static function getSocialStore()
    {
        if (null === self::$store) {
            self::$store = ArrayStore::create()->path(FrontOneConfig::getSocialLinksFile());
        }
        return self::$store;
    }
}