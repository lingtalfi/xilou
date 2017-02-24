<?php


use AdminTable\View\AdminTableRenderer;
use AssetsList\AssetsList;
use Backup\AppBackup;
use DirScanner\YorgDirScannerTool;
use Icons\Icons;
use AdminTable\Listable\ArrayListable;
use AdminTable\Table\AdminTable;
use AdminTable\Table\ListWidgets;
use Layout\Goofy;


$_SESSION['backupsQueryString'] = $_SERVER['QUERY_STRING'];


AssetsList::css("style/zilu.css");
AssetsList::css("/style/admintable.css");


?>


<div class="zilu" id="zilu-backups">
    <div class="zilu-topbar">

        <button class="button-with-icon" id="backups-newbackup-btn">
            <span>
                <span>Créer une sauvegarde</span>
                <?php Icons::printIcon("add", 'white'); ?>
            </span>
        </button>
    </div>
    <div class="zilu-main" style="margin-top: 20px">

        <?php


        $arr = [];
        $dir = APP_ROOT_DIR . "/backup";
        $files = YorgDirScannerTool::getFilesWithExtension($dir, 'sql', false, true, true);
        foreach ($files as $f) {
            $arr[] = [
                'file' => $f,
            ];
        }

        $list = AdminTable::create()
            ->setRic(['file'])
            ->setRicSeparator('--*--')
            ->setWidgets(ListWidgets::create()
                ->setNbItemsPerPageList([1, 2, 5, 'all'])
                ->disableMultipleActions()
                ->disablePagination()
                ->disableNippSelector()
                ->disablePageSelector()
                ->disableSearch()
            )
            ->setListable(ArrayListable::create()->setArray($arr))
            ->setExtraColumn('restore', '<a href="#" class="confirmlink postlink" data-action="restore" data-ric="{ric}" data-value="myvalue">Restore</a>')
            ->setTransformer('restore', function ($v, $item, $ricValue) {
                return str_replace('{ric}', $ricValue, $v);
            })
            ->setSingleActionHandler('restore', function ($ric) {
                try {

                    if (true === AppBackup::create()->restoreBackup($ric['file'])) {
                        Goofy::alertSuccess("Backup restauré");
                    } else {
                        Goofy::alertError("Quelque chose s'est mal passé avec le backup, veuillez contacter le webmaster");
                    }
                } catch (\Exception $e) {
                    Goofy::alertError("Quelque chose s'est mal passé avec le backup, veuillez contacter le webmaster");
                }
            })
            ->setRenderer(AdminTableRenderer::create());

        $list->showCheckboxes = false;

        $list->displayTable();
        ?>
    </div>
</div>


<script>


    $(document).ready(function () {
        $("#backups-newbackup-btn").on('click', function (e) {

            e.preventDefault();
            var jTarget = $(e.target);
            if ('undefined' !== typeof $("#backups-dialog-newbackup").dialog('instance')) {
                $("#backups-dialog-newbackup").dialog("close");
            }

            $("#backups-dialog-newbackup").dialog({
                position: {
                    my: "left top",
                    at: "left top",
                    of: jTarget
                },
                width: 600,
                open: function (event, ui) {


                    var jSubmit = $("#backups-dialog-newbackup").find("button");
                    jSubmit
                        .off('click')
                        .on('click', function (e) {

                            e.preventDefault();

                            var jLoader = $("#backups-dialog-newbackup").find(".loader");
                            jLoader.removeClass("hidden");

                            var relativePath = $('#backups-newbackup-backupname').val();

                            $.get('/services/zilu.php?action=backups-newbackup&relativepath=' + relativePath, function (data) {
                                if ('ok' === data) {
                                    location.reload();
                                }
//                                else {
//                                    jErrorCommandEmpty.removeClass("hidden");
//                                    jErrorCommandEmpty.find('.error').html(data);
//                                }
                            }, 'json');
                        });
                }
            });
        });
    });


</script>


<div style="display:none">


    <div id="backups-dialog-newbackup" title="Créer une nouvelle sauvegarde" class="zilu-dialog centered">
        <div class="container">
            <form>
                <div class="formerror"></div>
                <div class="formwarning"></div>
                <ul class="flex-outer">
                    <li>
                        <label for="first-name">Nom de la sauvegarde</label>
                        <input id="backups-newbackup-backupname" type="text"
                               value="<?php echo date('Ymd--His--') . "mybackup.sql"; ?>"></li>
                    <li>
                        <button type="submit">Sauvegarder</button>
                    </li>
                </ul>
            </form>
            <p class="hidden loader">
                Veuillez patienter...
            </p>
        </div>
    </div>
</div>
