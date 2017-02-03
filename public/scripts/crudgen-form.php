<?php


require_once __DIR__ . "/../init.php";


use Crud\CrudConfig;
use Crud\Util\CrudFormGenerator;


$gen = new CrudFormGenerator();
$gen->foreignKeyPrettierColumns = CrudConfig::getForeignKeyPrettierColumns();
$gen->prettyTableNames = CrudConfig::getPrettyTableNames();
$gen->fixPrettyColumnNames = CrudConfig::getPrettyColumnNames();


$gen->generateForms();




