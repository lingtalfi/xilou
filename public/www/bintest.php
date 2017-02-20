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
        'weight' => 10,
        'volume' => 10,
    ],
    [
        'name' => "medium",
        'weight' => 20,
        'volume' => 20,
    ],
];

$items = [
    [
        'name' => "p1",
        'weight' => 7,
        'volume' => 3,
    ],
    [
        'name' => "p2",
        'weight' => 7,
        'volume' => 2,
    ],
    [
        'name' => "p3",
        'weight' => 6,
        'volume' => 4,
    ],
    [
        'name' => "p4",
        'weight' => 3,
        'volume' => 5,
    ],
    [
        'name' => "p5",
        'weight' => 7,
        'volume' => 4,
    ],
    [
        'name' => "p6",
        'weight' => 1,
        'volume' => 6,
    ],
];


$o = LingSwapBinUtil::create()->setContainers($containers)->setItems($items);
$containersToUse = $o->getContainersToUse();
a($containersToUse);
$unusedSpace = 0;
$c = $o->bestFit($containersToUse, $unusedSpace);
echo '<hr>';
a($c);
a("Unused space: " . $unusedSpace);

