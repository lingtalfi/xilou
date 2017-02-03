<?php

use AssetsList\AssetsList;
use Layout\Body\Tabby\TabbyTabs;
use Layout\Goofy;
use LogWatcher\LogWatcherUtil;
use PublicException\PublicException;


AssetsList::css('/style/tabby.css');
AssetsList::css('/style/key2value-form.css');


define('LL', 'modules/logWatcher/logWatcher');
Spirit::set('ll', LL);


$tab = "logs";
if (array_key_exists("tab", $_GET)) {
    $tab = $_GET['tab'];
    if (false === in_array($tab, ['logs', 'help', 'config'])) {
        $tab = "logs";
    }
}


?>
<div class="tabby">
    <?php
    $tabs = TabbyTabs::create();
    $tabs->addLeftTab(__("Logs", LL), LogWatcherUtil::getTabUri("logs"))->icon('find-page');
    $tabs->addRightTab(__("Help", LL), LogWatcherUtil::getTabUri("help"))->icon("help");
    $tabs->addRightTab(__("Config", LL), LogWatcherUtil::getTabUri("config"))->icon("settings");
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