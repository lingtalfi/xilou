<?php


namespace Updf;


use Updf\Model\LingAbstractModel;
use Updf\Util\UpdfUtil;

class DummyProPurchaseOrderInvoiceModel extends ProPurchaseOrderInvoiceModel
{




    protected function getThemeVariables()
    {
        return [];
    }



    protected function getTemplateVariables()
    {
        return [
            //------------------------------------------------------------------------------/
            // VARIABLES
            //------------------------------------------------------------------------------/
            // header
            'header_logo_width' => "100",
            'header_logo_img_src' => UpdfUtil::getImgSrc(APP_ROOT_DIR . "/www/img/leaderfit-logo-new.jpg"),
            'header_date' => date('Y-m-d'),
            'header_order' => "#FA000027",
            'header_provider' => "WOHLSTAND",


            // products
            'order_details' => [
                [
                    'reference_lf' => '1438',
                    'reference_pro' => '08377',
                    'product_name' => 'Ballon paille',
                    'product_image_src' => UpdfUtil::getImgSrc(APP_ROOT_DIR . "/www/img/ballon-paille-bleu.jpg"),
                    'ean' => '280238508324092',
                    'packing' => "PR",
                    'description' => "should blabla feozoh ozehif ohz",
                    'logo' => UpdfUtil::getImgSrc(APP_ROOT_DIR . "/www/img/logo.jpg"),
                    'unit_price' => "4,50 €",
                    'quantity' => "1",
                    'total' => "4,50 €",
                ],
                [
                    'reference_lf' => '1438',
                    'reference_pro' => '08377',
                    'product_name' => 'Ballon paille',
                    'product_image_src' => UpdfUtil::getImgSrc(APP_ROOT_DIR . "/www/img/ballon-paille-bleu.jpg"),
                    'ean' => '280238508324092',
                    'packing' => "PR",
                    'description' => "should blabla feozoh ozehif ohz",
                    'logo' => UpdfUtil::getImgSrc(APP_ROOT_DIR . "/www/img/logo.jpg"),
                    'unit_price' => "4,50 €",
                    'quantity' => "1",
                    'total' => "4,50 €",
                ],
            ],
            'total' => "14,68 €",
        ];
    }

}