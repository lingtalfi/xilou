<?php


namespace Mail;


use Umail\Renderer\PhpRenderer;
use Umail\TemplateLoader\FileTemplateLoader;
use Umail\Umail;

class OrderProviderConfMail
{

    public static function create()
    {
        return new static();
    }


    public static function send($to)
    {
        $mail = Umail::create();


        $vars = [
            'order_number' => 'C_000938',
            'total_paid' => '128.80 €',
            'signature' => $mail->embedFile(APP_ROOT_DIR . "/www/img/email-signature.jpg"),
            'company' => "Leaderfit", // HDLP
            'order_details' => [
                [
                    'reference' => '#P_000382',
                    'provider_reference' => '#jKFZEO',
                    'name' => 'Ballon bleu',
                    'img_src' => $mail->embedFile(APP_ROOT_DIR . "/www/img/ballon-paille-bleu.jpg"),
                    'ean' => '3825203492042',
                    'packing' => '2 in a box',
                    'description' => '20cm wide',
                    'logo' => $mail->embedFile(APP_ROOT_DIR . "/www/img/logo.jpg"),
                    'unit_price' => '23.90 €',
                    'quantity' => '2',
                    'price' => '47.80 €',
                ],
                [
                    'reference' => '#P_000385',
                    'provider_reference' => '#fzAJFFOZz',
                    'name' => 'Haltères 6kg',
                    'img_src' => $mail->embedFile(APP_ROOT_DIR . "/www/img/pilates-ring-lf-noir.jpg"),
                    'ean' => '3825205632042',
                    'packing' => 'par lot de 3',
                    'description' => '2m',
                    'logo' => $mail->embedFile(APP_ROOT_DIR . "/www/img/logo2.png"),
                    'unit_price' => '27 €',
                    'quantity' => '3',
                    'price' => '81 €',
                ],
            ],
        ];
        $res = $mail->to($to)
            ->from('zilu-bot@leaderfit-equipement.com')
//            ->subject("Pre-ordering products for Leaderfit")
            ->subject("Purchase order")
            ->setVars($vars)
            ->setTemplateLoader(FileTemplateLoader::create()->setDir(APP_ROOT_DIR . "/mails")->setSuffix('.php'))
            ->setTemplate('zilu/order_provider_conf')
            ->setRenderer(PhpRenderer::create())
            ->send();
        a($res);

    }


}