<?php


namespace Mail;


use Commande\CommandeUtil;
use CommandeHasArticle\CommandeHasArticleUtil;
use QuickPdo\QuickPdo;
use Umail\Renderer\PhpRenderer;
use Umail\TemplateLoader\FileTemplateLoader;
use Umail\Umail;
use Util\GeneralUtil;

class OrderProviderConfMail
{

    public static function create()
    {
        return new static();
    }


    public static function sendByCommandeIdFournisseurId($to, $commandeId, $fournisseurId, $signature = "leaderfit")
    {
        $mail = Umail::create();

        $res = 0;

        if (false !== ($commande = QuickPdo::fetch("select reference from commande where id=" . (int)$commandeId))) {
            $orderName = $commande['reference'];

            list($prixTotal, $poidsTotal, $volumeTotal) = CommandeUtil::getCommandeSumInfo($commandeId);


            $signImg = ('hldp' === $signature) ? "email-signature2.jpg" : "email-signature.jpg";


            $items = CommandeHasArticleUtil::getCommandeDetailsByFournisseurId($commandeId, $fournisseurId);
            $orderDetails = [];
            foreach ($items as $item) {


                $unitPrice = $item['prix_override'];
                if ('' !== trim((string)$unitPrice)) {
                    $unitPrice = $item['prix'];
                }

                $totalPrice = $unitPrice * (int)$item['quantite'];
                $image = $item['photo'];
                if ('/' === trim($image)) {
                    $image = "/img/blank.jpg";
                }

                $orderDetails[] = [
                    'reference' => $item['reference_lf'],
                    'provider_reference' => $item['reference_pro'],
                    'fournisseur' => $item['fournisseur'],
                    'img_src' => $mail->embedFile(APP_ROOT_DIR . "/www" . $image),
                    'name' => $item['descr_fr'],
                    'unit_price' => $unitPrice . ' €',
                    'quantity' => $item['quantite'],
                    'price' => $totalPrice . ' €',
                    'ean' => $item['ean'],
                    'packing' => $item['unit'],
                    'description' => $item['descr_en'],
                    'logo' => $mail->embedFile(APP_ROOT_DIR . "/www/img/logo.jpg"),

                ];
            }


            $vars = [
                'order_number' => $orderName,
                'total_paid' => $prixTotal . ' €',
                'signature' => $mail->embedFile(APP_ROOT_DIR . "/www/img/" . $signImg),
                'company' => "Leaderfit", // HDLP
                'order_details' => $orderDetails,
            ];
            $res = $mail->to($to)
                ->from(MAIL_FROM)
//            ->subject("Pre-ordering products for Leaderfit")
                ->subject("Purchase order")
                ->setVars($vars)
                ->setTemplateLoader(FileTemplateLoader::create()->setDir(APP_ROOT_DIR . "/mails")->setSuffix('.php'))
                ->setTemplate('zilu/order_provider_conf')
                ->setRenderer(PhpRenderer::create())
                ->send();
        }
        return $res;
    }


}