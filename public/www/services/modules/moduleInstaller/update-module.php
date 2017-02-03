<?php



use ModuleInstaller\Layout\LiveSteps\ModuleInstallerLiveSteps;

require_once __DIR__ . "/../../../../init.php";


$o = new ModuleInstallerLiveSteps();
$o->listen();
