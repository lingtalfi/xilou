<?php


use Csv\CsvUtil;
use QuickPdo\QuickPdo;
use QuickPdo\QuickPdoInfoTool;

require_once __DIR__ . "/../init.php";


$table = 'csv_fournisseurs_containers';
$length = 121;


$s = 'CREATE TABLE IF NOT EXISTS `' . $table . '` (' . PHP_EOL;
$s .= '`id` int(11) NOT NULL AUTO_INCREMENT,' . PHP_EOL;


$aChamps = [];
/**
 * 'date_commande' => $item[0],
 * 'container' => $item[1],
 * 'produit_fr' => $item[2],
 * 'reference' => $item[3],
 * 'produits_fr' => $item[4],
 * 'produits_en' => $item[5],
 * 'details' => $item[6],
 * 'quantity' => $item[7],
 * 'unit' => $item[8],
 * 'unit_price' => $item[9],
 * 'total_price' => $item[10],
 * 'm3' => $item[11],
 * 'poids' => $item[12],
 * 'client' => $item[13],
 * 'ref_hldp' => $item[14],
 * 'ref_lf' => $item[15],
 * 'numero_commande' => $item[16],
 * 'm3_u' => $item[17],
 * 'kgs_u' => $item[18],
 * 'facture_lf' => $item[19],
 * 'commande_en_cours' => $item[20],
 * 'note' => $item[21],
 * 'livraison' => $item[22],
 * 'simulation_date' => $item[89],
 * 'simulation_date_2' => $item[90],
 */
$s .= 'PRIMARY KEY (`id`)' . PHP_EOL;
$s .= ') ENGINE=InnoDB  DEFAULT CHARSET=utf8;';
$s .= '';

//az($s);


$f = APP_ROOT_DIR . "/../pprivate/Pierre/Commande Fournisseurs AP-containers.csv";


//$a = QuickPdoInfoTool::getColumnNames("csv_fournisseurs_fournisseurs");
//foreach($a as $v){
//    echo "'" . $v . "' => \$item[0],<br>";
//}
//exit;


$arr = CsvUtil::readFile($f, ';');


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

//            a($item);
            array_walk($item, function (&$v) {
                $v = utf8_decode($v);
            });


            $filteredItem = [
                'date_commande' => $item[0],
                'container' => $item[1],
                'produit_fr' => $item[2],
                'reference' => $item[3],
                'produits_fr' => $item[4],
                'produits_en' => $item[5],
                'details' => $item[6],
                'quantity' => $item[7],
                'unit' => $item[8],
                'unit_price' => $item[9],
                'total_price' => $item[10],
                'm3' => $item[11],
                'poids' => $item[12],
                'client' => $item[13],
                'ref_hldp' => $item[14],
                'ref_lf' => $item[15],
                'numero_commande' => $item[16],
                'm3_u' => $item[17],
                'kgs_u' => $item[18],
                'facture_lf' => $item[19],
                'commande_en_cours' => $item[20],
                'note' => $item[21],
                'livraison' => $item[22],
                'simulation_date' => $item[89],
                'simulation_date_2' => $item[90],
            ];


//            $filteredItem = array_combine($aChamps, $item);
            QuickPdo::insert($table, $filteredItem);

        }
    }
}



