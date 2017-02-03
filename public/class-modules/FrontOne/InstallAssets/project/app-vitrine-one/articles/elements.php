<?php

use Shared\FrontOne\FileArticle;


$article = new FileArticle();
$article->setAnchor('elements');
$article->setLabel('Elements');
$article->setPosition(4);
$article->setIsActive(false);
$article->setFile(__DIR__ . "/content/elements.php");
