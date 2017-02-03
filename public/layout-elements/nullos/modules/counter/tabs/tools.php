<?php


use Bat\FileSystemTool;
use Counter\CounterConfig;
use Counter\CounterPreferences;
use Counter\CounterUtil;
use Linguist\LinguistConfig;
use Linguist\LinguistUtil;
use Linguist\Util\LinguistScanner;
use LogWatcher\LogWatcherPreferences;
use QuickDoc\QuickDocUtil;
use Stat\Extractor\Web\FakeCounterGeneratorUtil;

$form = QuickFormZ::create();
$form->title = __("Tools", LL);


$_targets = CounterUtil::getTargetSitesList();
$targets = [];
foreach ($_targets as $target => $path) {
    $targets[$target] = $target;
}


$form->formTreatmentFunc = function (array $formattedValues, &$msg) use ($_targets) {
    $target = $formattedValues['target'];
    if (array_key_exists($target, $_targets)) {
        $path = $_targets[$target];
        if (false === file_exists($path)) {
            $msg = __("The target directory does not exist or is not a dir: {target}", LL, ['target' => $path]);
            return false;
        }

        $counterPath = $path . "/" . CounterConfig::statsDirName();
        if (true === CounterUtil::initStats($path)) {
            $o = new FakeCounterGeneratorUtil();
            $endDay = date('Y-m-d');
            $startDay = (int)date('Y') - 2;
            $startDay .= '-01-01';
            $o->generateByPeriod($counterPath, $startDay, $endDay);
            $msg = __("Fake data have been successfully generated");
            return true;
        } else {
            $msg = __("Permission problem, I need to create a directory in {directory}", LL, ['directory' => $counterPath]);
            return false;
        }
    }
};
$form->title = __("Generate fake data", LL);
$form->labels = [
    'target' => __("Target", LL),
];
$form->defaultValues = [
    'targets' => [],
];
$form->addControl("target")->type('select', $targets);

$form->play();