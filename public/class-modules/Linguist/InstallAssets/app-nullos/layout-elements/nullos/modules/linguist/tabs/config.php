<?php


use Linguist\LinguistConfig;
use Linguist\LinguistPreferences;
use Linguist\Util\LinguistScanner;

$form = QuickFormZ::create();
$form->title = __("Configuration", LL);


$form->formTreatmentFunc = function (array $formattedValues, &$msg) {
    $refLang = $formattedValues['refLang'];
    $langDir = LinguistConfig::getLangDir();
    $refLangDir = $langDir . "/" . $refLang;
    if (file_exists($refLangDir)) {
        LinguistPreferences::setPreferences($formattedValues);
        return true;
    } else {
        $msg = __("The reference lang directory must exist", LL);
        return false;
    }
};


$prefs = LinguistPreferences::getPreferences();
$form->defaultValues = $prefs;

$form->labels = [
    "refLang" => __("Reference language"),
];

$langNames = LinguistScanner::getLangNames();
$langs = [];
foreach ($langNames as $name) {
    $langs[$name] = $name;
}

$form->addControl("refLang")->type('select', $langs)->addConstraint("required");

$form->play();