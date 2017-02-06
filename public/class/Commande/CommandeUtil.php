<?php


namespace Commande;


use QuickPdo\QuickPdo;

class CommandeUtil
{


    /**
     * Process the data file,
     * and returns the number of successfully parsed lines.
     */
    public static function importCommandeByCsvData(array $data)
    {
        return count($data);
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