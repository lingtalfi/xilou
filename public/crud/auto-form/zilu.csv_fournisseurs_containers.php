<?php


use Crud\CrudModule;

$form = CrudModule::getForm("zilu.csv_fournisseurs_containers", ['id']);



$form->labels = [
    "id" => "id",
    "date_commande" => "date commande",
    "container" => "container",
    "produit_fr" => "produit fr",
    "reference" => "reference",
    "produits_fr" => "produits fr",
    "produits_en" => "produits en",
    "details" => "details",
    "quantity" => "quantity",
    "unit" => "unit",
    "unit_price" => "unit price",
    "total_price" => "total price",
    "m3" => "m3",
    "poids" => "poids",
    "client" => "client",
    "ref_hldp" => "ref hldp",
    "ref_lf" => "ref lf",
    "numero_commande" => "numero commande",
    "m3_u" => "m3 u",
    "kgs_u" => "kgs u",
    "facture_lf" => "facture lf",
    "commande_en_cours" => "commande en cours",
    "note" => "note",
    "livraison" => "livraison",
    "simulation_date" => "simulation date",
    "simulation_date_2" => "simulation date 2",
];


$form->title = "Csv fournisseurs containers";


$form->addControl("date_commande")->type("text");
$form->addControl("container")->type("text");
$form->addControl("produit_fr")->type("message");
$form->addControl("reference")->type("text");
$form->addControl("produits_fr")->type("message");
$form->addControl("produits_en")->type("message");
$form->addControl("details")->type("message");
$form->addControl("quantity")->type("text");
$form->addControl("unit")->type("text");
$form->addControl("unit_price")->type("text");
$form->addControl("total_price")->type("text");
$form->addControl("m3")->type("text");
$form->addControl("poids")->type("text");
$form->addControl("client")->type("text");
$form->addControl("ref_hldp")->type("text");
$form->addControl("ref_lf")->type("text");
$form->addControl("numero_commande")->type("text");
$form->addControl("m3_u")->type("text");
$form->addControl("kgs_u")->type("text");
$form->addControl("facture_lf")->type("text");
$form->addControl("commande_en_cours")->type("text");
$form->addControl("note")->type("message");
$form->addControl("livraison")->type("message");
$form->addControl("simulation_date")->type("text");
$form->addControl("simulation_date_2")->type("text");


$form->display();
