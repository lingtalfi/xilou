<?php


namespace SrdExport\Exporter\Helper;


use QuickPdo\QuickPdo;
use UniqueNameGenerator\Generator\SimpleFileSystemUniqueNameGenerator;

class ExporterHelper
{

    public static function datetimeGetDate($datetime)
    {
        $p = explode(' ', $datetime);
        return $p[0];
    }

    public static function datetimeGetHoursMinutes($datetime)
    {
        $p = explode(' ', $datetime);
        return $p[1];
    }


    public static function getTotalTtcWithoutReductionWithoutShippingByCart(array $orderDetailRows)
    {
        $t = 0;
        foreach ($orderDetailRows as $row) {
            $t += $row['product_price'] * $row['product_quantity'];
        }
        return $t;
    }

    public static function getFileName($type, $dstDir)
    {
        $f = $dstDir . '/' . $type . '-' . time() . ".txt";
        return SimpleFileSystemUniqueNameGenerator::create()->generate($f);
    }


    // deprecated: too complicated to test
//    public static function getTotalTtcWithoutReductionWithoutShippingByCart($cart)
//    {
//        $products = $cart->getProducts();
//        $total_cart_ttc_without_reduction_without_shipping = 0;
//        foreach ($products as $product) {
//            $total_cart_ttc_without_reduction_without_shipping += $product['quantity'] * $product['price_without_reduction'];
//        }
//        return $total_cart_ttc_without_reduction_without_shipping;
//    }


    public static function getOrderDetailRows($orderId)
    {
        return QuickPdo::fetchAll('
select
 
id_tax_rules_group,
product_reference as reference,
product_quantity as quantity,
product_quantity,
product_price,
total_price_tax_incl,
total_price_tax_excl,
reduction_percent,
reduction_amount
 
from
ps_order_detail
where id_order=' . $orderId . '
');
    }
}