<?php


use Updf\DummyProPurchaseOrderInvoiceModel;
use Updf\Model\DummyInvoiceModel;
use Updf\TemplateLoader\TemplateLoader;
use Updf\TemplateLoader\TemplateLoaderInterface;
use Updf\Updf;





require __DIR__ . "/../init.php";
require_once __DIR__ . "/TCPDF/tcpdf.php";

//Updf::create()
//    ->addElement(InvoiceHeaderComponent::create())
//    ->addElement(InvoiceAddressBlockComponent::create())
//    ->addElement(InvoiceSummaryTableComponent::create())
//    ->addElement(InvoiceProductsTableComponent::create())
//    ->render();


//Updf::create()
//    ->setModel(DummyInvoiceModel::create())
//    ->setTemplate('invoice')
//    ->setTemplateLoader(TemplateLoader::create())
//    ->render();
//exit;



Updf::create()
    ->setTemplateLoader(TemplateLoader::create()->setTemplateDir(APP_ROOT_DIR . "/pdf"))
    ->setModel(DummyProPurchaseOrderInvoiceModel::create())
    ->setTemplate('mail-purchase-order')
    ->render();

