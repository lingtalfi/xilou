<?php

use Crud\CrudHelper;
use Crud\CrudModule;

$fields = '
id,
label,
poids_max,
volume_max
';


$query = "select
%s
from zilu.type_container
";


$table = CrudModule::getDataTable("zilu.type_container", $query, $fields, ['id']);

$table->title = "Type container";


$table->columnLabels= [
    "id" => "id",
    "label" => "label",
    "poids_max" => "poids max",
    "volume_max" => "volume max",
];


$table->hiddenColumns = [
    "id",
];


$table->displayTable();
