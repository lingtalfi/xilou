<?php


namespace NullosInfo;


class NullosInfoUtil
{
    public static function getTabUri($tab)
    {
        return NullosInfoConfig::getUri() . "?tab=" . $tab;
    }
}