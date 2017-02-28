<?php


namespace Linguist;


class LinguistConfig
{

    public static function getLangDir()
    {
        return APP_ROOT_DIR . "/lang";
    }

    public static function getPage()
    {
        return "modules/linguist/linguist.php";
    }

    public static function getUri()
    {
        return "/linguist";
    }

}