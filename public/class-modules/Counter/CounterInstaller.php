<?php


namespace Counter;


use Installer\Saas\ModuleSaasInterface;
use Installer\BaseModuleInstaller;
use Installer\Universe\ModuleUniverseInterface;


class CounterInstaller extends BaseModuleInstaller implements ModuleSaasInterface, ModuleUniverseInterface
{
    public function getSubscriberServiceIds()
    {
        return [
            'ToolsLeftMenuSection.displayToolsLeftMenuLinks',
            'Router.decorateUri2PagesMap',
        ];
    }

    public function getPlanetDependencies()
    {
        return [
            'git::/lingtalfi/AssetsList',
            'git::/lingtalfi/Stat',
            'git::/lingtalfi/Bat',
        ];
    }


}