<?php

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
//    ->render();


Updf::create()
    ->setModel(HelloModel::create())
    ->setTemplate('hello')
    ->render();