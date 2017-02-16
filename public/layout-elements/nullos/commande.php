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


$_SESSION['commandeQueryString'] = $_SERVER['QUERY_STRING'];

$idCommande = 0;
if (array_key_exists('commande', $_GET)) {
    $idCommande = (int)$_GET['commande'];
}

if (array_key_exists('csvfile', $_FILES)) {
    $uploaddir = APP_ROOT_DIR . "/fixtures/csv-commande/";
    $uploadfile = $uploaddir . basename($_FILES['csvfile']['name']);
    if (move_uploaded_file($_FILES['csvfile']['tmp_name'], $uploadfile)) {
        $data = CsvUtil::readFile($uploadfile);
        $nbSuccess = CommandeUtil::importCommandeByCsvData($data);
        $nbTotal = count($data);
        Goofy::alertSuccess("Import réussi, $nbSuccess/$nbTotal lignes ont été correctement traitées");
        // todo: set idCommande here
    } else {
        Goofy::alertError("Un problème est survenu, veuillez contacter le webmaster");
    }
}


AssetsList::css("style/zilu.css");
AssetsList::css("/style/admintable.css");
AssetsList::js("/libs/lightbox2/src/js/lightbox.js");
AssetsList::css("/libs/lightbox2/src/css/lightbox.css");


$commandeId2Refs = CommandeUtil::getId2Labels();

?>


<div class="zilu" id="zilu">
    <div class="zilu-topbar">

        <button class="button-with-icon csv-import-button">
            <span>
                <span>Importer un fichier csv</span>
                <?php Icons::printIcon("add", 'white'); ?>
            </span>
        </button>
        <div class="commande-actions-group">
            <div class="commande-actions-vertical" id="commande-actions-vertical">
                <form>
                    <select id="change-all-fournisseurs-selector">
                        <option>Pour tous les articles de cette commande...</option>
                        <option value="moinscher">Appliquer le fournisseur le moins cher pour chaque produit</option>
                        <option value="leaderfit">Appliquer le fournisseur leaderfit pour chaque produit</option>
                    </select>
                </form>
                <form>
                    <select id="send-mail-selector">
                        <option>Envoyer un email...</option>
                        <option value="direction-test">récapitulatif de commande à Zilu</option>
                        <option value="direction">récapitulatif de commande à Didier</option>
                        <option value="fournisseurs-test">de demande de devis à Zilu</option>
                        <option value="fournisseurs">de demande de devis aux fournisseurs</option>
                    </select>
                </form>
            </div>
        </div>

    </div>
    <div class="zilu-split">
        <div class="zilu-summary">

            <form action="" method="get">

                <select id="commande-select" name="commande">
                    <option value="0">Choisissez une commande</option>
                    <?php foreach ($commandeId2Refs as $id => $label):
                        $sel = ($idCommande === (int)$id) ? 'selected="selected"' : '';
                        ?>
                        <option <?php echo $sel; ?>
                                value="<?php echo $id; ?>"><?php echo htmlspecialchars($label); ?></option>
                    <?php endforeach; ?>
                </select>
            </form>

            <?php


            if (0 !== $idCommande) {

                $query = "select
a.poids,
h.prix_override,
h.quantite,
fha.prix
from commande_has_article h
inner join article a on a.id=h.article_id
inner join fournisseur_has_article fha on fha.fournisseur_id=h.fournisseur_id and fha.article_id=h.article_id
where h.commande_id=" . $idCommande;
                if (false !== ($res = QuickPdo::fetchAll($query))):

                    $prixTotal = 0;
                    $poidsTotal = 0;
                    foreach ($res as $item) {
                        $prix = $item['prix'];
                        $qte = $item['quantite'];
                        if ('' !== trim($item['prix_override'])) {
                            $prix = $item['prix_override'];
                        }
                        $prixTotal += $qte * $prix;
                        $poidsTotal += $item['poids'];
                    }

                    ?>

                    <table class="zilu-info">
                        <tr>
                            <td>Prix total</td>
                            <td><?php echo $prixTotal; ?>€</td>
                        </tr>
                        <tr>
                            <td>Poids total</td>
                            <td><?php echo $poidsTotal; ?> kg</td>
                        </tr>
                    </table>
                    <?php
                endif;
            }

            ?>
        </div>
        <div id="zilu-table" class="zilu-table">
            <?php

            if (0 !== $idCommande) {


                $fields = '
c.id,
co.id as container_id,
co.nom as container,
c.reference,
a.poids,
f.id as fournisseur_id,
f.nom as fournisseur,
fha.prix,
h.prix_override,
h.quantite,
h.date_estimee,
a.id as aid,
a.reference_lf,
a.reference_hldp,
a.descr_fr,
a.descr_en,
h.sav_id as sav
';


                $query = "select
%s
from zilu.commande c
inner join commande_has_article h on h.commande_id=c.id
inner join fournisseur f on f.id=h.fournisseur_id
inner join fournisseur_has_article fha on fha.fournisseur_id=h.fournisseur_id and fha.article_id=h.article_id
inner join article a on a.id=h.article_id
left join container co on co.id=h.container_id
where c.id=" . $idCommande;


                $list = CommandeAdminTable::create()
                    ->setRic(['id', 'aid'])
//                    ->setExtraColumn("sav", "",0)
                    ->setListable(QuickPdoListable::create()->setFields($fields)->setQuery($query))
                    ->setRenderer(AdminTableRenderer::create()
                        ->setExtraHiddenFields([
                            "commande" => $idCommande,
                        ])
                        ->setOnItemIteratedCallback(function (array $item, &$trClass) {
                            if ($item['sav'] !== null) {
                                $trClass = 'red';
                            }
                        })
                    );

                $list->setTransformer("container", function ($value, $item, $ricValue) {
                    $text = $value;
                    if (null === $value) {
                        $text = "(choisissez un container)";
                    }
                    return '<a class="container-selector" data-ric="' . htmlspecialchars($ricValue) . '" data-container-id="' . htmlspecialchars($item['container_id']) . '" href="#">' . $text . '</a>';
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


        $('#commande-topmenu-link').attr('href', "/commande" + window.location.search);


        $(document).tooltip();
        var commandeId = <?php echo $idCommande; ?>;


        // http://stackoverflow.com/questions/10899384/uploading-both-data-and-files-in-one-form-using-ajax
        function ajax_form($form, on_complete) {
            var iframe;

            if (!$form.attr('target')) {
                //create a unique iframe for the form
                iframe = $("<iframe></iframe>").attr('name', 'ajax_form_' + Math.floor(Math.random() * 999999)).hide().appendTo($('body'));
                $form.attr('target', iframe.attr('name'));
            }

            if (on_complete) {
                iframe = iframe || $('iframe[name=" ' + $form.attr('target') + ' "]');
                iframe.load(function () {
                    //get the server response
                    var response = iframe.contents().find('body').text();
                    on_complete(response);
                });
            }
        }


        var csvInput = document.getElementById("import-csv-input");
        var jCsvForm = $("form#csv-import-form");

        ajax_form(jCsvForm, function(data){
            console.log(data);
        });



        var commandeSelect = document.getElementById("commande-select");
        csvInput.addEventListener('change', function () {
            csvInput.parentNode.submit();
        });

        commandeSelect.addEventListener('change', function () {
            var value = commandeSelect.value;
            if ('0' !== value) {
                commandeId = value;
                commandeSelect.parentNode.submit();
            }
        });


        $("#change-all-fournisseurs-selector").selectmenu({
            select: function (event, data) {
                if ('moinscher' === data.item.value) {
                    $("#apply-fournisseur-cheapest-confirm-dialog").dialog({
                        position: {
                            my: "center",
                            at: "center",
                            of: '#zilu-table .search-input'
                        },
                        resizable: false,
                        height: "auto",
                        width: 400,
                        modal: true,
                        buttons: {
                            "Appliquer": function () {
                                $(this).dialog().find(".text").addClass('hidden');
                                $(this).dialog().find(".loader").removeClass('hidden');
                                $.getJSON('/services/zilu.php?action=apply-fournisseurs&type=moinscher&commandeId=' + commandeId, function (data) {
                                    console.log(data);
                                    if ('ok' === data) {
                                        location.reload();
                                    }
                                });


                            },
                            "Annuler": function () {
                                $(this).dialog("close");
                            }
                        }
                    });
                }
                else if ('leaderfit' === data.item.value) {
                    $("#apply-fournisseur-cheapest-confirm-dialog").dialog({
                        position: {
                            my: "center",
                            at: "center",
                            of: '#zilu-table .search-input'
                        },
                        resizable: false,
                        height: "auto",
                        width: 400,
                        modal: true,
                        buttons: {
                            "Appliquer": function () {
                                $(this).dialog().find(".text").addClass('hidden');
                                $(this).dialog().find(".loader").removeClass('hidden');
                                $.getJSON('/services/zilu.php?action=apply-fournisseurs&type=leaderfit&commandeId=' + commandeId, function (data) {
                                    console.log(data);
                                    if ('ok' === data) {
                                        location.reload();
                                    }
                                });


                            },
                            "Annuler": function () {
                                $(this).dialog("close");
                            }
                        }
                    });
                }
            }
        });
        $("#send-mail-selector").selectmenu({
            select: function (event, data) {
                var value = $(this).val();
                var confMsg = "";
                if ('direction-test' === value) {
                    confMsg = "Vous êtes sur le point d'envoyer le mail récapitulatif de commande à zilu.";
                }
                confMsg += " ";
                confMsg += "Etes-vous certain de vouloir exécuter cette action ?";
                if (true === window.confirm(confMsg)) {
                    console.log("kk");
                }

            }
        });


        $('#zilu').on('click', function (e) {
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
            else if (jTarget.hasClass("csv-import-button")) {
                e.preventDefault();
                $("#csv-import-dialog").dialog({
                    position: {
                        my: "top",
                        at: "center",
                        of: jTarget
                    },
                    width: 600,
                    open: function (event, ui) {
                    }
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
                            $.getJSON('/services/zilu.php?action=update-commande-field&col=' + column + '&ric=' + ricValue + '&value=' + val, function (data) {
                                if ('ok' === data) {
                                    $("#update-column-dialog").dialog('close');
                                    window.location.reload();
                                }
                            });

                        });


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
            <form id="csv-import-form" action="/services/zilu.php?action=csv-import-form" method="post" enctype="multipart/form-data">
                <ul class="flex-outer">
                    <li>
                        <label for="first-name">Nom de la commande</label>
                        <input name="nom" type="text" value="<?php echo "C-" . date('Y-m-d'); ?>"></li>
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
    <div id="apply-fournisseur-cheapest-confirm-dialog" title="Appliquer le fournisseur le moins cher">
        <p class="text"><span class="ui-icon ui-icon-alert" style="float:left; margin:12px 12px 20px 0;"></span>Etes-vous
            sûr(e) de
            vouloir appliquer le fournisseur le moins cher pour chaque produit de cette commande ?</p>
        <p class="hidden loader">
            Veuillez patienter...
        </p>
    </div>
    <div id="apply-fournisseur-leaderfit-confirm-dialog" title="Appliquer le fournisseur leaderfit">
        <p class="text"><span class="ui-icon ui-icon-alert" style="float:left; margin:12px 12px 20px 0;"></span>Etes-vous
            sûr(e) de
            vouloir appliquer le fournisseur leaderfit pour chaque produit de cette commande ?</p>
        <p class="hidden loader">
            Veuillez patienter...
        </p>
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
</div>
