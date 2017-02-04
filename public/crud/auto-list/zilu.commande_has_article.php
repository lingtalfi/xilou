<?php

use Crud\CrudHelper;
use Crud\CrudModule;

$fields = '
c.commande_id,
zi.reference as commande_reference,
c.article_id,
z.reference_lf as article_reference_lf,
c.container_id,
zil.nom as container_nom,
c.fournisseur_id,
zilu.nom as fournisseur_nom
';


$query = "select
%s
from zilu.commande_has_article c
inner join zilu.article z on z.id=c.article_id
inner join zilu.commande zi on zi.id=c.commande_id
inner join zilu.container zil on zil.id=c.container_id
inner join zilu.fournisseur zilu on zilu.id=c.fournisseur_id
";


$table = CrudModule::getDataTable("zilu.commande_has_article", $query, $fields, ['commande_id', 'article_id']);

$table->title = "Commande has article";


$table->columnLabels= [
    "commande_reference" => "commande",
    "article_reference_lf" => "article",
    "container_nom" => "container",
    "fournisseur_nom" => "fournisseur",
];


$table->hiddenColumns = [
    "commande_id",
    "article_id",
    "container_id",
    "fournisseur_id",
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




$table->displayTable();
