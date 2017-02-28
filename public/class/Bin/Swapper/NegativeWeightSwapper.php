<?php


namespace Bin\Swapper;

use Bin\SummaryInfoTool;
use Bin\Swapper\ItemsSelector\ItemsSelectorInterface;
use Bin\Swapper\Validator\ValidatorInterface;


class NegativeWeightSwapper implements SwapperInterface
{


    /**
     * @var ItemsSelectorInterface
     */
    private $itemSelector;
    /**
     * @var ValidatorInterface
     */
    private $validator;
    private $nbTries;

    private $keyContainerId;
    private $keyItemId;


    private $measureBeforeScore;
    private $measureAfterScore;


    public function __construct()
    {
        $this->nbTries = 3;
        $this->keyContainerId = 'id';
        $this->keyItemId = 'aid';
    }


    public static function create()
    {
        return new static();
    }

    public function setItemSelector(ItemsSelectorInterface $itemSelector)
    {
        $this->itemSelector = $itemSelector;
        return $this;
    }

    public function setValidator(ValidatorInterface $validator)
    {
        $this->validator = $validator;
        return $this;
    }

    public function setNbTries($nbTries)
    {
        $this->nbTries = $nbTries;
        return $this;
    }


    /**
     * Return false in case of error,
     * or the swapped used containers array.
     */
    public function swap(array $usedContainers)
    {

        // is swappable?
        if (true === $this->isSwappable($usedContainers)) {


            for ($i = 0; $i < $this->nbTries; $i++) {

                // measure before test
                $this->measureBefore($usedContainers);


                // test
                if (false !== ($itemsToSwap = $this->selectItems($usedContainers, $i + 1))) {
                    $this->doSwapping($usedContainers, $itemsToSwap);
                }

                // measure after test
                $this->measureAfter($usedContainers);

                // test results
                if (true === $this->testIsValid()) {
                    return true;
                }
            }
        }
        return false;
    }

    //------------------------------------------------------------------------------/
    //
    //------------------------------------------------------------------------------/
    /**
     * returns array:
     *
     * - source:
     *      - containerId
     *      - itemId
     * - target:
     *      - containerId
     *      - itemId
     *
     * or false if there is no (estimated) possibility of optimization
     */
    private function selectItems(array $usedContainers, $variationNumber = 1)
    {
        /**
         * The source.
         * We want to select the biggest item in the container that has the biggest negative weight.
         *
         * The target.
         * The biggest item in the container that has the biggest positive weight.
         *
         * The source's weight must not exceed the target's container maximum weight capacity, and vice versa.
         */

        list($negContainersId2Weight, $posContainersId2Weight) = SummaryInfoTool::findNegativePositiveWeightContainers($usedContainers);

        if (count($negContainersId2Weight) > 0 && count($posContainersId2Weight) > 0) {
            if (false !== ($negativeContainerId = $this->getHeaviestId($negContainersId2Weight))) {
                if (false !== ($positiveContainerId = $this->getHeaviestId($posContainersId2Weight, false))) {

                    $negativeContainer = $this->getContainerById($negativeContainerId, $usedContainers);

                    $positiveContainer = $this->getContainerById($positiveContainerId, $usedContainers);

                    $negativeContainerItems = $negativeContainer['items'];
                    $positiveContainerItems = $positiveContainer['items'];

                    $sourceMaxWeight = $negativeContainer['maxWeight'];
                    $targetMaxWeight = $positiveContainer['maxWeight'];

                    if (false !== ($sourceItemId = $this->findHeaviestItemId($negativeContainerItems, $sourceMaxWeight, $variationNumber))) {
                        if (false !== ($targetItemId = $this->findHeaviestItemId($positiveContainerItems, $targetMaxWeight, $variationNumber))) {
                            return [
                                'source' => [
                                    'containerId' => $negativeContainerId,
                                    'itemId' => $sourceItemId,
                                ],
                                'target' => [
                                    'containerId' => $positiveContainerId,
                                    'itemId' => $targetItemId,
                                ],
                            ];
                        }
                    }
                }
            }
        }
        return false;
    }


    /**
     * Swap the selected items and return the new "configuration"'s score.
     * Score is based on negative/positive balance.
     *
     */
    private function doSwapping(array &$usedContainers, array $itemsToSwap)
    {
        $source = $itemsToSwap['source'];
        $target = $itemsToSwap['target'];

        if ($source['containerId'] !== $target['containerId']) {

            $srcItem = $this->extractItem($source, $usedContainers);
            $targetItem = $this->extractItem($target, $usedContainers);
            $this->injectItem($srcItem, $target['containerId'], $usedContainers);
            $this->injectItem($targetItem, $source['containerId'], $usedContainers);

        } else {
            throw new \Exception("The source container and target container must be different");
        }
    }


    private function injectItem(array $item, $containerId, array &$usedContainers)
    {
        foreach ($usedContainers as $k => $container) {
            if ((string)$container[$this->keyContainerId] === (string)$containerId) {
                $usedContainers[$k]["items"][] = $item;
                return;
            }
        }
        throw new \Exception("Item not found");
    }

    private function extractItem(array $itemToSwap, array &$usedContainers)
    {
        $containerId = $itemToSwap['containerId'];
        $itemId = $itemToSwap['itemId'];
        foreach ($usedContainers as $k => $container) {
            if ((string)$container[$this->keyContainerId] === (string)$containerId) {
                foreach ($container['items'] as $m => $item) {
                    if ((string)$item[$this->keyItemId] === (string)$itemId) {
                        $returnItem = $usedContainers[$k]['items'][$m];
                        unset($usedContainers[$k]['items'][$m]);
                        return $returnItem;
                    }
                }
            }
        }
        throw new \Exception("Item not found");
    }

    private function getBestScore(array $scores)
    {
        if (count($scores) > 0) {

            usort($scores, function ($a, $b) {
                return $a['score'] < $b['score'];
            });
            return $scores[0];
        } else {
            throw new \Exception("No score found (count=0)");
        }
    }

    //--------------------------------------------
    //
    //--------------------------------------------
    private function isSwappable(array $usedContainers)
    {
        $balance = 0;
        $hasNeg = false;
        foreach ($usedContainers as $c) {
            if ($c['remainingWeight'] < 0) {
                $hasNeg = true;
            }
            $balance += $c["remainingWeight"];
        }
        return (true === $hasNeg && $balance > 0);
    }

    private function measureBefore(array $usedContainers)
    {
        $this->measureBeforeScore = $this->getNegativeWeightSum($usedContainers);
//        a("measureBefore: " . $this->measureBeforeScore);
    }

    private function measureAfter(array $usedContainers)
    {
        $this->measureAfterScore = $this->getNegativeWeightSum($usedContainers);
//        a("measureAfter: " . $this->measureAfterScore);
    }

    private function getNegativeWeightSum(array $usedContainers)
    {
        $negative = 0;
        $w = 'remainingWeight';

        foreach ($usedContainers as $c) {
            if ($c[$w] < 0) {
                $negative += $c[$w];
            }
        }
        return $negative;
    }

    private function testIsValid()
    {
        return ($this->measureAfterScore > $this->measureBeforeScore);
    }

    private function getHeaviestId(array $id2Weight, $isNegative = true)
    {
        $i = false;
        $w = 0;
        foreach ($id2Weight as $id => $weight) {
            if (
                (true === $isNegative && $weight < $w) ||
                (false === $isNegative && $weight > $w)
            ) {
                $w = $weight;
                $i = $id;
            }
        }
        return $i;
    }

    private function getContainerById($id, array $usedContainers)
    {
        foreach ($usedContainers as $usedContainer) {
            if ((string)$usedContainer['id'] === (string)$id) {
                return $usedContainer;
            }
        }
        throw new \Exception("Container not found with id $id");
    }

    private function findHeaviestItemId(array $items, $maxWeight, $variationNumber = 1)
    {
        usort($items, function ($a, $b) {
            return $a['poids'] < $b['poids'];
        });
        $lastIdFound = null;
        foreach ($items as $item) {
            if ($item['poids'] <= $maxWeight) {
                $lastIdFound = $item[$this->keyItemId];
                $variationNumber--;
                if (0 === $variationNumber) {
                    return $item[$this->keyItemId];
                }
            }
        }
        if (null !== $lastIdFound) {
            return $lastIdFound;
        }
        return false;
    }
}