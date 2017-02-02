<?php

// generate forms files
use Crud\CrudConfig;
use Crud\Util\CrudFormGenerator;



//--------------------------------------------
// SCRIPT
//--------------------------------------------
$prefs = [];
require_once CrudConfig::getCrudFilesPreferencesAutoFile();


$gen = new CrudFormGenerator();
$gen->foreignKeyPrettierColumns = $prefs['foreignKeyPrettierColumns'];
$gen->prettyTableNames = $prefs['prettyTableNames'];
$gen->fixPrettyColumnNames = $prefs['prettyColumnNames'];


$gen->generateForms();