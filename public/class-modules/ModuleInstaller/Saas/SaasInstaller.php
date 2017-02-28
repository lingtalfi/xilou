<?php


namespace ModuleInstaller\Saas;


use Bat\ClassTool;
use Installer\Report\Report;
use Installer\Saas\ModuleSaasInterface;
use ModuleInstaller\ModuleInstallerUtil;

class SaasInstaller
{


    public static function subscribe(ModuleSaasInterface $module, Report $report)
    {

        $list = ModuleInstallerUtil::getModulesList();

        //------------------------------------------------------------------------------/
        // SUBSCRIBE
        //------------------------------------------------------------------------------/
        $serviceIds = $module->getSubscriberServiceIds();
        $moduleBase = '\\' . ltrim(substr(get_class($module), 0, -9), '\\');
        $subscriberModule = $moduleBase . 'Module';
        foreach ($serviceIds as $id) {
            try {
                self::transformByServiceId($id, $subscriberModule, $list, 'add');
            } catch (\Exception $e) {
                $report->addMessage("Message from $subscriberModule while subscribing to $id");
                $report->addMessage($e);
            }
        }

        //------------------------------------------------------------------------------/
        // PROVIDERS
        //------------------------------------------------------------------------------/
        if (true) {


            $transformMode = 'add';
            $serviceClass = $moduleBase . 'Services';
            $services = [];
            try {
                $class = new \ReflectionClass($serviceClass);
            } catch (\ReflectionException $e) {
                $class = null;
            }
            if (null !== $class) { // it's a provider
                $methods = $class->getMethods(\ReflectionMethod::IS_PUBLIC | \ReflectionMethod::IS_STATIC);
                foreach ($methods as $method) {
                    $services[] = $method->getName();
                }
            }
            foreach ($list as $info) {
                if (1 === $info['core'] || 'installed' === $info['state']) {
                    $name = $info['name'];
                    $moduleClass = '\\' . $name . '\\' . $name . "Module";
                    try {
                        $class = new \ReflectionClass($moduleClass);
                    } catch (\ReflectionException $e) {
                        $class = null;
                    }
                    if (null !== $class) {
                        // does it have the expected methods?
                        foreach ($services as $method) {
                            try {
                                $reflectionMethod = new \ReflectionMethod($moduleClass, $method);
                            } catch (\ReflectionException $e) {
                                $reflectionMethod = null;
                            }
                            if (null !== $reflectionMethod) {
                                // it has the method, so register it


                                // note: method from the subscriber and provider should be the same,
                                // but just in case...
                                $_method = new \ReflectionMethod($serviceClass, $method);
                                $invocMethod = self::getInvocationMethod($_method);
                                $line = $moduleClass . '::' . $invocMethod . ';';
                                $transformer = self::getTransformer($line, $transformMode);
                                ClassTool::rewriteMethodContent($serviceClass, $method, $transformer);
                            }
                        }
                    }
                }
            }
        }

    }


    public static function unsubscribe(ModuleSaasInterface $module, Report $report)
    {
        //------------------------------------------------------------------------------/
        // UNSUBSCRIBE
        //------------------------------------------------------------------------------/
        $serviceIds = $module->getSubscriberServiceIds();
        $moduleBase = '\\' . ltrim(substr(get_class($module), 0, -9), '\\');
        $subscriberModule = $moduleBase . 'Module';
        $list = ModuleInstallerUtil::getModulesList();
        foreach ($serviceIds as $id) {
            try {
                self::transformByServiceId($id, $subscriberModule, $list, 'remove');
            } catch (\Exception $e) {
                $report->addMessage($e);
            }
        }

        //------------------------------------------------------------------------------/
        // PROVIDERS
        //------------------------------------------------------------------------------/
        // just remove all services subscribers
        $serviceClass = $moduleBase . 'Services';
        try {
            $class = new \ReflectionClass($serviceClass);
        } catch (\ReflectionException $e) {
            $class = null;
        }
        if (null !== $class) { // it's a provider
            $methods = $class->getMethods(\ReflectionMethod::IS_PUBLIC | \ReflectionMethod::IS_STATIC);
            foreach ($methods as $method) {
                $methodName = $method->getName();
                $transformer = function (array &$lines) {
                    $lines = [];
                };
                ClassTool::rewriteMethodContent($serviceClass, $methodName, $transformer);
            }
        }
    }






    //------------------------------------------------------------------------------/
    //
    //------------------------------------------------------------------------------/
    private static function error($msg)
    {
        throw new \Exception($msg);
    }

    private static function getInvocationMethod(\ReflectionMethod $method)
    {
        $params = [];
        foreach ($method->getParameters() as $parameter) {
            $params[] = '$' . $parameter->getName();
        }
        $s = '';
        $s .= $method->getName();
        $s .= '(' . implode(', ', $params) . ')';
        return $s;
    }

    private static function transformByServiceId($serviceId, $subscriberModule, $moduleList, $transformMode)
    {
        $p = explode('.', $serviceId);
        $moduleName = $p[0];
        $method = $p[1];
        $p = explode(':', $method);
        $method = $p[0];

        /**
         * Desired pos:
         * - null: last
         * - positive int: the index
         */
        $desiredPos = null;
        if (array_key_exists(1, $p)) {
            $desiredPos = $p[1];
        }


        if (array_key_exists($moduleName, $moduleList)) {

            $class = '\\' . $moduleName . '\\' . $moduleName . "Services";

            if (1 === $moduleList[$moduleName]['core'] || 'installed' === $moduleList[$moduleName]['state']) {
                $serviceFile = APP_ROOT_DIR . "/class-modules/$moduleName/$moduleName" . "Services.php";
                if (file_exists($serviceFile)) {

                    $refMethod = new \ReflectionMethod($class, $method);
                    $invocMethod = self::getInvocationMethod($refMethod);
                    $line = $subscriberModule . '::' . $invocMethod . ';';
                    $transformer = self::getTransformer($line, $transformMode, $desiredPos);
                    ClassTool::rewriteMethodContent($class, $method, $transformer);

                } else {
                    self::error("Services file not found for module $moduleName");
                }
            }
        }
    }


    public static function subscribeModule($class, $method, $subscriberModule, $transformMode, $desiredPos)
    {
        $refMethod = new \ReflectionMethod($class, $method);
        $invocMethod = self::getInvocationMethod($refMethod);
        $line = $subscriberModule . '::' . $invocMethod . ';';
        $transformer = self::getTransformer($line, $transformMode, $desiredPos);
        ClassTool::rewriteMethodContent($class, $method, $transformer);
    }

    private static function getTransformer($line, $transformMode, $desiredPos = null)
    {
        return function (&$lines) use ($line, $transformMode, $desiredPos) {
            if ('add' === $transformMode) {
                if (false === in_array($line, $lines, true)) {
                    if (is_numeric($desiredPos)) {
                        $desiredPos = (int)$desiredPos;
                        array_splice($lines, $desiredPos, 0, $line);
                        return;
                    }
                    $lines[] = $line;
                }
            } elseif ("remove" === $transformMode) {
                foreach ($lines as $k => $l) {
                    if ($l === $line) {
                        unset($lines[$k]);
                    }
                }
            }
        };
    }
}