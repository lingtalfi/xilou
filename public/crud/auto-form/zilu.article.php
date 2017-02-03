<?php


use Crud\CrudModule;

$form = CrudModule::getForm("zilu.article", ['id']);



$form->labels = [
    "id" => "id",
    "reference_lf" => "reference lf",
    "reference_hldp" => "reference hldp",
    "prix" => "prix",
    "poids" => "poids",
    "descr_fr" => "descr fr",
    "descr_en" => "descr en",
];


$form->title = "Article";


$form->addControl("reference_lf")->type("text")
->addConstraint("required");
$form->addControl("reference_hldp")->type("text");
$form->addControl("prix")->type("text");
$form->addControl("poids")->type("text");
$form->addControl("descr_fr")->type("message");
$form->addControl("descr_en")->type("message");


$form->display();
