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


$_SESSION['containerQueryString'] = $_SERVER['QUERY_STRING'];


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

        <button class="button-with-icon repartition-container-button">Répartition automatique...</button>

        <div class="commande-actions-group">
            <button class="button-with-icon csv-import-button">
            <span>
                <span>Exporter les fichiers csv...</span>
                <?php Icons::printIcon("add", 'white'); ?>
            </span>
            </button>
        </div>

    </div>
    <div class="zilu-split">
        <div class="zilu-summary">

            <form action="" method="get" id="container-form">


                <?php

                $cptContainer = 0;
                $nbContainers = count($containerIds);
                foreach ($containerIds as $containerId):
                    $sFirst = (0 === $cptContainer) ? "first" : "";
                    ?>


                    <div class="container-spy-block <?php echo $sFirst; ?>" data-id="<?php echo $containerId; ?>">
                        <select class="container-spy-selector" name="containers[]">
                            <option value="0">Choisissez un container</option>
                            <option value="-1">Nouveau container</option>
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


                            $query = "select
fha.volume,
a.poids
from commande_has_article h
inner join article a on a.id=h.article_id
inner join fournisseur_has_article fha on fha.fournisseur_id=h.fournisseur_id and fha.article_id=h.article_id
where h.container_id=" . $containerId;

                            if (false !== ($res = QuickPdo::fetchAll($query))):


                                $poids = 0;
                                $volume = 0;
                                foreach ($res as $item) {
                                    $poids += $item['poids'];
                                    $volume += $item['volume'];
                                }


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
                                        <td><?php echo $poids; ?> kg</td>
                                    </tr>
                                    <tr>
                                        <td>Volume actuel</td>
                                        <td><?php echo $volume; ?> m&#179;</td>
                                    </tr>
                                </table>

                            <?php endif; ?>
                        <?php endif; ?>


                        <?php if (0 !== $cptContainer || $nbContainers > 1): ?>
                            <button type="submit" class="remove-container-spy">Supprimer ce container</button>
                        <?php endif; ?>

                    </div>
                    <?php
                    $cptContainer++;
                endforeach; ?>
                <button class="add-container-spy">ajouter un container</button>
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
fha.volume,
f.id as fournisseur_id,
f.nom as fournisseur,
fha.prix,
a.id as aid,
a.reference_lf,
a.reference_hldp,
a.descr_fr,
a.descr_en
';


            $textMaxLength = 10;

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

            $list->setTransformer("descr_fr", function ($value, $item, $ricValue) use ($textMaxLength) {
                $cut = substr($value, 0, $textMaxLength);
                return '<span class="longtext" title="' . $value . '">' . htmlspecialchars($cut) . '...</span>';
            });
            $list->setTransformer("descr_en", function ($value, $item, $ricValue) use ($textMaxLength) {
                $cut = substr($value, 0, $textMaxLength);
                return '<span class="longtext" title="' . $value . '">' . htmlspecialchars($cut) . '...</span>';
            });

            $list->hiddenColumns = [
                'cid',
                'id',
                'aid',
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


        $('#container-topmenu-link').attr('href', "/container" + window.location.search);

//        $("input.ui-checkbox").checkboxradio();


        $(document).tooltip();

        $(".container-spy-selector").on('change', function () {
            if ('-1' === $(this).val()) {
                $("#new-container-form-dialog").dialog({
                    position: {
                        my: "left top",
                        at: "left top",
                        of: $(this)
                    },
                    width: 600,
                    open: function (event, ui) {
                        var jBtn = $("#new-container-form-dialog").find('button');
                        var jForm = $("#new-container-form-dialog").find('form');
                        var jInput = $("#new-container-form-dialog").find('input');
                        jBtn.on("click", function (e) {
                            e.preventDefault();
                            var s = jForm.serialize();
                            $.getJSON('/services/zilu.php?action=container-create&' + s, function (data) {
                                if ('duplicate' === data) {
                                    var jError = $("#new-container-form-dialog").find('p.error');
                                    jError.removeClass("hidden");
                                    jInput.on("focus", function () {
                                        jError.addClass("hidden");
                                    });
                                }
                                else {
                                    $(".container-spy-selector:last").append('<option value="' + data + '" selected="selected">any</option>');
                                    var jMainForm = $('#container-form');
                                    jMainForm.submit();
                                }
                            });
                        });
                    }
                });
            }
            else {
                $(this).parent().parent().submit();
            }
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
            else if (jTarget.hasClass("remove-container-spy")) {
                e.preventDefault();
                var jContainerSpyBlock = jTarget.parent();
                containerId = jContainerSpyBlock.attr('data-id');
                jForm = jContainerSpyBlock.parent();
                var jSelectToRemove = jForm.find('.container-spy-block[data-id=' + containerId + '] select');
                jSelectToRemove.removeAttr("name");
                jForm.submit();

            }
            else if (jTarget.hasClass("repartition-container-button")) {
                e.preventDefault();
                $("#repartition-container-dialog").dialog({
                    position: {
                        my: "left top",
                        at: "left top",
                        of: jTarget
                    },
                    minWidth: 700,
                    open: function (event, ui) {
                        var jSubmit = $("#repartition-container-dialog").find("#repartition-submit-btn")
                        jSubmit.on('click', function (e) {
                            e.preventDefault();
                            var jForm = $("#repartition-container-dialog").find('form');
                            $.getJSON('/services/zilu.php?action=container-distribute&' + jForm.serialize(), function (data) {
                                if ('error' in data) {

                                }
                            });

                        });
                    }
                });
            }
        });


//        $(".article-autocomplete-input").autocomplete({
//            source: "/services/zilu.php?action=article-autocomplete",
//            minLength: 2,
////            appendTo: "#repartition-container-dialog",
//            classes: {
//                "ui-autocomplete": "zilu-auto-complete"
//            }
//        });

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
                               accept=".csv, application/vnd.openxmlformats-officedocument.spreadsheetml.sheet, application/vnd.ms-excel">
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

    <div id="new-container-form-dialog" title="Créer un nouveau container" class="zilu-dialog centered">
        <div class="container">
            <form action="" method="post">
                <p class="error hidden">Ce nom de container existe déjà, veuillez choisir un autre nom</p>
                <ul class="flex-outer">
                    <li>
                        <label for="name">Nom</label>
                        <input type="text" name="name">
                    </li>
                    <li>
                        <label for="type">Type</label>
                        <select name="type">
                            <?php
                            $id2Types = ContainerUtil::getContainerTypes();
                            foreach ($id2Types as $id => $type):
                                ?>
                                <option value="<?php echo $id; ?>"><?php echo $type; ?></option>
                                <?php
                            endforeach;
                            ?>
                        </select>
                    </li>
                    <li>
                        <button type="submit">Créer</button>
                    </li>
                </ul>
            </form>
        </div>
    </div>
    <div id="repartition-container-dialog" title="Répartir les articles dans les containers"
         class="zilu-dialog centered">
        <p>
            Distribuer tous les produits de la commande donnée
            dans les containers sélectionnés.
        </p>
        <p class="warning">
            Attention, cette opération supprimera toutes les liaisons existantes pour ces containers
        </p>
        <p class="error error-container hidden"></p>
        <form action="" method="post">
            <ul class="flex-outer">
                <li>
                    <select name="commande_id">
                        <option value="0">Choisissez une commande...</option>
                        <?php
                        $id2labels = CommandeUtil::getId2Labels();
                        foreach ($id2labels as $id => $label):
                            ?>
                            <option value="<?php echo htmlspecialchars($id); ?>"><?php echo $label; ?></option><?php
                        endforeach;
                        ?>
                    </select>
                </li>
                <li>
                    <fieldset>
                        <legend>Options:</legend>
                        <label for="create-container-1">Créer des nouveaux containers automatiquement</label>
                        <input class="ui-checkbox" type="checkbox" checked name="create-container"
                               id="create-container-1">
                    </fieldset>
                </li>
                <li>
                    <button id="repartition-submit-btn" type="submit">Créer</button>
                </li>
            </ul>
        </form>
    </div>
</div>
