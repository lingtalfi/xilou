<?php

use Crud\CrudHelper;
use Crud\CrudModule;

$fields = '
id,
ref_hldp,
ref_lf,
produits
';


$query = "select
%s
from zilu.csv_product_list
";


$table = CrudModule::getDataTable("zilu.csv_product_list", $query, $fields, ['id']);

$table->title = "Csv product list";


$table->columnLabels= [
    "id" => "id",
    "ref_hldp" => "ref hldp",
    "ref_lf" => "ref lf",
    "produits" => "produits",
];


$table->hiddenColumns = [
    "id",
];


$n = 30;
$table->setTransformer('produits', function ($v) use ($n) {
    return substr($v, 0, $n) . '...';
});


$table->displayTable();
