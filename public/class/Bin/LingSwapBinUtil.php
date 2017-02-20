<?php


namespace Bin;

/**
 *
 *
 * $containers = [
 *      [
 *          'name' => "small",
 *          'weight' => 2,
 *          'volume' => 2,
 *      ],
 *      [
 *          'name' => "medium",
 *          'weight' => 4,
 *          'volume' => 4,
 *      ],
 * ];
 *
 * $items = [
 *      [
 *          'ref' => "p1",
 *          'weight' => 3,
 *          'volume' => 3,
 *      ],
 *      [
 *          'ref' => "p2",
 *          'weight' => 2,
 *          'volume' => 2,
 *      ],
 * ];
 */
class LingSwapBinUtil
{

    private $items;
    private $containers;
    private $keyItemVolume;
    private $keyItemWeight;
    private $keyContainerVolume;
    private $keyContainerWeight;
    private $keyContainerName;


    public function __construct()
    {
        $this->items = [];
        $this->containers = [];
        $this->keyItemVolume = 'volume';
        $this->keyItemWeight = 'weight';
        $this->keyContainerVolume = 'volume';
        $this->keyContainerWeight = 'weight';
        $this->keyContainerName = 'name';
    }

    public static function create()
    {
        return new static();
    }

    public function setItems(array $items)
    {
        $this->items = $items;
        return $this;
    }

    public function setContainers(array $containers)
    {
        $this->containers = $containers;
        return $this;
    }


    public function getContainersToUse()
    {
        $items = $this->items;
        $totalItemsVolume = 0;
        $totalItemsWeight = 0;
        array_walk($items, function ($item) use (&$totalItemsVolume, &$totalItemsWeight) {
            $totalItemsVolume += $item[$this->keyItemVolume];
            $totalItemsWeight += $item[$this->keyItemWeight];
        });
        $remainingVolume = $totalItemsVolume;
        $remainingWeight = $totalItemsWeight;


        $containersToUseVol = $this->getContainersToUseByKey($remainingVolume, $this->keyContainerVolume, $this->keyItemVolume);
        $containersToUseWeight = $this->getContainersToUseByKey($remainingWeight, $this->keyContainerWeight, $this->keyContainerWeight);

        // we choose the container combination that gives us the most space and weight capacity
        $scoreVol = $this->getContainersScore($containersToUseVol);
        $scoreWeight = $this->getContainersScore($containersToUseWeight);


        $containersToUse = ($scoreVol > $scoreWeight) ? $containersToUseVol : $containersToUseWeight;

        return $containersToUse;
    }

    private function getContainersToUseByKey($remainingQty, $containerKey, $itemKey)
    {
        $containersToUse = [];
        if (count($this->containers) > 0) {
            $items = $this->items;
            $orderedContainers = $this->containers;
            $this->sortContainers($orderedContainers, $containerKey);
            $this->sortItems($items, $itemKey);
            $containersToUse = [];
            $biggestContainer = $orderedContainers[0];
            while ($remainingQty > 0) {
                if ($remainingQty - $biggestContainer[$containerKey] > 0) {
                    $remainingQty -= $biggestContainer[$containerKey];
                    $containersToUse[] = $biggestContainer;
                } else {
                    break;
                }
            }
            if (false !== ($c = $this->getMinContainer($orderedContainers, $remainingQty, $containerKey))) {
                $containersToUse[] = $c;
            }
        }
        return $containersToUse;
    }


    /**
     * Distribute the items in the given containers, and
     * returns an array of:
     *      - containerName
     *      - items
     *
     *
     */
    public function bestFit(array $containersToUse, &$unusedSpace = 0)
    {
        $ret = [];
        $items = $this->items;
        $this->sortItems($items, $this->keyItemVolume);
        $this->sortContainers($containersToUse, $this->keyContainerVolume);


        // prepare the returned array
        foreach ($containersToUse as $c) {
            $ret[] = [
                'name' => $c[$this->keyContainerName],
                'remainingVolume' => $c[$this->keyContainerVolume],
                'remainingWeight' => $c[$this->keyContainerWeight],
                'items' => [],
            ];
        }


        $curIndex = 0;
        $nbContainers = count($containersToUse);


        foreach ($items as $item) {

            $tries = 0;

            while (true) {
                $curIndex = $curIndex % $nbContainers;

                // does the item fit in the container?
                if ($ret[$curIndex]['remainingVolume'] - $item[$this->keyItemVolume] >= 0) {
                    $ret[$curIndex]['remainingVolume'] -= $item[$this->keyItemVolume];
                    $ret[$curIndex]['remainingWeight'] -= $item[$this->keyItemWeight];
                    $ret[$curIndex]['items'][] = $item;
                    $curIndex++;
                    break;
                } else {

                    // if the item does not fit in any container
                    // then for now this algo fails
                    if ($tries >= $nbContainers) {
                        throw new \Exception("cannot put the item in any container (volume: " . $item[$this->keyItemVolume] . "; weight: " . $item[$this->keyItemWeight] . ")");
                    } else {
                        // try the next container (in the next iteration)
                        $curIndex++;
                        $tries++;
                    }
                }
            }
        }


        // check volume
        $this->fixVolume($ret);


        $unusedSpace = $this->getUnusedSpace($ret);
        return $ret;
    }

    public function fixVolume(array &$containersInfo)
    {

    }




    //------------------------------------------------------------------------------/
    //
    //------------------------------------------------------------------------------/
    // gets the biggest container that can contain a full volume (no remaining space) of items
    private function getMinContainer(array $orderedContainers, $remainingQty, $key)
    {
        $maxIndex = count($orderedContainers) - 1;
        for ($i = $maxIndex; $i >= 0; $i--) {
            $c = $orderedContainers[$i];
            if (($remainingQty - $c[$key]) <= 0) {
                return $c;
            }
        }
        return false;
    }


    /**
     * Sort items by decreasing volume
     */
    private function sortItems(array &$items, $key)
    {
        usort($items, function ($a, $b) use ($key) {
            return $a[$key] < $b[$key];
        });
    }

    /**
     * Sort containers by decreasing volume capacity
     */
    private function sortContainers(array &$containers, $key)
    {
        usort($containers, function ($a, $b) use ($key) {
            return $a[$key] < $b[$key];
        });
    }


    private function getUnusedSpace(array $containersInfo)
    {
        $unusedSpace = 0;
        foreach ($containersInfo as $c) {
            $unusedSpace += $c['remainingVolume'];
        }
        return $unusedSpace;
    }

    private function findItemsSmallestVolume(array $items)
    {
        $ret = 0;
        if (count($items) > 0) {
            $ret = $items[0][$this->keyItemVolume];
            foreach ($items as $item) {
                if ($item[$this->keyItemVolume] < $ret) {
                    $ret = $item[$this->keyItemVolume];
                }
            }
        }
        return $ret;
    }

    private function getContainersScore(array $containers)
    {
        $score = 0;
        foreach($containers as $c){
            $score += $c['weight'];
            $score += $c['volume'];
        }
        return $score;
    }

}