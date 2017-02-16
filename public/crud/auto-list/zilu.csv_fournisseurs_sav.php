<?php

use Crud\CrudHelper;
use Crud\CrudModule;

$fields = '
id,
fournisseur,
reference_lf,
produit,
livre_le,
quantite,
prix,
nb_produits_defec,
date_notif,
demande_remboursement,
montant_rembourse,
remboursement,
forme,
statut,
avoir_lf,
date_remboursement,
problemes,
avancement
';


$query = "select
%s
from zilu.csv_fournisseurs_sav
";


$table = CrudModule::getDataTable("zilu.csv_fournisseurs_sav", $query, $fields, ['id']);

$table->title = "Csv fournisseurs sav";


$table->columnLabels= [
    "id" => "id",
    "fournisseur" => "fournisseur",
    "reference_lf" => "reference lf",
    "produit" => "produit",
    "livre_le" => "livre le",
    "quantite" => "quantite",
    "prix" => "prix",
    "nb_produits_defec" => "nb produits defec",
    "date_notif" => "date notif",
    "demande_remboursement" => "demande remboursement",
    "montant_rembourse" => "montant rembourse",
    "remboursement" => "remboursement",
    "forme" => "forme",
    "statut" => "statut",
    "avoir_lf" => "avoir lf",
    "date_remboursement" => "date remboursement",
    "problemes" => "problemes",
    "avancement" => "avancement",
];


$table->hiddenColumns = [
    "id",
];


$n = 30;
$table->setTransformer('date_remboursement', function ($v) use ($n) {
    return substr($v, 0, $n) . '...';
});
$table->setTransformer('problemes', function ($v) use ($n) {
    return substr($v, 0, $n) . '...';
});
$table->setTransformer('avancement', function ($v) use ($n) {
    return substr($v, 0, $n) . '...';
});


$table->displayTable();
