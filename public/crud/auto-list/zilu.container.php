<?php

use Crud\CrudHelper;
use Crud\CrudModule;

$fields = '
c.id,
c.nom,
c.type_container_id,
t.label as type_container_label
';


$query = "select
%s
from zilu.container c
inner join zilu.type_container t on t.id=c.type_container_id
";


$table = CrudModule::getDataTable("zilu.container", $query, $fields, ['id']);

$table->title = "Container";


$table->columnLabels= [
    "id" => "id",
    "nom" => "nom",
    "type_container_label" => "type container",
];


$table->hiddenColumns = [
    "id",
    "type_container_id",
];


$table->setTransformer('type_container_label', function ($v, array $item) {
    return '<a href="' . CrudHelper::getUpdateFormUrl('zilu.type_container', $item['type_container_id']) . '">' . $v . '</a>';
});




$table->displayTable();
