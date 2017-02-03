<?php


require_once __DIR__ . "/../init.php";


use Icons\Util\FactoryGenerator;


$className = 'IconsFactory';
$svgFiles = [
    APP_ROOT_DIR . "/class-core/Icons/icons.svg",
];
$dstDir = APP_ROOT_DIR . "/class-core/Icons";

//------------------------------------------------------------------------------/
// SCRIPT
//------------------------------------------------------------------------------/
FactoryGenerator::createFactory($className, $svgFiles, $dstDir);