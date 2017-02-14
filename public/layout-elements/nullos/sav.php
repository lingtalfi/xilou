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


$_SESSION['savQueryString'] = $_SERVER['QUERY_STRING'];


$idCommande = 0;
if (array_key_exists('commande', $_GET)) {
    $idCommande = (int)$_GET['commande'];
}


AssetsList::css("style/zilu.css");
AssetsList::css("/style/admintable.css");
AssetsList::js("/libs/lightbox2/src/js/lightbox.js");
AssetsList::css("/libs/lightbox2/src/css/lightbox.css");


$commandeId2Refs = CommandeUtil::getId2Labels();


?>


<div class="zilu" id="zilu">
    <div class="zilu-topbar">

<!--        <button class="button-with-icon sav-add-button">-->
<!--            <span>-->
<!--                <span>Ajouter une ligne SAV</span>-->
<!--                --><?php //Icons::printIcon("add", 'white'); ?>
<!--            </span>-->
<!--        </button>-->
        <!--        <div class="commande-actions-group">-->
        <!--            <div class="commande-actions-vertical" id="commande-actions-vertical">-->
        <!--                <form>-->
        <!--                    <select id="change-all-fournisseurs-selector">-->
        <!--                        <option>Pour tous les articles de cette commande...</option>-->
        <!--                        <option value="moinscher">Appliquer le fournisseur le moins cher pour chaque produit</option>-->
        <!--                        <option value="leaderfit">Appliquer le fournisseur leaderfit pour chaque produit</option>-->
        <!--                    </select>-->
        <!--                </form>-->
        <!--                <form>-->
        <!--                    <select id="send-mail-selector">-->
        <!--                        <option>Envoyer un email...</option>-->
        <!--                        <option value="direction">à Didier</option>-->
        <!--                        <option value="fournisseurs">aux fournisseurs</option>-->
        <!--                    </select>-->
        <!--                </form>-->
        <!--            </div>-->
        <!--        </div>-->

    </div>
    <div class="zilu-split">
        <div class="zilu-summary">

            <form action="" method="get">

                <label>Commande</label>
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

        </div>
        <div id="zilu-table" class="zilu-table">
            <?php


            $fields = '
s.id,
s.fournisseur,
s.reference_lf,
s.produit,
s.livre_le,
s.quantite,
s.prix,
s.nb_produits_defec,
s.date_notif,
s.demande_remboursement,
s.montant_rembourse,
s.pourcentage_rembourse,
s.date_remboursement,
s.forme,
s.statut,
s.photo,
s.avancement
';


            $query = "select
%s
from zilu.sav s";


            if (0 !== $idCommande) {

                $query .= "
                 inner join commande_has_article h on h.sav_id=s.id
                 where h.commande_id=" . $idCommande;
            }

            $list = AdminTable::create()
                ->setRic(['id'])
                ->setListable(QuickPdoListable::create()->setFields($fields)->setQuery($query))
                ->setRenderer(AdminTableRenderer::create()
                    ->setExtraHiddenFields([
                        "commande" => $idCommande,
                    ])
                );

            $list->setTransformer("photo", function ($value, $item, $ricValue) {
                if ($value) {
                    $thumb = htmlspecialchars($value);
                    $big = dirname(dirname($value)) . '/' . basename($value);
                    return '<a href="' . $big . '" data-lightbox="image-1">
                <img src="' . $thumb . '">
                </a>';
                }
                return '';
            });


            $list->hiddenColumns = [
                'id',
            ];
            $list->displayTable();
            ?>
        </div>
    </div>


</div>


<script>


    $(document).ready(function () {


        $('#sav-topmenu-link').attr('href', "/commande" + window.location.search);
        var commandeSelect = document.getElementById("commande-select");

        commandeSelect.addEventListener('change', function () {
            var value = commandeSelect.value;
            if ('0' !== value) {
                commandeId = value;
                commandeSelect.parentNode.submit();
            }
        });


        $('#zilu').on('click', function (e) {
            var jTarget = $(e.target);
            if (jTarget.hasClass("sav-add-button")) {
                e.preventDefault();
                $("#sav-add-dialog").dialog({
                    position: {
                        my: "top",
                        at: "center",
                        of: jTarget
                    },
                    width: 600,
                    open: function (event, ui) {
                        $.get("/services/zilu.php?action=sav-form-add", function (html) {
                            $("#inject-container").html(html);
                        });
                    }
                });
            }
        });


    });


</script>

<div style="display:none">
    <div id="sav-add-dialog" title="Ajouter une ligne sav" class="zilu-dialog centered">
        <div id="inject-container"></div>
    </div>
</div>