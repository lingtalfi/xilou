<?php


namespace FournisseurHasArticle;

use QuickPdo\QuickPdo;

class FournisseurHasArticle
{


    public static function insertEmpty($fournisseur_id, $article_id)
    {
        QuickPdo::insert('fournisseur_has_article', [
            'fournisseur_id' => $fournisseur_id,
            'article_id' => $article_id,
            'reference' => "",
            'prix' => "",
            'volume' => "",
            'poids' => "",
        ]);
    }
}