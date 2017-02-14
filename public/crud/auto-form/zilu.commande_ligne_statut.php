<?php


use Crud\CrudModule;

$form = CrudModule::getForm("zilu.commande_ligne_statut", ['id']);



$form->labels = [
    "id" => "id",
    "nom" => "nom",
];


$form->title = "Commande ligne statut";


$form->addControl("nom")->type("text")
->addConstraint("required");


$form->display();
