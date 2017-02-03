<?php

use AssetsList\AssetsList;
use Layout\Body\Tabby\TabbyTabs;
use Layout\Goofy;
use ModuleInstaller\ModuleInstallerUtil;
use QuickDoc\QuickDocException;


AssetsList::css('/style/tabby.css');
AssetsList::css('/style/key2value-form.css');


define('LL', 'modules/moduleInstaller/moduleInstaller');
Spirit::set('ll', LL);


$tab = "modules";
if (array_key_exists("tab", $_GET)) {
    $tab = $_GET['tab'];
    if (false === in_array($tab, ['modules', 'help', 'tools'])) {
        $tab = "modules";
    }
}




?>
<div class="tabby blues">
    <?php
    $tabs = TabbyTabs::create();
    $tabs->addLeftTab(__("Modules", LL), ModuleInstallerUtil::getTabUri("modules"))->icon('widgets');
    $tabs->addLeftTab(__("Tools", LL), ModuleInstallerUtil::getTabUri("tools"))->icon('build');
    $tabs->addRightTab(__("Help", LL), ModuleInstallerUtil::getTabUri("help"))->icon("help");
    $tabs->display();
    ?>

    <div class="body">
        <?php
        try {
            require_once __DIR__ . "/tabs/" . $tab . ".php";
        } catch (QuickDocException $e) {
            Goofy::alertError($e->getMessage());
        }
        ?>
    </div>
</div>