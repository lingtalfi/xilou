<?php


use Csv\CsvUtil;
use QuickPdo\QuickPdo;

require_once __DIR__ . "/../init.php";




$file = "/myphp/xilou/pprivate/Pierre/Products-list.csv";
$items = CsvUtil::readFile($file, ';');

foreach ($items as $item) {
    QuickPdo::insert('csv_product_list', [
        'ref_hldp' => $item[0],
        'ref_lf' => utf8_decode($item[1]),
        'produits' => utf8_decode($item[2]),
    ]);
}

