<?php

use Crud\CrudHelper;
use Crud\CrudModule;

$fields = '
id,
date,
statut_nom,
reference_lf,
fournisseur_nom,
reference_fournisseur,
commande_reference,
commentaire,
commande_has_article_id
';


$query = "select
%s
from zilu.historique_statut
";


$table = CrudModule::getDataTable("zilu.historique_statut", $query, $fields, ['id']);

$table->title = "Historique statut";


$table->columnLabels= [
    "id" => "id",
    "date" => "date",
    "statut_nom" => "statut nom",
    "reference_lf" => "reference lf",
    "fournisseur_nom" => "fournisseur nom",
    "reference_fournisseur" => "reference fournisseur",
    "commande_reference" => "commande reference",
    "commentaire" => "commentaire",
    "commande_has_article_id" => "commande has article",
];


$table->hiddenColumns = [
    "id",
];


$n = 30;
$table->setTransformer('commentaire', function ($v) use ($n) {
    return substr($v, 0, $n) . '...';
});


$table->displayTable();
