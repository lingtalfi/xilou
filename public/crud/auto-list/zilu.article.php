<?php

use Crud\CrudHelper;
use Crud\CrudModule;

$fields = '
id,
reference_lf,
reference_hldp,
descr_fr,
descr_en,
ean,
photo
';


$query = "select
%s
from zilu.article
";


$table = CrudModule::getDataTable("zilu.article", $query, $fields, ['id']);

$table->title = "Article";


$table->columnLabels= [
    "id" => "id",
    "reference_lf" => "reference lf",
    "reference_hldp" => "reference hldp",
    "descr_fr" => "descr fr",
    "descr_en" => "descr en",
    "ean" => "ean",
    "photo" => "photo",
];


$table->hiddenColumns = [
    "id",
];


$n = 30;
$table->setTransformer('descr_fr', function ($v) use ($n) {
    return substr($v, 0, $n) . '...';
});
$table->setTransformer('descr_en', function ($v) use ($n) {
    return substr($v, 0, $n) . '...';
});


$table->displayTable();
