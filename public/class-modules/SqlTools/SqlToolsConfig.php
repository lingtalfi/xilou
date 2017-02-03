<?php

namespace SqlTools;

class SqlToolsConfig
{
    public static function showLeftMenuLinks()
    {
        return true;
    }

    public static function getFavoriteDirs()
    {
        return [
            APP_ROOT_DIR . "/fixtures",
        ];
    }

}