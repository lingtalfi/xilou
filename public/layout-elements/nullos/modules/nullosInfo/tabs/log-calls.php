<?php


use Layout\Body\GroupedItems\GroupedItemsLayout;
use NullosInfo\NullosInfoPreferences;
use NullosInfo\Util\InfoScanner;


$groups = InfoScanner::getLogCalls();



$prefs = NullosInfoPreferences::getPreferences();
$defaultAlpha = $prefs['logCalls']['alpha'];
$defaultGroup = $prefs['logCalls']['group'];


$layout = new GroupedItemsLayout();
$layout->onPreferencesChange(function (array $newPrefs) {
    NullosInfoPreferences::setPreferences([
        'logCalls' => $newPrefs,
    ]);
});

?>
<h3 class="tac"><?php echo __("Log calls", LL); ?></h3>
<?php

$layout
    ->alpha($defaultAlpha)
    ->groupByFiles($defaultGroup)
    ->groups($groups)
    ->texts([
        'alpha' => __("alphabetical order", LL),
        'group' => __("grouped", LL),
        'all' => __("All items", LL),
    ])
    ->display();
