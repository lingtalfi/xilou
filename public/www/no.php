<?php


use CommandeHasArticle\CommandeHasArticleUtil;
use CsvExport\CommandeExporterUtil;
use DevisHasCommandeHasArticle\DevisHasCommandeHasArticleUtil;

require_once __DIR__ . "/../init.php";
require_once __DIR__ . '/PHPExcel/Classes/PHPExcel.php';





$commandeId = 1;
$file = "zilu.xlsx";
CommandeExporterUtil::createCsvFileByCommande($file, $commandeId, 'container');


//$file_url = 'http://zilu/zilu.xlsx';
//header('Content-Type: application/octet-stream');
//header("Content-Transfer-Encoding: Binary");
//header("Content-disposition: attachment; filename=\"" . basename($file_url) . "\"");
//readfile($file_url); // do the double-download-dance (dirty but worky)


