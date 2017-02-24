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
use Icons\Icons;
use Layout\Goofy;

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

        <button class="button-with-icon csv-import-button" id="csv-import-button">
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
                        <option value="devis">Associer un devis à tous les articles d'une commande</option>
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

                list($prixTotal, $poidsTotal, $volumeTotal) = CommandeUtil::getCommandeSumInfo($idCommande);

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

            if (0 !== $idCommande) {


                $fields = '
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


        var jCsvForm = $("form#csv-import-form");

        ajax_form(jCsvForm, function (data) {
            var oData = JSON.parse(data);

            var jFormError = $("#csv-import-dialog").find(".formerror");
            var jFormWarning = $("#csv-import-dialog").find(".formwarning");
            jFormError.addClass('hidden');
            jFormWarning.addClass('hidden');


            if ("error" in oData) {
                var formError = oData.error;
                jFormError.removeClass('hidden');
                jFormError.html(formError);
            }
            else if ("success" in oData) {
                var idCommande = oData.success;
                window.location.href = "/commande?commande=" + idCommande;
            }
            else if ("missingRefs" in oData) {
                var missingRefs = oData.missingRefs;
                jFormWarning.removeClass('hidden');
                jFormWarning.html(missingRefs);

            }

        });


        var commandeSelect = document.getElementById("commande-select");


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
                else if ('devis' === data.item.value) {
                    $("#commande-dialog-apply-devis").dialog({
                        position: {
                            my: "left top",
                            at: "left top",
                            of: '#csv-import-button'
                        },
                        resizable: false,
                        height: "auto",
                        width: 400,
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

        function getDatePlusDays(dt, days) {
            return new Date(dt.getTime() + (days * 86400000));
        }

        $("#send-mail-selector").selectmenu({
            select: function (e, data) {

                var value = $(this).val();

                if ('direction-test' === value || 'direction' === value) {

                    var argTest = "";
                    if ("direction-test" === value) {
                        argTest = "&test=1";
                    }


                    $("#order-conf-mail-dialog").dialog({
                        position: {
                            my: "left top",
                            at: "left center",
                            of: $(".zilu-topbar")
                        },
                        width: 600,
                        open: function (event, ui) {
                            var curDate = getDatePlusDays(new Date(), 30);
                            var defaultValue = curDate.toISOString().slice(0, 19).replace('T', ' ');
                            var jDate = $("#order-conf-mail-dialog").find('.datepicker');
                            var jBtn = $("#order-conf-mail-dialog").find('.order-conf-mail-submit-btn');
                            jDate.datepicker({
                                dateFormat: "yy-mm-dd"
                            });
                            jDate.datepicker("setDate", defaultValue);
                            jDate.blur();


                            jBtn.on('click', function (e) {
                                e.preventDefault();
                                var jForm = jBtn.closest('form');
                                var formData = jForm.serialize();


                                var jLoader = $("#order-conf-mail-dialog").find(".loader");
                                jLoader.removeClass("hidden");
                                var jBlock = $("#order-conf-mail-dialog").find(".block");
                                jBlock.addClass("hidden");


                                $.getJSON('/services/zilu.php?action=send-mail-purchase-order' + argTest + '&' + formData, function (data) {
                                    if ('ok' === data) {
                                        $("order-conf-mail-dialog").dialog('close');
                                        window.location.reload();
                                    }
                                    else {
                                        jLoader.html(data);
                                    }
                                });
                            });
                        }
                    });
                }
                else if ("fournisseurs" === value || "fournisseurs-test" === value) {
                    var argTest = "";
                    if ("fournisseurs-test" === value) {
                        argTest = "&test=1";
                    }


                    $("#order-pro-conf-mail-dialog").dialog({
                        position: {
                            my: "left top",
                            at: "left center",
                            of: $(".zilu-topbar")
                        },
                        width: 600,
                        open: function (event, ui) {

                            var jSignature = $("#order-pro-conf-mail-dialog").find('.selector');
                            var jBtn = $("#order-pro-conf-mail-dialog").find('.order-pro-conf-mail-submit-btn');


                            jBtn.on('click', function (e) {
                                e.preventDefault();
                                var jForm = jBtn.closest('form');
                                var formData = jForm.serialize();


                                var jProviders = $('#mail-providers-checkboxes').find('input');
                                var aProviders = [];
                                jProviders.each(function () {
                                    var jInput = $(this);
                                    if (true === jInput.prop('checked')) {
                                        aProviders.push([jInput.val(), jInput.parent().text().trim()]);
                                    }
                                });

                                var nbProviders = aProviders.length;
                                var currentProvider = 0;

                                var jLoader = $("#order-pro-conf-mail-dialog").find(".loader");
                                jLoader.removeClass("hidden");
                                var jBlock = $("#order-pro-conf-mail-dialog").find(".block");
                                jBlock.addClass("hidden");


                                function processProvider() {
                                    var providerInfo = aProviders.shift();
                                    if ('undefined' !== typeof providerInfo) {

                                        var providerId = providerInfo[0];
                                        var providerName = providerInfo[1];

                                        currentProvider++;
                                        var jLine = $("<p>Préparation du mail pour " + providerName + " (" + currentProvider + "/" + nbProviders + ") ...</p>");
                                        jLoader.append(jLine);

                                        $.getJSON('/services/zilu.php?action=send-mail-pro-purchase-order' + argTest + '&provider_id=' + providerId + '&' + formData, function (data) {

                                            if ("success" in data) {
                                                jLine.append('<span class="zilu-success">envoyé</span>');
                                            }
                                            else if ("error" in data) {
                                                jLine.append('<span class="zilu-error">' + data['error'] + '</span>');
                                            }


                                            // recursion
                                            if (currentProvider < nbProviders) {
                                                processProvider();
                                            }
                                            else {
                                                jLine.append('<div style="height: 20px;"></div><div style="text-align: center"><button class="zilu-close zilu-black-button">Ok</button></div>');
                                            }
                                        });
                                    }
                                }

                                processProvider();
                            });
                        }
                    });
                }

            }
        });




        $('#commande-dialog-applydevis-devis-selector').on('change', function(){

            //todo...
            $.get('/services/zilu.php?action=commande-getcommande-by-devis', function(){

            });
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
            <form id="csv-import-form" action="/services/zilu.php?action=csv-import-form" method="post"
                  enctype="multipart/form-data">
                <div class="formerror"></div>
                <div class="formwarning"></div>
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
    <div id="commande-dialog-apply-devis" title="Associer un devis à tous les articles d'une commande">
        <table>
            <tr>
                <td>Devis</td>
                <td>Commande</td>
            </tr>
            <tr>
                <td>
                    <select id="commande-dialog-applydevis-devis-selector">
                        <option value="0">Choisissez un devis</option>
                        <?php
                        $id2Labels = DevisUtil::getId2Labels();
                        foreach ($id2Labels as $id => $label):
                            ?>
                            <option value="<?php echo $id; ?>"><?php echo $label; ?></option><?php
                        endforeach;
                        ?>
                    </select>
                </td>
                <td>
                    <select id="commande-dialog-applydevis-commande-selector">
                        <option value="0">Choisissez une commande</option>
                    </select>
                </td>
            </tr>
        </table>
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
    <div id="order-conf-mail-dialog" title="Informations à inscrire dans le mail">
        <div class="block">
            <form style="text-align: center">
                <ul class="flex-outer">
                    <li>
                        <label>Date estimée</label>
                        <input type="text" name="estimated_date" class="datepicker" value="">
                    </li>
                    <li>
                        <button class="order-conf-mail-submit-btn" type="submit">Envoyer le mail</button>
                    </li>
                </ul>
                <input type="hidden" name="commande_id" value="<?php echo $idCommande; ?>">
            </form>
        </div>
        <div class="loader hidden">
            Veuillez patienter un instant...
        </div>
    </div>
    <div id="order-pro-conf-mail-dialog" title="Informations à inscrire dans le mail">
        <div class="block">
            <form style="text-align: center">
                <ul class="zilu-nobullet">
                    <li>
                        <label>Signature à utiliser</label>
                        <select class="selector" name="signature">
                            <option value="leaderfit">Leaderfit</option>
                            <option value="hldp">Hldp</option>
                        </select>
                    </li>
                    <li>

                        <table>
                            <tr>
                                <td>
                                    <table id="mail-providers-checkboxes">
                                        <?php
                                        $providerId2Labels = FournisseurUtil::getId2LabelsByCommandeId($idCommande);
                                        foreach ($providerId2Labels as $id => $label): ?>
                                            <tr>
                                                <td style="text-align: left">
                                                    <label>
                                                        <input type="checkbox"
                                                               value="<?php echo $id; ?>" checked>
                                                        <?php echo $label; ?>
                                                    </label>
                                                </td>
                                            </tr>
                                        <?php endforeach; ?>
                                    </table>
                                </td>
                                <td>
                                    <table>
                                        <tr>
                                            <td style="text-align: left">
                                                <button class="providers-checkall">Cocher tout</button>
                                            </td>
                                            <td style="text-align: left">
                                                <button class="providers-uncheckall">Décocher tout</button>
                                            </td>
                                        </tr>
                                    </table>
                                </td>
                            </tr>
                        </table>
                    </li
                    <li>
                        <button class="order-pro-conf-mail-submit-btn zilu-black-button" type="submit">Envoyer le mail
                        </button>
                    </li>
                </ul>
                <input type="hidden" name="commande_id" value="<?php echo $idCommande; ?>">
            </form>
        </div>
        <div class="loader hidden">
            Veuillez patienter un instant...
        </div>
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
</div>
