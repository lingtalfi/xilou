<?php


namespace IconsViewer;


use Installer\BaseModuleInstaller;
use Installer\Saas\ModuleSaasInterface;


class IconsViewerInstaller extends BaseModuleInstaller implements ModuleSaasInterface
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