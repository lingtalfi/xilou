<?php


namespace Bin\Swapper\ItemsSelector;


interface ItemsSelectorInterface
{

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
     */
    public function select(array $usedContainers);

}