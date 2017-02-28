<?php

use Shared\FrontOne\FileArticle;

$article = new FileArticle();
$article->setAnchor('intro');
$article->setLabel('Intro');
$article->setPosition(0);
$article->setFile(__DIR__ . "/content/intro.php");
