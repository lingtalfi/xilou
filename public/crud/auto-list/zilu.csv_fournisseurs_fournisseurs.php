<?php

use Crud\CrudHelper;
use Crud\CrudModule;

$fields = '
id,
fournisseur,
ref_hldp,
ref,
produits_fr,
produits_en,
moq,
details,
client,
quantity,
unit,
unit_price,
total_amount,
packing_details,
m3,
poids,
m3_unit,
poids_unit,
units_20,
units_40,
units_40hq,
lf,
reference,
champ1,
champ2,
champ3,
champ4,
fournisseur_nom1,
fournisseur_nom2
';


$query = "select
%s
from zilu.csv_fournisseurs_fournisseurs
";


$table = CrudModule::getDataTable("zilu.csv_fournisseurs_fournisseurs", $query, $fields, ['id']);

$table->title = "Csv fournisseurs fournisseurs";


$table->columnLabels= [
    "id" => "id",
    "fournisseur" => "fournisseur",
    "ref_hldp" => "ref hldp",
    "ref" => "ref",
    "produits_fr" => "produits fr",
    "produits_en" => "produits en",
    "moq" => "moq",
    "details" => "details",
    "client" => "client",
    "quantity" => "quantity",
    "unit" => "unit",
    "unit_price" => "unit price",
    "total_amount" => "total amount",
    "packing_details" => "packing details",
    "m3" => "m3",
    "poids" => "poids",
    "m3_unit" => "m3 unit",
    "poids_unit" => "poids unit",
    "units_20" => "units 20",
    "units_40" => "units 40",
    "units_40hq" => "units 40hq",
    "lf" => "lf",
    "reference" => "reference",
    "champ1" => "champ1",
    "champ2" => "champ2",
    "champ3" => "champ3",
    "champ4" => "champ4",
    "fournisseur_nom1" => "fournisseur nom1",
    "fournisseur_nom2" => "fournisseur nom2",
];


$table->hiddenColumns = [
    "id",
];


$n = 30;
$table->setTransformer('produits_fr', function ($v) use ($n) {
    return substr($v, 0, $n) . '...';
});
$table->setTransformer('produits_en', function ($v) use ($n) {
    return substr($v, 0, $n) . '...';
});
$table->setTransformer('details', function ($v) use ($n) {
    return substr($v, 0, $n) . '...';
});
$table->setTransformer('packing_details', function ($v) use ($n) {
    return substr($v, 0, $n) . '...';
});
$table->setTransformer('champ1', function ($v) use ($n) {
    return substr($v, 0, $n) . '...';
});
$table->setTransformer('champ2', function ($v) use ($n) {
    return substr($v, 0, $n) . '...';
});
$table->setTransformer('champ3', function ($v) use ($n) {
    return substr($v, 0, $n) . '...';
});
$table->setTransformer('champ4', function ($v) use ($n) {
    return substr($v, 0, $n) . '...';
});


$table->displayTable();
