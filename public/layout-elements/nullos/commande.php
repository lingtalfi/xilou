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


$_SESSION['commandeQueryString'] = $_SERVER['QUERY_STRING'];

$idCommande = 0;
if (array_key_exists('commande', $_GET)) {
    $idCommande = (int)$_GET['commande'];
}


if (array_key_exists("download", $_SESSION)) {

    $file = $_SESSION['download'];
    unset($_SESSION['download']);
    HttpResponseUtil::downloadFile($file, "application/xlsx");

}


AssetsList::css("style/zilu.css");
AssetsList::css("/style/admintable.css");
AssetsList::js("/libs/lightbox2/src/js/lightbox.js");
AssetsList::css("/libs/lightbox2/src/css/lightbox.css");


$commandeId2Refs = CommandeUtil::getId2Labels();

?>


<div class="zilu" id="zilu">
    <div class="zilu-topbar">

        <div>
            <button class="button-with-icon csv-import-button" id="csv-import-button">
            <span>
                <span>Importer un fichier excel</span>
                <?php Icons::printIcon("add", 'white'); ?>
            </span>
            </button>
        </div>
        <div style="margin-left:20px;">
            <button class="button-with-icon csv-export-button">
            <span>
                <span>Exporter un fichier excel</span>
                <?php Icons::printIcon("add", 'white'); ?>
            </span>
            </button>
        </div>

        <div class="commande-actions-group">
            <div class="commande-actions-vertical" id="commande-actions-vertical">
                <form>
                    <select id="change-all-fournisseurs-selector">
                        <option>Pour tous les articles de cette commande...</option>
                        <option value="moinscher">Appliquer le fournisseur le moins cher pour chaque produit</option>
                    </select>
                </form>
                <form>
                    <select id="apply-devis-selector">
                        <option>Appliquer le devis d'un fournisseur...</option>
                        <option value="devis">à tous les articles de ce fournisseur pour cette commande</option>
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
c.id as commande_id,
h.id,
h.commande_ligne_statut_id as statut,
co.id as container_id,
co.nom as container,
c.reference as commande,
a.reference_lf,
a.reference_hldp,
a.logo,
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
(select count(*) from devis_has_commande_has_article where commande_has_article_id=h.id) as devis,
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
                    ->setRic(['id'])
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
                    return '<a class="update-statut-link" data-value="' . $value . '" data-id="' . $item['id'] . '" href="#" style="white-space: nowrap">' . CommandeLigneStatutUtil::toString($value) . '</a>';
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

                $list->setTransformer("logo", function ($value, $item, $ricValue) {
                    if ('/' === $value) {
                        return "";
                    }
                    return '<img width="50" src="' . htmlspecialchars($value) . '">';
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
                    'commande_id',
                    'cid',
//                    'id',
                    'aid',
                    'container_id',
                    'fournisseur_id',
                ];


//                $list->setMultipleActionHandler("changestatut", "Changer le statut", $fn, $conf=false);

                $list->displayTable();

            }
            ?>
        </div>
    </div>


</div>


<script>


    $(document).ready(function () {


        var jGlobalTarget = null;

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

            var oData = {};
            if ('' !== data) {
                oData = JSON.parse(data);
            }

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


        $("#apply-devis-selector").selectmenu({
            select: function (event, data) {
                if ('devis' === data.item.value) {
                    $("#commande-dialog-apply-devis").dialog({
                        position: {
                            my: "left top",
                            at: "left top",
                            of: '#csv-import-button'
                        },
                        height: "auto",
                        width: 600
                    });
                }
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

                    if ('undefined' !== typeof $("#order-conf-mail-dialog").dialog('instance')) {
                        $("#order-conf-mail-dialog").dialog("close");
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

                            var jLoader = $("#order-conf-mail-dialog").find(".loader");
                            var jBlock = $("#order-conf-mail-dialog").find(".block");
                            jLoader.addClass("hidden");
                            jBlock.removeClass("hidden");


                            jBtn
                                .off('click')
                                .on('click', function (e) {
                                    e.preventDefault();
                                    var jForm = jBtn.closest('form');
                                    var formData = jForm.serialize();


                                    jLoader.removeClass("hidden");
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


                    if ('undefined' !== typeof $("#order-pro-conf-mail-dialog").dialog('instance')) {
                        $("#order-pro-conf-mail-dialog").dialog("close");
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

                            var jLoader = $("#order-pro-conf-mail-dialog").find(".loader");
                            var jBlock = $("#order-pro-conf-mail-dialog").find(".block");
                            jLoader.addClass("hidden");
                            jBlock.removeClass("hidden");


                            jBtn
                                .off('click')
                                .on('click', function (e) {
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


                                    jLoader.removeClass("hidden");
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


        $('#commande-dialog-applydevis-devis-selector').on('change', function () {


            var jLoader = $('#commande-dialog-apply-devis').find('.loader');
            var jMain = $('#commande-dialog-apply-devis').find('.maincontent');
            jLoader.removeClass('hidden');
            jMain.addClass('hidden');

            var devisId = $(this).val();
            var commandeId = $(this).attr('data-cid');


            $.getJSON('/services/zilu.php?action=commande-applydevis&did=' + devisId + "&cid=" + commandeId, function (data) {
                if ('ok' === data) {
                    location.reload();
                }
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
            else if (jTarget.hasClass("csv-export-button")) {
                e.preventDefault();
                $("#csv-export-dialog").dialog({
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
                var lineId = jTarget.attr('data-id');
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
                    width: 600,
                    buttons: {
                        "Appliquer": function () {

                            var jSelect = $("#container-status-dialog").find('select');
                            var jComment = $("#container-status-dialog").find('textarea');
                            var value = jSelect.val();
                            var comment = jComment.val();
                            $.post('/services/zilu.php?action=commande-update-statut&statut=' + value + "&id=" + lineId, {
                                'commentaire': comment
                            }, function (data) {
                                if ('ok' === data) {
                                    location.reload();
                                }
                            }, 'json');
                        },
                        "Annuler": function () {
                            $(this).dialog("close");
                        }
                    },
                    open: function (event, ui) {
                        var jSelect = $("#container-status-dialog").find('select');
                        jSelect.val(statutValue);
                        var jHistoContainer = $("#container-status-dialog").find('.historique-statuts');
                        var jShowTable = $("#container-status-dialog").find('.showtable');
                        var jHideTable = $("#container-status-dialog").find('.hidetable');


                        jHistoContainer.hide();
                        jShowTable.hide();
                        jHideTable.hide();
                        jHideTable.show();
                        jHistoContainer.show();

                        jShowTable
                            .off('click')
                            .on('click', function (e) {
                                e.preventDefault();
                                jHideTable.show();
                                jShowTable.hide();
                                jHistoContainer.show();
                            });

                        jHideTable
                            .off('click')
                            .on('click', function (e) {
                                e.preventDefault();
                                jShowTable.show();
                                jHideTable.hide();
                                jHistoContainer.hide();
                            });



                        $.get('/services/zilu.php?action=commande-get-historiquestatut&id=' + lineId, function (data) {
                            jHistoContainer.html(data);
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
                var lineId = jTable.attr('data-id');
                $.getJSON('/services/zilu.php?action=devis-add-bindure&did=' + did + "&id=" + lineId, function (data) {
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
                var lineId = jTable.attr('data-id');
                $.getJSON('/services/zilu.php?action=devis-remove-bindure&did=' + did + "&id=" + lineId, function (data) {
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
            else if (jTarget.hasClass("multiple-action-selector")) {
                e.preventDefault();

                var jUpdateZone = null;
                var updateType = null;


                $("#commande-dialog-multipleaction-choices").dialog({
                    position: {
                        my: "bottom",
                        at: "bottom",
                        of: jTarget
                    },
                    width: 600,
                    buttons: {
                        "Appliquer": function () {
                            var value = jUpdateZone.find('.valueholder').val();
                            var value2 = jUpdateZone.find('.valueholder2').val();

                            // get all checked ids
                            var jDataTable = $("table.datatable");
                            var aRics = [];
                            jDataTable.find("input.checkbox").each(function () {
                                if ($(this).prop('checked')) {
                                    aRics.push($(this).val());
                                }
                            });

                            $.post('/services/zilu.php?action=multipleaction&type=' + updateType, {
                                'rics': aRics,
                                'value': value,
                                'value2': value2
                            }, function (data) {
                                if ('ok' === data) {
                                    location.reload();
                                }
                            }, 'json');


                        },
                        "Annuler": function () {
                            $(this).dialog("close");
                        }
                    },
                    open: function (event, ui) {
                        jUpdateZone = $(this).dialog().find("#multipleaction-choices-zone");
                        var jButtonPane = $(this).dialog().parent().find(".ui-dialog-buttonpane");

                        jButtonPane.hide();
                        jUpdateZone.hide();


                        var jSelect = $(this).dialog().find("#multipleaction-choices-selector");

                        jSelect
                            .off('change')
                            .on('change', function () {
                                var value = $(this).val();
                                if ('0' === value) {
                                    jButtonPane.hide();
                                    jUpdateZone.hide();
                                }
                                else {
                                    updateType = value;
                                    jButtonPane.show();
                                    jUpdateZone.show();
                                    $.get('/services/zilu.php?action=commande-multipleaction-control&control=' + value, function (data) {
                                        jUpdateZone.empty().html(data);
                                    });
                                }
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
    <div id="csv-import-dialog" title="Importer une commande par fichier excel" class="zilu-dialog centered">
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
        <ul style="list-style-type: none">
            <li>
                <select>
                    <?php
                    $id2CommandeLigneStatutLabels = CommandeLigneStatutUtil::getIds2Labels();

                    foreach ($id2CommandeLigneStatutLabels as $id => $label):
                        ?>
                        <option value="<?php echo $id; ?>"><?php echo $label; ?></option>
                    <?php endforeach; ?>
                </select>
            </li>
            <li style="display: flex; align-items: flex-start; margin-top: 10px">
                <span style="margin-right: 10px">Commentaire</span>
                <textarea></textarea>
            </li>
            <li style="margin-top: 20px;">
                <a class="showtable" href="#">Montrer la table des statuts</a>
                <a class="hidetable" href="#">Masquer la table des statuts</a>
            </li>
            <li class="historique-statuts">

            </li>
        </ul>
    </div>
    <div id="commande-dialog-apply-devis" title="Associer le devis d'un fournisseur aux articles correspondants"
         style="text-align: center"
    >
        <table style="width: 100%">
            <tr class="maincontent">
                <td>
                    <select data-cid="<?php echo $idCommande; ?>" id="commande-dialog-applydevis-devis-selector">
                        <option value="0">Choisissez un devis</option>
                        <?php
                        $id2Labels = DevisUtil::getAppliableId2LabelsByCommande($idCommande);
                        foreach ($id2Labels as $id => $label):
                            ?>
                            <option value="<?php echo $id; ?>"><?php echo $label; ?></option><?php
                        endforeach;
                        ?>
                    </select>
                </td>
            </tr>
            <tr class="loader hidden">
                <td>
                    Veuillez patienter un instant...
                </td>
            </tr>
        </table>
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
                        <?php foreach ($commandeId2Refs as $id => $ref): ?>
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
    <div id="commande-dialog-multipleaction-choices" title="Action à effectuer pour toutes les lignes sélectionnées">
        <div class="mainbody">
            <select id="multipleaction-choices-selector">
                <option value="0">Choisissez une action...</option>
                <option value="statut">Changer le statut</option>
                <option value="devis">Envoyer un email de demande de devis</option>
            </select>
            <div id="multipleaction-choices-zone" style="margin-top: 10px;">

            </div>
        </div>
    </div>
</div>
