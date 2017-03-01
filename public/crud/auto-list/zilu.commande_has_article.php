<?php

use Crud\CrudHelper;
use Crud\CrudModule;

$fields = '
c.id,
c.commande_id,
co.reference as commande_reference,
c.article_id,
a.reference_lf as article_reference_lf,
c.container_id,
con.nom as container_nom,
c.fournisseur_id,
f.nom as fournisseur_nom,
c.sav_id,
s.fournisseur as sav_fournisseur,
c.commande_ligne_statut_id,
com.nom as commande_ligne_statut_nom,
c.prix_override,
c.date_estimee,
c.quantite,
c.unit
';


$query = "select
%s
from zilu.commande_has_article c
inner join zilu.article a on a.id=c.article_id
inner join zilu.commande co on co.id=c.commande_id
inner join zilu.commande_ligne_statut com on com.id=c.commande_ligne_statut_id
inner join zilu.container con on con.id=c.container_id
inner join zilu.fournisseur f on f.id=c.fournisseur_id
inner join zilu.sav s on s.id=c.sav_id
";


$table = CrudModule::getDataTable("zilu.commande_has_article", $query, $fields, ['id']);

$table->title = "Commande has article";


$table->columnLabels= [
    "id" => "id",
    "commande_reference" => "commande",
    "article_reference_lf" => "article",
    "container_nom" => "container",
    "fournisseur_nom" => "fournisseur",
    "sav_fournisseur" => "sav",
    "commande_ligne_statut_nom" => "commande ligne statut",
    "prix_override" => "prix override",
    "date_estimee" => "date estimee",
    "quantite" => "quantite",
    "unit" => "unit",
];


$table->hiddenColumns = [
    "id",
    "commande_id",
    "article_id",
    "container_id",
    "fournisseur_id",
    "sav_id",
    "commande_ligne_statut_id",
];


$table->setTransformer('commande_reference', function ($v, array $item) {
    return '<a href="' . CrudHelper::getUpdateFormUrl('zilu.commande', $item['commande_id']) . '">' . $v . '</a>';
});

$table->setTransformer('article_reference_lf', function ($v, array $item) {
    return '<a href="' . CrudHelper::getUpdateFormUrl('zilu.article', $item['article_id']) . '">' . $v . '</a>';
});

$table->setTransformer('container_nom', function ($v, array $item) {
    return '<a href="' . CrudHelper::getUpdateFormUrl('zilu.container', $item['container_id']) . '">' . $v . '</a>';
});

$table->setTransformer('fournisseur_nom', function ($v, array $item) {
    return '<a href="' . CrudHelper::getUpdateFormUrl('zilu.fournisseur', $item['fournisseur_id']) . '">' . $v . '</a>';
});

$table->setTransformer('sav_fournisseur', function ($v, array $item) {
    return '<a href="' . CrudHelper::getUpdateFormUrl('zilu.sav', $item['sav_id']) . '">' . $v . '</a>';
});

$table->setTransformer('commande_ligne_statut_nom', function ($v, array $item) {
    return '<a href="' . CrudHelper::getUpdateFormUrl('zilu.commande_ligne_statut', $item['commande_ligne_statut_id']) . '">' . $v . '</a>';
});




$table->displayTable();
