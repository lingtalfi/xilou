<?php



namespace Bin\Exception;


/**
 * This exception is thrown when the algorithm's distribution leads to overloaded containers.
 * The gui user might be warned so that she can manually fix the algo errors.
 *
 */
class WeightOverloadException extends \Exception{

    public $usedContainers;

}