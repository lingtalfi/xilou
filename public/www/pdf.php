<?php


use Updf\Component\Ling\Invoice\InvoiceAddressBlockComponent;
use Updf\Component\Ling\Invoice\InvoiceHeaderComponent;
use Updf\Component\Ling\Invoice\InvoiceOrderProductsTableComponent;
use Updf\Component\Ling\Invoice\InvoiceSummaryTableComponent;
use Updf\Updf;


require __DIR__ . "/../init.php";
require_once __DIR__ . "/TCPDF/tcpdf.php";

Updf::create()
    ->addElement(InvoiceHeaderComponent::create())
    ->addElement(InvoiceAddressBlockComponent::create())
    ->addElement(InvoiceSummaryTableComponent::create())
    ->addElement(InvoiceOrderProductsTableComponent::create())
    ->render();


