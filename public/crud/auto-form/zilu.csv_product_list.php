<?php


use Crud\CrudModule;

$form = CrudModule::getForm("zilu.csv_product_list", ['id']);



$form->labels = [
    "id" => "id",
    "ref_hldp" => "ref hldp",
    "ref_lf" => "ref lf",
    "produits" => "produits",
];


$form->title = "Csv product list";


$form->addControl("ref_hldp")->type("text");
$form->addControl("ref_lf")->type("text");
$form->addControl("produits")->type("message");


$form->display();
