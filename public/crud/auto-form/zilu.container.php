<?php


use Crud\CrudModule;

$form = CrudModule::getForm("zilu.container", ['id']);



$form->labels = [
    "id" => "id",
    "nom" => "nom",
];


$form->title = "Container";


$form->addControl("nom")->type("text")
->addConstraint("required");


$form->display();
