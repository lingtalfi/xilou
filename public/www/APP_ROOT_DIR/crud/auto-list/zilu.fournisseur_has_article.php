<?php

use Crud\CrudHelper;
use Crud\CrudModule;

$fields = '
f.fournisseur_id,
zi.nom as fournisseur_nom,
f.article_id,
z.reference_lf as article_reference_lf,
f.reference,
f.prix
';


$query = "select
%s
from zilu.fournisseur_has_article f
inner join zilu.article z on z.id=f.article_id
inner join zilu.fournisseur zi on zi.id=f.fournisseur_id
";


$table = CrudModule::getDataTable("zilu.fournisseur_has_article", $query, $fields, ['fournisseur_id', 'article_id']);

$table->title = "Fournisseur has article";


$table->columnLabels= [
    "fournisseur_nom" => "fournisseur",
    "article_reference_lf" => "article",
    "reference" => "reference",
    "prix" => "prix",
];


$table->hiddenColumns = [
    "fournisseur_id",
    "article_id",
];


$table->setTransformer('fournisseur_nom', function ($v, array $item) {
    return '<a href="' . CrudHelper::getUpdateFormUrl('zilu.fournisseur', $item['fournisseur_id']) . '">' . $v . '</a>';
});

$table->setTransformer('article_reference_lf', function ($v, array $item) {
    return '<a href="' . CrudHelper::getUpdateFormUrl('zilu.article', $item['article_id']) . '">' . $v . '</a>';
});




$table->displayTable();
