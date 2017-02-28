<?php


namespace Bin;


class SummaryInfoTool
{

    public $keyItemId;
    public $keyItemVolume;
    public $keyItemWeight;
    public $keyContainerVolume;
    public $keyContainerWeight;
    public $keyContainerName;


    public function __construct()
    {
        $this->keyItemId = 'id';
        $this->keyItemVolume = 'volume';
        $this->keyItemWeight = 'weight';
        $this->keyContainerVolume = 'volume';
        $this->keyContainerWeight = 'weight';
        $this->keyContainerName = 'name';
    }


    public function getSummaryInfo(array $items, array $usedContainers, array $containersToUse)
    {

        $summary = [];


        // collecting summary info
        list($balanceVol, $negativeVol, $positiveVol) = $this->getUsedContainerInfo($usedContainers, false);
        list($balanceWeight, $negativeWeight, $positiveWeight) = $this->getUsedContainerInfo($usedContainers, true);
        list($totalVol, $totalWeight) = $this->getItemsTotals($items);
        list($negativeVolContainers, $negativeWeightContainers, $positiveVolContainers, $positiveWeightContainers) = $this->findNegativePositiveContainers($usedContainers);
        list($itemsZeroVolume, $itemsZeroWeight) = $this->findItemsWithZeroValue($items);


        list($containersMaxVol, $containersMaxWeight) = $this->getContainersMaxInfo($containersToUse);

        $summary['itemsTotalVolume'] = $totalVol;
        $summary['itemsTotalWeight'] = $totalWeight;

        $summary['containersMaxVolume'] = $containersMaxVol;
        $summary['containersMaxWeight'] = $containersMaxWeight;

        $summary['containersVolumeBalancedSum'] = $balanceVol;
        $summary['containersVolumeNegativeSum'] = $negativeVol;
        $summary['containersVolumePositiveSum'] = $positiveVol;

        $summary['containersWeightBalancedSum'] = $balanceWeight;
        $summary['containersWeightNegativeSum'] = $negativeWeight;
        $summary['containersWeightPositiveSum'] = $positiveWeight;

        $summary['containersWithNegativeSpace'] = $negativeVolContainers;
        $summary['containersWithNegativeWeight'] = $negativeWeightContainers;

        $summary['containersWithPositiveSpace'] = $positiveVolContainers;
        $summary['containersWithPositiveWeight'] = $positiveWeightContainers;

        $summary['itemsWithVolumeZero'] = $itemsZeroVolume;
        $summary['itemsWithWeightZero'] = $itemsZeroWeight;

        return $summary;
    }



    //--------------------------------------------
    //
    //--------------------------------------------
    public static function findNegativePositiveWeightContainers(array $usedContainers)
    {
        $negWeight = [];
        $posWeight = [];
        foreach ($usedContainers as $c) {
            if ($c['remainingWeight'] < 0) {
                $negWeight[$c['id']] = $c['remainingWeight'];
            } elseif ($c['remainingWeight'] > 0) {
                $posWeight[$c['id']] = $c['remainingWeight'];
            }
        }
        return [
            $negWeight,
            $posWeight,
        ];
    }


    //------------------------------------------------------------------------------/
    //
    //------------------------------------------------------------------------------/

    private function getUsedContainerInfo(array $usedContainers, $isWeight)
    {
        $balance = 0;
        $negative = 0;
        $positive = 0;


        $w = (true === $isWeight) ? 'remainingWeight' : 'remainingVolume';

        foreach ($usedContainers as $c) {
            $balance += $c[$w];
            if ($c[$w] < 0) {
                $negative += $c[$w];
            } elseif ($c[$w] > 0) {
                $positive += $c[$w];
            }
        }
        return [
            $balance,
            $negative,
            $positive,
        ];
    }


    private function getItemsTotals(array $items)
    {
        $volume = 0;
        $weight = 0;
        foreach ($items as $item) {
            $volume += $item[$this->keyItemVolume];
            $weight += $item[$this->keyItemWeight];
        }
        return [$volume, $weight];
    }


    private function findItemsWithZeroValue(array $items)
    {
        $zeroVol = [];
        $zeroWeight = [];
        foreach ($items as $item) {
            if ((float)$item[$this->keyItemVolume] === 0.0) {
                $zeroVol[] = $item[$this->keyItemId];
            }
            if ((float)$item[$this->keyItemWeight] === 0.0) {
                $zeroWeight[] = $item[$this->keyItemId];
            }
        }
        return [
            $zeroVol,
            $zeroWeight,
        ];
    }


    private function getContainersMaxInfo(array $containersToUse)
    {
        $totalVol = 0;
        $totalWeight = 0;
        foreach ($containersToUse as $c) {
            $totalVol += $c[$this->keyContainerVolume];
            $totalWeight += $c[$this->keyContainerWeight];
        }

        return [
            $totalVol,
            $totalWeight,
        ];
    }


    private function findNegativePositiveContainers(array $usedContainers)
    {
        $negVol = [];
        $negWeight = [];
        $posVol = [];
        $posWeight = [];
        foreach ($usedContainers as $c) {
            if ($c['remainingVolume'] < 0) {
                $negVol[$c['id']] = $c['remainingVolume'];
            } elseif ($c['remainingVolume'] > 0) {
                $posVol[$c['id']] = $c['remainingVolume'];
            }
            if ($c['remainingWeight'] < 0) {
                $negWeight[$c['id']] = $c['remainingWeight'];
            } elseif ($c['remainingWeight'] > 0) {
                $posWeight[$c['id']] = $c['remainingWeight'];
            }
        }
        return [
            $negVol,
            $negWeight,
            $posVol,
            $posWeight,
        ];
    }


}