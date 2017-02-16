<?php


use Crud\CrudModule;

$form = CrudModule::getForm("zilu.csv_prix_materiel", ['id']);



$form->labels = [
    "id" => "id",
    "reference" => "reference",
    "reference_fournisseur" => "reference fournisseur",
    "fournisseur" => "fournisseur",
    "produits" => "produits",
    "libelle_origine" => "libelle origine",
    "unit" => "unit",
    "pmp_achat_dollar" => "pmp achat dollar",
    "pmp_achat_euro" => "pmp achat euro",
    "port" => "port",
    "paht_frais" => "paht frais",
    "pv_public_ht" => "pv public ht",
    "marge_prix_public" => "marge prix public",
    "pv_public_ttc" => "pv public ttc",
    "prix_pro" => "prix pro",
    "remise_club" => "remise club",
    "marge_prix_club" => "marge prix club",
    "prix_franchise" => "prix franchise",
    "remise_franchise" => "remise franchise",
    "marge_franchise" => "marge franchise",
    "poids_net" => "poids net",
    "poids" => "poids",
    "famille_produit" => "famille produit",
    "dimensions" => "dimensions",
    "code_compta" => "code compta",
    "description" => "description",
    "photos" => "photos",
    "tva" => "tva",
    "code_ean" => "code ean",
    "date_arrivee" => "date arrivee",
    "m3" => "m3",
];


$form->title = "Csv prix materiel";


$form->addControl("reference")->type("text");
$form->addControl("reference_fournisseur")->type("text");
$form->addControl("fournisseur")->type("text");
$form->addControl("produits")->type("text");
$form->addControl("libelle_origine")->type("text");
$form->addControl("unit")->type("text");
$form->addControl("pmp_achat_dollar")->type("text");
$form->addControl("pmp_achat_euro")->type("text");
$form->addControl("port")->type("text");
$form->addControl("paht_frais")->type("text");
$form->addControl("pv_public_ht")->type("text");
$form->addControl("marge_prix_public")->type("text");
$form->addControl("pv_public_ttc")->type("text");
$form->addControl("prix_pro")->type("text");
$form->addControl("remise_club")->type("text");
$form->addControl("marge_prix_club")->type("text");
$form->addControl("prix_franchise")->type("text");
$form->addControl("remise_franchise")->type("text");
$form->addControl("marge_franchise")->type("text");
$form->addControl("poids_net")->type("text");
$form->addControl("poids")->type("text");
$form->addControl("famille_produit")->type("text");
$form->addControl("dimensions")->type("text");
$form->addControl("code_compta")->type("text");
$form->addControl("description")->type("message");
$form->addControl("photos")->type("text");
$form->addControl("tva")->type("text");
$form->addControl("code_ean")->type("text");
$form->addControl("date_arrivee")->type("text");
$form->addControl("m3")->type("text");


$form->display();
