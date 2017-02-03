<?php


use ModuleInstaller\ModuleInstallerPreferences;

$form = QuickFormZ::create();
$form->title = __("Configuration", LL);


$form->formTreatmentFunc = function (array $formattedValues, &$msg) {
    ModuleInstallerPreferences::setPreferences($formattedValues);
    return true;
};


$prefs = ModuleInstallerPreferences::getPreferences();
$form->defaultValues = $prefs;

$form->labels = [
    "warpZone" => __("Warp zone"),
];
$form->addControl("warpZone")->type('text')->addConstraint("required");

$form->play();