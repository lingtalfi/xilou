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
pourcentage_rembourse,
date_remboursement,
forme,
statut,
photo,
avancement
';


$query = "select
%s
from zilu.sav
";


$table = CrudModule::getDataTable("zilu.sav", $query, $fields, ['id']);

$table->title = "Sav";


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
    "pourcentage_rembourse" => "pourcentage rembourse",
    "date_remboursement" => "date remboursement",
    "forme" => "forme",
    "statut" => "statut",
    "photo" => "photo",
    "avancement" => "avancement",
];


$table->hiddenColumns = [
    "id",
];


$n = 30;
$table->setTransformer('statut', function ($v) use ($n) {
    return substr($v, 0, $n) . '...';
});


$table->displayTable();
