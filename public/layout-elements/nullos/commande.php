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

$commandeId2Refs = CommandeUtil::getId2Labels();

?>


<div class="zilu">
    <div class="zilu-topbar">
        <form action="" method="post" enctype="multipart/form-data">
            <div class="button-icon">
                <label>
                    Importer un fichier csv
                </label>
                <input id="import-csv-input" type="file" name="csvfile"
                       accept=".csv, application/vnd.openxmlformats-officedocument.spreadsheetml.sheet, application/vnd.ms-excel"
                >
            </div>
        </form>
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

            $sTable = '';

            if (0 !== $idCommande) {


                $fields = '
c.id,
co.nom as container,
c.reference,
a.reference_lf,
a.reference_hldp,
a.prix,
a.poids,
a.descr_fr,
a.descr_en
';


                $query = "select
%s
from zilu.commande c
inner join commande_has_article h on h.commande_id=c.id
inner join article a on a.id=h.article_id
left join container co on co.id=h.container_id
where c.id=" . $idCommande;

                $prixTotal = 0;
                $poidsTotal = 0;

                ob_start();
                $list = CommandeAdminTable::create()
                    ->setRic(['id'])
                    ->setListable(QuickPdoListable::create()->setFields($fields)->setQuery($query))
                    ->setRenderer(AdminTableRenderer::create()
                        ->setExtraHiddenFields([
                            "commande" => $idCommande,
                        ])
                        ->setOnItemIteratedCallback(function ($v) use (&$prixTotal, &$poidsTotal) {
                            $prixTotal += $v['prix'];
                            $poidsTotal += $v['poids'];
                        })
                    );

                $list->setTransformer("container", function ($value, $item, $ricValue) {
                    $text = $value;
                    if (null === $value) {
                        $text = "(no container)";
                    }
                    return '
                    <div class="container-selector">
                        
                    </div>
                    <a class="jslink" data-handler="containerSelector" href="#">' . $text . '</a>
';
                });

                $list->displayTable();
                $sTable = ob_get_clean();


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
            }


            $containers = ContainerUtil::getId2Labels();
            ?>
            <div id="containerSelector" style="display: none;">
                <select>
                    <option value="0">Choisissez un container</option>
                    <?php
                    foreach ($containers as $id => $label) {
                        ?>
                        <option value="<?php echo $id; ?>"><?php echo htmlspecialchars($label); ?></option>
                        <?php
                    }
                    ?>
                </select>
                <button><?php Icons::printIcon("clear"); ?></button>
            </div>
        </div>
        <div id="zilu-table" class="zilu-table">
            <?php

            if (0 !== $idCommande) {
                echo $sTable;
            }
            ?>
        </div>
    </div>


</div>


<script>
    var csvInput = document.getElementById("import-csv-input");
    var commandeSelect = document.getElementById("commande-select");
    csvInput.addEventListener('change', function () {
        csvInput.parentNode.parentNode.submit();
    });
    commandeSelect.addEventListener('change', function () {
        var value = commandeSelect.value;
        if ('0' !== value) {
            commandeSelect.parentNode.submit();
        }
    });

    var ziluTable = document.getElementById('zilu-table');
    ziluTable.addEventListener('click', function (e) {
        if (e.target.classList.contains('postlink')) {
            e.preventDefault();
        }

    });


    var theContainerSelector = document.getElementById("containerSelector");
    var containerSelector = theContainerSelector.querySelector('select');
    var containerSelectorCloseBtn = theContainerSelector.querySelector('button');


    containerSelector.addEventListener('change', function (e) {
        if ('0' !== this.value) {
            this.parentNode.classList.add("hidden");
            this.parentNode.parentNode.querySelector(".jslink").classList.remove("hidden");
        }
    });


    function toggleContainerSelector(element) {
        var nodeName = element.nodeName;
        if ('A' === nodeName) {
            element.classList.add("hidden");
            var containerContainer = element.parentNode.querySelector(".container-selector");
            containerContainer.appendChild(containerSelector);
            element.parentNode.querySelector(".container-selector").classList.remove('hidden');
        }
    }


    AdminTable.registerAjaxCallback("containerSelector", function (el) {
        toggleContainerSelector(el);
    });


</script>