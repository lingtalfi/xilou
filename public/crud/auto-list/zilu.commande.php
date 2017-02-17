<?php

use Crud\CrudHelper;
use Crud\CrudModule;

$fields = '
id,
reference
';


$query = "select
%s
from zilu.commande
";


$table = CrudModule::getDataTable("zilu.commande", $query, $fields, ['id']);

$table->title = "Commande";


$table->columnLabels= [
    "id" => "id",
    "reference" => "reference",
];


$table->hiddenColumns = [
    "id",
];


$table->displayTable();
