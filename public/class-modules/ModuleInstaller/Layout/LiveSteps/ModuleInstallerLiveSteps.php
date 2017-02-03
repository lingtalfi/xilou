<?php


namespace ModuleInstaller\Layout\LiveSteps;


use Layout\Body\LiveSteps\LiveSteps;
use ModuleInstaller\ModuleInstallerUtil;
use ModuleInstaller\ModuleRepo\MainRepoUtil;
use Privilege\Privilege;

class ModuleInstallerLiveSteps extends LiveSteps
{


    protected function acceptRequest()
    {
        if (array_key_exists('module', $_POST)) {
            return Privilege::has('moduleInstaller.installModule');
        }
    }

    protected function processRequest()
    {
        if (array_key_exists('module', $_POST)) {
            $module = $_POST['module'];
            $dstModuleDir = ModuleInstallerUtil::getModuleDir($module);

            if (true === MainRepoUtil::downloadModule($module, $dstModuleDir, function ($msg) {
                    $this->addMessageInfo($msg);
                })
            ) {
                $this->addMessageFinish("done", true);
            }
        } else {
            echo "module-var-not-set";
        }
    }
}
