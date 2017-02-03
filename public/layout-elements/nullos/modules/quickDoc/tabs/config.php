<?php


use Bat\FileSystemTool;
use QuickDoc\QuickDocPreferences;

$form = QuickFormZ::create();
$form->title = __("Configuration", LL);


$form->formTreatmentFunc = function (array $formattedValues, &$msg) {
    $srcDir = $formattedValues['srcDir'];
    $dstDir = $formattedValues['dstDir'];
    $linksUrlPrefix = $formattedValues['linksUrlPrefix'];
    $linksAbsoluteUrlPrefix = $formattedValues['linksAbsoluteUrlPrefix'];


    if (file_exists($srcDir)) {
        FileSystemTool::mkdir($dstDir, 0777, true);
        if (file_exists($dstDir)) {
            QuickDocPreferences::setPreferences([
                'srcDir' => $srcDir,
                'dstDir' => $dstDir,
                'linksUrlPrefix' => $linksUrlPrefix,
                'linksAbsoluteUrlPrefix' => $linksAbsoluteUrlPrefix,
            ]);
            return true;
        } else {
            $msg = __("The destination directory must exist", LL);
            return false;
        }
    } else {
        $msg = __("The source directory must exist", LL);
        return false;
    }
};


$prefs = QuickDocPreferences::getPreferences();
$form->defaultValues = [
    'srcDir' => $prefs['srcDir'],
    'dstDir' => $prefs['dstDir'],
    'linksUrlPrefix' => $prefs['linksUrlPrefix'],
    'linksAbsoluteUrlPrefix' => $prefs['linksAbsoluteUrlPrefix'],
];

$form->labels = [
    'srcDir' => __('srcDir', LL),
    'dstDir' => __('dstDir', LL),
    'linksUrlPrefix' => __('linksUrlPrefix', LL),
    'linksAbsoluteUrlPrefix' => __('linksAbsoluteUrlPrefix', LL),
];


$form->addControl("srcDir")->type('text')->addConstraint("required");
$form->addControl("dstDir")->type('text')->addConstraint("required");
$form->addControl("linksUrlPrefix")->type('text');
$form->addControl("linksAbsoluteUrlPrefix")->type('text');


$form->play();