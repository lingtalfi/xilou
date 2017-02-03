<?php


use Crud\CrudModule;

$form = CrudModule::getForm("zilu.fournisseur_has_article", ['fournisseur_id', 'article_id']);



$form->labels = [
    "fournisseur_id" => "fournisseur",
    "article_id" => "article",
    "reference" => "reference",
];


$form->title = "Fournisseur has article";


$form->addControl("fournisseur_id")->type("selectByRequest", "select id, nom from zilu.fournisseur");
$form->addControl("article_id")->type("selectByRequest", "select id, reference_lf from zilu.article");
$form->addControl("reference")->type("text");


$form->display();
