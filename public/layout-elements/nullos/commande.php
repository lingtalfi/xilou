<?php


use AdminTable\Listable\QuickPdoListable;
use AdminTable\Table\AdminTable;
use AdminTable\View\AdminTableRenderer;
use AssetsList\AssetsList;
use Commande\CommandeUtil;
use Csv\CsvUtil;
use Layout\Goofy;

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
                    <?php foreach ($commandeId2Refs as $id => $label): ?>
                        <option value="<?php echo $id; ?>"><?php echo htmlspecialchars($label); ?></option>
                    <?php endforeach; ?>
                </select>
            </form>

            <?php

            if (0 !== $idCommande) {
                ?>

                <table class="zilu-info">
                    <tr>
                        <td>Prix total</td>
                        <td>60€</td>
                    </tr>
                    <tr>
                        <td>Poids total</td>
                        <td>50 kg</td>
                    </tr>
                </table>

                <?php
            }

            ?>

        </div>
        <div class="zilu-table">
            <?php

            if (0 !== $idCommande) {


                $fields = '
c.id,
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
";


                $list = AdminTable::create()
                    ->setRic(['id'])
                    ->setListable(QuickPdoListable::create()->setFields($fields)->setQuery($query))
                    ->setRenderer(AdminTableRenderer::create());
                $list->displayTable();
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
</script>