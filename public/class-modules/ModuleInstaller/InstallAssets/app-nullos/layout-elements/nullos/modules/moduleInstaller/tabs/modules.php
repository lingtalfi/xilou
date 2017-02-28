<?php


use AdminTable\Listable\ArrayListable;
use AdminTable\NullosAdminTable;
use AdminTable\Table\ListWidgets;
use Layout\Goofy;
use ModuleInstaller\Exception\ReportException;
use ModuleInstaller\ModuleInstallerUtil;
use PublicException\PublicException;


?>
    <div class="tac bignose install-page">
        <h3><?php echo __("Modules", LL); ?></h3>
    </div>
<?php

if (
    array_key_exists('ric', $_POST) &&
    array_key_exists('action', $_POST)
) {
    $name = (string)$_POST['ric'];
    $value = (string)$_POST['action'];

    try {

        $msg = '';
        if ('install' === $value) {
            ModuleInstallerUtil::installModule($name);
            $msg = __("The module was successfully installed", LL);
        } elseif ('uninstall' === $value) {
            ModuleInstallerUtil::uninstallModule($name);
            $msg = __("The module was successfully uninstalled", LL);
        } elseif ('pack' === $value) {
            ModuleInstallerUtil::packModule($name);
            $msg = __("The module was successfully packed", LL);
        } elseif ('packall' === $value) {
            ModuleInstallerUtil::packAllModules();
            $msg = __("The modules were successfully packed", LL);
        } elseif ('remove' === $value) {
            ModuleInstallerUtil::removeModule($name);
            $msg = __("The module was successfully removed", LL);
        } else {
            throw new \Exception("Unknown value type: $value");
        }
        Goofy::alertSuccess($msg, false, true);
    } catch (ReportException $e) {
        $m = __("Message(s) from the report");
        $m .= "<br>";
        foreach ($e->getReport()->getMessages() as $message) {
            $m .= $message . '<br>';
        }
        Goofy::alertError($m);
    } catch (PublicException $e) {
        Goofy::alertError($e->getMessage());
    } catch (\Exception $e) {
        Goofy::alertError(__("Oops, an error occurred, please check the logs"));
        Logger::log($e, "moduleInstaller.modulesList");
    }
}


$arr = ModuleInstallerUtil::getModulesList();
$arr = array_values($arr);
$list = NullosAdminTable::create()
    ->setRic(['name'])
    ->setWidgets(ListWidgets::create()
        ->disableMultipleActions()
    )
    ->setListable(ArrayListable::create()->setArray($arr)->searchColumns(['name']));


$list->showCheckboxes = false;


$list->setTransformer('installer', function ($v, $item, $ricValue) {
    if ($v >= 1) {
        $s = '
<div style="display: flex; justify-content: center">
<a href="#" data-action="uninstall" data-ric="' . $ricValue . '"  class="action-link postlink confirmlink">' . __("Uninstall", LL) . '</a> 
<span style="margin:0 10px">-</span> 
<a href="#" data-action="install" data-ric="' . $ricValue . '"  class="action-link postlink confirmlink">' . __("Install", LL) . '</a>';

        if (2 === $v) {
            $s .= '
<span style="margin:0 10px">-</span> 
<a href="#" data-action="pack" data-ric="' . $ricValue . '"  class="action-link postlink confirmlink">' . __("Pack", LL) . '</a>
';
        }

        $s .= '</div>';
        return $s;
    }
    return '';
});

$list->setTransformer('core', function ($v, $item, $ricValue) {
    if (0 === $v) {
        return '
<a href="#" data-action="remove" data-ric="' . $ricValue . '"  class="action-link confirmlink postlink">' . __("Remove", LL) . '</a>
';
    }
    return '';
});


$classes = [
    'unknown' => 'black',
    'installed' => 'green',
    'uninstalled' => 'gray',
];
$list->setTransformer('state', function ($v, $item) use ($classes) {

    $class = $classes[$v];
    return '<span style="color: ' . $class . '">' . $v . '</span>';
});


?>
    <div class="pad">
        <form method="post" action="">
            <button id="moduleinstaller-packall-btn" type="submit"
                    class="autowidth"><?php echo __("Pack all modules", LL); ?></button>
            <input type="hidden" name="ric" value="fake">
            <input type="hidden" name="action" value="packall">
        </form>
        <script>
            var btn = document.getElementById('moduleinstaller-packall-btn');
            btn.addEventListener('click', function (e) {
                e.preventDefault();
                e.target.parentNode.submit();
            });
        </script>
    </div>
<?php

$list->displayTable();
