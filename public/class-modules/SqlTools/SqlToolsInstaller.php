<?php


namespace SqlTools;


use Installer\BaseModuleInstaller;
use Installer\Saas\ModuleSaasInterface;


class SqlToolsInstaller  extends BaseModuleInstaller implements ModuleSaasInterface
{
    //------------------------------------------------------------------------------/
    // SAAS
    //------------------------------------------------------------------------------/
    public function getSubscriberServiceIds()
    {
        return [
            'ToolsLeftMenuSection.displayToolsLeftMenuLinks:3',
            'Router.decorateUri2PagesMap',
        ];
    }


}