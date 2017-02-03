<?php


namespace Container;

use QuickPdo\QuickPdo;

class ContainerUtil{


    /**
     * Use this to feed html select options
     */
    public static function getId2Labels()
    {
        return QuickPdo::fetchAll("select id, nom from container order by id asc", [], \PDO::FETCH_COLUMN|\PDO::FETCH_UNIQUE);
    }
}