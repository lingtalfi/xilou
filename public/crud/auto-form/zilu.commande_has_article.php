<?php


use Crud\CrudModule;

$form = CrudModule::getForm("zilu.commande_has_article", ['id']);



$form->labels = [
    "id" => "id",
    "commande_id" => "commande",
    "article_id" => "article",
    "container_id" => "container",
    "fournisseur_id" => "fournisseur",
    "sav_id" => "sav",
    "commande_ligne_statut_id" => "commande ligne statut",
    "prix_override" => "prix override",
    "date_estimee" => "date estimee",
    "quantite" => "quantite",
    "unit" => "unit",
];


$form->title = "Commande has article";


$form->addControl("commande_id")->type("selectByRequest", "select id, reference from zilu.commande");
$form->addControl("article_id")->type("selectByRequest", "select id, reference_lf from zilu.article");
$form->addControl("container_id")->type("selectByRequest", "select id, nom from zilu.container");
$form->addControl("fournisseur_id")->type("selectByRequest", "select id, nom from zilu.fournisseur");
$form->addControl("sav_id")->type("selectByRequest", "select id, fournisseur from zilu.sav");
$form->addControl("commande_ligne_statut_id")->type("selectByRequest", "select id, nom from zilu.commande_ligne_statut");
$form->addControl("prix_override")->type("text");
$form->addControl("date_estimee")->type("date3");
$form->addControl("quantite")->type("text")
->value(0);
$form->addControl("unit")->type("text")
->addConstraint("required");


$form->display();
