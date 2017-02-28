<?php


namespace Mail;


use Umail\Renderer\PhpRenderer;
use Umail\TemplateLoader\FileTemplateLoader;
use Umail\Umail;

class OrderProviderMail
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
//    'shop_name' => 'Leaderfit',
            'total_paid' => '128.80 €',
            'order_details' => [
                [
                    'reference' => '#P_000382',
                    'name' => 'Ballon bleu',
                    'unit_price' => '23.90 €',
                    'quantity' => '2',
                    'price' => '47.80 €',
                ],
                [
                    'reference' => '#P_000385',
                    'name' => 'Haltères 6kg',
                    'unit_price' => '27 €',
                    'quantity' => '3',
                    'price' => '81 €',
                ],
            ],
        ];
        $res = $mail->to($to)
            ->from(MAIL_FROM)
            ->subject("Ordering products for Leaderfit")
            ->setVars($vars)
            ->setTemplateLoader(FileTemplateLoader::create()->setDir(APP_ROOT_DIR . "/mails")->setSuffix('.php'))
            ->setTemplate('zilu/order_provider')
            ->setRenderer(PhpRenderer::create())
            ->send();
        a($res);

    }


}