<?php


namespace FrontOne;

class FrontOneConfig
{


    public static function getThemeUri()
    {
        return "/front-one/themes";
    }

    public static function getArticlesUri()
    {
        return "/front-one/articles";
    }

    public static function getSocialUri()
    {
        return "/front-one/social";
    }

    public static function getThemePage()
    {
        return "modules/frontOne/frontOne-theme.php";
    }

    public static function getArticlesPage()
    {
        return "modules/frontOne/frontOne-articles.php";
    }

    public static function getSocialPage()
    {
        return "modules/frontOne/frontOne-social.php";
    }

}