<?php


use Crud\CrudModule;

$form = CrudModule::getForm("zilu.container_has_article", ['container_id', 'article_id']);



$form->labels = [
    "container_id" => "container",
    "article_id" => "article",
];


$form->title = "Container has article";


$form->addControl("container_id")->type("selectByRequest", "select id, nom from zilu.container");
$form->addControl("article_id")->type("selectByRequest", "select id, reference_lf from zilu.article");


$form->display();
