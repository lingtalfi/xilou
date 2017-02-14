<?php


use Crud\CrudModule;

$form = CrudModule::getForm("zilu.commande", ['id']);



$form->labels = [
    "id" => "id",
    "reference" => "reference",
    "estimated_date" => "estimated date",
];


$form->title = "Commande";


$form->addControl("reference")->type("text")
->addConstraint("required");
$form->addControl("estimated_date")->type("date3");


$form->display();
