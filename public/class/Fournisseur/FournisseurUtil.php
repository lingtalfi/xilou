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
        $articleId = (int)$articleId;
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

    public static function getId2LabelsByCommandeId($commandId)
    {
        $commandId = (int)$commandId;
        return QuickPdo::fetchAll("select f.id, f.nom 
        from fournisseur f 
        inner join commande_has_article h on h.fournisseur_id=f.id
        where h.commande_id=$commandId
        order by id asc",
            [], \PDO::FETCH_COLUMN | \PDO::FETCH_UNIQUE);
    }


    public static function getFournisseurByNom($nom)
    {
        return QuickPdo::fetch('select * from fournisseur where nom=:nom', [
            'nom' => $nom,
        ]);
    }

    public static function getFournisseurNomById($id)
    {
        if (false !== ($res = QuickPdo::fetch('select nom from fournisseur where id=' . (int)$id))) {
            return $res['nom'];
        }
        return false;
    }

    public static function getEmail($id)
    {
        if (false !== ($res = QuickPdo::fetch('select email from fournisseur where id=' . (int)$id))) {
            return $res['email'];
        }
        return false;
    }

}