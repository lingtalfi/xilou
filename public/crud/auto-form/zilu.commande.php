<?php


use Crud\CrudModule;

$form = CrudModule::getForm("zilu.commande", ['id']);



$form->labels = [
    "id" => "id",
    "reference" => "reference",
];


$form->title = "Commande";


$form->addControl("reference")->type("text")
->addConstraint("required");


$form->display();
