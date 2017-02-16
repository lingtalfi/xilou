<?php

use Crud\CrudHelper;
use Crud\CrudModule;

$fields = '
id,
reference,
reference_fournisseur,
fournisseur,
produits,
libelle_origine,
unit,
pmp_achat_dollar,
pmp_achat_euro,
port,
paht_frais,
pv_public_ht,
marge_prix_public,
pv_public_ttc,
prix_pro,
remise_club,
marge_prix_club,
prix_franchise,
remise_franchise,
marge_franchise,
poids_net,
poids,
famille_produit,
dimensions,
code_compta,
description,
photos,
tva,
code_ean,
date_arrivee,
m3
';


$query = "select
%s
from zilu.csv_prix_materiel
";


$table = CrudModule::getDataTable("zilu.csv_prix_materiel", $query, $fields, ['id']);

$table->title = "Csv prix materiel";


$table->columnLabels= [
    "id" => "id",
    "reference" => "reference",
    "reference_fournisseur" => "reference fournisseur",
    "fournisseur" => "fournisseur",
    "produits" => "produits",
    "libelle_origine" => "libelle origine",
    "unit" => "unit",
    "pmp_achat_dollar" => "pmp achat dollar",
    "pmp_achat_euro" => "pmp achat euro",
    "port" => "port",
    "paht_frais" => "paht frais",
    "pv_public_ht" => "pv public ht",
    "marge_prix_public" => "marge prix public",
    "pv_public_ttc" => "pv public ttc",
    "prix_pro" => "prix pro",
    "remise_club" => "remise club",
    "marge_prix_club" => "marge prix club",
    "prix_franchise" => "prix franchise",
    "remise_franchise" => "remise franchise",
    "marge_franchise" => "marge franchise",
    "poids_net" => "poids net",
    "poids" => "poids",
    "famille_produit" => "famille produit",
    "dimensions" => "dimensions",
    "code_compta" => "code compta",
    "description" => "description",
    "photos" => "photos",
    "tva" => "tva",
    "code_ean" => "code ean",
    "date_arrivee" => "date arrivee",
    "m3" => "m3",
];


$table->hiddenColumns = [
    "id",
];


$n = 30;
$table->setTransformer('description', function ($v) use ($n) {
    return substr($v, 0, $n) . '...';
});


$table->displayTable();
