<?php


namespace Bin\Swapper\Validator;


interface ValidatorInterface
{
    /**
     * @return bool
     */
    public function validate(array $usedContainers);
}