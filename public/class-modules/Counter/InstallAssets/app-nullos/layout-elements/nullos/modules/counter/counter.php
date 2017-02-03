<?php

use AssetsList\AssetsList;
use Layout\Body\Tabby\TabbyTabs;
use Layout\Goofy;

use PublicException\PublicException;
use Counter\CounterUtil;


AssetsList::css('/style/tabby.css');
AssetsList::css('/style/key2value-form.css');


define('LL', 'modules/counter/counter');
Spirit::set('ll', LL);


$tab = "counter";
if (array_key_exists("tab", $_GET)) {
    $tab = $_GET['tab'];
    if (false === in_array($tab, ['counter', 'tools', 'help', 'config'])) {
        $tab = "counter";
    }
}


?>
<div class="tabby">
    <?php
    $tabs = TabbyTabs::create();
    $tabs->addLeftTab(__("Counter", LL), CounterUtil::getTabUri("counter"))->icon('timeline');
    $tabs->addLeftTab(__("Tools", LL), CounterUtil::getTabUri("tools"))->icon('build');
    $tabs->addRightTab(__("Help", LL), CounterUtil::getTabUri("help"))->icon("help");
//    $tabs->addRightTab(__("Config", LL), CounterUtil::getTabUri("config"))->icon("settings");
    $tabs->display();
    ?>

    <div class="body">
        <?php
        try {
            require_once __DIR__ . "/tabs/" . $tab . ".php";
        } catch (PublicException $e) {
            Goofy::alertError($e->getMessage());
        }
        ?>
    </div>
</div>