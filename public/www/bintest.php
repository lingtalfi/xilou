<?php


use Bin\LingSwapBinUtil;

require_once __DIR__ . "/../init.php";

/**
 * Find the optimized number of containers necessary to hold the merchandise.
 * Each product has an estimated weight and an estimated volume.
 *
 * Products are put inside containers which have a maximum weight capacity and volume capacity.
 * There are different types of containers, each having its max weight and max volume characteristics.
 */
$containers = [
    [
        'name' => "small",
        'weight' => 2,
        'volume' => 2,
    ],
    [
        'name' => "medium",
        'weight' => 4,
        'volume' => 4,
    ],
];

$items = [
    [
        'ref' => "p1",
        'weight' => 3,
        'volume' => 3,
    ],
    [
        'ref' => "p2",
        'weight' => 2,
        'volume' => 2,
    ],
];


$o = LingSwapBinUtil::create()->setContainers($containers)->setItems($items);
$containersToUse = $o->getContainersToUse();
a($containersToUse);
$o->bestFit($containersToUse);


