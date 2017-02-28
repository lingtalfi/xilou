<?php


namespace ModuleInstaller\ModuleRepo;


use BabyYaml\BabyYamlUtil;
use Bat\FileSystemTool;
use ModuleInstaller\ModuleInstallerConfig;

class ModuleRepoInstaller
{

    private $localDir;
    private $appModulesDir;
    private $logs;


    public function __construct()
    {
        $this->localDir = APP_ROOT_DIR . "/../module-repo-local";
        $this->appModulesDir = APP_ROOT_DIR . "/class-modules";
    }

    public function setLocalDir($localDir)
    {
        $this->localDir = $localDir;
        return $this;
    }


    /**
     *
     * In this implementation, version means minVersion.
     *
     * version:
     *  - null: means any version
     *  - string: means at least this version number
     *
     *
     */
    public function install($module, $version = null)
    {
        $this->logs = [];
        if (false === $this->hasModule($this->appModulesDir, $module, $version)) {
            $modulesDir = $this->getLocalModulesDir();
            FileSystemTool::mkdir($modulesDir, 0777, true);


            if (false === $this->hasModule($modulesDir, $module, $version)) {


                $listUrl = ModuleInstallerConfig::getMainModuleRepoUrl() . "/module-list.json";
                $list = json_decode(file_get_contents($listUrl), true);
                if (array_key_exists($module, $list)) {
                    $info = $list[$module];

                    if (null !== $version && array_key_exists('version', $info)) {
                        if ($info['version'] < $version) {
                            $this->logError(__("module {module} in web repository doesnt' have version {expectedVersion}, it only has version {currentVersion}", LL, [
                                'module' => $module,
                                'expectedVersion' => $version,
                                'currentVersion' => $info['version'],
                            ]));
                            return;
                        }
                    }

                    $dstModuleDir = $this->appModulesDir . "/$module";
                    MainRepoUtil::downloadModule($module, $dstModuleDir);

                } else {
                    $this->logError(__("module {module} was not found in the web repository", LL, [
                        "module" => $module,
                    ]));
                }
            } else {
                $this->logInfo(__("module {module} found in cache", LL, [
                    'module' => $module,
                ]));
                $this->copyFromCache($module);
            }
        } else {
            $this->logInfo(__("module {module} already installed in the application", LL, [
                'module' => $module,
            ]));
        }

    }

    //------------------------------------------------------------------------------/
    //
    //------------------------------------------------------------------------------/
    private function copyFromCache($module)
    {
        $src = $this->getLocalModulesDir() . "/$module";
        $dst = $this->appModulesDir . "/$module";
        if (is_dir($dst)) {
            FileSystemTool::remove($dst);
        }
        FileSystemTool::copyDir($src, $dst);
    }

    private function getLocalModulesDir()
    {
        return $this->localDir . "/modules";
    }

    private function hasModule($modulesDir, $module, $version = null)
    {
        $modDir = $modulesDir . "/$module";
        if (is_dir($modDir)) {
            $f = $modDir . "/module-info.yml";
            if (file_exists($f)) {
                if (null === $version) {
                    return true;
                } else {
                    $info = BabyYamlUtil::readFile($f);
                    if (array_key_exists('version', $info)) {
                        return ($info['version'] >= $version);
                    } else {
                        return true;
                    }
                }
            } else {
                return true;
            }
        } else {
            return false;
        }
    }

    private function logError($msg)
    {
        $this->logs[] = [
            'error',
            $msg,
        ];
    }

    private function logInfo($msg)
    {
        $this->logs[] = [
            'info',
            $msg,
        ];
    }
}