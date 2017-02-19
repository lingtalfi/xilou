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


    public function __construct()
    {
        $this->items = [];
        $this->containers = [];
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
        array_walk($items, function ($item) use (&$totalItemsVolume) {
            $totalItemsVolume += $item['volume'];
        });
        $remainingVolume = $totalItemsVolume;

        $orderedContainers = $this->containers;
        usort($orderedContainers, function ($a, $b) {
            return $a['volume'] < $b['volume'];
        });

        usort($items, function ($a, $b) {
            return $a['volume'] < $b['volume'];
        });

        $containersToUse = [];


        $biggestContainer = $orderedContainers[0];
        while ($remainingVolume > 0) {
            if ($remainingVolume - $biggestContainer['volume'] > 0) {
                $remainingVolume -= $biggestContainer['volume'];
                $containersToUse[] = $biggestContainer;
            } else {
                break;
            }
        }
        if (false !== ($c = $this->getMinContainer($orderedContainers, $remainingVolume))) {
            $containersToUse[] = $c;
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
    public function bestFit(array $containersToUse)
    {
        $items = $this->items;
        foreach ($items as $item) {

        }
    }

    //------------------------------------------------------------------------------/
    //
    //------------------------------------------------------------------------------/
    // gets the biggest container that can contain a full volume (no remaining space) of items
    private function getMinContainer(array $orderedContainers, $remainingVolume)
    {
        $maxIndex = count($orderedContainers) - 1;
        for ($i = $maxIndex; $i >= 0; $i--) {
            $c = $orderedContainers[$i];
            if (($remainingVolume - $c['volume']) <= 0) {
                return $c;
            }
        }
        return false;
    }
}