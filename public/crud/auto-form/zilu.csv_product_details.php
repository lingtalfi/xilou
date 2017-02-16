<?php


use Crud\CrudModule;

$form = CrudModule::getForm("zilu.csv_product_details", ['id']);



$form->labels = [
    "id" => "id",
    "ref" => "ref",
    "product_fr" => "product fr",
    "product" => "product",
    "photo" => "photo",
    "features" => "features",
    "logo" => "logo",
    "packing" => "packing",
    "ean" => "ean",
];


$form->title = "Csv product details";


$form->addControl("ref")->type("text");
$form->addControl("product_fr")->type("text");
$form->addControl("product")->type("text");
$form->addControl("photo")->type("text");
$form->addControl("features")->type("message");
$form->addControl("logo")->type("text");
$form->addControl("packing")->type("message");
$form->addControl("ean")->type("text");


$form->display();
