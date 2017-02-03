<?php

use AssetsList\AssetsList;
use Layout\Body\Tabby\TabbyTabs;
use Layout\Goofy;
use QuickDoc\QuickDocException;
use QuickDoc\QuickDocPreferences;
use QuickDoc\QuickDocUtil;
use QuickDoc\Util\TodoUtil;

AssetsList::css('/style/tabby.css');
AssetsList::css('/style/key2value-form.css');
AssetsList::css("/style/modules/quickDoc/quickDoc.css");

define('LL', 'modules/quickDoc/quickDoc');
Spirit::set('ll', LL);

$prefs = QuickDocPreferences::getPreferences();


$tab = "links";
if (array_key_exists("tab", $_GET)) {
    $tab = $_GET['tab'];
    if (false === in_array($tab, ['links', 'images', 'todo', 'action', 'help', 'config'])) {
        $tab = "links";
    }
}

if (null === $prefs['srcDir']) {
    $tab = "config";
}

$nMissingLinks = QuickDocUtil::countUnfoundItemsByName("links");
$nMissingImages = QuickDocUtil::countUnfoundItemsByName("images");
$nTodos = TodoUtil::getCountTodos();




?>
<div class="tabby quickdoc">
    <?php
    $tabs = TabbyTabs::create();
    $tabs->addLeftTab(__("Links", LL), QuickDocUtil::getTabUri("links"))->icon('link')->badge($nMissingLinks, 'error');
    $tabs->addLeftTab(__("Images", LL), QuickDocUtil::getTabUri("images"))->icon('image')->badge($nMissingImages, 'error');
    $tabs->addLeftTab(__("Todo", LL), QuickDocUtil::getTabUri("todo"))->icon('assignment')->badge($nTodos, 'error');
    $tabs->addLeftTab(__("Action", LL), QuickDocUtil::getTabUri("action"))->icon('play');
    $tabs->addRightTab(__("Help", LL), QuickDocUtil::getTabUri("help"))->icon("help");
    $tabs->addRightTab(__("Config", LL), QuickDocUtil::getTabUri("config"))->icon("settings");
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