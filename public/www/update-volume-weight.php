<?php


use CommandeHasArticle\CommandeHasArticleUtil;
use CsvExport\CommandeExporterUtil;
use DbTransition\Fournisseur;
use DevisHasCommandeHasArticle\DevisHasCommandeHasArticleUtil;
use QuickPdo\QuickPdo;

require_once __DIR__ . "/../init.php";





a(QuickPdo::fetch("select count(*) as count from fournisseur_has_article where poids='0.0000000000'"));

az();




if (false) {

    $items = QuickPdo::fetchAll("select fournisseur, ref_hldp, poids_unit from csv_fournisseurs_fournisseurs");
//az($items); // 3810
    $n = 0;
    foreach ($items as $item) {
        $articleId = 0;
        $fournisseurId = 0;

        if (false !== ($res = QuickPdo::fetch("select id from fournisseur where nom like :nom", [
                'nom' => '%' . str_replace('%', '\%', $item['fournisseur']) . '%',
            ]))
        ) {
            $fournisseurId = $res['id'];
            if (false !== ($res2 = QuickPdo::fetch("select id from article where reference_hldp like :ref", [
                    'ref' => '%' . str_replace('%', '\%', $item['ref_hldp']) . '%',
                ]))
            ) {
//            a($item);
                $n++;
                $articleId = $res2['id'];
                $q = "update fournisseur_has_article set poids='" . str_replace(',', '.', $item['poids_unit']) . "' where article_id=" . $articleId .
                    " and fournisseur_id=" . $fournisseurId;
                a(QuickPdo::freeExec($q), $fournisseurId . "-" . $articleId . "-" . $item['poids_unit']);

            }
        }
//    if($n> 100){
//        break;
//    }
    }

    a($n); // 2443 fournisseurs trouvés, 1871 références lf

}


$items = QuickPdo::fetchAll("select fournisseur, ref, poids_unit, m3_unit from csv_fournisseurs_fournisseurs");
//az($items); // 3810
$n = 0;
foreach ($items as $item) {
    $articleId = 0;
    $fournisseurId = 0;

    if (false !== ($res = QuickPdo::fetch("select fournisseur_id, article_id from fournisseur_has_article where reference like :ref", [
            'ref' => '%' . str_replace('%', '\%', $item['ref']) . '%',
        ]))
    ) {
        $fournisseurId = $res['fournisseur_id'];
        $articleId = $res['article_id'];
        $n++;

        $q = "update fournisseur_has_article set 
            poids='" . str_replace(',', '.', $item['poids_unit']) . "', 
            volume='" . str_replace(',', '.', $item['m3_unit']) . "' 
            where article_id=" . $articleId .
            " and fournisseur_id=" . $fournisseurId;
        a(QuickPdo::freeExec($q), $fournisseurId . "-" . $articleId . "-" . $item['poids_unit']);

    }
//    if($n> 100){
//        break;
//    }
}
a($n);
