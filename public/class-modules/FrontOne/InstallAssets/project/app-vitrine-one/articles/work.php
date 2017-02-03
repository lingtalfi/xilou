<?php


use Shared\FrontOne\FileArticle;

$article = new FileArticle();
$article->setAnchor('work');
$article->setLabel('Work');
$article->setPosition(1);
$article->setFile(__DIR__ . "/content/work.php");
