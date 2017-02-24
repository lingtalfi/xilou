<?php
$prefs = [
    'actionColumnsPosition' => 'right',
    'prettyTableNames' => [
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
        'zilu.type_container' => 'type container',
    ],
    'foreignKeyPrettierColumns' => [
        'zilu.article' => 'reference_lf',
        'zilu.commande' => 'reference',
        'zilu.commande_ligne_statut' => 'nom',
        'zilu.container' => 'nom',
        'zilu.fournisseur' => 'nom',
        'zilu.sav' => 'fournisseur',
        'zilu.type_container' => 'label',
        'zilu.commande_has_article' => 'unit',
        'zilu.devis' => 'reference',
    ],
    'prettyColumnNames' => [
        'reference_lf' => 'reference lf',
        'reference_hldp' => 'reference hldp',
        'descr_fr' => 'descr fr',
        'descr_en' => 'descr en',
        'long_desc_en' => 'long desc en',
        'commande_id' => 'commande',
        'article_id' => 'article',
        'container_id' => 'container',
        'fournisseur_id' => 'fournisseur',
        'sav_id' => 'sav',
        'commande_ligne_statut_id' => 'commande ligne statut',
        'prix_override' => 'prix override',
        'date_estimee' => 'date estimee',
        'type_container_id' => 'type container',
        'ref_hldp' => 'ref hldp',
        'ref_lf' => 'ref lf',
        'vendu_par' => 'vendu par',
        'nom_hldp' => 'nom hldp',
        'nom_leaderfit' => 'nom leaderfit',
        'etat_import' => 'etat import',
        'en_products' => 'en products',
        'en_sold_by' => 'en sold by',
        'en_packaging' => 'en packaging',
        'en_material' => 'en material',
        'en_description' => 'en description',
        'en_category' => 'en category',
        'es_products' => 'es products',
        'es_sold_by' => 'es sold by',
        'es_packaging' => 'es packaging',
        'es_material' => 'es material',
        'es_category' => 'es category',
        'top_asia' => 'top asia',
        'modern_sports' => 'modern sports',
        'live_up' => 'live up',
        'pa_dollar' => 'pa dollar',
        'pa_fdp_inclus' => 'pa fdp inclus',
        'ob_marge_hldp' => 'ob marge hldp',
        'ob_pv_fob_dollar' => 'ob pv fob dollar',
        'ob_pv_fob' => 'ob pv fob',
        'ob_pv_hldp_dollar' => 'ob pv hldp dollar',
        'ob_pv_hldp' => 'ob pv hldp',
        'pv_lf_orange' => 'pv lf orange',
        'produit_specifique' => 'produit specifique',
        'rev_marge_hldp' => 'rev marge hldp',
        'rev_pv_fob_dollar' => 'rev pv fob dollar',
        'rev_pv_fob' => 'rev pv fob',
        'rev_pv_hldp_dollar' => 'rev pv hldp dollar',
        'rev_pv_hldp' => 'rev pv hldp',
        'gev_marge_hldp' => 'gev marge hldp',
        'gev_pv_fob_dollar' => 'gev pv fob dollar',
        'gev_pv_fob' => 'gev pv fob',
        'gev_pv_hldp_dollar' => 'gev pv hldp dollar',
        'gev_pv_hldp' => 'gev pv hldp',
        'gev_pv_hldp2' => 'gev pv hldp2',
        'gev_pv_hldp3' => 'gev pv hldp3',
        'cha_marge_hldp' => 'cha marge hldp',
        'cha_pv_fob_dollar' => 'cha pv fob dollar',
        'cha_pv_fob' => 'cha pv fob',
        'cha_pv_hldp_dollar' => 'cha pv hldp dollar',
        'cha_pv_hldp' => 'cha pv hldp',
        'cha_pv_hldp2' => 'cha pv hldp2',
        'kin_marge_hldp' => 'kin marge hldp',
        'kin_pv_fob_dollar' => 'kin pv fob dollar',
        'kin_pv_fob' => 'kin pv fob',
        'kin_pv_hldp_dollar' => 'kin pv hldp dollar',
        'kin_pv_hldp' => 'kin pv hldp',
        'kin_pv_hldp2' => 'kin pv hldp2',
        'fit_marge_hldp' => 'fit marge hldp',
        'fit_pv_fob_dollar' => 'fit pv fob dollar',
        'fit_pv_fob' => 'fit pv fob',
        'fit_pv_hldp_dollar' => 'fit pv hldp dollar',
        'fit_pv_hldp' => 'fit pv hldp',
        'fit_pv_hldp2' => 'fit pv hldp2',
        'lf_pv_public' => 'lf pv public',
        'lf_pv_public_dollar' => 'lf pv public dollar',
        'lf_reduction' => 'lf reduction',
        'lf_pv_revendeur' => 'lf pv revendeur',
        'lf_pv_revendeur_dollar' => 'lf pv revendeur dollar',
        'date_commande' => 'date commande',
        'produit_fr' => 'produit fr',
        'produits_fr' => 'produits fr',
        'produits_en' => 'produits en',
        'unit_price' => 'unit price',
        'total_price' => 'total price',
        'numero_commande' => 'numero commande',
        'm3_u' => 'm3 u',
        'kgs_u' => 'kgs u',
        'facture_lf' => 'facture lf',
        'commande_en_cours' => 'commande en cours',
        'simulation_date' => 'simulation date',
        'simulation_date_2' => 'simulation date 2',
        'total_amount' => 'total amount',
        'packing_details' => 'packing details',
        'm3_unit' => 'm3 unit',
        'poids_unit' => 'poids unit',
        'units_20' => 'units 20',
        'units_40' => 'units 40',
        'units_40hq' => 'units 40hq',
        'fournisseur_nom1' => 'fournisseur nom1',
        'fournisseur_nom2' => 'fournisseur nom2',
        'livre_le' => 'livre le',
        'nb_produits_defec' => 'nb produits defec',
        'date_notif' => 'date notif',
        'demande_remboursement' => 'demande remboursement',
        'montant_rembourse' => 'montant rembourse',
        'avoir_lf' => 'avoir lf',
        'date_remboursement' => 'date remboursement',
        'reference_fournisseur' => 'reference fournisseur',
        'libelle_origine' => 'libelle origine',
        'pmp_achat_dollar' => 'pmp achat dollar',
        'pmp_achat_euro' => 'pmp achat euro',
        'paht_frais' => 'paht frais',
        'pv_public_ht' => 'pv public ht',
        'marge_prix_public' => 'marge prix public',
        'pv_public_ttc' => 'pv public ttc',
        'prix_pro' => 'prix pro',
        'remise_club' => 'remise club',
        'marge_prix_club' => 'marge prix club',
        'prix_franchise' => 'prix franchise',
        'remise_franchise' => 'remise franchise',
        'marge_franchise' => 'marge franchise',
        'poids_net' => 'poids net',
        'famille_produit' => 'famille produit',
        'code_compta' => 'code compta',
        'code_ean' => 'code ean',
        'date_arrivee' => 'date arrivee',
        'product_fr' => 'product fr',
        'date_reception' => 'date reception',
        'devis_id' => 'devis',
        'commande_has_article_commande_id' => 'commande has article commande',
        'commande_has_article_article_id' => 'commande has article article',
        'pourcentage_rembourse' => 'pourcentage rembourse',
        'poids_max' => 'poids max',
        'volume_max' => 'volume max',
    ],
    'urlTransformerIf' => function ($c) {
            return (false !== strpos($c, 'url_'));
        },
];
