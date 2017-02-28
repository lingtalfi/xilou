<?php


use Layout\Body\GroupedItems\GroupedItemsLayout;
use NullosInfo\NullosInfoPreferences;
use NullosInfo\Util\InfoScanner;


$groups = InfoScanner::getPrivilegeHasCalls();


$prefs = NullosInfoPreferences::getPreferences();
$defaultAlpha = $prefs['privileges']['alpha'];
$defaultGroup = $prefs['privileges']['group'];


$layout = new GroupedItemsLayout();
$layout->onPreferencesChange(function (array $newPrefs) {
    NullosInfoPreferences::setPreferences([
        'privileges' => $newPrefs,
    ]);
});

?>
    <h3 class="tac"><?php echo __("Privileges", LL); ?></h3>
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
