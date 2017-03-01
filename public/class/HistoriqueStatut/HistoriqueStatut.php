<?php


namespace HistoriqueStatut;

use CommandeHasArticle\CommandeHasArticleUtil;
use CommandeLigneStatut\CommandeLigneStatutUtil;
use QuickPdo\QuickPdo;

class HistoriqueStatut
{

    public static function insert($lineId, $statutId, $commentaire)
    {
        $date = date("Y-m-d H:i:s");
        $statutNom = CommandeLigneStatutUtil::getNom($statutId);

        if (false !== ($info = CommandeHasArticleUtil::getLineInfo($lineId))) {

            return QuickPdo::insert("historique_statut", [
                "date" => $date,
                "statut_nom" => $statutNom,
                "reference_lf" => $info['reference_lf'],
                "fournisseur_nom" => $info['fournisseur_nom'],
                "reference_fournisseur" => $info['reference_fournisseur'],
                "commande_reference" => $info['commande_reference'],
                "commentaire" => $commentaire,
                "commande_has_article_id" => $lineId,
            ]);
        }
        return false;
    }


    public static function displayHistoriqueByLineId($lineId)
    {

        $items = QuickPdo::fetchAll("select 
commande_reference,
`date`,
statut_nom,
reference_lf,
fournisseur_nom,
reference_fournisseur,
commentaire
from historique_statut where commande_has_article_id=" . (int)$lineId);

        if (count($items) > 0) {
            ?>
            <table class="zilu-commande-historique-statut-table">
                <tr>
                    <th>Référence commande</th>
                    <th>Date</th>
                    <th>Statut</th>
                    <th>Référence LF</th>
                    <th>Fournisseur</th>
                    <th>Référence fournisseur</th>
                    <th>Commentaire</th>
                </tr>
                <?php foreach ($items as $item): ?>
                    <tr>
                        <?php foreach ($item as $v): ?>
                            <td><?php echo $v; ?></td>
                        <?php endforeach; ?>
                    </tr>
                <?php endforeach; ?>
            </table>
            <?php
        }

    }
}