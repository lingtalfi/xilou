<?php


namespace Mail;


use Commande\CommandeUtil;
use CommandeHasArticle\CommandeHasArticleUtil;
use Fournisseur\FournisseurUtil;
use QuickPdo\QuickPdo;
use Umail\Renderer\PhpRenderer;
use Umail\TemplateLoader\FileTemplateLoader;
use Umail\Umail;
use Updf\AppUpdfUtil;

class OrderProviderConfMail
{

    public static function create()
    {
        return new static();
    }


    public static function sendByCommandeIdFournisseurId($to, $commandeId, $fournisseurId, $signature = "leaderfit")
    {

        $res = 0;

        if (false !== ($commande = QuickPdo::fetch("select reference from commande where id=" . (int)$commandeId))) {
            $mail = Umail::create();
            $orderName = $commande['reference'];

            list($prixTotal, $poidsTotal, $volumeTotal) = CommandeUtil::getCommandeSumInfo($commandeId);


            $signImg = ('hldp' === $signature) ? "email-signature2.jpg" : "email-signature.jpg";
            $vars = [
                'order_number' => $orderName,
                'total_paid' => $prixTotal . ' €',
                'signature' => $mail->embedFile(APP_ROOT_DIR . "/www/img/" . $signImg),
                'company' => "Leaderfit Équipement",
            ];


            $location = "/tmp/updf/zilu-personal-tmp.pdf";
            $dir = dirname($location);
            if (false === is_dir($dir)) {
                mkdir($dir);
            }

            AppUpdfUtil::createProPurchaseOrderInvoicePdf($location, $commandeId, $fournisseurId);


            $res = $mail->to($to)
                ->from(MAIL_FROM)
//            ->subject("Pre-ordering products for Leaderfit")
                ->subject("Purchase order")
                ->setVars($vars)
                ->setTemplateLoader(FileTemplateLoader::create()->setDir(APP_ROOT_DIR . "/mails")->setSuffix('.php'))
                ->setTemplate('zilu/order_provider_conf')
                ->setRenderer(PhpRenderer::create())
                ->attachFile($location, "purchase-order.pdf", "application/pdf", true)
                ->send();
        }
        return $res;
    }


    /**
     * All lines must belong to the same provider.
     * (There should be only one provider.)
     *
     */
    public static function sendByLineIds(array $lineIds, $toOverride = null, $signature = "leaderfit")
    {
        $res = 0;

        if (count($lineIds) > 0) {
            // todo: ....
        }
        $mail = Umail::create();
        list($prixTotal, $poidsTotal, $volumeTotal) = CommandeUtil::getCommandeSumInfoByLineIds($lineIds);


        $signImg = ('hldp' === $signature) ? "email-signature2.jpg" : "email-signature.jpg";
        $vars = [
            'total_paid' => $prixTotal . ' €',
            'signature' => $mail->embedFile(APP_ROOT_DIR . "/www/img/" . $signImg),
            'company' => "Leaderfit Équipement",
        ];


        $location = "/tmp/updf/zilu-personal-tmp.pdf";
        $dir = dirname($location);
        if (false === is_dir($dir)) {
            mkdir($dir);
        }

        $fournisseurId = 0;
        AppUpdfUtil::createProPurchaseOrderInvoicePdfByLineIds($location, $fournisseurId, $lineIds);


        $res = $mail->to($to)
            ->from(MAIL_FROM)
//            ->subject("Pre-ordering products for Leaderfit")
            ->subject("Purchase order")
            ->setVars($vars)
            ->setTemplateLoader(FileTemplateLoader::create()->setDir(APP_ROOT_DIR . "/mails")->setSuffix('.php'))
            ->setTemplate('zilu/order_provider_conf')
            ->setRenderer(PhpRenderer::create())
            ->attachFile($location, "purchase-order.pdf", "application/pdf", true)
            ->send();
        return $res;
    }

    //--------------------------------------------
    //
    //--------------------------------------------


    private static function getCommandeIdToFournisseurIds(array $lineIds)
    {
        $sIds = implode(', ', $lineIds);
        $items = QuickPdo::fetchAll('select commande_id, fournisseur_id from commande_has_article where id in(' . $sIds . ')');
        $ret = [];
        foreach ($items as $item) {
            if (!array_key_exists($item['commande_id'], $ret)) {
                $ret[$item['commande_id']] = [];
            }
            if (!array_key_exists($item['fournisseur_id'], $ret[$item['commande_id']])) {
                $ret[$item['commande_id']][$item['fournisseur_id']] = [];
            }
            if (!in_array($item['id'], $ret[$item['commande_id']][$item['fournisseur_id']])) {
                $ret[$item['commande_id']][$item['fournisseur_id']][] = $item['id'];
            }
        }
        return $ret;
    }


}