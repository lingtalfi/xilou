<?php


namespace NullosInfo;


use Installer\BaseModuleInstaller;
use Installer\Saas\ModuleSaasInterface;


class NullosInfoInstaller extends BaseModuleInstaller implements ModuleSaasInterface
{
    //------------------------------------------------------------------------------/
    // SAAS
    //------------------------------------------------------------------------------/
    public function getSubscriberServiceIds()
    {
        return [
            'ToolsLeftMenuSection.displayToolsLeftMenuLinks',
            'Router.decorateUri2PagesMap',
        ];
    }
}