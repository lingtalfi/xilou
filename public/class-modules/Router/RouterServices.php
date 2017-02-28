<?php


namespace Router;

use Boot\BootModule;
use Crud\CrudModule;
use FrontOne\FrontOneModule;
use IconsViewer\IconsViewerModule;
use Linguist\LinguistModule;
use LogWatcher\LogWatcherModule;
use ModuleInstaller\ModuleInstallerModule;
use NullosInfo\NullosInfoModule;
use QuickDoc\QuickDocModule;
use SqlTools\SqlToolsModule;

class RouterServices
{

    public static function decorateUri2PagesMap(array &$uri2pagesMap)
    {
        ModuleInstallerModule::decorateUri2PagesMap($uri2pagesMap);
        FrontOneModule::decorateUri2PagesMap($uri2pagesMap);
        LinguistModule::decorateUri2PagesMap($uri2pagesMap);
        NullosInfoModule::decorateUri2PagesMap($uri2pagesMap);
        QuickDocModule::decorateUri2PagesMap($uri2pagesMap);
        SqlToolsModule::decorateUri2PagesMap($uri2pagesMap);
        CrudModule::decorateUri2PagesMap($uri2pagesMap);
        \Boot\BootModule::decorateUri2PagesMap($uri2pagesMap);
        \IconsViewer\IconsViewerModule::decorateUri2PagesMap($uri2pagesMap);
        \LogWatcher\LogWatcherModule::decorateUri2PagesMap($uri2pagesMap);
        \Linguist\LinguistModule::decorateUri2PagesMap($uri2pagesMap);
        \Crud\CrudModule::decorateUri2PagesMap($uri2pagesMap);
        \Counter\CounterModule::decorateUri2PagesMap($uri2pagesMap);
    }

}