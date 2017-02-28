<?php


namespace Bin\Swapper;


interface SwapperInterface
{

    /**
     * Return false in case of error,
     * or the swapped used containers array.
     */
    public function swap(array $usedContainers);
}