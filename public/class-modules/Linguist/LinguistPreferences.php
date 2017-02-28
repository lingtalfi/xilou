<?php


namespace Linguist;


use Module\ModulePreferences;

class LinguistPreferences extends ModulePreferences
{

    public static function getDefaultPreferences()
    {
        return [
            'curLang' => "en",
            'refLang' => "en",
            'translateTab' => [
                'mode' => 'unmodified',
                'group' => false,
                'alpha' => true,
            ],
        ];
    }

}