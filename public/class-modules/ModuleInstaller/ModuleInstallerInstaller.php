<?php


namespace ModuleInstaller;


use Installer\Saas\ModuleSaasInterface;
use Installer\BaseModuleInstaller;

class ModuleInstallerInstaller extends BaseModuleInstaller implements ModuleSaasInterface
{



    //------------------------------------------------------------------------------/
    // SAAS
    //------------------------------------------------------------------------------/
    public function getSubscriberServiceIds()
    {
        return [
            'ToolsLeftMenuSection.displayToolsLeftMenuLinks:1',
            'Router.decorateUri2PagesMap',
        ];
    }


}