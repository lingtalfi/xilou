<?php


namespace Updf\Model;


use Updf\Util\UpdfUtil;

class InvoiceModel extends AbstractModel
{


    //------------------------------------------------------------------------------/
    //
    //------------------------------------------------------------------------------/
    public $theme_color_header;
    public $theme_color_border;
    public $theme_color_border_lighter;
    public $theme_color_line_even;
    public $theme_color_line_odd;
    public $theme_font_size_text;
    public $theme_font_size_header;
    public $theme_font_size_product;
    public $theme_height_header;
    public $theme_table_padding;

    //------------------------------------------------------------------------------/
    //
    //------------------------------------------------------------------------------/
    // address tab
    public $text_delivery_address;
    public $text_billing_address;

    // summary tab
    public $text_invoice_number;
    public $text_invoice_date;
    public $text_order_reference;
    public $text_order_date;


    // product tab
    public $text_reference;
    public $text_product;
    public $text_tax_rate;
    public $text_base_price; // base price as set in the bo
    public $text_unit_price; // base price with discount applied
    public $text_quantity;
    public $text_total;


    // cart rules
    public $text_discounts;


    // tax tab
    public $text_tax_detail;
    public $text_tax_rate_label;
    public $text_tax_base_price;
    public $text_tax_total;
    public $text_tax_exempted;


    //------------------------------------------------------------------------------/
    //
    //------------------------------------------------------------------------------/
    // address tab
    public $shop_address;
    public $delivery_address;
    public $billing_address;

    // summary tab
    public $invoice_number;
    public $invoice_date;
    public $order_reference;
    public $order_date;


    // products tab
    public $display_product_images;
    public $order_details;
    public $cart_discounts;


    // tax tab
    public $tax_exempt;
    public $tax_details;


    public function __construct()
    {
        $this->theme_color_header = "#F0F0F0";
        $this->theme_color_border = "#000000";
        $this->theme_color_border_lighter = "#cccccc";
        $this->theme_color_line_even = "#ffffff";
        $this->theme_color_line_odd = "#f9f9f9";
        $this->theme_font_size_text = "9pt";
        $this->theme_font_size_header = "9pt";
        $this->theme_font_size_product = "9pt";
        $this->theme_height_header = "20px";
        $this->theme_table_padding = "4px";

        //------------------------------------------------------------------------------/
        //
        //------------------------------------------------------------------------------/
        // address tab
        $this->text_delivery_address = "Delivery Address";
        $this->text_billing_address = "Billing Address";

        // summary tab
        $this->text_invoice_number = "Invoice Number";
        $this->text_invoice_date = "Invoice Date";
        $this->text_order_reference = "Order Reference";
        $this->text_order_date = "Order Date";


        // product tab
        $this->text_reference = "Reference";
        $this->text_product = "Product";
        $this->text_tax_rate = "Tax Rate";
        $this->text_base_price = "Base Price";
        $this->text_unit_price = "Unit Price";
        $this->text_quantity = "Qty";
        $this->text_total = "Total";

        // cart rules
        $this->text_discounts = 'Discounts';

        // tax tab
        $this->text_tax_exempted = "Exempt of VAT according to section 259B of the General Tax Code.";
        $this->text_tax_detail = "Tax Detail";
        $this->text_tax_rate_label = "Tax Rate";
        $this->text_tax_base_price = "Base Price";
        $this->text_tax_total = "Total Tax";
        $this->text_no_taxes = "No taxes";


        //------------------------------------------------------------------------------/
        //
        //------------------------------------------------------------------------------/
        // address tab
        $this->shop_address = "MyCompany
FRANCE";

        $this->delivery_address = "Ling talfi
110 rue verte
49000 Domours    
    ";
        $this->billing_address = "Ling talfi
110 rue verte
49000 Domours    
    ";


        // summary tab
        $this->invoice_number = "#FR_0000024";
        $this->invoice_date = "2017-02-10";
        $this->order_reference = "C_00003523";
        $this->order_date = "2017-02-10";


        // products tab
        $this->display_product_images = true;
        $this->order_details = [
            [
                'product_reference' => '1438',
                'product_name' => 'Ballon paille',
                'product_image_src' => UpdfUtil::getImgSrc(__DIR__ . "/img/ballon-paille-bleu.jpg"),
                'tax_label' => "20 %",
                'base_price' => "9,20 €",
                'unit_price' => "4,50 €",
                'quantity' => "1",
                'total' => "4,50 €",
            ],
            [
                'product_reference' => '1470',
                'product_name' => 'Corde à sauter',
                'product_image_src' => UpdfUtil::getImgSrc(__DIR__ . "/img/pilates-ring-lf-noir.jpg"),
                'tax_label' => "20 %",
                'base_price' => "3,50 €",
                'unit_price' => "1,20 €",
                'quantity' => "2",
                'total' => "2,40 €",
            ],
        ];


        // cart rules
        $this->cart_discounts = [
            [
                'name' => "Réduction 20 % pour tout achat de plus de 50 €",
                'price' => "-10,46 €",
            ],
        ];


        // tax tab
        $this->tax_exempt = true;
        $this->tax_details = [
            [
                'label' => "Produits",
                'tax_label' => "20.000",
                'base_price' => "34,50 €",
                'total_taxes' => "6.90 €",
            ],
            [
                'label' => "Livraison",
                'tax_label' => "20.000",
                'base_price' => "8,15 €",
                'total_taxes' => "1,63 €",
            ],
        ];


    }
}