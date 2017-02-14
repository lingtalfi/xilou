<?php

use Crud\CrudHelper;
use Crud\CrudModule;

$fields = '
id,
reference,
estimated_date
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
    "estimated_date" => "estimated date",
];


$table->hiddenColumns = [
    "id",
];


$table->displayTable();
