<?php


namespace QuickDoc;


use Installer\BaseModuleInstaller;
use Installer\Saas\ModuleSaasInterface;


class QuickDocInstaller extends BaseModuleInstaller implements ModuleSaasInterface
{


    //------------------------------------------------------------------------------/
    // SAAS
    //------------------------------------------------------------------------------/
    public function getSubscriberServiceIds()
    {
        return [
            'ToolsLeftMenuSection.displayToolsLeftMenuLinks:4',
            'Router.decorateUri2PagesMap',
        ];
    }
}