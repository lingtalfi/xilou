<?php


use Crud\CrudModule;

$form = CrudModule::getForm("zilu.fournisseur", ['id']);



$form->labels = [
    "id" => "id",
    "nom" => "nom",
];


$form->title = "Fournisseur";


$form->addControl("nom")->type("text")
->addConstraint("required");


$form->display();
