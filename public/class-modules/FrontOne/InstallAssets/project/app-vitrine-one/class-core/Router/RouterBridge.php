<?php


namespace Router;

use Boot\BootModule;
use Crud\CrudModule;
use IconsViewer\IconsViewerModule;
use Linguist\LinguistModule;
use NullosInfo\NullosInfoModule;
use QuickDoc\QuickDocModule;
use SqlTools\SqlToolsModule;

class RouterBridge
{
    /**
     * Owned by:
     * - router in www/index.php
     */
    public static function decorateUri2PagesMap(array &$uri2pagesMap)
    {
    }

}