<?php

namespace Shared\FrontOne;


class Ether
{

    private static $vars = [
        'FRONT_ROOT_DIR' => __DIR__ . "/../../../app-vitrine-one",
        'BACK_ROOT_DIR' => __DIR__ . "/../../../app-nullos",
    ];

    public static function set($key, $value)
    {
        self::$vars[$key] = $value;
    }

    public static function get($key)
    {
        return self::$vars[$key];
    }
}