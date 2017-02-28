<?php


namespace Bin\Swapper\ItemsSelector;


use Bin\BinUtil;
use Bin\SummaryInfoTool;

class NegativeWeightItemsSelector implements ItemsSelectorInterface
{

    public $keyItemWeight;

    public function __construct()
    {
        $this->keyItemWeight = "poids";
    }

    public static function create()
    {
        return new static();
    }


    public function select(array $usedContainers)
    {

        list($containersWithNegativeWeight, $containersWithPositiveWeight) = SummaryInfoTool::findNegativePositiveWeightContainers($usedContainers);


        /**
         * Select, as the source, the item with the max weight from the container
         * with the maximum negative amount.
         *
         * Select, as the target, the item with the max weight from the container
         * with the maximum positive amount.
         *
         */


        $negativeContainers = $summary['containersWithNegativeWeight'];
        $positiveContainers = $summary['containersWithPositiveWeight'];

        list($negativeContainerId, $positiveContainerId) = $this->selectContainersIds($negativeContainers, $positiveContainers);
        a($negativeContainerId, $positiveContainerId);

        if (false !== ($negativeUsedContainer = BinUtil::getUsedContainerById($usedContainers, $negativeContainerId))) {


            $positiveMaxWeight = $positiveContainers;


            $sourceHeaviestItem = $this->getBiggestItem($negativeUsedContainer['items']);

            $sourceItemMaxWeight = $sourceHeaviestItem[$this->keyItemWeight];
            $targetPositiveWeight = $positiveContainers[$positiveContainerId];
            $maxTransferredItemWeight = ($sourceItemMaxWeight > $targetPositiveWeight) ? $targetPositiveWeight : $sourceItemMaxWeight;


            $itemId = $this->findCandidateItemId($negativeContainers, $maxTransferredItemWeight);


            $source = [
                'containerId' => 0,
                'itemId' => 0,
            ];
            $target = [
                'containerId' => 0,
                'itemId' => 0,
            ];
            return [
                $source,
                $target,
            ];
        } else {
            throw new \Exception("Negative container not found with id $negativeContainerId");
        }
    }


    //------------------------------------------------------------------------------/
    //
    //------------------------------------------------------------------------------/
    private function selectContainersIds(array $negativeContainers, array $positiveContainers)
    {
        $neg = 0;
        $pos = 0;
        $negId = false;
        $posId = false;

        foreach ($negativeContainers as $containerId => $negativeSpace) {
            if ($negativeSpace < $neg) {
                $neg = $negativeSpace;
                $negId = $containerId;
            }
        }
        foreach ($positiveContainers as $containerId => $positiveSpace) {
            if ($positiveSpace > $pos) {
                $pos = $positiveSpace;
                $posId = $containerId;
            }
        }
        return [
            $negId,
            $posId,
        ];
    }


    private function getBiggestItem(array $items)
    {
        $c = false;
        $cur = 0;
        foreach ($items as $item) {
            if ($item[$this->keyItemWeight] > $cur) {
                $c = $item;
                $cur = $item[$this->keyItemWeight];
            }
        }
        return $c;
    }


}