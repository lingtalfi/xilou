<?php
$prefs = [
    'actionColumnsPosition' => 'right',
    'prettyTableNames' => [
        'zilu.commande_has_article' => 'commande has article',
        'zilu.fournisseur_has_article' => 'fournisseur has article',
    ],
    'foreignKeyPrettierColumns' => [
        'zilu.article' => 'reference_lf',
        'zilu.commande' => 'reference',
        'zilu.container' => 'nom',
        'zilu.fournisseur' => 'nom',
    ],
    'prettyColumnNames' => [
        'reference_lf' => 'reference lf',
        'reference_hldp' => 'reference hldp',
        'descr_fr' => 'descr fr',
        'descr_en' => 'descr en',
        'commande_id' => 'commande',
        'article_id' => 'article',
        'container_id' => 'container',
        'fournisseur_id' => 'fournisseur',
    ],
    'urlTransformerIf' => function ($c) {
            return (false !== strpos($c, 'url_'));
        },
];
