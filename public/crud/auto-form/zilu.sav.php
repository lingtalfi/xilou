<?php


use Crud\CrudModule;

$form = CrudModule::getForm("zilu.sav", ['id']);



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
    "pourcentage_rembourse" => "pourcentage rembourse",
    "date_remboursement" => "date remboursement",
    "forme" => "forme",
    "statut" => "statut",
    "photo" => "photo",
    "avancement" => "avancement",
];


$form->title = "Sav";


$form->addControl("fournisseur")->type("text")
->addConstraint("required");
$form->addControl("reference_lf")->type("text");
$form->addControl("produit")->type("text");
$form->addControl("livre_le")->type("date3");
$form->addControl("quantite")->type("text")
->value(0);
$form->addControl("prix")->type("text");
$form->addControl("nb_produits_defec")->type("text")
->value(0);
$form->addControl("date_notif")->type("date3");
$form->addControl("demande_remboursement")->type("text");
$form->addControl("montant_rembourse")->type("text");
$form->addControl("pourcentage_rembourse")->type("text")
->value(0);
$form->addControl("date_remboursement")->type("date3");
$form->addControl("forme")->type("text");
$form->addControl("statut")->type("message");
$form->addControl("photo")->type("text");
$form->addControl("avancement")->type("text");


$form->display();
