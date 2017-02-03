<?php

use AssetsList\AssetsList;
use Layout\Body\Tabby\TabbyTabs;
use Layout\Goofy;
use Linguist\LinguistUtil;
use PublicException\PublicException;


AssetsList::css('/style/tabby.css');
AssetsList::css('/style/key2value-form.css');


define('LL', 'modules/linguist/linguist');
Spirit::set('ll', LL);


$tab = "translate";
if (array_key_exists("tab", $_GET)) {
    $tab = $_GET['tab'];
    if (false === in_array($tab, ['translate', 'tools', 'help', 'config'])) {
        $tab = "translate";
    }
}







?>
<div class="tabby blues">
    <?php
    $tabs = TabbyTabs::create();
    $tabs->addLeftTab(__("Translate", LL), LinguistUtil::getTabUri("links"))->icon('translate');
    $tabs->addLeftTab(__("Tools", LL), LinguistUtil::getTabUri("tools"))->icon('build');
    $tabs->addRightTab(__("Help", LL), LinguistUtil::getTabUri("help"))->icon("help");
    $tabs->addRightTab(__("Config", LL), LinguistUtil::getTabUri("config"))->icon("settings");
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