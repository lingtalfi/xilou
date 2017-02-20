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


$f = "/Users/pierrelafitte/Downloads/COMMANDE ZILU 02-2017.xlsx";
$f = "/Users/lafitte/Downloads/COMMANDE ZILU 02-2017.xlsx";


$pdfPath = "/private/tmp/updf/zilu-personal-tmp.pdf";

//a(Umail::create()
//    ->to("lingtalfi@gmail.com")
//    ->from('ling@localhost.com')
//    ->attachFile($pdfPath)
//    ->subject("Hello")
//    ->htmlBody("<span style='color: red'>hello, this is just a test message</span>")
//    ->send());
//az();


$mail = "lingtalfi@gmail.com";
$commandeId = 1;


$providerId = 1;
$signature = 'leaderfit';

$mail = MAIL_DIDIER;
if (array_key_exists('test', $_GET)) {
    $mail = MAIL_ZILU;
}

$output = "";

try {
    $n = OrderProviderConfMail::sendByCommandeIdFournisseurId($mail, $commandeId, $providerId, $signature);
    a($n);
    if (1 === $n) {
        $output = [
            'success' => 'ok',
        ];
    } else {
        $output = [
            "error" => "Une erreur est survenue, le mail n'a pas été envoyé; veuillez contacter le webmaster",
        ];
    }
} catch (\Exception $e) {
    az($e);
    $output = [
        'error' => $e->getMessage(),
    ];
}

a($output);