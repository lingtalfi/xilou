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

            if (false !== ($articlesIds = QuickPdo::fetchAll("
select 
article_id
from commande_has_article h 
where 
h.commande_id=$commandeId
and h.fournisseur_id=$providerId", [], \PDO::FETCH_COLUMN))
            ) {
                foreach ($articlesIds as $aid) {
                    DevisHasCommandeHasArticleUtil::insert($devisId, $commandeId, $aid);
                }
            }
        }
    }

    public static function insert($devisId, $commandeId, $aid)
    {
        return QuickPdo::insert("devis_has_commande_has_article", [
            'devis_id' => $devisId,
            'commande_has_article_commande_id' => $commandeId,
            'commande_has_article_article_id' => $aid,
        ], 'ignore');
    }

    public static function remove($devisId, $commandeId, $aid)
    {
        return QuickPdo::delete("devis_has_commande_has_article", [
            ['devis_id', '=', $devisId],
            ['commande_has_article_commande_id', '=', $commandeId],
            ['commande_has_article_article_id', '=', $aid],
        ]);
    }

    public static function getNbDevisPerLine($commandeId, $articleId)
    {
        $commandeId = (int)$commandeId;
        $articleId = (int)$articleId;

        if (false !== ($res = QuickPdo::fetch('
select count(*) as count
from devis_has_commande_has_article 
where commande_has_article_commande_id=' . $commandeId . ' 
and commande_has_article_article_id=' . $articleId . '
'))
        ) {
            return (int)$res['count'];
        }
        return 0;
    }
}