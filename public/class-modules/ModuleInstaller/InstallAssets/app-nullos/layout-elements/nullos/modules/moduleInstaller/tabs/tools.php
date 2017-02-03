<?php


use AssetsList\AssetsList;
use Bat\FileSystemTool;
use DirScanner\YorgDirScannerTool;
use Layout\Goofy;
use Linguist\LinguistUtil;
use Linguist\Util\LinguistModuleScanner;
use Linguist\Util\LinguistScanner;
use ModuleInstaller\ModuleInstallerUtil;
use ModuleInstaller\Universe\UniverseUtil;


$dir = APP_ROOT_DIR;
$modules = ModuleInstallerUtil::getModuleNames();


AssetsList::css("style/modules/layout/blues.css");


$values = null;
$themodule = "";
try {

    if (array_key_exists('module', $_POST)) {
        $themodule = $_POST['module'];
        $values = UniverseUtil::getUseStatementsByModule($themodule);
    }
} catch (\Exception $e) {
    Goofy::alertError(Helper::defaultLogMsg());
    Logger::log($e, "moduleInstaller.gui.tools");
}


?>

<section class="pad">
    <div class="tac boxy">
        <h3 class="banner"><?php echo __("Display things", LL); ?></h3>
        <div>
            <?php echo __("Choose a module to display the 'use' statements it uses", LL); ?>

            <form method="post" action="" id="theformmodule">
                <select name="module" id="theselectmodule">
                    <option value="0"><?php echo __("Choose a module...", LL); ?></option>
                    <?php foreach ($modules as $module):
                        $sel = ($themodule === $module) ? ' selected="selected"' : '';
                        ?>
                        <option <?php echo $sel; ?> value="<?php echo $module; ?>"><?php echo $module; ?></option>
                    <?php endforeach; ?>
                </select>
            </form>
        </div>

        <script>

            var formModule = document.getElementById('theformmodule');
            var selectModule = document.getElementById('theselectmodule');
            selectModule.addEventListener('change', function () {
                formModule.submit();
            });
        </script>


        <div class="results whiteboard tal mt20">
            <?php
            if (null !== $values) {
                if (count($values) > 0) {
                    foreach ($values as $v) {
                        echo $v . '<br>';
                    }
                } else {
                    ?>
                    <p><?php echo __("No values to display", LL); ?></p>
                    <?php
                }
            }
            ?>
        </div>
    </div>
</section>