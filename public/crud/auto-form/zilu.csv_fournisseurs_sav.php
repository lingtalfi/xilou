<?php


use Crud\CrudModule;

$form = CrudModule::getForm("zilu.csv_fournisseurs_sav", ['id']);



$form->labels = [
    "id" => "id",
    "fournisseur" => "fournisseur",
    "reference_lf" => "reference lf",
    "produit" => "produit",
    "livre_le" => "livre le",
    "quantite" => "quantite",
    "prix" => "prix",
    "nb_produits_defec" => "nb produits defec",
    "date_notif" => "date notif",
    "demande_remboursement" => "demande remboursement",
    "montant_rembourse" => "montant rembourse",
    "remboursement" => "remboursement",
    "forme" => "forme",
    "statut" => "statut",
    "avoir_lf" => "avoir lf",
    "date_remboursement" => "date remboursement",
    "problemes" => "problemes",
    "avancement" => "avancement",
];


$form->title = "Csv fournisseurs sav";


$form->addControl("fournisseur")->type("text");
$form->addControl("reference_lf")->type("text");
$form->addControl("produit")->type("text");
$form->addControl("livre_le")->type("text");
$form->addControl("quantite")->type("text");
$form->addControl("prix")->type("text");
$form->addControl("nb_produits_defec")->type("text");
$form->addControl("date_notif")->type("text");
$form->addControl("demande_remboursement")->type("text");
$form->addControl("montant_rembourse")->type("text");
$form->addControl("remboursement")->type("text");
$form->addControl("forme")->type("text");
$form->addControl("statut")->type("text");
$form->addControl("avoir_lf")->type("text");
$form->addControl("date_remboursement")->type("message");
$form->addControl("problemes")->type("message");
$form->addControl("avancement")->type("message");


$form->display();
