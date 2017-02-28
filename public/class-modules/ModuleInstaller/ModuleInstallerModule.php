<?php


namespace ModuleInstaller;

use Privilege\Privilege;

class ModuleInstallerModule
{


    public static function decorateUri2PagesMap(array &$uri2pagesMap)
    {
        $uri2pagesMap[ModuleInstallerConfig::getUri()] = ModuleInstallerConfig::getPage();
    }


    public static function displayToolsLeftMenuLinks()
    {

        $ll = "modules/moduleInstaller/moduleInstaller";
        if (Privilege::has('moduleInstaller.access')):
            ?>
            <li>
                <a href="<?php echo ModuleInstallerConfig::getUri(); ?>"><?php echo __("Module Installer", $ll); ?></a>
            </li>
            <?php
        endif;
    }


}