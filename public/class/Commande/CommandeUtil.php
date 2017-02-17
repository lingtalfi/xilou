<?php


namespace Commande;


use QuickPdo\QuickPdo;

class CommandeUtil
{


    public static function getCommandeSumInfo($commandeId)
    {
        $prixTotal = 0;
        $poidsTotal = 0;
        $volumeTotal = 0;

        $query = "select
h.prix_override,
h.quantite,
fha.prix,
fha.volume,
fha.poids

from commande_has_article h
inner join article a on a.id=h.article_id
inner join fournisseur_has_article fha on fha.fournisseur_id=h.fournisseur_id and fha.article_id=h.article_id
where h.commande_id=" . $commandeId;
        if (false !== ($res = QuickPdo::fetchAll($query))) {
            foreach ($res as $item) {
                $prix = $item['prix'];
                $qte = $item['quantite'];
                if ('' !== trim($item['prix_override'])) {
                    $prix = $item['prix_override'];
                }
                $prixTotal += $qte * $prix;
                $poidsTotal += $qte * $item['poids'];
                $volumeTotal += $qte * $item['volume'];
            }
        }
        return [$prixTotal, $poidsTotal, $volumeTotal];
    }

    public static function insertCommande(array $values)
    {
        $values = array_merge([
            'reference' => "",
        ], $values);
        return QuickPdo::insert("commande", $values);
    }

    /**
     * Use this to feed html select options
     */
    public static function getId2Labels()
    {
        return QuickPdo::fetchAll("select id, reference from commande order by id asc", [], \PDO::FETCH_COLUMN | \PDO::FETCH_UNIQUE);
    }


    /**
     * $type: string, leaderfit|moinscher
     */
    public static function applyFournisseurs($commandeId, $type)
    {
        $commandeId = (int)$commandeId;
        $query = "select
h.article_id
from commande_has_article h
inner join article a on a.id=h.article_id
inner join fournisseur_has_article fha on fha.fournisseur_id=h.fournisseur_id and fha.article_id=h.article_id
where h.commande_id=" . $commandeId;

        $articlesIds = QuickPdo::fetchAll($query, [], \PDO::FETCH_COLUMN);
        if ('moinscher' === $type) {
            foreach ($articlesIds as $id) {
                if (false !== ($res = QuickPdo::fetch('select fournisseur_id from fournisseur_has_article where
                prix = (select MIN(prix) from fournisseur_has_article where article_id=' . $id . ')
                and article_id=' . $id . "
                "))
                ) {
                    QuickPdo::update('commande_has_article', [
                        'fournisseur_id' => $res['fournisseur_id'],
                    ], [
                        ['commande_id', '=', $commandeId],
                        ['article_id', '=', $id],
                    ]);
                } else {
                    throw new \Exception("oops");
                }
            }
        } else {
            if (false !== ($res = QuickPdo::fetch("select id from fournisseur where nom='leaderfit'"))) {
                $idLeaderfit = $res['id'];
                foreach ($articlesIds as $id) {
                    QuickPdo::update('commande_has_article', [
                        'fournisseur_id' => $idLeaderfit,
                    ], [
                        ['commande_id', '=', $commandeId],
                        ['article_id', '=', $id],
                    ]);
                }
            } else {
                throw new \Exception("oops");
            }
        }


    }
}