<?php


use Csv\CsvUtil;
use QuickPdo\QuickPdo;
use QuickPdo\QuickPdoInfoTool;

require_once __DIR__ . "/../init.php";


/**
 * fournisseur - 1
 * REF HLDP - 2
 * REF - 3
 * PRODUITS FR - 4
 * PRODUCTS EN - 5
 * MOQ  - 6
 * Details  - 7
 * Client  - 8
 * Quantity  - 9
 * Unit  - 10
 * Unit price - 11
 * Total amount - 12
 * Packing details - 13
 * m3 - 14
 * poids - 15
 * m3/unit - 16, string
 * poids/unit - 17
 * units/20 - 18
 * units/40 - 19
 * units/40HQ - 20
 * LF - 21
 * référence - 22  (demander ce que c'est...)
 * champ1 - 26
 * champ2 - 27
 * champ3 - 28
 * champ4 - 29
 *
 * Fournisseur nom 1- 38
 * Fournisseur nom 2- 39
 *
 */
$f = APP_ROOT_DIR . "/../pprivate/Pierre/Commande Fournisseurs AP-fournisseurs.csv";


//$a = QuickPdoInfoTool::getColumnNames("csv_fournisseurs_fournisseurs");
//foreach($a as $v){
//    echo "'" . $v . "' => \$item[0],<br>";
//}
//exit;


$length = 40;
$arr = CsvUtil::readFile($f, ';');

$table = 'csv_fournisseurs_fournisseurs';


function checkLength($length, array $arr)
{
    foreach ($arr as $item) {
        if (count($item) !== $length) {
            throw new \Exception("length error");
        }
    }
}

//checkLength($length, $arr);


function toDecimal($string)
{
    if (preg_match('![0-9]+(,[0-9]+)?!', $string, $m)) {
        return str_replace(',', '.', $m[0]);
    }

    return "0.00";
}

//az(toDecimal("66,86%"));

//$item = $arr[96];
//az($arr);


foreach ($arr as $item) {

    if ($length === count($item)) {

        if ('' !== $item[1]) {

            a($item);
            array_walk($item, function (&$v) {
                $v = utf8_decode($v);
            });

            $filteredItem = [
                'fournisseur' => $item[1],
                'ref_hldp' => $item[2],
                'ref' => $item[3],
                'produits_fr' => $item[4],
                'produits_en' => $item[5],
                'moq' => $item[6],
                'details' => $item[7],
                'client' => $item[8],
                'quantity' => $item[9],
                'unit' => $item[10],
                'unit_price' => $item[11],

                'total_amount' => $item[12],
                'packing_details' => $item[13],

                'm3' => $item[14],
                'poids' => $item[15],
                'm3_unit' => $item[16],
                'poids_unit' => $item[17],
                'units_20' => $item[18],
                'units_40' => $item[19],
                'units_40hq' => $item[20],
                'lf' => $item[21],
                'reference' => $item[22],
                'champ1' => $item[26],
                'champ2' => $item[27],
                'champ3' => $item[28],
                'champ4' => $item[29],
                'fournisseur_nom1' => $item[38],
                'fournisseur_nom2' => $item[39],
            ];
            QuickPdo::insert($table, $filteredItem);
        }
    }
}



