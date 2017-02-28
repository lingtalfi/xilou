<?php


use AssetsList\AssetsList;
use Bat\FileSystemTool;
use DirScanner\YorgDirScannerTool;
use Layout\Goofy;
use Linguist\LinguistUtil;
use Linguist\Util\LinguistModuleScanner;
use Linguist\Util\LinguistScanner;
use ModuleInstaller\ModuleInstallerUtil;


$dir = APP_ROOT_DIR;
$files = YorgDirScannerTool::getFiles($dir, true, true);
$dirs = YorgDirScannerTool::getDirs($dir, true, true);
$modules = ModuleInstallerUtil::getModuleNames();


AssetsList::css("style/modules/layout/blues.css");


$messages = [];
$thefile = "";
$thedir = "";
$themodule = "";
$useDeflat = true;
$showMessages = true;
try {

    if (array_key_exists('file', $_POST)) {
        $thefile = $_POST['file'];
        $messages = LinguistScanner::scanTranslationsByFile($thefile);
    } elseif (array_key_exists('dir', $_POST)) {
        $thedir = $_POST['dir'];
        $messages = LinguistScanner::scanTranslationsByDir($thedir);
    } elseif (array_key_exists('module', $_POST)) {
        $themodule = $_POST['module'];
        $tmpDir = FileSystemTool::tempDir();
        $allMessages = LinguistModuleScanner::getTranslationsByModule($themodule, $tmpDir);
        $messages = $allMessages;
        $useDeflat = false;
    } elseif (array_key_exists('trans', $_POST)) {
        $themodule = $_POST['trans'];
        $langs = LinguistModuleScanner::getModuleLangs($themodule);
        LinguistModuleScanner::createModuleTranslationsFile($themodule, $langs);
        $showMessages = false;
        Goofy::alertSuccess(__("The translations file has been created successfully.", LL));
    } elseif (array_key_exists('alltrans', $_POST)) {
        LinguistUtil::completeAllModules();
        Goofy::alertSuccess(__("All the translations files has been created.", LL));
    }
} catch (\Exception $e) {
    Goofy::alertError(Helper::defaultLogMsg());
    Logger::log($e, "linguist.gui.tools");
}


?>

<section class="pad">
    <div class="tac boxy">
        <h3 class="banner"><?php echo __("Display things", LL); ?></h3>


        <div>
            <?php echo __("Choose a file to display its translations", LL); ?>

            <form method="post" action="" id="theform">
                <select name="file" id="theselect">
                    <option value="0"><?php echo __("Choose a file...", LL); ?></option>
                    <?php foreach ($files as $file):
                        $absFile = $dir . '/' . $file;
                        $sel = ($thefile === $absFile) ? ' selected="selected"' : '';
                        ?>
                        <option <?php echo $sel; ?> value="<?php echo $absFile; ?>"><?php echo $file; ?></option>
                    <?php endforeach; ?>
                </select>
            </form>
        </div>
        <div>
            <?php echo __("Or choose a directory to display all the translations it contains", LL); ?>

            <form method="post" action="" id="theformdir">
                <select name="dir" id="theselectdir">
                    <option value="0"><?php echo __("Choose a directory...", LL); ?></option>
                    <?php foreach ($dirs as $_dir):
                        $absDir = $dir . '/' . $_dir;
                        $sel = ($thedir === $absDir) ? ' selected="selected"' : '';
                        ?>
                        <option <?php echo $sel; ?> value="<?php echo $absDir; ?>"><?php echo $_dir; ?></option>
                    <?php endforeach; ?>
                </select>
            </form>
        </div>


        <div>
            <?php echo __("Or choose a module to display all its translations", LL); ?>

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


        <h3 class="banner"><?php echo __("Complete", LL); ?></h3>
        <div>

            <?php echo __("Complete the translation file for a given module", LL); ?>

            <form method="post" action="" id="theformtrans">
                <select name="trans" id="theselecttrans">
                    <option value="0"><?php echo __("Choose a module...", LL); ?></option>
                    <?php foreach ($modules as $module):
                        $sel = ($themodule === $module) ? ' selected="selected"' : '';
                        ?>
                        <option <?php echo $sel; ?> value="<?php echo $module; ?>"><?php echo $module; ?></option>
                    <?php endforeach; ?>
                </select>
            </form>
        </div>

        <div>

            <?php echo __("Click the button below to complete ALL module's translation files", LL); ?>

            <form method="post" action="" id="theformalltrans">
                <input type="hidden" name="alltrans" value="any">
                <button type="submit" id="theselectalltrans"><?php echo __("Complete All", LL); ?></button>
            </form>
        </div>

        <script>
            var form = document.getElementById('theform');
            var select = document.getElementById('theselect');
            select.addEventListener('change', function () {
                form.submit();
            });


            var formDir = document.getElementById('theformdir');
            var selectDir = document.getElementById('theselectdir');
            selectDir.addEventListener('change', function () {
                formDir.submit();
            });


            var formModule = document.getElementById('theformmodule');
            var selectModule = document.getElementById('theselectmodule');
            selectModule.addEventListener('change', function () {
                formModule.submit();
            });

            var formTrans = document.getElementById('theformtrans');
            var selectTrans = document.getElementById('theselecttrans');
            selectTrans.addEventListener('change', function () {
                formTrans.submit();
            });


            var formAllTrans = document.getElementById('theformalltrans');
            var selectAllTrans = document.getElementById('theselectalltrans');
            selectAllTrans.addEventListener('change', function () {
                formAllTrans.submit();
            });
        </script>


        <div class="results">
            <?php

            if (true === $showMessages) {
                foreach ($messages as $info) {
                    if (true === $useDeflat) {
                        $id = str_replace('"', '\\"', $info['id']);
                    } else {
                        $id = $info;
                    }

                    echo '"' . $id . '" => "' . $id . '",';
                    echo '<br>';
                }
            }

            ?>
        </div>
    </div>
</section>