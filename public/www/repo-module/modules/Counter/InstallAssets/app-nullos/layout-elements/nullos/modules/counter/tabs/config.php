<?php


use Counter\CounterPreferences;
use LogWatcher\LogWatcherPreferences;

$form = QuickFormZ::create();
$form->title = __("Configuration", LL);


$form->formTreatmentFunc = function (array $formattedValues, &$msg) {
    CounterPreferences::setPreferences($formattedValues);
};


$prefs = CounterPreferences::getPreferences();
$form->labels = [
    'nbLinesPerPageList' => __("List of 'number of lines per page'", LL),
];
$form->defaultValues = [
    'nbLinesPerPageList' => $prefs['nbLinesPerPageList'],
];
$form->addControl("nbLinesPerPageList")->type('multipleInput');

$form->play();