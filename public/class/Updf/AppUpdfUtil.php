<?php


namespace Updf;

use Updf\Model\FooterModel;
use Updf\TemplateLoader\TemplateLoader;

class AppUpdfUtil
{

    public static function createProPurchaseOrderInvoicePdf($location, $commandeId, $fournisseurId)
    {
        require_once APP_ROOT_DIR . "/www/TCPDF/tcpdf.php";
        Updf::create()
            ->setTemplateLoader(TemplateLoader::create()->setTemplateDir(APP_ROOT_DIR . "/pdf"))
            ->setModel(ProPurchaseOrderInvoiceModel::create()->prepareByCommandeIdFournisseurId($commandeId, $fournisseurId))
//    ->setModel(DummyProPurchaseOrderInvoiceModel::create())
            ->setTemplate('mail-purchase-order')
            ->setFooterModel(FooterModel::create()->setFooterText("Leaderfit - France"))
            ->render($location);

    }


    public static function createProPurchaseOrderInvoicePdfByLineIds($location, $fournisseurId, array $lineIds)
    {
        require_once APP_ROOT_DIR . "/www/TCPDF/tcpdf.php";
        Updf::create()
            ->setTemplateLoader(TemplateLoader::create()->setTemplateDir(APP_ROOT_DIR . "/pdf"))
            ->setModel(ProPurchaseOrderInvoiceByLinesModel::create()->prepareByCommandeIdFournisseurId($fournisseurId, $lineIds))
//    ->setModel(DummyProPurchaseOrderInvoiceModel::create())
            ->setTemplate('mail-purchase-order-by-lines')
            ->setFooterModel(FooterModel::create()->setFooterText("Leaderfit - France"))
            ->render($location);

    }
}