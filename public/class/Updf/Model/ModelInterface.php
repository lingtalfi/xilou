<?php


namespace Updf\Model;


interface ModelInterface
{
    /**
     * @return array of variables to use with the templates
     */
    public function getVariables();
}