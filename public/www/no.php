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
use Umail\Umail;
use Util\ArrayRenderer;
use Util\GeneralUtil;
use Util\RowsRenderer;

require_once __DIR__ . "/../init.php";


$tel = "09 34 02 38 48";
$tel2 = "06 34 02 38 48";
$tel = "06 34 02 38 48";
$tel2 = "09 34 02 38 48";


list($phone, $mobile) = TelHelper::getPhoneAndMobile($tel, $tel2);

a($phone, $mobile);