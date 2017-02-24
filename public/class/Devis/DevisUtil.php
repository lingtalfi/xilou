<?php


namespace Devis;


use QuickPdo\QuickPdo;

class DevisUtil
{

    /**
     * Use this to feed html select options
     */
    public static function getId2Labels()
    {
        return QuickPdo::fetchAll("select id, reference from devis order by id asc", [], \PDO::FETCH_COLUMN | \PDO::FETCH_UNIQUE);
    }
}