<?php


use Layout\Goofy;
use QuickDoc\QuickDocPreferences;
use QuickDoc\QuickDocUtil;
use QuickDoc\Util\Key2ValueListForm;


$type = "images";


$prefs = QuickDocPreferences::getPreferences();

$mappings = QuickDocUtil::getMappings($type);

$defaultMode = $prefs[$type]['mode'];
$defaultAlpha = $prefs[$type]['alpha'];
$defaultGroup = $prefs[$type]['group'];


$form = Key2ValueListForm::create();
$form->onSubmit(function (array $foundList, array $unfoundList) use ($type) {
    $mappings = [
        "found" => $foundList,
        "unfound" => $unfoundList,
    ];


    if (true === QuickDocUtil::mergeMappings($type, $mappings)) {
        return Goofy::alertSuccess(__("The mappings have been successfully updated", LL), true);
    } else {
        return Goofy::alertError(__("Couldn't write the mappings. Are your file permissions correct?", LL), true);
    }
});


$form->onPreferencesChange(function (array $newPrefs) use ($type) {
    QuickDocPreferences::setPreferences([
        $type => $newPrefs,
    ]);
});

$form
    ->mode($defaultMode)
    ->alpha($defaultAlpha)
    ->groupByFiles($defaultGroup)
    ->mappings($mappings)
    ->titles(__("Unresolved images", LL), __("Resolved images", LL), __("All images", LL))
    ->display();
