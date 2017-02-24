<?php


use Crud\CrudModule;

$form = CrudModule::getForm("zilu.devis", ['id']);



$form->labels = [
    "id" => "id",
    "reference" => "reference",
    "date_reception" => "date reception",
    "fournisseur_id" => "fournisseur",
];


$form->title = "Devis";


$form->addControl("reference")->type("text")
->addConstraint("required");
$form->addControl("date_reception")->type("date3");
$form->addControl("fournisseur_id")->type("selectByRequest", "select id, nom from zilu.fournisseur");


$form->display();
