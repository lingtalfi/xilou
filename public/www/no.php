<?php


use CsvExport\CommandeExporterUtil;

require_once __DIR__ . "/../init.php";
require_once __DIR__ . '/PHPExcel/Classes/PHPExcel.php';


CommandeExporterUtil::createCsvFileByCommande("zilu.xlsx", 1);


//require_once "/myphp/xilou/public/www/PHPExcel/Examples/17html.php";