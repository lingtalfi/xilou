<?php


use Bin\CommandeToBinHelper;
use Bin\SummaryInfoTool;
use CommandeHasArticle\CommandeHasArticleUtil;

require_once __DIR__ . "/../init.php";


$res = CommandeToBinHelper::distributeCommandeById(1);
echo "<hr>";
a($res);







