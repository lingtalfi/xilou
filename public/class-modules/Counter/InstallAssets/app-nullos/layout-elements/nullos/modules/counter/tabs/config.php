<?php


use Counter\CounterPreferences;
use LogWatcher\LogWatcherPreferences;

$form = QuickFormZ::create();
$form->title = __("Configuration", LL);


$form->formTreatmentFunc = function (array $formattedValues, &$msg) {
    CounterPreferences::setPreferences($formattedValues);
};


$prefs = CounterPreferences::getPreferences();
$availableSites = $prefs['availableSites'];


$form->labels = [
    'availableSites' => __("Available sites", LL),
];
$form->defaultValues = [
    'availableSites' => $prefs['availableSites'],
];
$form->addControl("availableSites")->type('selectMultiple', $availableSites);

$form->play();