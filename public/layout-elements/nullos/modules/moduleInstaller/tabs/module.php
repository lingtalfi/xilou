<?php
use AssetsList\AssetsList;
use ModuleInstaller\ModuleInstallerUtil;
use QuickTable\QuickTable;

$module = null;
if (array_key_exists('module', $_GET)) {
    $module = $_GET['module'];
}
?>
    <div class="tac bignose">
        <h3><?php echo __("Module {module}", LL, [
                'module' => $module,
            ]); ?></h3>
    </div>
<?php


if (null !== $module) {
    if (false !== ($info = ModuleInstallerUtil::getModuleInfo($module))) {


        AssetsList::css(url('style/infoitem.css'));
        QuickTable::printItem($info, [
            'class' => 'infoitem',
        ]);


    } else {
        ?>
        <p><?php echo __("No information about this module", LL); ?></p>
        <?php
    }
} else {
    ?>
    <p><?php echo __("No module to display", LL); ?></p>
    <?php
}
