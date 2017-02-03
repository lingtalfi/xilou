<?php


namespace Commande;


use QuickPdo\QuickPdo;

class CommandeUtil
{


    /**
     * Process the data file,
     * and returns the number of successfully parsed lines.
     */
    public static function importCommandeByCsvData(array $data)
    {
        return count($data);
    }

    /**
     * Use this to feed html select options
     */
    public static function getId2Labels()
    {
        return QuickPdo::fetchAll("select id, reference from commande order by id asc", [], \PDO::FETCH_COLUMN|\PDO::FETCH_UNIQUE);
    }
}