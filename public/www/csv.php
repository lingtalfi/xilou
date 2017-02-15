<?php


use Csv\CsvUtil;
use QuickPdo\QuickPdo;
use QuickPdo\QuickPdoInfoTool;

require_once __DIR__ . "/../init.php";


$f = __DIR__ . "/../assets/csv-commande/test1.csv";
$f = "/Volumes/Macintosh HD 2/it/php/projects/xilou/pprivate/Pierre/PRIX MATERIEL LF-msdos.csv";
$arr = CsvUtil::readFile($f, ';');


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
    if (59 === count($item)) {

        a($item);
        array_walk($item, function (&$v) {
            $v = utf8_decode($v);
        });
        $filteredItem = [
            'reference' => $item[0],
            'reference_fournisseur' => $item[1],
            'fournisseur' => $item[2],
            'produits' => $item[3],
            'libelle_origine' => $item[4],
            'unit' => $item[5],
            'pmp_achat_dollar' => toDecimal($item[6]),
            'pmp_achat_euro' => toDecimal($item[7]),
            'port' => toDecimal($item[8]),
            'paht_frais' => toDecimal($item[9]),
            'pv_public_ht' => toDecimal($item[11]),
            'marge_prix_public' => toDecimal($item[12]),
            'pv_public_ttc' => toDecimal($item[13]),
            'prix_pro' => toDecimal($item[14]),
            'remise_club' => toDecimal($item[15]),
            'marge_prix_club' => toDecimal($item[16]),
            'prix_franchise' => toDecimal($item[17]),
            'remise_franchise' => toDecimal($item[18]),
            'marge_franchise' => toDecimal($item[19]),
            'poids_net' => toDecimal($item[20]),
            'poids' => toDecimal($item[21]),
            'famille_produit' => $item[22],
            'dimensions' => $item[23],
            'code_compta' => $item[24],
            'description' => $item[25],
            'photos' => $item[26],
            'tva' => toDecimal($item[27]),
            'code_ean' => $item[28],
            'date_arrivee' => $item[29],
            'm3' => $item[30],
        ];


        QuickPdo::insert('csv_prix_materiel', $filteredItem);

    }
}




/**
 * référence
 * référence fournisseur
 * fournisseur
 * produits
 * libellé d'origine
 * unit
 * pmp achat ht
 * pmp achat ht€
 * port
 * paht+frais
 * pv public ht
 * marge % prix public
 * prix PRO
 * remise CLUB
 * marge/prix club
 * prix francehise
 * remise franchise
 * marge franchise
 * poids net
 * poids brut
 * famille produit
 * dimensions
 * code compta
 * description
 * photos
 * tva
 * code ean
 * date arrivée catalogue
 * M3
 */