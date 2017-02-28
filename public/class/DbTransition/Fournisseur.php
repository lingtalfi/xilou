<?php


namespace DbTransition;


use QuickPdo\QuickPdo;

class Fournisseur
{


    public static function createFournisseurs()
    {
        $items = QuickPdo::fetchAll('select distinct fournisseur from csv_prix_materiel');
        foreach ($items as $item) {
            $fournisseur = trim($item['fournisseur']);
            if ('' !== $fournisseur) {
                QuickPdo::insert('fournisseur', [
                    'nom' => $fournisseur,
                    'email' => '',
                ]);
            }
        }
    }


    public static function getFournisseurIds()
    {
        return QuickPdo::fetchAll('select nom, id from fournisseur', [], \PDO::FETCH_UNIQUE | \PDO::FETCH_COLUMN | \PDO::FETCH_GROUP);
    }
}