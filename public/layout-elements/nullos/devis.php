<?php


use AdminTable\Listable\QuickPdoListable;
use AdminTable\View\AdminTableRenderer;
use AssetsList\AssetsList;
use Commande\AdminTable\CommandeAdminTable;
use Commande\CommandeUtil;
use CommandeLigneStatut\CommandeLigneStatutUtil;
use Csv\CsvUtil;
use Devis\DevisUtil;
use Fournisseur\FournisseurUtil;
use Http\HttpResponseUtil;
use Icons\Icons;
use Layout\Goofy;

$ll = "zilu";


$_SESSION['devisQueryString'] = $_SERVER['QUERY_STRING'];

$idDevis = 0;
if (array_key_exists('devis', $_GET)) {
    $idDevis = (int)$_GET['devis'];
}



AssetsList::css("style/zilu.css");
AssetsList::css("/style/admintable.css");
AssetsList::js("/libs/lightbox2/src/js/lightbox.js");
AssetsList::css("/libs/lightbox2/src/css/lightbox.css");


$devisId2Refs = DevisUtil::getId2Labels()

?>


<div class="zilu" id="zilu">
    <div class="zilu-topbar">
    </div>
    <div class="zilu-split">
        <div class="zilu-summary">

            <form action="" method="get">

                <select id="devis-select" name="devis">
                    <option value="0">Choisissez un devis</option>
                    <?php foreach ($devisId2Refs as $id => $label):
                        $sel = ($idDevis === (int)$id) ? 'selected="selected"' : '';
                        ?>
                        <option <?php echo $sel; ?>
                                value="<?php echo $id; ?>"><?php echo htmlspecialchars($label); ?></option>
                    <?php endforeach; ?>
                </select>
            </form>

            <?php



            if (0 !== $idDevis) {

                list($prixTotal, $poidsTotal, $volumeTotal) = CommandeUtil::getCommandeSumInfoByDevis($idDevis);

                ?>
                <table class="zilu-info">
                    <tr>
                        <td>Prix total estimé</td>
                        <td><?php echo $prixTotal; ?>€</td>
                    </tr>
                    <tr>
                        <td>Poids total estimé</td>
                        <td><?php echo $poidsTotal; ?> kg</td>
                    </tr>
                    <tr>
                        <td>Volume total estimé</td>
                        <td><?php echo $volumeTotal; ?> m3</td>
                    </tr>
                </table>
                <?php
            }

            ?>
        </div>
        <div id="zilu-table" class="zilu-table">
            <?php

            if (0 !== $idDevis) {


                $fields = '
dh.devis_id,                
d.reference as devis_reference,                
c.id,
h.commande_ligne_statut_id as statut,
co.id as container_id,
co.nom as container,
c.reference as commande,
a.reference_lf,
a.reference_hldp,
f.id as fournisseur_id,
f.nom as fournisseur,
h.quantite,
fha.poids,
fha.volume,
fha.prix,
h.prix_override,
h.date_estimee,
a.id as aid,
a.descr_fr,
a.descr_en,
(select count(*) from devis_has_commande_has_article where commande_has_article_commande_id=h.commande_id and commande_has_article_article_id=h.article_id) as devis,
h.sav_id as sav
';


                $query = "select
%s
from zilu.commande c
inner join commande_has_article h on h.commande_id=c.id
inner join fournisseur f on f.id=h.fournisseur_id
inner join fournisseur_has_article fha on fha.fournisseur_id=h.fournisseur_id and fha.article_id=h.article_id
inner join article a on a.id=h.article_id
inner join devis_has_commande_has_article dh on dh.commande_has_article_commande_id=h.commande_id and dh.commande_has_article_article_id=h.article_id
inner join devis d on d.id=dh.devis_id
left join container co on co.id=h.container_id
where dh.devis_id=" . $idDevis;


                $list = CommandeAdminTable::create()
                    ->setRic(['id', 'aid'])
//                    ->setExtraColumn("sav", "",0)
                    ->setListable(QuickPdoListable::create()->setFields($fields)->setQuery($query))
                    ->setRenderer(AdminTableRenderer::create()
                        ->setExtraHiddenFields([
                            "devis" => $idDevis,
                        ])
                        ->setOnItemIteratedCallback(function (array $item, &$trClass) {
                            if ($item['sav'] !== null) {
                                $trClass = 'red';
                            }
                        })
                    );

                $list->setTransformer("statut", function ($value, $item, $ricValue) {
                    return '<a class="update-statut-link" data-value="' . $value . '" data-cid="' . $item['id'] . '" data-aid="' . $item['aid'] . '" href="#" style="white-space: nowrap">' . CommandeLigneStatutUtil::toString($value) . '</a>';
                });

                $list->setTransformer("container", function ($value, $item, $ricValue) {
                    $text = $value;
                    if (null === $value) {
                        $text = "(choisissez un container)";
                    }
                    return '<a class="container-selector" data-ric="' . htmlspecialchars($ricValue) . '" data-container-id="' . htmlspecialchars($item['container_id']) . '" href="#">' . $text . '</a>';
                });


                $list->setTransformer("poids", function ($value, $item, $ricValue) {
                    $text = $value;
                    return '<a class="update-link" data-column="poids" data-default="' . htmlspecialchars($value) . '" data-fid="' . $item['fournisseur_id'] . '" data-aid="' . $item['aid'] . '" href="#">' . $text . '</a>';
                });


                $list->setTransformer("devis", function ($value, $item, $ricValue) {
                    $text = $value;
                    return '<a class="show-devis-list" data-ric="' . $ricValue . '" href="#">' . $text . '</a>';
                });

                $list->setTransformer("volume", function ($value, $item, $ricValue) {
                    $text = $value;
                    return '<a class="update-link" data-column="volume" data-default="' . htmlspecialchars($value) . '" data-fid="' . $item['fournisseur_id'] . '" data-aid="' . $item['aid'] . '" href="#">' . $text . '</a>';
                });

                $list->setTransformer("prix_override", function ($value, $item, $ricValue) {
                    $text = $value;
                    if (null === $value) {
                        $text = "(not set)";
                    }
                    return '<a class="update-link" data-column="prix_override" data-default="' . htmlspecialchars($value) . '" data-ric="' . htmlspecialchars($ricValue) . '" href="#">' . $text . '</a>';
                });

                $list->setTransformer("quantite", function ($value, $item, $ricValue) {
                    $text = $value;
                    if (null === $value) {
                        $text = "(not set)";
                    }
                    return '<a class="update-link" data-column="quantite" data-default="' . htmlspecialchars($value) . '" data-ric="' . htmlspecialchars($ricValue) . '" href="#">' . $text . '</a>';
                });

                $list->setTransformer("date_estimee", function ($value, $item, $ricValue) {
                    $text = $value;
                    if (null === $value) {
                        $text = "(not set)";
                    }
                    return '<a class="update-link" data-type="date"  data-column="date_estimee" data-default="' . htmlspecialchars($value) . '" data-ric="' . htmlspecialchars($ricValue) . '" href="#">' . $text . '</a>';
                });


                $list->setTransformer("fournisseur", function ($value, $item, $ricValue) {
                    $text = $value;
                    if (null === $value) {
                        $text = "(choisissez un fournisseur)";
                    }
                    return '<a class="fournisseur-selector" data-article-id="' . $item['aid'] . '" data-ric="' . htmlspecialchars($ricValue) . '" data-fournisseur-id="' . htmlspecialchars($item['fournisseur_id']) . '" href="#">' . $text . '</a>';
                });


                $list->setTransformer("sav", function ($value, $item, $ricValue) {
                    if (null === $item['sav']) {
                        return '
                    <a class="sav-transform-link nowrap" data-ric="' . htmlspecialchars($ricValue) . '" href="#">Créer un SAV pour cette ligne</a>
                    ';
                    } else {
                        return '
                    <a class="sav-details-link nowrap" data-id="' . $item['sav'] . '" data-ric="' . htmlspecialchars($ricValue) . '" href="#">Voir le détail</a>
                    ';
                    }
                });


                $textMaxLength = 10;
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
                    'devis_id',
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


        var jGlobalTarget = null;

        $('#devis-topmenu-link').attr('href', "/devis" + window.location.search);


        $(document).tooltip();
        var devisId = <?php echo $idDevis; ?>;



        var devisSelect = document.getElementById("devis-select");


        devisSelect.addEventListener('change', function () {
            var value = devisSelect.value;
            if ('0' !== value) {
                devisId = value;
                devisSelect.parentNode.submit();
            }
        });



        $('body').on('click', function (e) {
            var jTarget = $(e.target);
            if (jTarget.hasClass("postlink")) {
                e.preventDefault();
            }
            else if (jTarget.hasClass("container-selector")) {
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
            else if (jTarget.hasClass("sav-details-link")) {
                e.preventDefault();
                var savId = jTarget.attr("data-id");
                $("#sav-details-dialog").dialog({
                    position: {
                        my: "right top",
                        at: "left center",
                        of: jTarget
                    },
                    width: 600,
                    open: function (event, ui) {
                        $.get('/services/zilu.php?action=sav-details&savId=' + savId, function (data) {
                            $("#sav-details-container").html(data);
                        });
                    }
                });
            }
            else if (jTarget.hasClass("sav-transform-link")) {
                e.preventDefault();
                var _ricVal = jTarget.attr('data-ric');
                $("#sav-transform-dialog").dialog({
                    position: {
                        my: "right center",
                        at: "left center",
                        of: jTarget
                    },
                    width: 600,
                    open: function (event, ui) {
                        $.get('/services/zilu.php?action=sav-transform-form&ric=' + _ricVal, function (data) {
                            $("#sav-transform-container").html(data);
                        });
                    }
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
            else if (jTarget.hasClass("providers-checkall")) {
                e.preventDefault();
                $('#mail-providers-checkboxes').find('input').prop('checked', true);
                return false;
            }
            else if (jTarget.hasClass("providers-uncheckall")) {
                e.preventDefault();
                $('#mail-providers-checkboxes').find('input').prop('checked', false);
                return false;
            }
            else if (jTarget.hasClass("zilu-close")) {
                e.preventDefault();
                $("#order-pro-conf-mail-dialog").dialog("close");
                location.reload();
                return false;
            }
            else if (jTarget.hasClass("update-statut-link")) {
                e.preventDefault();
                var articleId = jTarget.attr('data-aid');
                var commandeId = jTarget.attr('data-cid');
                var statutValue = jTarget.attr('data-value');


                if ('undefined' !== typeof $("#container-status-dialog").dialog('instance')) {
                    $("#container-status-dialog").dialog("close");
                }

                $("#container-status-dialog").dialog({
                    position: {
                        my: "center",
                        at: "center",
                        of: jTarget
                    },
                    open: function (event, ui) {
                        var jSelect = $("#container-status-dialog").find('select');
                        jSelect.val(statutValue);


                        jSelect.on('change', function () {
                            var value = jSelect.val();
                            $.getJSON('/services/zilu.php?action=commande-update-statut&statut=' + value + "&cid=" + commandeId + "&aid=" + articleId, function (data) {
                                if ('ok' === data) {
                                    location.reload();
                                }
                            });
                        });
                    }
                });
            }
            else if (jTarget.hasClass("show-devis-list")) {
                e.preventDefault();
                var ricValue = jTarget.attr('data-ric');
                var fid = jTarget.attr('data-fid');
                jGlobalTarget = jTarget;


                if ('undefined' !== typeof $("#commande-dialog-devislist").dialog('instance')) {
                    $("#commande-dialog-devislist").dialog("close");
                }

                $("#commande-dialog-devislist").dialog({
                    position: {
                        my: "center",
                        at: "center",
                        of: jTarget
                    },
                    width: 600,
                    open: function (event, ui) {
                        var jMain = $("#commande-dialog-devislist").find('.mainbody');
                        $.get('/services/zilu.php?action=commande-devislist&ric=' + ricValue, function (data) {
                            jMain.html(data);
                        });
                    }
                });
            }
            else if (jTarget.hasClass("devis-add-bindure")) {
                e.preventDefault();
                var jTable = jTarget.closest("table");
                var did = jTable.find('.devis-add-bindure-selector').val();
                var articleId = jTable.attr('data-aid');
                var commandeId = jTable.attr('data-cid');
                $.getJSON('/services/zilu.php?action=devis-add-bindure&did=' + did + "&cid=" + commandeId + "&aid=" + articleId, function (data) {
                    if ('html' in data) {
                        var jMain = $("#commande-dialog-devislist").find('.mainbody');
                        jMain.html(data['html']);
                    }
                    if ('nbDevis' in data) {
                        jGlobalTarget.text(data['nbDevis']);
                    }
                });
            }
            else if (jTarget.hasClass("devis-remove-bindure")) {
                e.preventDefault();
                var jTable = jTarget.closest("table");
                var did = jTarget.attr('data-did');
                var articleId = jTable.attr('data-aid');
                var commandeId = jTable.attr('data-cid');
                $.getJSON('/services/zilu.php?action=devis-remove-bindure&did=' + did + "&cid=" + commandeId + "&aid=" + articleId, function (data) {
                    if ('html' in data) {
                        var jMain = $("#commande-dialog-devislist").find('.mainbody');
                        jMain.html(data['html']);
                    }
                    if ('nbDevis' in data) {
                        jGlobalTarget.text(data['nbDevis']);
                    }
                });
            }
            else if (jTarget.hasClass("export-csv-button")) {
                e.preventDefault();

                var commandeId = $('#exportcsv-commande').val();
                var type = $('#exportcsv-type').val();

                $.getJSON('/services/zilu.php?action=commande-exportcsv&type=' + type + "&cid=" + commandeId, function (data) {
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
    <div id="sav-details-dialog" title="Détails du SAV">
        <div id="sav-details-container"></div>
    </div>
    <div id="sav-transform-dialog" title="Passer cette ligne en SAV">
        <div id="sav-transform-container"></div>
    </div>
    <div id="update-column-dialog" title="Mettre à jour un champ">
        <form style="text-align: center">
            <input type="text" value="" id="update-column-input">
            <br>
            <br>
            <button type="submit" id="update-column-submit-btn">Modifier</button>
        </form>
    </div>
    <div id="container-status-dialog" title="Mise à jour du statut">
        <select>
            <?php
            $id2labels = CommandeLigneStatutUtil::getIds2Labels();

            foreach ($id2labels as $id => $label):
                ?>
                <option value="<?php echo $id; ?>"><?php echo $label; ?></option>
            <?php endforeach; ?>
        </select>
    </div>

    <div id="commande-dialog-devislist" title="Liste des devis associés à cette ligne">
        <div class="mainbody"></div>
    </div>
    <div id="csv-export-dialog" title="Exporter une commande au format xlsx" class="zilu-dialog centered">
        <div class="container">
            <ul class="flex-outer">
                <li>
                    <label for="exportcsv-commande">Commande</label>
                    <select id="exportcsv-commande">
                        <?php foreach ($devisId2Refs as $id => $ref): ?>
                            <option value="<?php echo $id; ?>"><?php echo $ref; ?></option>
                        <?php endforeach; ?>
                    </select>
                </li>
                <li>
                    <label for="exportcsv-type">Type</label>
                    <select id="exportcsv-type">
                        <option value="default">Comme import</option>
                        <option value="container">Par container</option>
                    </select>
                </li>
                <li>
                    <button type="submit" class="export-csv-button">Exporter</button>
                </li>
            </ul>
        </div>
    </div>
</div>
