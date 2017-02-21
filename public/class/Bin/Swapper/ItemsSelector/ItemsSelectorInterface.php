<?php


namespace Bin\Swapper\ItemsSelector;


interface ItemsSelectorInterface
{

    /**
     * This method is called when it's assumed already that there is a problem to solve (i.e. the validator-> returned true)
     *
     *
     *
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
    public function select(array $usedContainers);

}