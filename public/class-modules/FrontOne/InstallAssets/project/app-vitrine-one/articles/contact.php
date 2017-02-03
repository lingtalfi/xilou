<?php


use Shared\FrontOne\FileArticle;

$article = new FileArticle();
$article->setAnchor('contact');
$article->setLabel('Contact');
$article->setPosition(3);
$article->setIsDynamic(true);
$article->setFile(__DIR__ . "/content/contact.php");
