<?php

namespace QuickDoc;


use Module\ModulePreferences;

class QuickDocPreferences extends ModulePreferences
{
    public static function getDefaultPreferences()
    {
        /**
         * mode:
         *      - unresolved: show unresolved only
         *      - resolved: show resolved only
         *      - all: show all
         *
         * alpha: alphabetic order (otherwise order as items are encountered)
         *
         * group: organize the items by file (otherwise one big category for all items)
         *
         */
        return [
            'srcDir' => null,
            'dstDir' => null,
            'linksUrlPrefix' => '',
            'linksUrlAbsolutePrefix' => '',
            'links' => [
                'mode' => 'all',
                'alpha' => true,
                'group' => true,
            ],
            'images' => [
                'mode' => 'all',
                'alpha' => true,
                'group' => true,
            ],
        ];
    }


}