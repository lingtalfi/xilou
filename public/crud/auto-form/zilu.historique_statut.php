<?php


use Crud\CrudModule;

$form = CrudModule::getForm("zilu.historique_statut", ['id']);



$form->labels = [
    "id" => "id",
    "date" => "date",
    "statut_nom" => "statut nom",
    "reference_lf" => "reference lf",
    "fournisseur_nom" => "fournisseur nom",
    "reference_fournisseur" => "reference fournisseur",
    "commande_nom" => "commande nom",
];


$form->title = "Historique statut";


$form->addControl("date")->type("date6");
$form->addControl("statut_nom")->type("text");
$form->addControl("reference_lf")->type("text");
$form->addControl("fournisseur_nom")->type("text");
$form->addControl("reference_fournisseur")->type("text");
$form->addControl("commande_nom")->type("text");


$form->display();
