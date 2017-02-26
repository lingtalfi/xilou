<?php


namespace Devis;


use QuickPdo\QuickPdo;

class DevisUtil
{

    /**
     * Use this to feed html select options
     */
    public static function getId2Labels()
    {
        return QuickPdo::fetchAll("select id, reference from devis order by id asc", [], \PDO::FETCH_COLUMN | \PDO::FETCH_UNIQUE);
    }

    public static function getAppliableId2LabelsByCommande($commandeId)
    {
        $commandeId = (int)$commandeId;
        return QuickPdo::fetchAll("
select 
d.id, 
d.reference 
from devis d
inner join commande_has_article h on h.fournisseur_id=d.fournisseur_id
where h.commande_id=$commandeId
order by d.id asc", [], \PDO::FETCH_COLUMN | \PDO::FETCH_UNIQUE);
    }


    public static function getFournisseurId($devisId)
    {
        if (false !== ($res = QuickPdo::fetch("select fournisseur_id from devis where id=" . (int)$devisId))) {
            return (int)$res['fournisseur_id'];
        }
        return false;
    }
}