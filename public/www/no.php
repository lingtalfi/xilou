<?php


use Csv\CsvUtil;
use QuickPdo\QuickPdo;
use QuickPdo\QuickPdoInfoTool;
use Util\ArrayRenderer;

require_once __DIR__ . "/../init.php";


$f = '/myphp/xilou/pprivate/Pierre/Commande Fournisseurs AP-comparatif.csv';
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
                'produits' => $item[7],
                'm3' => $item[10],
                'gw' => $item[10],
                'nw' => $item[10],
                'vendu_par' => $item[10],
                'ean' => $item[10],
                'nom_hldp' => $item[10],
                'nom_lf' => $item[10],
                'poids' => $item[10],
                'poids2' => $item[10],
                'materiaux' => $item[10],
                'deja_importes' => $item[10],
                'largeur' => $item[2],
                'hauteur' => $item[2],
                'longueur' => $item[2],
                'resistance' => $item[2],
                'autres' => $item[2],
                'MOQ' => $item[2],
                'packaging' => $item[2],
                'categorie' => $item[2],
                'descriptif' => $item[2],
                'url' => $item[2],
                'products' => $item[3],
                'sold_by' => $item[3], // 32
                'packaging2' => $item[3],
                'materia' => $item[4],
                'others' => $item[5],
                'description' => $item[6],
                'category' => $item[7], // 37
                'productos' => $item[7],
                'sold_by2' => $item[7],
                'packaging3' => $item[8], // 40
                'materia2' => $item[9], // 41
                'others2' => $item[10], // 42
                'description2' => $item[10], //
                'category2' => $item[10], // 44
                'moyenne' => $item[11],
                'wohlstand' => $item[11],
                'rising' => $item[11],
                'top_asia' => $item[11],
                'azuni' => $item[11],
                'kylin' => $item[11],
                'modern_sporting' => $item[11],
                'gyco' => $item[11],
                'lion' => $item[11],
                'live_up' => $item[12],
                'ironmaster' => $item[13],
                'record' => $item[13],
                'tengtai' => $item[13],
                'dekai' => $item[13],
                'alex' => $item[13],
                'regal' => $item[13],
                'helisports' => $item[13],
                'amaya' => $item[13],
                'msd' => $item[13],
                'fournisseur' => $item[13],
                'unit' => $item[13],
                'pa' => $item[13],
                'pa_fdp_inclus' => $item[13],
                'orange_bleue' => $item[13],
                'ref_hldp' => $item[14], // 68


                'marge_hldp' => $item[14], // 68
                'pv_fob_dollar' => $item[14],
                'pv_fob' => $item[14],
                'pv_hldp_ddp_dollar' => $item[14],
                'pv_hldp_ddp' => $item[14],
                'pv_hldp_ddp_fix' => $item[14],
                'pv_lf_orange_bleue' => $item[14],
                'reduction_pv_lf_or_bl' => $item[14],


                'pv_hldp_ddp_dollar' => $item[14],
                'pv_hldp_ddp' => $item[14],
                'pv_hldp_ddp_fix' => $item[14], // 100
                'marge_hldp' => $item[14],
                'pv_fob_dollar' => $item[14],
                'pv_fob' => $item[14],
                'pv_hldp_ddp_dollar' => $item[14],
                'pv_hldp_ddp2' => $item[14],
                'pv_hldp_ddp_fix2' => $item[14],
                'pv_public' => $item[14],
                'pv_public_dollar' => $item[14],
                'reduction_pv_public' => $item[14],
                'pv_lf_revendeur' => $item[14],
                'pv_lf_revendeur_dollar' => $item[14], // 111
            ];


//            $filteredItem = array_combine($aChamps, $item);
            QuickPdo::insert($table, $filteredItem);

        }
    }
}



