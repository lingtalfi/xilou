<?php

use Crud\CrudHelper;
use Crud\CrudModule;

$fields = '
id,
date_commande,
container,
produit_fr,
reference,
produits_fr,
produits_en,
details,
quantity,
unit,
unit_price,
total_price,
m3,
poids,
client,
ref_hldp,
ref_lf,
numero_commande,
m3_u,
kgs_u,
facture_lf,
commande_en_cours,
note,
livraison,
simulation_date,
simulation_date_2
';


$query = "select
%s
from zilu.csv_fournisseurs_containers
";


$table = CrudModule::getDataTable("zilu.csv_fournisseurs_containers", $query, $fields, ['id']);

$table->title = "Csv fournisseurs containers";


$table->columnLabels= [
    "id" => "id",
    "date_commande" => "date commande",
    "container" => "container",
    "produit_fr" => "produit fr",
    "reference" => "reference",
    "produits_fr" => "produits fr",
    "produits_en" => "produits en",
    "details" => "details",
    "quantity" => "quantity",
    "unit" => "unit",
    "unit_price" => "unit price",
    "total_price" => "total price",
    "m3" => "m3",
    "poids" => "poids",
    "client" => "client",
    "ref_hldp" => "ref hldp",
    "ref_lf" => "ref lf",
    "numero_commande" => "numero commande",
    "m3_u" => "m3 u",
    "kgs_u" => "kgs u",
    "facture_lf" => "facture lf",
    "commande_en_cours" => "commande en cours",
    "note" => "note",
    "livraison" => "livraison",
    "simulation_date" => "simulation date",
    "simulation_date_2" => "simulation date 2",
];


$table->hiddenColumns = [
    "id",
];


$n = 30;
$table->setTransformer('produit_fr', function ($v) use ($n) {
    return substr($v, 0, $n) . '...';
});
$table->setTransformer('produits_fr', function ($v) use ($n) {
    return substr($v, 0, $n) . '...';
});
$table->setTransformer('produits_en', function ($v) use ($n) {
    return substr($v, 0, $n) . '...';
});
$table->setTransformer('details', function ($v) use ($n) {
    return substr($v, 0, $n) . '...';
});
$table->setTransformer('note', function ($v) use ($n) {
    return substr($v, 0, $n) . '...';
});
$table->setTransformer('livraison', function ($v) use ($n) {
    return substr($v, 0, $n) . '...';
});


$table->displayTable();
