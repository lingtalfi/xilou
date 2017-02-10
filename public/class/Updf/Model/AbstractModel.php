<?php


namespace Updf\Model;


abstract class AbstractModel implements ModelInterface
{
    private $vars;

    public function __construct()
    {
        $this->vars = [];
    }


    public static function create()
    {
        return new static();
    }


    public function getVariables()
    {
        if (null === $this->vars) {
            $this->vars = [];
        }
        $publicPropsVars = $this->getPublicPropsVars();
        $this->vars = array_merge($this->vars, $publicPropsVars);
        return $this->vars;
    }


    //--------------------------------------------
    //
    //--------------------------------------------
    protected function getPublicPropsVars()
    {

        $ret = [];
        $r = new \ReflectionClass($this);
        foreach ($r->getProperties(\ReflectionProperty::IS_PUBLIC) as $property) {
            $ret[$property->getName()] = $property->getValue($this);
        }
        return $ret;
    }
}