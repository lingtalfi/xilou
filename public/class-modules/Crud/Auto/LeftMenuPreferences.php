<?php


namespace Crud\Auto;


class LeftMenuPreferences
{


    public static function getLeftMenuSectionBlocks()
    {
        return [
    'Website' => [
        'zilu.article',
        'zilu.commande',
        'zilu.commande_has_article',
        'zilu.commande_ligne_statut',
        'zilu.container',
        'zilu.fournisseur',
        'zilu.fournisseur_has_article',
        'zilu.sav',
        'zilu.type_container',
    ],
];
    }

    /**
     * Labels are used in the left menu only
     */
    public static function getTableLabels()
    {
        return [
    'zilu.commande_has_article' => 'commande has article',
    'zilu.commande_ligne_statut' => 'commande ligne statut',
    'zilu.fournisseur_has_article' => 'fournisseur has article',
    'zilu.type_container' => 'type container',
];
    }

}