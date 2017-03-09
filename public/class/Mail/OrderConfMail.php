<?php


namespace Mail;


use Commande\CommandeUtil;
use CommandeHasArticle\CommandeHasArticleUtil;
use CsvExport\CommandeExporterUtil;
use QuickPdo\QuickPdo;
use Umail\Renderer\PhpRenderer;
use Umail\TemplateLoader\FileTemplateLoader;
use Umail\Umail;
use Util\GeneralUtil;

class OrderConfMail
{

    public static function create()
    {
        return new static();
    }


    public static function sendByCommandeId($to, $commandeId, $estimatedDate = null)
    {

        $res = 0;

        if (null === $estimatedDate) {
            $estimatedDate = date("Y-m-d", time() + 30 * 86400);
        }

        if (false !== ($commande = QuickPdo::fetch("select reference from commande where id=" . (int)$commandeId))) {
            $orderName = $commande['reference'];

            //------------------------------------------------------------------------------/
            // EMBED A FILE
            //------------------------------------------------------------------------------/
            $logoFile = APP_ROOT_DIR . "/www/img/leaderfit-logo-new.jpg";
            $mail = Umail::create();

            list($prixTotal, $poidsTotal, $volumeTotal) = CommandeUtil::getCommandeSumInfo($commandeId);


            $dollarToEuroRate = GeneralUtil::getDollarToEuroRate();
            $prixTotalEuros = (float)str_replace(',','',$prixTotal) * (float)str_replace(',','',$dollarToEuroRate);



            $items = CommandeHasArticleUtil::getCommandeDetails((int)$commandeId);
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

//                $orderDetails[] = [
//                    'reference' => $item['reference_lf'],
//                    'provider_reference' => $item['reference_pro'],
//                    'fournisseur' => $item['fournisseur'],
//                    'img_src' => $mail->embedFile(APP_ROOT_DIR . "/www" . $image),
//                    'name' => $item['descr_fr'],
//                    'unit_price' => $unitPrice . ' €',
//                    'quantity' => $item['quantite'],
//                    'price' => $totalPrice . ' €',
//                ];
            }

            $vars = [
                'order_number' => $orderName,
                'order_estimated_date' => (string)$estimatedDate,
                'shop_name' => 'Leaderfit Equipement',
                'total_paid' => $prixTotalEuros . ' €',
                'shop_url' => 'http://leaderfit-equipement.com/',
                'shop_logo' => $mail->embedFile($logoFile),
                'order_details' => $orderDetails,
            ];

            $location = "/tmp/xilou/zilu-personal-tmp.xlsx";
            $dir = dirname($location);
            if (false === is_dir($dir)) {
                mkdir($dir);
            }
            CommandeExporterUtil::createCsvFileByCommande($location, $commandeId);





            $res = $mail->to($to)
                ->from(MAIL_FROM)
                ->subject("Commande en cours de préparation")
                ->setVars($vars)
                ->setRenderer(PhpRenderer::create())
                ->setTemplateLoader(FileTemplateLoader::create()->setDir(APP_ROOT_DIR . "/mails")->setSuffix('.php'))
//                ->setTemplate('zilu/order_conf')
                ->setTemplate('zilu/order_conf_with_xlsx')
                ->attachFile($location, "order-conf.xlsx", "application/vnd.openxmlformats-officedocument.spreadsheetml.sheet", true)
                ->send();
        }

        return $res;

    }


}