<?php

use Crud\CrudHelper;
use Crud\CrudModule;

$fields = '
d.devis_id,
de.reference as devis_reference,
d.commande_has_article_commande_id,
co.unit as commande_has_article_unit,
d.commande_has_article_article_id,
co.unit as commande_has_article_unit
';


$query = "select
%s
from zilu.devis_has_commande_has_article d
inner join zilu.commande_has_article co on co.commande_id=d.commande_has_article_commande_id
inner join zilu.commande_has_article co on co.article_id=d.commande_has_article_article_id
inner join zilu.devis de on de.id=d.devis_id
";


$table = CrudModule::getDataTable("zilu.devis_has_commande_has_article", $query, $fields, ['devis_id', 'commande_has_article_commande_id', 'commande_has_article_article_id']);

$table->title = "Devis has commande has article";


$table->columnLabels= [
    "devis_reference" => "devis",
    "commande_has_article_unit" => "commande has article article",
];


$table->hiddenColumns = [
    "devis_id",
    "commande_has_article_commande_id",
    "commande_has_article_article_id",
];


$table->setTransformer('devis_reference', function ($v, array $item) {
    return '<a href="' . CrudHelper::getUpdateFormUrl('zilu.devis', $item['devis_id']) . '">' . $v . '</a>';
});

$table->setTransformer('commande_has_article_unit', function ($v, array $item) {
    return '<a href="' . CrudHelper::getUpdateFormUrl('zilu.commande_has_article', $item['commande_has_article_commande_id']) . '">' . $v . '</a>';
});

$table->setTransformer('commande_has_article_unit', function ($v, array $item) {
    return '<a href="' . CrudHelper::getUpdateFormUrl('zilu.commande_has_article', $item['commande_has_article_article_id']) . '">' . $v . '</a>';
});




$table->displayTable();
