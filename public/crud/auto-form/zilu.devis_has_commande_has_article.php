<?php


use Crud\CrudModule;

$form = CrudModule::getForm("zilu.devis_has_commande_has_article", ['devis_id', 'commande_has_article_commande_id', 'commande_has_article_article_id']);



$form->labels = [
    "devis_id" => "devis",
    "commande_has_article_commande_id" => "commande has article commande",
    "commande_has_article_article_id" => "commande has article article",
];


$form->title = "Devis has commande has article";


$form->addControl("devis_id")->type("selectByRequest", "select id, reference from zilu.devis");
$form->addControl("commande_has_article_commande_id")->type("selectByRequest", "select commande_id, unit from zilu.commande_has_article");
$form->addControl("commande_has_article_article_id")->type("selectByRequest", "select article_id, unit from zilu.commande_has_article");


$form->display();
