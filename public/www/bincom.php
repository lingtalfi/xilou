<?php


use Bin\BinGuiUtil;
use Bin\CommandeToBinHelper;
use Bin\Exception\WeightOverloadException;

require_once __DIR__ . "/../init.php";




$commandeId = 1;
$overloadWarning = false;
try {
    $usedContainers = CommandeToBinHelper::distributeCommandeById($commandeId);
} catch (WeightOverloadException $e) {
    $usedContainers = $e->usedContainers;
    $overloadWarning = true;
}



BinGuiUtil::decorateUsedContainers($usedContainers, $commandeId);
a("kk");
az($usedContainers);

ob_start();
BinGuiUtil::displayDecoratedUsedContainers($usedContainers, $commandeId);
$output = ob_get_clean();
echo $output;


exit;


$commandeId = 1;
$overloadWarning = false;
try {
    $usedContainers = CommandeToBinHelper::distributeCommandeById($commandeId);
} catch (WeightOverloadException $e) {
    $usedContainers = $e->usedContainers;
    $overloadWarning = true;
}
az($usedContainers);

$c1 = $usedContainers[0];
$items = $c1['items'];

BinGuiUtil::displayContainerItems($items);









