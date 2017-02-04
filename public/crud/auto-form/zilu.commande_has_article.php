<?php


use Crud\CrudModule;

$form = CrudModule::getForm("zilu.commande_has_article", ['commande_id', 'article_id']);



$form->labels = [
    "commande_id" => "commande",
    "article_id" => "article",
    "container_id" => "container",
    "fournisseur_id" => "fournisseur",
];


$form->title = "Commande has article";


$form->addControl("commande_id")->type("selectByRequest", "select id, reference from zilu.commande");
$form->addControl("article_id")->type("selectByRequest", "select id, reference_lf from zilu.article");
$form->addControl("container_id")->type("selectByRequest", "select id, nom from zilu.container");
$form->addControl("fournisseur_id")->type("selectByRequest", "select id, nom from zilu.fournisseur");


$form->display();