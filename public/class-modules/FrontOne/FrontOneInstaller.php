<?php


namespace FrontOne;


use Installer\Installer;
use Installer\Operation\Init\InitAutoloadOperation\InitAutoloadOperation;
use Installer\Report\ReportInterface;
use Installer\Saas\ModuleSaasInterface;
use Installer\Universe\ModuleUniverseInterface;
use Installer\WithPackModuleInstaller;


/**
 * Install process is composed of many operations
 *
 * Each operation can add messages to the Report.
 *
 * An operation can throw an AbortInstallException, which would stop the
 * whole process.
 *
 * An operation can throw any other exception, which would automatically be added
 * to the report.
 *
 */
class FrontOneInstaller extends WithPackModuleInstaller implements ModuleSaasInterface, ModuleUniverseInterface
{
    public function install(ReportInterface $report)
    {
        /**
         * Deploy Files:
         * - /app-vitrine-one/
         * - /pages/modules/frontOne/
         * - /layout-elements/nullos/modules/frontOne/
         * - /lang/++/modules/frontOne/
         * - /assets/modules/frontOne/
         * - ../class-shared
         * - /services/modules/frontOne/
         *
         *
         * Hook into:
         * - class/Router/RouterServices
         * - class/Layout/LayoutServices
         * - init.php (autoloader)
         */
        $installer = new Installer();
        $installer->addOperation(InitAutoloadOperation::create()->setLocationTransformer(function (array &$locations) {
            $loc = '__DIR__ . "/../class-shared"';
            if (false === in_array($loc, $locations, true)) {
                $locations[] = $loc;
            }
        }));

        $this->prepareDeployFiles($installer);


        $installer->run($report);
    }


    public function uninstall(ReportInterface $report)
    {
        $installer = new Installer();
        $installer->addOperation(InitAutoloadOperation::create()->setLocationTransformer(function (array &$locations) {
            $loc = '__DIR__ . "/../class-shared"';
            foreach ($locations as $k => $loca) {
                if ($loca === $loc) {
                    unset($locations[$k]);
                }
            }
        }));

        $this->prepareRemoveFiles($installer);


        $installer->run($report);
    }


    //------------------------------------------------------------------------------/
    // PACKER
    //------------------------------------------------------------------------------/
    protected function getSources()
    {
        return [
            'class-shared/Shared/FrontOne',
            'app-vitrine-one',
            'app-nullos/lang/en/modules/frontOne',
            'app-nullos/layout-elements/nullos/modules/frontOne',
            'app-nullos/pages/modules/frontOne',
            'app-nullos/www/services/modules/frontOne',
        ];
    }

    protected function getTargetDir()
    {
        return APP_ROOT_DIR . "/..";
    }

    protected function getSourceDir()
    {
        return __DIR__ . "/InstallAssets/project";
    }

    //------------------------------------------------------------------------------/
    // SAAS
    //------------------------------------------------------------------------------/
    public function getSubscriberServiceIds()
    {
        return [
            'Router.decorateUri2PagesMap',
            'Layout.displayLeftMenuBlocks',
            'ModuleInfo.getFrontWebsites',
        ];
    }

    public function getPlanetDependencies()
    {
        return [
            'git::/lingtalfi/Installer',
        ];
    }


}