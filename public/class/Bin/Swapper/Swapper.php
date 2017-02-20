<?php


namespace Bin\Swapper;

use Bin\Swapper\ItemsSelector\ItemsSelectorInterface;
use Bin\Swapper\Validator\ValidatorInterface;


class Swapper
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


    public function __construct()
    {
        $this->nbTries = 3;
        $this->keyContainerId = 'id';
        $this->keyItemId = 'id';
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

        $scores = [];
        for ($i = 0; $i < $this->nbTries; $i++) {
            $itemsToSwap = $this->itemSelector->select($usedContainers);


            $swappedUsedContainers = $usedContainers;
            $swappingScore = $this->doSwapping($swappedUsedContainers, $itemsToSwap);

            if (true === $this->validator->validate($usedContainers)) {
                $scores[] = [
                    'score' => $swappingScore,
                    'itemsToSwap' => $itemsToSwap,
                ];
            }
        }


        if (count($scores) > 0) {
            $swappedUsedContainers = $usedContainers;
            $bestScore = $this->getBestScore($scores);
            $this->doSwapping($swappedUsedContainers, $bestScore['itemsToSwap']);
            return $swappedUsedContainers;
        }
        return false;
    }

    //------------------------------------------------------------------------------/
    //
    //------------------------------------------------------------------------------/
    /**
     * Swap the selected items and return the new "configuration"'s score.
     * Score is based on negative/positive balance.
     *
     */
    private function doSwapping(array &$usedContainers, array $itemsToSwap)
    {
        $source = $itemsToSwap['source'];
        $target = $itemsToSwap['target'];

        a("before");
        a($usedContainers);
        if ($source['containerId'] !== $target['containerId']) {

            $srcItem = $this->extractItem($source, $usedContainers);
            $targetItem = $this->extractItem($target, $usedContainers);
            $this->injectItem($srcItem, $target['containerId'], $usedContainers);
            $this->injectItem($targetItem, $source['containerId'], $usedContainers);


            az($usedContainers);


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
                return $a['score'] > $b['score'];
            });
            return $scores[0];
        } else {
            throw new \Exception("No score found (count=0)");
        }
    }
}