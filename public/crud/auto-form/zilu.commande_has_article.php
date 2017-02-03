<?php


use Crud\CrudModule;

$form = CrudModule::getForm("zilu.commande_has_article", ['commande_id', 'article_id']);



$form->labels = [
    "commande_id" => "commande",
    "article_id" => "article",
];


$form->title = "Commande has article";


$form->addControl("commande_id")->type("selectByRequest", "select id, reference from zilu.commande");
$form->addControl("article_id")->type("selectByRequest", "select id, reference_lf from zilu.article");


$form->display();
