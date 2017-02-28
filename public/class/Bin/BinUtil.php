<?php


namespace Bin;


class BinUtil
{


    public static function getUsedContainerById(array $usedContainers, $id)
    {
        foreach ($usedContainers as $usedContainer) {
            if ((string)$usedContainer["id"] === (string)$id) {
                return $usedContainer;
            }
        }
        return false;
    }
}