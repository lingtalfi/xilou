<?php


namespace TypeContainer;


use QuickPdo\QuickPdo;

class TypeContainer
{


    public static function getTypeContainerDetails()
    {
        return QuickPdo::fetchAll("select label, poids_max, volume_max from type_container");
    }
}