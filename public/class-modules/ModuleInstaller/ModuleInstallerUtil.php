<?php


namespace ModuleInstaller;


use BabyYaml\BabyYamlUtil;
use Bat\FileSystemTool;
use Installer\ModuleInstallerInterface;
use Installer\PackableModuleInstallerInterface;
use Installer\Report\Report;
use Installer\Saas\ModuleSaasInterface;
use Installer\Universe\ModuleUniverseInterface;
use ModuleInstaller\Exception\ReportException;
use ModuleInstaller\ModuleRepo\MainRepoUtil;
use ModuleInstaller\Saas\SaasInstaller;
use ModuleInstaller\Universe\ModuleUniverseInstaller;
use ModuleInstaller\Util\RepoCachedListUtil;
use PublicException\PublicException;

class ModuleInstallerUtil
{


    public static function getTabUri($tab, array $extra = null)
    {
        $ret = ModuleInstallerConfig::getUri() . "?tab=" . $tab;
        if (null !== $extra) {
            foreach ($extra as $k => $v) {
                $ret .= "&" . $k . "=$v";
            }
        }
        return $ret;
    }

    public static function getModuleDir($name)
    {
        return ModuleInstallerConfig::getModulesDir() . '/' . str_replace('.', '', $name);
    }

    public static function getModuleNames()
    {
        $ret = [];
        $dir = ModuleInstallerConfig::getModulesDir();
        $files = scandir($dir);
        foreach ($files as $f) {
            if ('.' !== $f && '..' !== $f) {
                $file = $dir . '/' . $f;
                if (is_dir($file)) {
                    $ret[] = $f;
                }
            }
        }
        return $ret;
    }


    public static function getModuleInfo($module)
    {
        $dir = ModuleInstallerConfig::getModulesDir();
        $file = $dir . "/$module/module-info.yml";
        if (file_exists($file)) {
            $info = BabyYamlUtil::readFile($file);
            $version = (array_key_exists('version', $info)) ? $info['version'] : "";
            $author = (array_key_exists('author', $info)) ? $info['author'] : "";
            $description = (array_key_exists('description', $info)) ? $info['description'] : "";
            $releaseDate = (array_key_exists('releaseDate', $info)) ? $info['releaseDate'] : "";
            return [
                'version' => $version,
                'author' => $author,
                'description' => $description,
                'releaseDate' => $releaseDate,
            ];
        }
        return false;
    }

    public static function getModulesList()
    {

        $cachedList = RepoCachedListUtil::getCachedRepoList();


        $ret = [];
        $dir = ModuleInstallerConfig::getModulesDir();
        $files = scandir($dir);
        $coreMods = ModuleInstallerConfig::getCoreModules();
        $states = ModuleInstallerPreferences::getPreferences();
        foreach ($files as $f) {
            if ('.' !== $f && '..' !== $f) {
                $file = $dir . '/' . $f;
                if (is_dir($file)) {


                    $hasInstaller = 0;
                    $installerFile = $file . "/$f" . "Installer.php";
                    $moduleInfoFile = $file . "/module-info.yml";

                    if (file_exists($installerFile)) {
                        $hasInstaller = 1;
                        $class = '\\' . $f . '\\' . $f . 'Installer';
                        $object = new $class;
                        if ($object instanceof PackableModuleInstallerInterface) {
                            $hasInstaller++;
                        }
                    }

                    $version = "";
                    $lastVersion = "";
                    $description = "";
                    $releaseDate = "";
                    if (file_exists($moduleInfoFile)) {
                        $info = BabyYamlUtil::readFile($moduleInfoFile);
                        $version = (array_key_exists('version', $info)) ? $info['version'] : $description;
                    }

                    if (array_key_exists($f, $cachedList)) {
                        if (array_key_exists('version', $cachedList[$f])) {
                            $lastVersion = $cachedList[$f]['version'];
                        }
                    }

                    $state = 'unknown'; // unknown|installed|uninstalled
                    if (array_key_exists($f, $states)) {
                        $state = $states[$f];
                    }
                    $isCore = (in_array($f, $coreMods, true)); // core modules cannot be deleted


                    $ret[$f] = [
                        'name' => $f,
                        'version' => $version,
                        'lastVersion' => $lastVersion,
                        'state' => $state,
                        'installer' => (int)$hasInstaller,
                        'core' => (int)$isCore,
                    ];
                }
            }
        }
        return $ret;
    }

    public static function installModule($name)
    {
        $class = self::getModuleInstallerInstance($name, 'install');
        $report = new Report();
        call_user_func([$class, 'install'], $report);

        if ($class instanceof ModuleSaasInterface) {
            SaasInstaller::subscribe($class, $report);
        }
        if ($class instanceof ModuleUniverseInterface) {
            ModuleUniverseInstaller::installPlanets($class, $report);
        }
        self::throwReportIfNecessary($report);


        $states = ModuleInstallerPreferences::getPreferences();
        $states[$name] = 'installed';
        ModuleInstallerPreferences::setPreferences($states);
        return $report;
    }

    public static function uninstallModule($name)
    {
        $class = self::getModuleInstallerInstance($name, 'uninstall');
        $report = new Report();
        call_user_func([$class, 'uninstall'], $report);

        if ($class instanceof ModuleSaasInterface) {
            SaasInstaller::unsubscribe($class, $report);
        }
        if ($class instanceof ModuleUniverseInterface) {
            ModuleUniverseInstaller::uninstallPlanets($class, $report);
        }
        self::throwReportIfNecessary($report);


        $states = ModuleInstallerPreferences::getPreferences();
        $states[$name] = 'uninstalled';
        ModuleInstallerPreferences::setPreferences($states);
    }

    public static function packModule($name)
    {
        $class = self::getModuleInstallerInstance($name, 'pack');
        if (method_exists($class, "pack")) {
            call_user_func([$class, 'pack']);
        }
    }

    public static function packAllModules()
    {
        $list = self::getModulesList();
        foreach ($list as $info) {
            if (2 === $info['installer']) {
                self::packModule($info['name']);
            }
        }
    }

    public static function removeModule($name)
    {
        $dir = self::getModuleDir($name);
        if (true === FileSystemTool::existsUnder($dir, ModuleInstallerConfig::getModulesDir())) {

            $states = ModuleInstallerPreferences::getPreferences();
            if ('installed' === $states[$name]) {
                self::uninstallModule($name);
            }
            unset($states[$name]);
            ModuleInstallerPreferences::setPreferences($states);


            $list = self::getModulesList();
            foreach ($list as $item) {
                if ($name === $item['name']) {
                    if (0 === $item['core']) {
                        FileSystemTool::remove($dir);
                    } else {
                        throw new \Exception("A core module cannot be removed via the gui");
                    }
                }
            }
        }

    }

    //------------------------------------------------------------------------------/
    // REPO METHODS
    //------------------------------------------------------------------------------/
    public static function repoListIsOutOfDate()
    {
        $prefs = ModuleInstallerPreferences::getPreferences();
        $id = MainRepoUtil::getRepoId();
        if ($id === (int)$prefs['mainRepoId']) {
            return false;
        }
        return true;
    }

    public static function updateRepoList()
    {
        $id = MainRepoUtil::getRepoId();
        $list = MainRepoUtil::getModuleInfoList();
        $prefs = [
            'mainRepoId' => $id,
        ];
        ModuleInstallerPreferences::setPreferences($prefs);
        RepoCachedListUtil::setCachedRepoList($list);
    }

    public static function updateModule($module)
    {
        //  we actually force the update of the module in this case
        // i.e. the checkings have been done before
        $list = RepoCachedListUtil::getCachedRepoList();
        if (array_key_exists($module, $list)) {
            $dstModuleDir = APP_ROOT_DIR . "/class-modules/$module";
            $progressFile = ModuleInstallerConfig::getProgressFile();
            MainRepoUtil::downloadModule($module, $dstModuleDir, function($file, $n, $count) use($progressFile){
                az($file);
                file_put_contents($progressFile, "$n/$count");
            });
        }
    }


    //------------------------------------------------------------------------------/
    //
    //------------------------------------------------------------------------------/
    private static function getModuleInstallerInstance($name, $method)
    {
        $dir = self::getModuleDir($name);
        if (true === FileSystemTool::existsUnder($dir, ModuleInstallerConfig::getModulesDir())) {
            $installerFile = $dir . "/$name" . "Installer.php";
            if (file_exists($installerFile)) {
                $class = '\\' . $name . '\\' . $name . 'Installer';
                $inst = new $class;
                if ($inst instanceof ModuleInstallerInterface) {

                    if ('pack' === $method) {
                        if (!$inst instanceof PackableModuleInstallerInterface) {
                            throw new \Exception("module installer for $name must implement PackableModuleInstallerInterface in order to call pack");
                        }
                    }

                    return $inst;
                } else {
                    throw new PublicException(__("Oops, module installer for {name} does not implement ModuleInstallerInterface", "modules/moduleInstaller/moduleInstaller", [
                        'name' => $name,
                        'method' => $method,
                    ]));
                }
            } else {
                throw new PublicException(__("Oops, module installer not found", "modules/moduleInstaller/moduleInstaller"));
            }
        } else {
            throw new PublicException(__("Oops, module installer not found", "modules/moduleInstaller/moduleInstaller"));
        }
    }

    private static function throwReportIfNecessary(Report $report)
    {
        if (true === $report->hasMessages()) {
            $e = new ReportException();
            $e->setReport($report);
            throw $e;
        }
    }

}