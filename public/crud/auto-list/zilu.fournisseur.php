<?php

use Crud\CrudHelper;
use Crud\CrudModule;

$fields = '
id,
nom,
email
';


$query = "select
%s
from zilu.fournisseur
";


$table = CrudModule::getDataTable("zilu.fournisseur", $query, $fields, ['id']);

$table->title = "Fournisseur";


$table->columnLabels= [
    "id" => "id",
    "nom" => "nom",
    "email" => "email",
];


$table->hiddenColumns = [
    "id",
];


$table->displayTable();
