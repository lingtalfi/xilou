<?php


use Bin\CommandeToBinHelper;
use Bin\Exception\WeightOverloadException;
use Bin\SummaryInfoTool;
use CommandeHasArticle\CommandeHasArticleUtil;

require_once __DIR__ . "/../init.php";



$overloadWarning = false;
try {
    $res = CommandeToBinHelper::distributeCommandeById(1);
} catch (WeightOverloadException $e) {
    $res = $e->usedContainers;
    $overloadWarning = true;
}
echo "<hr>";
a($overloadWarning);
a($res);








