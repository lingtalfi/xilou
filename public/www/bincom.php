<?php


use Bin\CommandeToBinHelper;
use CommandeHasArticle\CommandeHasArticleUtil;

require_once __DIR__ . "/../init.php";



$res  =CommandeToBinHelper::distributeCommandeById(1);
a($res);

