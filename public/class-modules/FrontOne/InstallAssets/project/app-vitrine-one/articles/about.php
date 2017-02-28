<?php

use Shared\FrontOne\FileArticle;

$article = new FileArticle();
$article->setAnchor('about');
$article->setLabel('About');
$article->setPosition(2);
$article->setIsProtected(true);
$article->setFile(__DIR__ . "/content/about.php");
