<?php


namespace Mail;


use Umail\Renderer\PhpRenderer;
use Umail\TemplateLoader\FileTemplateLoader;
use Umail\Umail;

class OrderConfMail
{

    public static function create()
    {
        return new static();
    }


    public static function send($to)
    {


        //------------------------------------------------------------------------------/
        // EMBED A FILE
        //------------------------------------------------------------------------------/
        $logoFile = APP_ROOT_DIR . "/www/img/leaderfit-logo-new.jpg";
        $mail = Umail::create();


        $vars = [
            'order_number' => 'C_000938',
            'order_estimated_date' => '2017-04-13',
            'shop_name' => 'Leaderfit',
            'total_paid' => '128.80 €',
            'shop_url' => 'http://leaderfit-equipement.com/',
            'shop_logo' => $mail->embedFile($logoFile),
            'order_details' => [
                [
                    'reference' => '#P_000382',
                    'provider_reference' => 'XKOFP',
                    'fournisseur' => 'Whozang',
                    'img_src' => $mail->embedFile(APP_ROOT_DIR . "/www/img/ballon-paille-bleu.jpg"),
                    'name' => 'Ballon bleu',
                    'unit_price' => '23.90 €',
                    'quantity' => '2',
                    'price' => '47.80 €',
                ],
                [
                    'reference' => '#P_000385',
                    'provider_reference' => 'DXKOFP',
                    'fournisseur' => 'Asia carpet',
                    'img_src' => $mail->embedFile(APP_ROOT_DIR . "/www/img/pilates-ring-lf-noir.jpg"),
                    'name' => 'Haltères 6kg',
                    'unit_price' => '27 €',
                    'quantity' => '3',
                    'price' => '81 €',
                ],
            ],
        ];
        $res = $mail->to($to)
            ->from('zilu-bot@leaderfit-equipement.com')
            ->subject("Commande en cours de préparation")
            ->setVars($vars)
            ->setTemplateLoader(FileTemplateLoader::create()->setDir(APP_ROOT_DIR . "/mails")->setSuffix('.php'))
            ->setTemplate('zilu/order_conf')
            ->setRenderer(PhpRenderer::create())
            ->send();
        a($res);

    }


}