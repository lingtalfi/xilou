<?php


namespace Container;

use QuickPdo\QuickPdo;

class ContainerUtil
{


    /**
     * Use this to feed html select options
     */
    public static function getId2Labels()
    {
        return QuickPdo::fetchAll("select id, nom from container order by id asc", [], \PDO::FETCH_COLUMN | \PDO::FETCH_UNIQUE);
    }


    public static function getContainerTypes()
    {
        return QuickPdo::fetchAll('select id, label from type_container', [], \PDO::FETCH_COLUMN | \PDO::FETCH_UNIQUE);
    }

    public static function createContainer($name, $typeId)
    {
        return QuickPdo::insert('container', [
            'nom' => $name,
            'type_container_id' => (int)$typeId,
        ]);
    }
}