<?php


require_once __DIR__ . "/../init.php";


use Crud\CrudConfig;
use Crud\Util\CrudListGenerator;


$gen = new CrudListGenerator();
$gen->foreignKeyPrettierColumns = CrudConfig::getForeignKeyPrettierColumns();
$gen->prettyTableNames = CrudConfig::getPrettyTableNames();
$gen->fixPrettyColumnNames = CrudConfig::getPrettyColumnNames();


$gen->urlTransformerIf = CrudConfig::getListUrlTransformerIfCallback();

$gen->generateLists();




