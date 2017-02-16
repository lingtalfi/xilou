<?php


use Crud\CrudModule;

$form = CrudModule::getForm("zilu.csv_fournisseurs_fournisseurs", ['id']);



$form->labels = [
    "id" => "id",
    "fournisseur" => "fournisseur",
    "ref_hldp" => "ref hldp",
    "ref" => "ref",
    "produits_fr" => "produits fr",
    "produits_en" => "produits en",
    "moq" => "moq",
    "details" => "details",
    "client" => "client",
    "quantity" => "quantity",
    "unit" => "unit",
    "unit_price" => "unit price",
    "total_amount" => "total amount",
    "packing_details" => "packing details",
    "m3" => "m3",
    "poids" => "poids",
    "m3_unit" => "m3 unit",
    "poids_unit" => "poids unit",
    "units_20" => "units 20",
    "units_40" => "units 40",
    "units_40hq" => "units 40hq",
    "lf" => "lf",
    "reference" => "reference",
    "champ1" => "champ1",
    "champ2" => "champ2",
    "champ3" => "champ3",
    "champ4" => "champ4",
    "fournisseur_nom1" => "fournisseur nom1",
    "fournisseur_nom2" => "fournisseur nom2",
];


$form->title = "Csv fournisseurs fournisseurs";


$form->addControl("fournisseur")->type("text");
$form->addControl("ref_hldp")->type("text");
$form->addControl("ref")->type("text");
$form->addControl("produits_fr")->type("message");
$form->addControl("produits_en")->type("message");
$form->addControl("moq")->type("text");
$form->addControl("details")->type("message");
$form->addControl("client")->type("text");
$form->addControl("quantity")->type("text");
$form->addControl("unit")->type("text");
$form->addControl("unit_price")->type("text");
$form->addControl("total_amount")->type("text");
$form->addControl("packing_details")->type("message");
$form->addControl("m3")->type("text");
$form->addControl("poids")->type("text");
$form->addControl("m3_unit")->type("text");
$form->addControl("poids_unit")->type("text");
$form->addControl("units_20")->type("text");
$form->addControl("units_40")->type("text");
$form->addControl("units_40hq")->type("text");
$form->addControl("lf")->type("text");
$form->addControl("reference")->type("text");
$form->addControl("champ1")->type("message");
$form->addControl("champ2")->type("message");
$form->addControl("champ3")->type("message");
$form->addControl("champ4")->type("message");
$form->addControl("fournisseur_nom1")->type("text");
$form->addControl("fournisseur_nom2")->type("text");


$form->display();
