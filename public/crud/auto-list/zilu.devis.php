<?php

use Crud\CrudHelper;
use Crud\CrudModule;

$fields = '
d.id,
d.reference,
d.date_reception,
d.fournisseur_id,
f.nom as fournisseur_nom
';


$query = "select
%s
from zilu.devis d
inner join zilu.fournisseur f on f.id=d.fournisseur_id
";


$table = CrudModule::getDataTable("zilu.devis", $query, $fields, ['id']);

$table->title = "Devis";


$table->columnLabels= [
    "id" => "id",
    "reference" => "reference",
    "date_reception" => "date reception",
    "fournisseur_nom" => "fournisseur",
];


$table->hiddenColumns = [
    "id",
    "fournisseur_id",
];


$table->setTransformer('fournisseur_nom', function ($v, array $item) {
    return '<a href="' . CrudHelper::getUpdateFormUrl('zilu.fournisseur', $item['fournisseur_id']) . '">' . $v . '</a>';
});




$table->displayTable();
