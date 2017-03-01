<?php


use CommandeHasArticle\CommandeHasArticleUtil;
use CsvExport\CommandeExporterUtil;
use DevisHasCommandeHasArticle\DevisHasCommandeHasArticleUtil;
use QuickPdo\QuickPdo;

require_once __DIR__ . "/../init.php";



a(QuickPdo::fetchAll("select logo from article where logo != '/' and logo != '' order by logo asc", [], \PDO::FETCH_COLUMN));


