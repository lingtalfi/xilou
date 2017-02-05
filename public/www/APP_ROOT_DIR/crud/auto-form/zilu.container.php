<?php


use Crud\CrudModule;

$form = CrudModule::getForm("zilu.container", ['id']);



$form->labels = [
    "id" => "id",
    "nom" => "nom",
    "type_container_id" => "type container",
];


$form->title = "Container";


$form->addControl("nom")->type("text")
->addConstraint("required");
$form->addControl("type_container_id")->type("selectByRequest", "select id, label from zilu.type_container");


$form->display();
