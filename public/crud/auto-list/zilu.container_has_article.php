<?php

use Crud\CrudHelper;
use Crud\CrudModule;

$fields = '
c.container_id,
zi.nom as container_nom,
c.article_id,
z.reference_lf as article_reference_lf
';


$query = "select
%s
from zilu.container_has_article c
inner join zilu.article z on z.id=c.article_id
inner join zilu.container zi on zi.id=c.container_id
";


$table = CrudModule::getDataTable("zilu.container_has_article", $query, $fields, ['container_id', 'article_id']);

$table->title = "Container has article";


$table->columnLabels= [
    "container_nom" => "container",
    "article_reference_lf" => "article",
];


$table->hiddenColumns = [
    "container_id",
    "article_id",
];


$table->setTransformer('container_nom', function ($v, array $item) {
    return '<a href="' . CrudHelper::getUpdateFormUrl('zilu.container', $item['container_id']) . '">' . $v . '</a>';
});

$table->setTransformer('article_reference_lf', function ($v, array $item) {
    return '<a href="' . CrudHelper::getUpdateFormUrl('zilu.article', $item['article_id']) . '">' . $v . '</a>';
});




$table->displayTable();
