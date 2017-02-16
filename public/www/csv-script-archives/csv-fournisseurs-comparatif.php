<?php


use Csv\CsvUtil;
use QuickPdo\QuickPdo;
use QuickPdo\QuickPdoInfoTool;
use Util\ArrayRenderer;

require_once __DIR__ . "/../init.php";

$table = "csv_fournisseurs_comparatif";

//$a = QuickPdoInfoTool::getColumnNames($table);
//$i = 11;
//foreach ($a as $v) {
//    echo "'" . $v . "' => \$item[" . $i++ . "],<br>";
//}
//exit;


$f = '/myphp/xilou/pprivate/Pierre/Commande Fournisseurs AP-comparatif.csv';
$arr = CsvUtil::readFile($f, ';');


//az(toDecimal("66,86%"));

//$item = $arr[96];
//az($arr);


foreach ($arr as $item) {


//            a($item);
    array_walk($item, function (&$v) {
        $v = utf8_decode($v);
    });


    $filteredItem = [
        'ref_hldp' => $item[5],
        'ref_lf' => $item[6],
        'produit' => $item[7],
        //
        'm3' => $item[10],
        'gw' => $item[11],
        'nw' => $item[12],
        'vendu_par' => $item[13],
        'ean' => $item[14],
        'nom_hldp' => $item[15],
        'nom_leaderfit' => $item[16],
        //
        'poids' => $item[18],
        'materiaux' => $item[19],
        'etat_import' => $item[20],
        'largeur' => $item[21],
        'hauteur' => $item[22],
        'longueur' => $item[23],
        'resistance' => $item[24],
        'autres' => $item[25],
        'MOQ' => $item[26],
        'packaging' => $item[27],
        'categorie' => $item[28],
        'descriptif' => $item[29],
        'url' => $item[30],
        //
        'en_products' => $item[31],
        'en_sold_by' => $item[32],
        'en_packaging' => $item[33],
        'en_material' => $item[34],
        //
        'en_description' => $item[36],
        'en_category' => $item[37],
        'es_products' => $item[38],
        'es_sold_by' => $item[39],
        'es_packaging' => $item[40],
        'es_material' => $item[41],
        //
        'es_category' => $item[44],
        'moyenne' => $item[45],
        'wohlstand' => $item[46],
        'rising' => $item[47],
        'top_asia' => $item[48],
        'azuni' => $item[49],
        'kylin' => $item[50],
        'modern_sports' => $item[51],
        'gyco' => $item[52],
        'lion' => $item[53],
        'live_up' => $item[54],
        'ironmaster' => $item[55],
        'record' => $item[56],
        'tengtai' => $item[57],
        'dekai' => $item[58],
        'alex' => $item[59],
        'regal' => $item[60],
        'helisports' => $item[61],
        'amaya' => $item[62],
        'msd' => $item[63],
        'fournisseur' => $item[64], //
        'unit' => $item[65],
        'pa_dollar' => $item[66],
        'pa_fdp_inclus' => $item[67],
        'ob_marge_hldp' => $item[68],
        'ob_pv_fob_dollar' => $item[69],
        'ob_pv_fob' => $item[70], //
        'ob_pv_hldp_dollar' => $item[71],
        'ob_pv_hldp' => $item[72],
        //
        'pv_lf_orange' => $item[74],
        'reduction' => $item[75],
        'produit_specifique' => $item[76],
        'rev_marge_hldp' => $item[77],
        'rev_pv_fob_dollar' => $item[78],
        'rev_pv_fob' => $item[79],
        'rev_pv_hldp_dollar' => $item[80], // cc
        'rev_pv_hldp' => $item[81],
        'gev_marge_hldp' => $item[82],
        'gev_pv_fob_dollar' => $item[83],
        'gev_pv_fob' => $item[84],
        'gev_pv_hldp_dollar' => $item[85],
        'gev_pv_hldp' => $item[86],
        'gev_pv_hldp2' => $item[87],
        'gev_pv_hldp3' => $item[88],
        'cha_marge_hldp' => $item[89],
        'cha_pv_fob_dollar' => $item[90], // cm
        'cha_pv_fob' => $item[91],
        'cha_pv_hldp_dollar' => $item[92],
        'cha_pv_hldp' => $item[93],
        'cha_pv_hldp2' => $item[94],
        'kin_marge_hldp' => $item[95],
        'kin_pv_fob_dollar' => $item[96],
        'kin_pv_fob' => $item[97],
        'kin_pv_hldp_dollar' => $item[98],
        'kin_pv_hldp' => $item[99],
        'kin_pv_hldp2' => $item[100], // cw
        'fit_marge_hldp' => $item[101],
        'fit_pv_fob_dollar' => $item[102],
        'fit_pv_fob' => $item[103],
        'fit_pv_hldp_dollar' => $item[104],
        'fit_pv_hldp' => $item[105],
        'fit_pv_hldp2' => $item[106],
        'lf_pv_public' => $item[107],
        'lf_pv_public_dollar' => $item[108],
        'lf_reduction' => $item[109],
        'lf_pv_revendeur' => $item[110], //
        'lf_pv_revendeur_dollar' => $item[111],
    ];


//            $filteredItem = array_combine($aChamps, $item);
    QuickPdo::insert($table, $filteredItem);


}



