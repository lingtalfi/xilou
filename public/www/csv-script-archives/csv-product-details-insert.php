<?php


use Mail\OrderConfMail;
use Mail\OrderProviderConfMail;
use QuickPdo\QuickPdo;

require_once __DIR__ . "/../init.php";


$f = __DIR__ . "/services/items.txt";
$c = file($f);
$items = [];
foreach ($c as $line) {
    $items[] = json_decode($line);
}


?>
    <table>
        <?php
        foreach ($items as $obj) {
            ?>
            <tr>
                <td><?php echo $obj->ref; ?></td>
                <td><?php echo $obj->productFr; ?></td>
                <td><?php echo $obj->product; ?></td>
                <td><?php echo $obj->photo; ?></td>
                <td><?php echo $obj->features; ?></td>
                <td><?php echo $obj->logo; ?></td>
                <td><?php echo $obj->packing; ?></td>
                <td><?php echo $obj->ean; ?></td>
            </tr>
            <?php

            try {
                QuickPdo::insert("csv_product_details", [
                    'ref' => $obj->ref,
                    'product_fr' => utf8_encode($obj->productFr),
                    'product' => utf8_encode($obj->product),
                    'photo' => '/' . $obj->photo,
                    'features' => utf8_encode($obj->features),
                    'logo' => '/' . $obj->logo,
                    'packing' => utf8_encode($obj->packing),
                    'ean' => $obj->ean,

                ]);
            } catch (\Exception $e) {
                a($e->getMessage());
            }
        }
        ?>
    </table>
<?php