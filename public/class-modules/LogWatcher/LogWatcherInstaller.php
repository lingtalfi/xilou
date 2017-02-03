<?php


namespace LogWatcher;


use Installer\Saas\ModuleSaasInterface;
use Installer\BaseModuleInstaller;


class LogWatcherInstaller extends BaseModuleInstaller implements ModuleSaasInterface
{

    public function getSubscriberServiceIds()
    {
        return [
            'ToolsLeftMenuSection.displayToolsLeftMenuLinks:1001',
            'Router.decorateUri2PagesMap',
        ];
    }

}