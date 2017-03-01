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
        'zilu.csv_fournisseurs_comparatif',
        'zilu.csv_fournisseurs_containers',
        'zilu.csv_fournisseurs_fournisseurs',
        'zilu.csv_fournisseurs_sav',
        'zilu.csv_prix_materiel',
        'zilu.csv_product_details',
        'zilu.csv_product_list',
        'zilu.devis',
        'zilu.devis_has_commande_has_article',
        'zilu.fournisseur',
        'zilu.fournisseur_has_article',
        'zilu.historique_statut',
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
    'zilu.csv_fournisseurs_comparatif' => 'csv fournisseurs comparatif',
    'zilu.csv_fournisseurs_containers' => 'csv fournisseurs containers',
    'zilu.csv_fournisseurs_fournisseurs' => 'csv fournisseurs fournisseurs',
    'zilu.csv_fournisseurs_sav' => 'csv fournisseurs sav',
    'zilu.csv_prix_materiel' => 'csv prix materiel',
    'zilu.csv_product_details' => 'csv product details',
    'zilu.csv_product_list' => 'csv product list',
    'zilu.devis_has_commande_has_article' => 'devis has commande has article',
    'zilu.fournisseur_has_article' => 'fournisseur has article',
    'zilu.historique_statut' => 'historique statut',
    'zilu.type_container' => 'type container',
];
    }

}