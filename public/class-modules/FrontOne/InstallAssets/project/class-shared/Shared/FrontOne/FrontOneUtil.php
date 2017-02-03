<?php


namespace Shared\FrontOne;


class FrontOneUtil
{


    public static function getTheme()
    {
        $ret = FrontOneServices::getThemeStore()->retrieve();
        if (0 === count($ret)) {
            $ret = FrontOneConfig::getDefaultTheme();
        }
        return $ret;
    }

    public static function setTheme(array $config)
    {
        $prefs = self::getTheme();
        $newPrefs = array_replace_recursive($prefs, $config);
        FrontOneServices::getThemeStore()->store($newPrefs);
    }


    public static function getSocialLinks()
    {
        $ret = FrontOneServices::getSocialStore()->retrieve();
        if (0 === count($ret)) {
            $ret = FrontOneConfig::getDefaultSocialLinks();
        }
        return $ret;
    }

    public static function setSocialLinks(array $config)
    {
        $prefs = self::getSocialLinks();
        $newPrefs = array_replace_recursive($prefs, $config);
        FrontOneServices::getSocialStore()->store($newPrefs);
    }


}