<?php


namespace Updf;


use Commande\CommandeUtil;
use CommandeHasArticle\CommandeHasArticleUtil;
use Fournisseur\FournisseurUtil;
use Updf\Model\LingAbstractModel;
use Updf\Util\UpdfUtil;
use Util\GeneralUtil;

class ProPurchaseOrderInvoiceByLinesModel extends LingAbstractModel
{

    private $templateVars;

    public function __construct()
    {
        parent::__construct();
        $this->templateVars = [];
    }

    public function prepareByCommandeIdFournisseurId($fournisseurId, array $lineIds)
    {


        if (false !== ($fournisseur = FournisseurUtil::getFournisseurNomById($fournisseurId))) {


            $items = CommandeHasArticleUtil::getCommandeDetailsByLineIds($lineIds);


            $orderDetails = [];
            $total = 0;

            foreach ($items as $item) {


                $image = $item['photo'];
                if ('/' === trim($image)) {
                    $image = '';
                } else {
                    $image = UpdfUtil::getImgSrc(APP_ROOT_DIR . "/www" . $image);
                }

                $logo = $item['logo'];
                if ('/' === trim($logo)) {
                    $logo = '';
                } else {
                    $logo = UpdfUtil::getImgSrc(APP_ROOT_DIR . "/www" . $logo);
                }

                $unitPrice = $item['prix'];
                if ('' !== trim((string)$item['prix_override'])) {
                    $unitPrice = $item['prix_override'];
                }

                $totalPrice = $unitPrice * (int)$item['quantite'];
                $total += $totalPrice;

                $totalPrice = GeneralUtil::formatDollar($totalPrice);


                $orderDetails[] = [
                    'reference_lf' => $item['reference_lf'],
                    'reference_pro' => $item['reference_pro'],
                    'product_name' => $item['descr_en'],
                    'product_image_src' => $image,
                    'ean' => $item['ean'],
                    'packing' => $item['unit'],
                    'description' => $item['long_desc_en'],
                    'logo' => $logo,
                    'unit_price' => $unitPrice . " €",
                    'quantity' => $item['quantite'],
                    'total' => $totalPrice . "$",
                ];

            }


            $total = GeneralUtil::formatDollar($total);

            $this->templateVars = [
                //------------------------------------------------------------------------------/
                // VARIABLES
                //------------------------------------------------------------------------------/
                // header
                'header_logo_width' => "120",
                'header_logo_img_src' => UpdfUtil::getImgSrc(APP_ROOT_DIR . "/www/img/leaderfit-logo-new.jpg"),
                'header_date' => date('Y-m-d'),
                'header_provider' => strtoupper($fournisseur),

                // products
                'order_details' => $orderDetails,
                'total' => $total . "$",
            ];
        }

        return $this;
    }


    protected function getThemeVariables()
    {
        return [];
    }

    protected function getTemplateVariables()
    {
        return $this->templateVars;
    }

    protected function getTextVariables()
    {

        return [
            "text_reference_lf" => "Our ref.",
            "text_reference_pro" => "Your ref.",
            "text_product" => "Product",
            "text_ean" => "Ean",
            "text_packing" => "Packing",
            "text_description" => "Description",
            "text_logo" => "Logo",
            "text_unit_price" => "Price",
            "text_quantity" => "Qty",
            "text_total" => "Total",
        ];
    }


}