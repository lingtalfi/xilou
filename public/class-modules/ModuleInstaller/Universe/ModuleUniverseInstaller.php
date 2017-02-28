<?php

namespace ModuleInstaller\Universe;


use Explorer\Explorer\NeoMaculusExplorer;
use Explorer\Util\MaculusExplorerUtil;
use Installer\Report\ReportInterface;
use Installer\Universe\ModuleUniverseInterface;
use ModuleInstaller\ModuleInstallerConfig;
use ModuleInstaller\ModuleInstallerPreferences;

class ModuleUniverseInstaller
{
    public static function installPlanets(ModuleUniverseInterface $module, ReportInterface $report)
    {
        $forceImport = false;
        $forceInstall = false;
        $prefs = ModuleInstallerPreferences::getPreferences();
        $warpZone = $prefs['warpZone'];
        $workingUniverseDir = ModuleInstallerConfig::getUniverseWorkingDir();
        $deps = $module->getPlanetDependencies();
        foreach ($deps as $dependency) {
            $info = MaculusExplorerUtil::getDependencyInfo($dependency);
            $planetName = $info['planetName'];
            if (false === is_dir($workingUniverseDir . "/$planetName")) {
                NeoMaculusExplorer::create()
                    ->setWarpZone($warpZone)
                    ->install($dependency, $workingUniverseDir, $forceImport, $forceInstall);
            }
        }
    }

    public static function uninstallPlanets(ModuleUniverseInterface $module, ReportInterface $report)
    {

    }
}