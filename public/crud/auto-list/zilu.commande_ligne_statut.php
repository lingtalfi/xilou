<?php

use Crud\CrudHelper;
use Crud\CrudModule;

$fields = '
id,
nom
';


$query = "select
%s
from zilu.commande_ligne_statut
";


$table = CrudModule::getDataTable("zilu.commande_ligne_statut", $query, $fields, ['id']);

$table->title = "Commande ligne statut";


$table->columnLabels= [
    "id" => "id",
    "nom" => "nom",
];


$table->hiddenColumns = [
    "id",
];


$table->displayTable();
