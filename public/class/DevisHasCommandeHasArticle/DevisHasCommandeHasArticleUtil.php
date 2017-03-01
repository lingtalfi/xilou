<?php


namespace DevisHasCommandeHasArticle;


use Devis\DevisUtil;
use QuickPdo\QuickPdo;

class DevisHasCommandeHasArticleUtil
{

    public static function bindDevisToCommande($devisId, $commandeId)
    {
        if (false !== ($providerId = DevisUtil::getFournisseurId($devisId))) {

            $commandeId = (int)$commandeId;

            if (false !== ($lineIds = QuickPdo::fetchAll("
select 
id
from commande_has_article h 
where 
h.commande_id=$commandeId
and h.fournisseur_id=$providerId", [], \PDO::FETCH_COLUMN))
            ) {
                foreach ($lineIds as $id) {
                    DevisHasCommandeHasArticleUtil::insert($devisId, $id);
                }
            }
        }
    }

    public static function insert($devisId, $lineId)
    {
        return QuickPdo::insert("devis_has_commande_has_article", [
            'devis_id' => $devisId,
            'commande_has_article_id' => $lineId,
        ], 'ignore');
    }

    public static function remove($devisId, $lineId)
    {
        return QuickPdo::delete("devis_has_commande_has_article", [
            ['devis_id', '=', $devisId],
            ['commande_has_article_id', '=', $lineId],
        ]);
    }

    public static function getNbDevisPerLine($lineId)
    {
        if (false !== ($res = QuickPdo::fetch('
select count(*) as count
from devis_has_commande_has_article 
where commande_has_article_id=' . $lineId))
        ) {
            return (int)$res['count'];
        }
        return 0;
    }
}