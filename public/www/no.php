<?php


use AdminTable\Listable\QuickPdoListable;
use AdminTable\View\AdminTableRenderer;
use Commande\AdminTable\CommandeAdminTable;
use CommandeHasArticle\CommandeHasArticleUtil;
use Csv\CsvUtil;
use CsvImport\CommandeImporterUtil;
use DbTransition\CommandeLigneStatut;
use Mail\OrderConfMail;
use Mail\OrderProviderConfMail;
use QuickPdo\QuickPdo;
use Util\ArrayRenderer;
use Util\GeneralUtil;
use Util\RowsRenderer;

require_once __DIR__ . "/../init.php";


$f = "/Users/pierrelafitte/Downloads/COMMANDE ZILU 02-2017.xlsx";
$f = "/Users/lafitte/Downloads/COMMANDE ZILU 02-2017.xlsx";








$mail = "lingtalfi@gmail.com";
$n = OrderProviderConfMail::send($mail);
a($n);