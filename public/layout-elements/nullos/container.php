<?php


use AdminTable\Listable\QuickPdoListable;
use AdminTable\Table\AdminTable;
use AdminTable\Table\ListParameters;
use AdminTable\View\AdminTableRenderer;
use AssetsList\AssetsList;
use Commande\AdminTable\CommandeAdminTable;
use Commande\CommandeUtil;
use Container\ContainerUtil;
use Csv\CsvUtil;
use Icons\Icons;
use Layout\Goofy;
use QuickPdo\QuickPdo;

$ll = "zilu";


$containerIds = [];
if (array_key_exists('containers', $_GET) && is_array($_GET['containers'])) {
    $containerIds = array_map(function ($v) {
        return intval($v);
    }, $_GET['containers']);
}
if (0 === count($containerIds)) {
    $containerIds[] = 0;
}


AssetsList::css("style/zilu.css");
AssetsList::css("/style/admintable.css");


$containerId2Refs = ContainerUtil::getId2Labels();

?>


<div class="zilu" id="zilu">
    <div class="zilu-topbar">


        <div class="commande-actions-group">
            <div class="commande-actions-vertical">
                <form>
                    <select id="change-all-fournisseurs-selector">
                        <option>Pour tous les articles de cette commande...</option>
                        <option value="moinscher">Choisir le fournisseur le moins cher pour chaque produit</option>
                        <option value="leaderfit">Choisir le fournisseur leaderfit pour chaque produit</option>
                    </select>
                </form>
                <form>
                    <select id="send-mail-selector">
                        <option>Envoyer un email...</option>
                        <option value="direction">à Didier</option>
                        <option value="fournisseurs">aux fournisseurs</option>
                    </select>
                </form>
            </div>
        </div>

    </div>
    <div class="zilu-split">
        <div class="zilu-summary">

            <form action="" method="get">


                <?php foreach ($containerIds as $containerId): ?>


                    <select class="container-selector" name="containers[]">
                        <option value="0">Choisissez un container</option>
                        <?php foreach ($containerId2Refs as $id => $label):
                            $sel = ($containerId === (int)$id) ? 'selected="selected"' : '';
                            ?>
                            <option <?php echo $sel; ?>
                                    value="<?php echo $id; ?>"><?php echo htmlspecialchars($label); ?></option>
                        <?php endforeach; ?>
                    </select>

                    <?php

                    if (0 !== $containerId):

                        $prixTotal = 0;
                        $poidsTotal = 0;

                        $res = QuickPdo::fetch('
                select label, poids_max, volume_max
                from type_container t
                inner join container c on c.type_container_id=t.id
                where c.id=' . (int)$containerId);

                        $typeContainer = $res['label'];
                        $poidsMax = $res['poids_max'];
                        $volumeMax = $res['volume_max'];


                        ?>
                        <table class="zilu-info">
                            <tr>
                                <td>Type container</td>
                                <td><?php echo $typeContainer; ?></td>
                            </tr>
                            <tr>
                                <td>Poids max</td>
                                <td><?php echo $poidsMax; ?> kg</td>
                            </tr>
                            <tr>
                                <td>Volume max</td>
                                <td><?php echo $volumeMax; ?> m&#179;</td>
                            </tr>
                            <tr>
                                <td>Poids actuel</td>
                                <td><?php echo $prixTotal; ?> kg</td>
                            </tr>
                            <tr>
                                <td>Volume actuel</td>
                                <td><?php echo $poidsTotal; ?> m&#179;</td>
                            </tr>
                        </table>

                    <?php endif; ?>

                    <button class="add-container-spy">ajouter un container</button>

                <?php endforeach; ?>
            </form>
        </div>

        <?php

        $realContainerIds = array_filter($containerIds, function ($v) {
            return (0 !== $v);
        });

        if (count($realContainerIds) > 0) {

        ?>
        <div id="zilu-table" class="zilu-table">
            <?php
            $fields = '
c.id,
co.id as container_id,
co.nom as container,
c.reference,
a.poids,
f.id as fournisseur_id,
f.nom as fournisseur,
fha.prix,
a.id as aid,
a.reference_lf,
a.reference_hldp,
a.descr_fr,
a.descr_en
';


            $query = "select
%s
from zilu.commande c
inner join commande_has_article h on h.commande_id=c.id
inner join fournisseur f on f.id=h.fournisseur_id
inner join fournisseur_has_article fha on fha.fournisseur_id=h.fournisseur_id and fha.article_id=h.article_id
inner join article a on a.id=h.article_id
left join container co on co.id=h.container_id
where co.id in (" . implode(', ', $realContainerIds) . ")";


            $list = CommandeAdminTable::create()
                ->setRic(['id', 'aid'])
                ->setListable(QuickPdoListable::create()->setFields($fields)->setQuery($query))
                ->setRenderer(AdminTableRenderer::create()
                    ->setExtraHiddenFields([
                        "containers[]" => $realContainerIds,
                    ])
                );

            $list->setTransformer("container", function ($value, $item, $ricValue) {
                $text = $value;
                if (null === $value) {
                    $text = "(choisissez un container)";
                }
                return '<a class="container-selector" data-ric="' . htmlspecialchars($ricValue) . '" data-container-id="' . htmlspecialchars($item['container_id']) . '" href="#">' . $text . '</a>';
            });


            $list->setTransformer("fournisseur", function ($value, $item, $ricValue) {
                $text = $value;
                if (null === $value) {
                    $text = "(choisissez un fournisseur)";
                }
                return '<a class="fournisseur-selector" data-article-id="' . $item['aid'] . '" data-ric="' . htmlspecialchars($ricValue) . '" data-fournisseur-id="' . htmlspecialchars($item['fournisseur_id']) . '" href="#">' . $text . '</a>';
            });

            $list->hiddenColumns = [
                'cid',
                'id',
                'container_id',
                'fournisseur_id',
            ];
            $list->displayTable();

            }

            ?>
        </div>


    </div>


</div>


<script>


    $(document).ready(function () {


        $(".container-selector").on('change', function () {
            containerSelect.parentNode.submit();
        });


        $("#change-all-fournisseurs-selector").selectmenu();
        $("#send-mail-selector").selectmenu();


        $('#zilu').on('click', function (e) {
            var jTarget = $(e.target);

            if (jTarget.hasClass("container-selector")) {
                e.preventDefault();
                var containerId = jTarget.attr('data-container-id');
                var ricVal = jTarget.attr('data-ric');

                $.getJSON('/services/zilu.php?action=commande-container-selector&container=' + containerId, function (data) {
                    if ('undefined' !== typeof $("#container-dialog").dialog('instance')) {
                        $("#container-dialog").dialog("close");
                    }

                    $("#container-dialog").dialog({
                        position: {
                            my: "center",
                            at: "center",
                            of: jTarget
                        },
                        open: function (event, ui) {
                            var jSelect = $("#container-dialog").find('select');
                            jSelect.empty();
                            var s = ("0" === containerId) ? 'selected="selected"' : '';
                            jSelect.append('<option ' + s + ' value="0">Choisissez un container</option>');
                            for (var i in data) {
                                s = (i == containerId) ? 'selected="selected"' : '';
                                jSelect.append('<option ' + s + ' value="' + i + '">' + data[i] + '</option>');
                            }
                            jSelect.on('change', function () {
                                var value = jSelect.val();
                                $.getJSON('/services/zilu.php?action=commande-change-container&ric=' + ricVal + "&value=" + value, function (data) {
                                    if ('ok' === data) {
                                        location.reload();
                                    }
                                });
                            });
                        }
                    });
                });

            }
            else if (jTarget.hasClass("fournisseur-selector")) {
                e.preventDefault();
                var fournisseurId = jTarget.attr('data-fournisseur-id');
                var ricVal = jTarget.attr('data-ric');
                var articleId = jTarget.attr('data-article-id');

                $.getJSON('/services/zilu.php?action=commande-fournisseur-selector&article_id=' + articleId + '&fournisseur=' + fournisseurId, function (data) {
                    if ('undefined' !== typeof $("#fournisseur-dialog").dialog('instance')) {
                        $("#fournisseur-dialog").dialog("close");
                    }

                    $("#fournisseur-dialog").dialog({
                        maxHeight: 500,
                        width: 400,
                        position: {
                            my: "center",
                            at: "center",
                            of: jTarget
                        },
                        open: function (event, ui) {
                            var jTable = $("#fournisseur-dialog").find('.zilu-fournisseur-comparison-table > tbody');
                            jTable.empty();

                            var s = "";
                            for (var j in data) {
                                var object = data[j];

                                s = (object.id == fournisseurId) ? 'class="selected"' : '';
                                jTable.append('<tr ' + s + '>' +
                                    '<td><button data-id="' + object.id + '">' + object.nom + '</button></td>' +
                                    '<td>' + object.reference + '</td>' +
                                    '<td>' + object.prix + '</td>' +
                                    '</tr>');
                            }
                            jTable.find('button').on('click', function (e) {
                                e.preventDefault();
                                var selectedFournisseurId = $(this).attr('data-id');
                                $.getJSON('/services/zilu.php?action=commande-change-fournisseur&ric=' + ricVal + "&value=" + selectedFournisseurId, function (data) {
                                    if ('ok' === data) {
                                        location.reload();
                                    }
                                });

                            });
                        }
                    });
                });
            }
            else if (jTarget.hasClass("add-container-spy")) {
                e.preventDefault();
                var jForm = jTarget.parent();
                jForm.append('<input type="hidden" name="containers[]" value="0">');
                jForm.submit();
            }
        });
    });


</script>


<div style="display:none">
    <div id="container-dialog" title="Choisissez un container" class="zilu-dialog centered">
        <select>
            <option>Choisissez un container</option>
        </select>
    </div>
    <div id="fournisseur-dialog" title="Choisissez un fournisseur" class="zilu-dialog centered">
        <table class="zilu-fournisseur-comparison-table">
            <thead>
            <tr>
                <th>Fournisseur</th>
                <th>Référence</th>
                <th>Prix</th>
            </tr>
            </thead>
            <tbody>
            </tbody>
        </table>
    </div>
    <div id="csv-import-dialog" title="Importer une commande par fichier csv" class="zilu-dialog centered">
        <div class="container">
            <form action="" method="post" enctype="multipart/form-data">
                <ul class="flex-outer">
                    <li>
                        <label for="first-name">Choisissez un fichier csv</label>
                        <input id="import-csv-input" type="file" name="csvfile"
                               accept=".csv, application/vnd.openxmlformats-officedocument.spreadsheetml.sheet, application/vnd.ms-excel"
                    </li>
                    <li>
                        <label for="last-name">Pour tous les articles, choisir le fournisseur</label>
                        <select>
                            <option value="moinscher">le moins cher</option>
                            <option value="leaderfit">Leaderfit</option>
                        </select>
                    </li>
                    <li>
                        <button type="submit">Importer</button>
                    </li>
                </ul>
            </form>
        </div>
    </div>
</div>
