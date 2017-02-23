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
use TypeContainer\TypeContainer;

$ll = "zilu";


$_SESSION['containerQueryString'] = $_SERVER['QUERY_STRING'];


$containerIds = [];
$inactiveContainerIds = [];
$summaryIsPercent = 0; // 0|1: false|true

if (array_key_exists('container-container-ids', $_SESSION)) {
    $cids = $_SESSION['container-container-ids'];
    $containerIds = array_merge($containerIds, $cids);
}
if (array_key_exists('container-inactive-container-ids', $_SESSION)) {
    $inactiveContainerIds = $_SESSION['container-inactive-container-ids'];
    $inactiveContainerIds = array_unique($inactiveContainerIds);
}
if (array_key_exists('summaryIsPercent', $_SESSION)) {
    $summaryIsPercent = (int)$_SESSION['summaryIsPercent'];
}

$containerIds = array_unique($containerIds);


AssetsList::css("style/zilu.css");
AssetsList::css("/style/admintable.css");


$containerId2Refs = ContainerUtil::getId2Labels();
$commandeId2Refs = CommandeUtil::getId2Labels();

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

            <div>
                <select id="commande-select" name="commande">
                    <option value="0">Choisissez une commande</option>
                    <?php foreach ($commandeId2Refs as $id => $label):
                        $sel = ($idCommande === (int)$id) ? 'selected="selected"' : '';
                        ?>
                        <option <?php echo $sel; ?>
                                value="<?php echo $id; ?>"><?php echo htmlspecialchars($label); ?></option>
                    <?php endforeach; ?>
                </select>
                <label>
                    <?php
                    $checked = (1 === $summaryIsPercent) ? 'checked' : '';
                    ?>
                    <input id="container-summary-switch-percent" type="checkbox" <?php echo $checked; ?>> pourcentage
                </label>
            </div>
            <br>
            <br>


            <?php
            $cptContainer = 0;
            $nbContainers = count($containerIds);
            if ($nbContainers > 0):


                $unitVol = (1 === $summaryIsPercent) ? '(%)' : '(m3)';
                $unitWeight = (1 === $summaryIsPercent) ? '(%)' : '(kg)';


                $checked = (0 === count($inactiveContainerIds)) ? 'checked' : '';
                ?>
                <table id="container-summary-table">
                    <tr>
                        <th><input
                                    id="summary-checkbox-toggle"
                                    data-id="0"
                                    type="checkbox" <?php echo $checked; ?>></th>
                        <th>Container</th>
                        <th>Volume <?php echo $unitVol; ?></th>
                        <th>Poids <?php echo $unitWeight; ?></th>
                        <th></th>
                    </tr>


                    <?php


                    foreach ($containerIds as $containerId):
                        $sFirst = (0 === $cptContainer) ? "first" : "";

                        if (0 !== $containerId):


                            $query = "select
c.nom,
h.quantite,
h.container_id,
fha.volume,
fha.poids
from commande_has_article h
inner join article a on a.id=h.article_id
inner join fournisseur_has_article fha on fha.fournisseur_id=h.fournisseur_id and fha.article_id=h.article_id
inner join container c on c.id=h.container_id
where h.container_id=" . $containerId;

                            if (false !== ($res = QuickPdo::fetchAll($query))):
                                $poids = 0;
                                $volume = 0;
                                if (count($res) > 0):

                                    $idsUsed = [];

                                    foreach ($res as $item) {
                                        $poids += $item['poids'] * $item['quantite'];
                                        $volume += $item['volume'] * $item['quantite'];
                                    }


                                    $details = TypeContainer::getDetailsByContainerId($containerId);
                                    $poidsMax = $details['poids_max'];
                                    $volumeMax = $details['volume_max'];

                                    $poidsPercent = $poids / $poidsMax * 100;
                                    $volumePercent = $volume / $volumeMax * 100;

                                    if (1 === $summaryIsPercent) {
                                        $poids = $poidsPercent;
                                        $volume = $volumePercent;
                                    }

                                    $sOverload = '';
                                    if ($poidsPercent > 100 || $volumePercent > 100) {
                                        $sOverload = 'zilu-overload';
                                    }


                                    $checked = "";
                                    if (false === in_array($containerId, $inactiveContainerIds)) {
                                        $checked = "checked";
                                    }

                                    ?>
                                    <tr class="<?php echo $sOverload; ?>">
                                        <td><input data-id="<?php echo $containerId; ?>"
                                                   type="checkbox" <?php echo $checked; ?>></td>
                                        <td><?php echo $item['nom']; ?></td>
                                        <td><?php echo round($volume, 2); ?></td>
                                        <td><?php echo round($poids, 2); ?></td>
                                        <td><a href="#" data-id="<?php echo $containerId; ?>"
                                               class="container-delete">X</a></td>
                                    </tr>
                                    <?php
                                else:
                                    $containerName = $containerId2Refs[$containerId];

                                    $checked = "";
                                    if (false === in_array($containerId, $inactiveContainerIds)) {
                                        $checked = "checked";
                                    }

                                    ?>
                                    <tr>
                                        <td><input data-id="<?php echo $containerId; ?>"
                                                   type="checkbox" <?php echo $checked; ?>></td>
                                        <td><?php echo $containerName; ?></td>
                                        <td>0</td>
                                        <td>0</td>
                                        <td><a href="#" data-id="<?php echo $containerId; ?>"
                                               class="container-delete">X</a></td>
                                    </tr>
                                    <?php
                                endif;
                            endif;
                        endif;
                    endforeach; ?>

                    <tr>
                        <td></td>
                        <td>
                            <select id="container-add-selector">
                                <option>Ajouter un container</option>
                                <?php foreach ($containerId2Refs as $id => $ref): ?>
                                    <option value="<?php echo $id; ?>"><?php echo $ref; ?></option>
                                <?php endforeach; ?>
                            </select>
                        </td>
                        <td></td>
                        <td></td>
                        <td></td>
                    </tr>
                </table>
            <?php endif; ?>
        </div>

        <div id="zilu-table" class="zilu-table">
            <?php


            $containerIds = array_diff($containerIds, $inactiveContainerIds);
            $realContainerIds = array_filter($containerIds, function ($v) {
                return (0 !== $v);
            });


            if (count($realContainerIds) > 0) {

                $fields = '
c.id,
co.id as container_id,
co.nom as container,
c.reference,
h.quantite,
fha.volume,
fha.poids,
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

                $list->setTransformer("quantite", function ($value, $item, $ricValue) {
                    $text = $value;
                    if (null === $value) {
                        $text = "(not set)";
                    }
                    return '<a class="update-link" data-column="quantite" data-default="' . htmlspecialchars($value) . '" data-ric="' . htmlspecialchars($ricValue) . '" href="#">' . $text . '</a>';
                });

                $list->setTransformer("poids", function ($value, $item, $ricValue) {
                    $text = $value;
                    return '<a class="update-link" data-column="poids" data-default="' . htmlspecialchars($value) . '" data-fid="' . $item['fournisseur_id'] . '" data-aid="' . $item['aid'] . '" href="#">' . $text . '</a>';
                });

                $list->setTransformer("volume", function ($value, $item, $ricValue) {
                    $text = $value;
                    return '<a class="update-link" data-column="volume" data-default="' . htmlspecialchars($value) . '" data-fid="' . $item['fournisseur_id'] . '" data-aid="' . $item['aid'] . '" href="#">' . $text . '</a>';
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


    function updateRepartitionPanel(commandeId) {
        $.get('/services/zilu.php?action=order-auto-repartition&commande_id=' + commandeId, function (data) {
            $('#automatic-repartition-table').empty().html(data);
            var jSubmitContainer = $("#repartition-container-dialog").find(".repartition-btn-container");
            if ('' !== data) {
                jSubmitContainer.removeClass("hidden");
            }
            else {
                jSubmitContainer.addClass("hidden");
            }
        });
    }


    function rebindSummaryTable() {

        $('#container-summary-table').find("input[type='checkbox']").not('#summary-checkbox-toggle')
            .off('change')
            .on('change', function () {
                var containerId = $(this).attr('data-id');


                var word = "inactive";
                if (true === $(this).prop('checked')) {
                    word = "active";
                }

                $.getJSON("/services/zilu.php?action=container-container-" + word + "&id=" + containerId, function (data) {
                    if ('ok' === data) {
                        location.reload();
                    }
                });
                rebindSummaryTable();
            });
    }


    $(document).ready(function () {

        rebindSummaryTable();

        $('#summary-checkbox-toggle').on("change", function () {
            var checked = ($(this).prop('checked')) ? 1 : 0;
            $.getJSON('/services/zilu.php?action=container-summary-toggle&active=' + checked, function (data) {
                if ('ok' === data) {
                    location.reload();
                }
            });
        });

        $('#container-summary-switch-percent').on('change', function () {
            var checked = ($(this).prop('checked')) ? 1 : 0;
            $.getJSON('/services/zilu.php?action=container-summary-percent&percent=' + checked, function (data) {
                if ('ok' === data) {
                    location.reload();
                }
            });
        });


        $('#container-topmenu-link').attr('href', "/container" + window.location.search);

//        $("input.ui-checkbox").checkboxradio();


        $(document).tooltip();

        $("#commande-select").on('change', function () {
            $.getJSON('/services/zilu.php?action=container-commande-select&cid=' + $(this).val(), function (data) {
                if ('ok' === data) {
                    location.reload();
                }
            });
        });
        $("#container-add-selector").on('change', function () {
            $.getJSON('/services/zilu.php?action=container-container-select&id=' + $(this).val(), function (data) {
                if ('ok' === data) {
                    location.reload();
                }
            });
        });


//        $(".container-spy-selector").on('change', function () {
//            if ('-1' === $(this).val()) {
//                $("#new-container-form-dialog").dialog({
//                    position: {
//                        my: "left top",
//                        at: "left top",
//                        of: $(this)
//                    },
//                    width: 600,
//                    open: function (event, ui) {
//                        var jBtn = $("#new-container-form-dialog").find('button');
//                        var jForm = $("#new-container-form-dialog").find('form');
//                        var jInput = $("#new-container-form-dialog").find('input');
//                        jBtn.on("click", function (e) {
//                            e.preventDefault();
//                            var s = jForm.serialize();
//                            $.getJSON('/services/zilu.php?action=container-create&' + s, function (data) {
//                                if ('duplicate' === data) {
//                                    var jError = $("#new-container-form-dialog").find('p.error');
//                                    jError.removeClass("hidden");
//                                    jInput.on("focus", function () {
//                                        jError.addClass("hidden");
//                                    });
//                                }
//                                else {
//                                    $(".container-spy-selector:last").append('<option value="' + data + '" selected="selected">any</option>');
//                                    var jMainForm = $('#container-form');
//                                    jMainForm.submit();
//                                }
//                            });
//                        });
//                    }
//                });
//            }
//            else {
//                $(this).parent().parent().submit();
//            }
//        });


        $("#change-all-fournisseurs-selector").selectmenu();
        $("#send-mail-selector").selectmenu();


        $('body').on('click', function (e) {
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

                var jCommandeSelector = $("#repartition-container-dialog").find("select[name='commande_id']");

                if ('undefined' !== typeof $("#repartition-container-dialog").dialog("instance")) {
                    $("#repartition-container-dialog").dialog("close");
                    $('#automatic-repartition-table').empty();
                    jCommandeSelector.val(0);
                }

                $("#repartition-container-dialog").dialog({
                    position: {
                        my: "left top",
                        at: "left top",
                        of: jTarget
                    },
                    minWidth: 700,
                    open: function (event, ui) {
                        var jErrorCommandEmpty = $("#repartition-container-dialog").find('.error-commande-empty');

                        var jSubmit = $("#repartition-container-dialog").find("#repartition-submit-btn");

                        var jCancel = $("#repartition-container-dialog").find("#repartition-cancel-btn");
                        jCancel
                            .off('click')
                            .on('click', function (e) {
                                e.preventDefault();
                                $("#repartition-container-dialog").dialog("close");
                                return false;
                            });

                        var lastCommandeId = 0;

                        jCommandeSelector.off('change');
                        jCommandeSelector.on('change', function () {
                            lastCommandeId = $(this).val();
                            jErrorCommandEmpty.addClass('hidden');
                            updateRepartitionPanel($(this).val());

                        });
                        jSubmit
                            .off('click')
                            .on('click', function (e) {
                                e.preventDefault();

                                jErrorCommandEmpty.addClass('hidden');

                                var theJson = $('#decorated-used-containers-json').text();

                                $.post('/services/zilu.php?action=container-distribute&commande_id=' + lastCommandeId, {
                                    'json': theJson
                                }, function (data) {
                                    console.log("jjj");
                                    console.log(data);
                                    if ('ok' === data) {
                                        console.log("ok");
                                        $("#repartition-container-dialog").dialog("close");
                                        location.reload();
                                    }
                                    else {
                                        jErrorCommandEmpty.removeClass("hidden");
                                        jErrorCommandEmpty.find('.error').html(data);
                                    }
                                }, 'json');
                            });
                    }
                });
            }
            else if (jTarget.hasClass("container-item-link")) {
                e.preventDefault();
                var commandeId = jTarget.attr('data-cid');
                var containerId = jTarget.attr('data-coid');
                var jsonItems = jTarget.parent().find('.json-items').text();

                $.post('/services/zilu.php?action=display-container-items&cid=' + commandeId + '&coid=' + containerId, {
                    'jsonItems': jsonItems
                }, function (data) {

                    $("#container-items-dialog").dialog({
                        maxHeight: 500,
                        width: 600,
                        position: {
                            my: "center",
                            at: "center",
                            of: jTarget
                        },
                        open: function (event, ui) {
                            var jContainer = $("#container-items-dialog").find(".articles-container");
                            jContainer.html(data);
                        }
                    });
                });
            }
            else if (jTarget.hasClass("update-link")) {
                e.preventDefault();

                var column = jTarget.attr('data-column');
                var defaultValue = jTarget.attr('data-default');
                var ricValue = jTarget.attr('data-ric');
                var fournisseurId = jTarget.attr('data-fid');
                var articleId = jTarget.attr('data-aid');
                var type = jTarget.attr('data-type');


                $("#update-column-dialog").dialog({
                    position: {
                        my: "center",
                        at: "center",
                        of: jTarget
                    },
                    width: 600,
                    open: function (event, ui) {

                        var jInput = $("#update-column-input");


                        if ('date' === type) {
                            jInput.datepicker({
                                dateFormat: "yy-mm-dd"
                            });
                            jInput.datepicker("setDate", defaultValue);
                            jInput.blur();

                        }
                        else {
                            jInput.attr('value', defaultValue);
                        }

                        $("#update-column-submit-btn").on('click', function (e) {
                            e.preventDefault();
                            var val = jInput.val();
                            var url = '/services/zilu.php?action=update-commande-field&col=' + column + '&value=' + val;
                            if ('undefined' !== typeof ricValue) {
                                url += '&ric=' + ricValue;
                            }
                            if ('undefined' !== typeof fournisseurId && 'undefined' !== typeof articleId) {
                                url += '&fid=' + fournisseurId + '&aid=' + articleId;
                            }
                            $.getJSON(url, function (data) {
                                if ('ok' === data) {
                                    $("#update-column-dialog").dialog('close');
                                    window.location.reload();
                                }
                            });

                        });


                    }
                });
            }
            else if (jTarget.hasClass("container-delete")) {
                e.preventDefault();
                var containerId = jTarget.attr('data-id');

                $.getJSON("/services/zilu.php?action=container-container-delete&id=" + containerId, function (data) {
                    if ('ok' === data) {
                        location.reload();
                    }
                });
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
            Attention, cette opération créé de nouveaux containers
        </p>
        <form action="" method="post">
            <ul class="flex-outer">
                <li class="error-commande-empty hidden">
                    <div class="error"></div>
                </li>
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
                <li id="automatic-repartition-table">

                </li>
                <li>
                    <div class="repartition-btn-container hidden">
                        <button id="repartition-cancel-btn" type="submit">Annuler</button>
                        <button id="repartition-submit-btn" type="submit">Accepter</button>
                    </div>
                </li>
            </ul>
        </form>
    </div>
    <div id="container-items-dialog" title="Articles du container">
        <div class="articles-container"></div>
    </div>
    <div id="update-column-dialog" title="Mettre à jour un champ">
        <form style="text-align: center">
            <input type="text" value="" id="update-column-input">
            <br>
            <br>
            <button type="submit" id="update-column-submit-btn">Modifier</button>
        </form>
    </div>
</div>
