<?php


use Csv\CsvUtil;
use QuickPdo\QuickPdo;
use QuickPdo\QuickPdoInfoTool;

require_once __DIR__ . "/../init.php";


/**
 * Fournisseur
 * Ref LF
 * Produit
 * Livr� le
 * Quantit�
 * Prix
 * Nbre de pdts d�fectueux
 * Date de notification
 * Demande de remboursement
 * Montant Rembours�
 * Remboursement // complet
 * Forme
 * Statut
 * Avoir LF
 * Date du remboursement
 * Probl�mes
 * Avancement
 */
$f = APP_ROOT_DIR . "/../pprivate/Pierre/Commande Fournisseurs AP-sav.csv";


$length = 22;
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


//$a = QuickPdoInfoTool::getColumnNames("csv_prix_materiel");
//foreach($a as $v){
//    echo "'" . $v . "' => \$item[0],<br>";
//}
//exit;


function toDecimal($string)
{
    if (preg_match('![0-9]+(,[0-9]+)?!', $string, $m)) {
        return str_replace(',', '.', $m[0]);
    }

    return "0.00";
}

//az(toDecimal("66,86%"));

//$item = $arr[96];
//az($item);


foreach ($arr as $item) {
    if ($length === count($item)) {

        if ('' !== $item[0]) {

            a($item);
            array_walk($item, function (&$v) {
                $v = utf8_decode($v);
            });

            $filteredItem = [
                'fournisseur' => $item[0],
                'reference_lf' => $item[1],
                'produit' => $item[2],
                'livre_le' => $item[3],
                'quantite' => $item[4],
                'prix' => toDecimal($item[5]),
                'nb_produits_defec' => $item[6],
                'date_notif' => $item[7],
                'demande_remboursement' => toDecimal($item[8]),
                'montant_rembourse' => toDecimal($item[9]),
                'remboursement' => $item[10],
                'forme' => $item[11],
                'statut' => $item[12],
                'avoir_lf' => $item[13],
                'date_remboursement' => $item[14],
                'problemes' => $item[15],
                'avancement' => $item[16],
            ];
            QuickPdo::insert('csv_fournisseurs_sav', $filteredItem);
        }
    }
}




