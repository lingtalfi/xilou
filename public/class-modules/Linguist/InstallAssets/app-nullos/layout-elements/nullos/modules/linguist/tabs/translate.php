<?php


use Layout\Goofy;
use Linguist\LinguistConfig;
use Linguist\LinguistPreferences;
use Linguist\Util\LinguistEqualizer;
use Linguist\Util\LinguistKey2ValueListForm;
use Linguist\Util\LinguistScanner;


$prefs = LinguistPreferences::getPreferences();


$refLang = $prefs['refLang'];
$defaultMode = $prefs["translateTab"]['mode'];
$defaultAlpha = $prefs["translateTab"]['alpha'];
$defaultGroup = $prefs["translateTab"]['group'];

$form = LinguistKey2ValueListForm::create()
    ->lang($prefs['curLang'])
    ->mode($defaultMode)
    ->alpha($defaultAlpha)
    ->groupByFiles($defaultGroup)
    ->definitionItems(function ($curLang) use ($refLang) {
        return LinguistScanner::getDefinitionItems($curLang, $refLang);
    })
    ->titles(__("Modified translations", LL), __("Unmodified translations", LL), __("All translations", LL));


$curLang = $form->getLang();


$langDir = LinguistConfig::getLangDir();
$refDir = $langDir . "/" . $refLang;
$dstDir = $langDir . "/" . $curLang;


$importSuccess = false;
if (array_key_exists('import', $_POST)) {
    $importSuccess = LinguistEqualizer::equalize($refDir, $dstDir);
}


$form->onSubmit(function ($curLang, array $file2Defs) use ($refLang) {
    $langDir = LinguistConfig::getLangDir();
    $refDir = $langDir . "/" . $refLang;
    $curDir = $langDir . "/" . $curLang;
    if (true === LinguistEqualizer::equalizeByFile2Definitions($refDir, $curDir, $file2Defs)) {
        return Goofy::alertSuccess(__("The translations for lang {lang} have been successfully updated", LL, [
            'lang' => "'$curLang'",
        ]), true);
    } else {
        return Goofy::alertError(__("Couldn't write the translations. Are your file permissions correct?", LL), true);
    }
});


$form->onPreferencesChange(function ($curLang, array $newPrefs) {
    LinguistPreferences::setPreferences([
        'curLang' => $curLang,
        "translateTab" => $newPrefs,
    ]);
});

$form
    ->displayHead();


if (true === $importSuccess) {
    Goofy::alertSuccess(__("Missing translations have been successfully imported", LL));
}

$countMissing = LinguistEqualizer::countMissingDefinitions($dstDir, $refDir);

?>
<?php if ($countMissing > 0): ?>
    <div class="flexhe pad">
        <span><?php echo __("There are {count} missing translation strings.", LL, [
                'count' => $countMissing,
            ]); ?></span>

        <form action="" method="post">
            <input type="hidden" name="import" value="1">
            <button type="submit" class="marl"><?php echo __("Import from en", LL); ?></button>
        </form>
    </div>
<?php endif; ?>
<?php
$form->display();
