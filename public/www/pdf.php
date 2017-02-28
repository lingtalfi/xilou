<?php


use Updf\DummyProPurchaseOrderInvoiceModel;
use Updf\Model\DummyInvoiceModel;
use Updf\Model\FooterModel;
use Updf\ProPurchaseOrderInvoiceModel;
use Updf\TemplateLoader\TemplateLoader;
use Updf\TemplateLoader\TemplateLoaderInterface;
use Updf\Updf;
use Util\GeneralUtil;


require __DIR__ . "/../init.php";
require_once __DIR__ . "/TCPDF/tcpdf.php";

//Updf::create()
//    ->addElement(InvoiceHeaderComponent::create())
//    ->addElement(InvoiceAddressBlockComponent::create())
//    ->addElement(InvoiceSummaryTableComponent::create())
//    ->addElement(InvoiceProductsTableComponent::create())
//    ->render();


Updf::create()
    ->setModel(DummyInvoiceModel::create())
    ->setTemplate('invoice')
    ->setTemplateLoader(TemplateLoader::create())
    ->render();
exit;


$location = APP_ROOT_DIR . "/www/test.pdf";

$commandeId = 1;
$fournisseurId = 1;
Updf::create()
    ->setTemplateLoader(TemplateLoader::create()->setTemplateDir(APP_ROOT_DIR . "/pdf"))
    ->setModel(ProPurchaseOrderInvoiceModel::create()->prepareByCommandeIdFournisseurId($commandeId, $fournisseurId))
//    ->setModel(DummyProPurchaseOrderInvoiceModel::create())
    ->setTemplate('mail-purchase-order')
    ->setFooterModel(FooterModel::create()->setFooterText("Leaderfit - France"))
    ->render($location);

