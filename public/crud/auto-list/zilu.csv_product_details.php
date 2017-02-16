<?php

use Crud\CrudHelper;
use Crud\CrudModule;

$fields = '
id,
ref,
product_fr,
product,
photo,
features,
logo,
packing,
ean
';


$query = "select
%s
from zilu.csv_product_details
";


$table = CrudModule::getDataTable("zilu.csv_product_details", $query, $fields, ['id']);

$table->title = "Csv product details";


$table->columnLabels= [
    "id" => "id",
    "ref" => "ref",
    "product_fr" => "product fr",
    "product" => "product",
    "photo" => "photo",
    "features" => "features",
    "logo" => "logo",
    "packing" => "packing",
    "ean" => "ean",
];


$table->hiddenColumns = [
    "id",
];


$n = 30;
$table->setTransformer('features', function ($v) use ($n) {
    return substr($v, 0, $n) . '...';
});
$table->setTransformer('packing', function ($v) use ($n) {
    return substr($v, 0, $n) . '...';
});


$table->displayTable();
