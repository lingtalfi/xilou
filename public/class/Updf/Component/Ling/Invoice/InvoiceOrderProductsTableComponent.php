<?php


namespace Updf\Component\Ling\Invoice;


use Updf\Component\AbstractComponent;

class InvoiceOrderProductsTableComponent extends AbstractComponent
{


    /**
     * @var array of
     *      - reference: reference of the product
     *      - name: name of the product
     *      - taxRate: tax rate amound in percent applied to that type of product
     *      - basePrice: the product price HT
     *      - unitPrice: the price of one unit of that product, once discounts have been applied
     *      - quantity: the number of products ordered in the given order
     *      - total: quantity x unitPrice
     */
    public $products = [
        [
            "reference" => "P_000452",
            "name" => "Blue Ball",
            "tax_rate" => "20%",
            "base_price" => "80",
            "unit_price" => "80",
            "quantity" => "3",
            "total" => "240",
        ],
    ];

}