<?php


use Crud\CrudModule;

$form = CrudModule::getForm("zilu.article", ['id']);



$form->labels = [
    "id" => "id",
    "reference_lf" => "reference lf",
    "reference_hldp" => "reference hldp",
    "poids" => "poids",
    "descr_fr" => "descr fr",
    "descr_en" => "descr en",
    "ean" => "ean",
];


$form->title = "Article";


$form->addControl("reference_lf")->type("text")
->addConstraint("required");
$form->addControl("reference_hldp")->type("text");
$form->addControl("poids")->type("text");
$form->addControl("descr_fr")->type("message");
$form->addControl("descr_en")->type("message");
$form->addControl("ean")->type("text");


$form->display();
