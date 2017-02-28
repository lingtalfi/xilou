<?php


namespace Bin\Swapper\Validator;


class NegativeWeightValidator implements ValidatorInterface
{



    public static function create()
    {
        return new static();
    }



    public function validate(array $usedContainers)
    {
        $containersWeightBalancedSum = $this->getContainersWeightBalancedSum($usedContainers);
        return (true === $this->hasContainerWithNegativeWeight($usedContainers) && $containersWeightBalancedSum > 0);
    }



    //--------------------------------------------
    //
    //--------------------------------------------
    private function hasContainerWithNegativeWeight(array $usedContainers)
    {
        foreach ($usedContainers as $c) {
            if ($c['remainingWeight'] < 0) {
                return true;
            }
        }
        return false;
    }

    private function getContainersWeightBalancedSum(array $usedContainers)
    {
        $balance = 0;
        foreach ($usedContainers as $c) {
            $balance += $c["remainingWeight"];
        }
        return $balance;
    }

}