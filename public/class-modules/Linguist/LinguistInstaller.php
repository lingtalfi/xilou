<?php


namespace Linguist;

use Installer\BaseModuleInstaller;
use Installer\Saas\ModuleSaasInterface;
use Installer\Universe\ModuleUniverseInterface;


class LinguistInstaller extends BaseModuleInstaller implements ModuleSaasInterface, ModuleUniverseInterface
{
    //------------------------------------------------------------------------------/
    // SAAS
    //------------------------------------------------------------------------------/
    public function getSubscriberServiceIds()
    {
        return [
            'ToolsLeftMenuSection.displayToolsLeftMenuLinks:5',
            'Router.decorateUri2PagesMap',
        ];
    }

    public function getPlanetDependencies()
    {
        return [
            'git::/lingtalfi/AssetsList',
            'git::/lingtalfi/Bat',
            'git::/lingtalfi/DirScanner',
            'git::/lingtalfi/Installer',
            'git::/lingtalfi/SequenceMatcher',
            'git::/lingtalfi/Tokens',
        ];
    }


}