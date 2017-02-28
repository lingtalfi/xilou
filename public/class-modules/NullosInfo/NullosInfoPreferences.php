<?php


namespace NullosInfo;


use Module\ModulePreferences;

class NullosInfoPreferences extends ModulePreferences
{
    public static function getDefaultPreferences()
    {
        /**
         * alpha: alphabetic order (otherwise order as items are encountered)
         * group: organize the items by file (otherwise one big category for all items)
         */
        return [
            'logCalls' => [
                'alpha' => true,
                'group' => true,
            ],
            'privileges' => [
                'alpha' => true,
                'group' => true,
            ],
        ];
    }
}