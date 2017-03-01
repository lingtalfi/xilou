<?php


use Crud\CrudModule;

$form = CrudModule::getForm("zilu.devis_has_commande_has_article", ['devis_id', 'commande_has_article_id']);



$form->labels = [
    "devis_id" => "devis",
    "commande_has_article_id" => "commande has article",
];


$form->title = "Devis has commande has article";


$form->addControl("devis_id")->type("selectByRequest", "select id, reference from zilu.devis");
$form->addControl("commande_has_article_id")->type("selectByRequest", "select id, unit from zilu.commande_has_article");


$form->display();
