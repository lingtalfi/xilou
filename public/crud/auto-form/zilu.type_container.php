<?php


use Crud\CrudModule;

$form = CrudModule::getForm("zilu.type_container", ['id']);



$form->labels = [
    "id" => "id",
    "label" => "label",
    "poids_max" => "poids max",
    "volume_max" => "volume max",
];


$form->title = "Type container";


$form->addControl("label")->type("text")
->addConstraint("required");
$form->addControl("poids_max")->type("text");
$form->addControl("volume_max")->type("text");


$form->display();
