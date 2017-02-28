<?php


namespace TypeContainer;


use QuickPdo\QuickPdo;

class TypeContainer
{


    public static function getTypeContainerDetails()
    {
        return QuickPdo::fetchAll("select label, poids_max, volume_max from type_container");
    }

    public static function getLabel2Id()
    {
        return QuickPdo::fetchAll("select label, id from type_container", [], \PDO::FETCH_COLUMN | \PDO::FETCH_UNIQUE);
    }

    public static function getDetailsByContainerId($containerId)
    {
        return QuickPdo::fetch("
select label, poids_max, volume_max 
from type_container t 
inner join container c on c.type_container_id=t.id
where c.id=" . (int)$containerId);
    }
}