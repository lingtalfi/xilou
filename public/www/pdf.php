<?php


use Updf\Component\Ling\Invoice\InvoiceAddressBlockComponent;
use Updf\Component\Ling\Invoice\InvoiceHeaderComponent;
use Updf\Component\Ling\Invoice\InvoiceOrderProductsTableComponent;
use Updf\Component\Ling\Invoice\InvoiceProductsTableComponent;
use Updf\Component\Ling\Invoice\InvoiceSummaryTableComponent;
use Updf\Model\InvoiceModel;
use Updf\Updf;


require __DIR__ . "/../init.php";
require_once __DIR__ . "/TCPDF/tcpdf.php";

//Updf::create()
//    ->addElement(InvoiceHeaderComponent::create())
//    ->addElement(InvoiceAddressBlockComponent::create())
//    ->addElement(InvoiceSummaryTableComponent::create())
//    ->addElement(InvoiceProductsTableComponent::create())
//    ->render();


Updf::create()
    ->setModel(InvoiceModel::create())
    ->setVariables([])
    ->setTemplate('invoice')
//    ->setTemplate('invoice_b')
    ->render();