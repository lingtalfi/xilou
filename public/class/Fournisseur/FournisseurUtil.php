<?php


namespace Fournisseur;

use QuickPdo\QuickPdo;

class FournisseurUtil
{


    /**
     * Used by the commande tab.
     */
    public static function getComparisonInfo($articleId)
    {
        $articleId=(int)$articleId;
        return QuickPdo::fetchAll("
        select 
        f.id, 
        f.nom,
        h.reference,
        h.prix
        from fournisseur_has_article h 
        inner join fournisseur f on f.id=h.fournisseur_id
        where h.article_id=$articleId
        "
        );
    }

    /**
     * Use this to feed html select options
     */
    public static function getId2Labels()
    {
        return QuickPdo::fetchAll("select id, nom from fournisseur order by id asc", [], \PDO::FETCH_COLUMN | \PDO::FETCH_UNIQUE);
    }

}